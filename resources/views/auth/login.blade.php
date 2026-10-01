<x-guest-layout>
    <div class="min-h-screen flex">
        <!-- Left Side: Branding (Red Background) -->
        <div class="hidden md:flex md:w-1/2 bg-brand-blue flex-col justify-center items-center text-white p-12 relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute top-[-10%] left-[-10%] w-64 h-64 bg-blue-600 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob"></div>
            <div class="absolute top-[20%] right-[-10%] w-72 h-72 bg-blue-800 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-[-20%] left-[20%] w-80 h-80 bg-red-500 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob animation-delay-4000"></div>

            <div class="z-10 text-center flex flex-col items-center">
                <img src="{{ asset('assets/images/logo-white.png') }}" alt="Kargokita Logo" class="w-[360px] md:w-[420px] h-auto mb-8 rounded-2xl shadow-2xl object-cover">
                <p class="text-lg font-medium text-blue-100 max-w-sm mx-auto leading-relaxed mt-4">Platform Jaringan Logistik & Sosial Media Pertama untuk Driver dan Merchant di Indonesia.</p>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full md:w-1/2 flex items-center justify-center p-6 sm:p-12 bg-white">
            <div class="w-full max-w-md">
                <div class="text-center mb-8 md:mb-10">
                    <!-- Mobile Logo -->
                    <div class="md:hidden flex justify-center mb-6">
                        <img src="{{ asset('assets/images/logo-white3.png') }}" alt="Kargokita Logo" class="h-12 w-auto object-contain">
                    </div>
                    
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-brand-black">Selamat Datang Kembali</h2>
                    <p class="text-sm sm:text-base text-gray-500 mt-2">Masuk ke akun KargoKita Anda untuk melanjutkan</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" 
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-brand-blue focus:border-brand-blue sm:text-sm transition duration-150 ease-in-out" 
                            placeholder="nama@email.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-medium text-brand-blue hover:text-blue-800 transition duration-150 ease-in-out">Lupa password?</a>
                            @endif
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password" 
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-brand-blue focus:border-brand-blue sm:text-sm transition duration-150 ease-in-out" 
                            placeholder="••••••••">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 text-brand-blue focus:ring-brand-blue border-gray-300 rounded">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-900">Ingat Saya</label>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-brand-blue hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-blue transition duration-150 ease-in-out">
                            Masuk
                        </button>
                    </div>
                </form>
                
                <div class="mt-8 text-center text-sm text-gray-600">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="font-bold text-brand-blue hover:text-blue-800 transition duration-150 ease-in-out">Daftar sekarang</a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
