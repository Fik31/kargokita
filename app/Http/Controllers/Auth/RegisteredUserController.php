<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\User;
use App\Models\VerificationRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'role' => ['required', 'string', 'in:merchant,driver'],
            'upgrade' => ['required', 'string', 'in:yes,no'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'referral_code_input' => ['nullable', 'string', 'exists:users,referral_code'],
        ];

        if ($request->upgrade === 'yes') {
            $rules['phone'] = ['nullable', 'string', 'max:20'];
            $rules['address'] = ['nullable', 'string'];
            $rules['ktp_number'] = ['nullable', 'string', 'max:50'];
            $rules['npwp_number'] = ['nullable', 'string', 'max:50'];

            if ($request->role === 'merchant') {
                $rules['nib'] = ['nullable', 'string', 'max:255'];
                $rules['company_name'] = ['nullable', 'string', 'max:255'];
            } else {
                $rules['sim_number'] = ['nullable', 'string', 'max:255'];
                $rules['stnk_number'] = ['nullable', 'string', 'max:255'];
                $rules['vehicle_plate'] = ['nullable', 'string', 'max:255'];
                $rules['vehicle_type'] = ['nullable', 'string', 'max:255'];
                $rules['vehicle_capacity'] = ['nullable', 'numeric'];
            }
        }

        $request->validate($rules);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tier' => 'bronze',
            'referral_code' => strtoupper(Str::random(8)),
        ]);

        if ($request->filled('referral_code_input')) {
            $referrer = User::where('referral_code', $request->referral_code_input)->first();
            if ($referrer) {
                Referral::create([
                    'referrer_id' => $referrer->id,
                    'referred_id' => $user->id,
                    'type' => $request->role === 'merchant' ? 'merchant_to_merchant' : 'driver_to_driver',
                    'commission_amount' => 100000,
                    'status' => 'pending',
                ]);
            }
        }

        $user->assignRole($request->role);

        if ($request->upgrade === 'yes') {
            $data = [
                'phone' => $request->phone,
                'address' => $request->address,
                'ktp_number' => $request->ktp_number,
                'npwp_number' => $request->npwp_number,
            ];

            if ($request->role === 'merchant') {
                $data['nib'] = $request->nib;
                $data['company_name'] = $request->company_name;
            } else {
                $data['sim_number'] = $request->sim_number;
                $data['stnk_number'] = $request->stnk_number;
                $data['vehicle_plate'] = $request->vehicle_plate;
                $data['vehicle_type'] = $request->vehicle_type;
                $data['vehicle_capacity'] = $request->vehicle_capacity;
            }

            // Determine Tier
            $tier = 'bronze';
            $hasBasic = !empty($request->phone) && !empty($request->address);
            $hasSilver = $hasBasic && !empty($request->ktp_number) && !empty($request->npwp_number);
            
            $hasGold = false;
            if ($request->role === 'merchant') {
                $hasGold = $hasSilver && !empty($request->nib) && !empty($request->company_name);
            } else {
                $hasGold = $hasSilver && !empty($request->sim_number) && !empty($request->stnk_number) && !empty($request->vehicle_plate) && !empty($request->vehicle_type) && !empty($request->vehicle_capacity);
            }

            if ($hasGold) {
                $tier = 'gold';
            } elseif ($hasSilver) {
                $tier = 'silver';
            }

            $user->update(['tier' => $tier]);

            VerificationRequest::create([
                'user_id' => $user->id,
                'type' => $request->role,
                'data' => $data,
                'status' => 'approved', // Auto approve for mockup
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
