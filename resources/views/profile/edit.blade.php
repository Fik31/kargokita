<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(!Auth::user()->hasRole('merchant') && !Auth::user()->hasRole('driver') && !Auth::user()->hasRole('administrator') && !Auth::user()->hasRole('hse'))
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-brand-blue rounded-xl p-6 shadow-sm flex flex-col sm:flex-row items-center justify-between">
                    <div class="mb-4 sm:mb-0">
                        <h4 class="text-xl font-bold text-brand-black mb-1">Ayo Pilih Role-mu Sekarang!</h4>
                        <p class="text-gray-600 text-sm">Akun Anda saat ini berstatus <strong>COMMON</strong>. Upgrade akunmu menjadi Driver atau Merchant untuk bisa mulai mencari muatan atau menawarkan muatan.</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('verification') }}" class="px-4 py-2 bg-white text-brand-blue border border-brand-blue font-bold rounded-lg hover:bg-blue-50 transition text-sm">Jadi Merchant</a>
                        <a href="{{ route('driver.verification') }}" class="px-4 py-2 bg-brand-blue text-white font-bold rounded-lg hover:bg-blue-700 shadow transition text-sm">Jadi Driver</a>
                    </div>
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
