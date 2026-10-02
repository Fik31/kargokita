<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\Load;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstantLoadController extends Controller
{
    public function take(Request $request, $loadId)
    {
        $driver = auth()->user();

        try {
            DB::beginTransaction();

            $load = Load::where('id', $loadId)
                ->where('is_instant', true)
                ->lockForUpdate()
                ->first();

            if (! $load) {
                DB::rollBack();

                return response()->json(['message' => 'Order instant tidak ditemukan'], 404);
            }

            if ($load->status !== 'open') {
                DB::rollBack();

                return response()->json(['message' => 'Maaf, order ini sudah diambil oleh driver lain.'], 400);
            }

            $load->status = 'in_transit'; // Assuming 'in_transit' is the next status
            $load->save();

            $trip = Trip::create([
                'load_id' => $load->id,
                'driver_id' => $driver->id,
                'agreed_price' => $load->max_price,
                'status' => 'pending',
            ]);

            DB::commit();

            return response()->json(['message' => 'Berhasil mengambil order!', 'data' => $trip], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Terjadi kesalahan sistem', 'error' => $e->getMessage()], 500);
        }
    }
}
