<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
        <div class="max-w-xl w-full bg-white p-8 rounded-2xl shadow-lg border border-gray-100" x-data="{ step: {{ $errors->any() ? 1 : 1 }}, role: '{{ old('role') }}', upgrade: '{{ old('upgrade', 'no') }}' }">
            
            <div class="text-center mb-8">
                <a href="/">
                    <h2 class="text-3xl font-extrabold text-brand-black tracking-tight">Kargokita</h2>
                </a>
                <h2 class="mt-4 text-2xl font-bold text-gray-900">
                    Buat Akun Baru
                </h2>
                <!-- Progress Indicators -->
                <div class="flex items-center justify-center space-x-4 mt-6">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold" :class="step >= 1 ? 'bg-brand-blue text-white' : 'bg-gray-200 text-gray-600'">1</div>
                        <span class="text-xs mt-1 font-medium text-gray-500">Peran & Login</span>
                    </div>
                    <div class="w-10 h-1 border-t-2" :class="step >= 2 ? 'border-brand-blue' : 'border-gray-200'"></div>
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold" :class="step >= 2 ? 'bg-brand-blue text-white' : 'bg-gray-200 text-gray-600'">2</div>
                        <span class="text-xs mt-1 font-medium text-gray-500">Tier Bronze</span>
                    </div>
                    <div class="w-10 h-1 border-t-2" :class="step >= 3 ? 'border-brand-blue' : 'border-gray-200'"></div>
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold" :class="step >= 3 ? 'bg-brand-blue text-white' : 'bg-gray-200 text-gray-600'">3</div>
                        <span class="text-xs mt-1 font-medium text-gray-500">Upgrade Tier</span>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- ================= STEP 1: ROLE & LOGIN ================= -->
                <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="space-y-5">
                        <!-- Role Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">1. Pilih Peran Anda</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label :class="role === 'merchant' ? 'ring-2 ring-brand-blue bg-blue-50 border-brand-blue' : 'border-gray-200'" class="relative flex items-center justify-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                    <input type="radio" name="role" value="merchant" x-model="role" class="absolute h-0 w-0 opacity-0">
                                    <div class="text-center">
                                        <span class="block text-sm font-medium text-gray-900">Merchant</span>
                                        <span class="block text-xs text-gray-500">Pemilik Muatan</span>
                                    </div>
                                </label>
                                <label :class="role === 'driver' ? 'ring-2 ring-brand-blue bg-blue-50 border-brand-blue' : 'border-gray-200'" class="relative flex items-center justify-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                    <input type="radio" name="role" value="driver" x-model="role" class="absolute h-0 w-0 opacity-0">
                                    <div class="text-center">
                                        <span class="block text-sm font-medium text-gray-900">Driver</span>
                                        <span class="block text-xs text-gray-500">Pemilik Armada</span>
                                    </div>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <div class="border-t border-gray-200 pt-5">
                            <h3 class="text-sm font-bold text-gray-900 mb-4">2. Informasi Akun (Login)</h3>
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <input id="name" name="name" type="text" autocomplete="name" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-brand-blue focus:border-brand-blue sm:text-sm mt-1" value="{{ old('name') }}">
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                                <input id="email" name="email" type="email" autocomplete="email" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-brand-blue focus:border-brand-blue sm:text-sm mt-1" value="{{ old('email') }}">
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                    <input id="password" name="password" type="password" autocomplete="new-password" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-brand-blue focus:border-brand-blue sm:text-sm mt-1">
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-brand-blue focus:border-brand-blue sm:text-sm mt-1">
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <label for="referral_code_input" class="block text-sm font-medium text-gray-700">Kode Referral (Opsional)</label>
                                <input id="referral_code_input" name="referral_code_input" type="text" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-brand-blue focus:border-brand-blue sm:text-sm mt-1" value="{{ old('referral_code_input') }}" placeholder="Contoh: KRG-12345">
                                <x-input-error :messages="$errors->get('referral_code_input')" class="mt-2" />
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="button" @click="step = 2" class="px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-brand-blue hover:bg-blue-700 focus:outline-none transition">
                                Selanjutnya &rarr;
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ================= STEP 2: TIER BRONZE ================= -->
                <div x-show="step === 2" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="space-y-5">
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-4 text-sm text-gray-700">
                            <strong>Data Tier Bronze:</strong> Ini adalah syarat dasar untuk bisa berinteraksi di aplikasi.
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">No Handphone (Aktif WA)</label>
                            <input type="text" name="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="{{ old('phone') }}">
                            <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                            <textarea name="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ old('address') }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-1" />
                        </div>

                        <div class="pt-4 flex justify-between">
                            <button type="button" @click="step = 1" class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition">
                                &larr; Kembali
                            </button>
                            <button type="button" @click="step = 3" class="px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-brand-blue hover:bg-blue-700 focus:outline-none transition">
                                Selanjutnya &rarr;
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ================= STEP 3: UPGRADE TIER & GOLD/SILVER ================= -->
                <div x-show="step === 3" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="space-y-5">
                        
                        <!-- Tier Benefits Dummy Info -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
                            <div class="bg-blue-50 p-3 rounded-lg border border-blue-100 text-center">
                                <h4 class="font-bold text-blue-800 text-sm">Bronze</h4>
                                <p class="text-xs text-blue-600 mt-1">Akses Feed & Chat</p>
                            </div>
                            <div class="bg-gray-100 p-3 rounded-lg border border-gray-200 text-center">
                                <h4 class="font-bold text-gray-800 text-sm">Silver</h4>
                                <p class="text-xs text-gray-600 mt-1">Bidding Reguler</p>
                            </div>
                            <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-200 text-center">
                                <h4 class="font-bold text-yellow-800 text-sm">Gold</h4>
                                <p class="text-xs text-yellow-600 mt-1">Prioritas & Fitur Penuh</p>
                            </div>
                        </div>

                        <!-- Upgrade Question -->
                        <div class="bg-blue-50 p-5 rounded-xl border border-blue-100">
                            <label class="block text-base font-bold text-blue-900 mb-3">Apakah Anda ingin melengkapi data sekarang untuk naik ke Tier Silver / Gold?</label>
                            <div class="space-y-3">
                                <label class="flex items-center cursor-pointer group bg-white p-3 rounded-lg border border-blue-200 hover:border-blue-400 transition">
                                    <input type="radio" name="upgrade" value="yes" x-model="upgrade" class="text-brand-blue focus:ring-brand-blue h-5 w-5 border-gray-300">
                                    <span class="ml-3 text-sm text-gray-800 font-bold">Ya, lengkapi sekarang (Silver/Gold)</span>
                                </label>
                                <label class="flex items-center cursor-pointer group bg-white p-3 rounded-lg border border-blue-200 hover:border-blue-400 transition">
                                    <input type="radio" name="upgrade" value="no" x-model="upgrade" class="text-brand-blue focus:ring-brand-blue h-5 w-5 border-gray-300">
                                    <span class="ml-3 text-sm text-gray-600 font-medium">Tidak untuk saat ini (Tetap di Tier Bronze)</span>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('upgrade')" class="mt-2" />
                        </div>

                        <!-- Dynamic Extra Fields -->
                        <div x-show="upgrade === 'yes'" x-collapse class="space-y-4 pt-2">
                            <!-- Silver Req -->
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 space-y-4">
                                <h3 class="text-sm font-bold text-gray-800 border-b pb-2">Identitas Tambahan (Syarat Silver)</h3>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">No KTP (NIK)</label>
                                    <input type="text" name="ktp_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="{{ old('ktp_number') }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">No NPWP (Opsional)</label>
                                    <input type="text" name="npwp_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="{{ old('npwp_number') }}">
                                </div>
                            </div>

                            <!-- Merchant Gold Fields -->
                            <template x-if="role === 'merchant'">
                                <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-200 space-y-4">
                                    <h3 class="text-sm font-bold text-yellow-800 border-b border-yellow-200 pb-2">Data Usaha (Syarat Gold Merchant)</h3>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">NIB (Nomor Induk Berusaha)</label>
                                        <input type="text" name="nib" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="{{ old('nib') }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nama Perusahaan / UMKM</label>
                                        <input type="text" name="company_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="{{ old('company_name') }}">
                                    </div>
                                </div>
                            </template>

                            <!-- Driver Gold Fields -->
                            <template x-if="role === 'driver'">
                                <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-200 space-y-4">
                                    <h3 class="text-sm font-bold text-yellow-800 border-b border-yellow-200 pb-2">Data Armada (Syarat Gold Driver)</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nomor SIM (B1/B2)</label>
                                            <input type="text" name="sim_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="{{ old('sim_number') }}">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nomor STNK</label>
                                            <input type="text" name="stnk_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="{{ old('stnk_number') }}">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Plat Nomor Kendaraan</label>
                                            <input type="text" name="vehicle_plate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200 focus:ring-opacity-50 uppercase font-mono" value="{{ old('vehicle_plate') }}">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Tipe Kendaraan</label>
                                            <select name="vehicle_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <option value="">-- Pilih --</option>
                                                <option value="Engkel Bak" {{ old('vehicle_type') == 'Engkel Bak' ? 'selected' : '' }}>Engkel Bak</option>
                                                <option value="Engkel Box" {{ old('vehicle_type') == 'Engkel Box' ? 'selected' : '' }}>Engkel Box</option>
                                                <option value="CDD Bak" {{ old('vehicle_type') == 'CDD Bak' ? 'selected' : '' }}>CDD Bak</option>
                                                <option value="CDD Box" {{ old('vehicle_type') == 'CDD Box' ? 'selected' : '' }}>CDD Box</option>
                                                <option value="Fuso" {{ old('vehicle_type') == 'Fuso' ? 'selected' : '' }}>Fuso</option>
                                            </select>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700">Kapasitas Maksimal (Ton)</label>
                                            <input type="number" step="0.1" name="vehicle_capacity" class="mt-1 block w-full md:w-1/2 rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="{{ old('vehicle_capacity') }}">
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="pt-6 flex justify-between items-center border-t border-gray-200 mt-6">
                            <button type="button" @click="step = 2" class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition">
                                &larr; Kembali
                            </button>
                            <button type="submit" class="px-8 py-3 border border-transparent rounded-xl shadow-md text-base font-bold text-white bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-blue transition transform hover:-translate-y-0.5">
                                Daftar Akun
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Login Redirect Link (Only visible on step 1) -->
                <div x-show="step === 1" class="flex items-center justify-center mt-6">
                    <p class="text-sm text-gray-600">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="font-bold text-brand-blue hover:text-blue-800 transition">
                            Login di sini
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
