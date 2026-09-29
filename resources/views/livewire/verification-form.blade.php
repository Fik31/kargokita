<div class="max-w-6xl mx-auto py-12 px-4 sm:px-6 lg:px-8 font-sans">
    
    <div class="text-center mb-12">
        <h2 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-700 to-red-500 tracking-tight">Lengkapi Profil Anda</h2>
        <p class="mt-3 text-lg text-gray-500 max-w-2xl mx-auto">Lengkapi data diri dan armada Anda. Data ini akan digunakan untuk proses verifikasi operasional dan penentuan Tier melalui Penilaian HSE.</p>
    </div>

    @if (session()->has('message'))
        <div class="p-5 mb-8 text-sm text-green-800 rounded-2xl bg-green-50 shadow-sm border border-green-200 flex items-center gap-3 animate-pulse">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div><span class="font-bold text-base">Berhasil!</span> {{ session('message') }}</div>
        </div>
    @endif

    @if ($existingRequest && empty($phone))
        <div class="p-5 mb-8 text-sm rounded-2xl bg-blue-50 text-blue-800 shadow-sm border border-blue-200 flex items-center gap-3">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div><span class="font-bold text-base">Informasi:</span> Data profil Anda telah tersimpan dengan status <strong>{{ strtoupper($existingRequest->status) }}</strong>.</div>
        </div>
    @endif

    <!-- Main Form -->
    <form wire:submit.prevent="submit" class="space-y-10 bg-white p-8 md:p-12 rounded-[2rem] shadow-[0_8px_40px_rgb(0,0,0,0.06)] border border-gray-100 relative overflow-hidden">
        
        <!-- Form Header Background Accent -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-700 via-gray-400 to-yellow-400"></div>

        <!-- 1. Basic Info -->
        <div class="group relative z-10">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center font-black text-lg mr-4 shadow-sm border border-blue-100">1</div>
                <div>
                    <h3 class="font-extrabold text-2xl text-gray-900 tracking-tight">Informasi Dasar</h3>
                    <p class="text-xs text-gray-500 font-medium">Informasi kontak utama Anda</p>
                </div>
            </div>
            <div class="md:pl-14 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-semibold text-sm text-gray-700 mb-2">Nomor Handphone (Aktif WhatsApp)</label>
                    <input type="text" wire:model="phone" class="block w-full border-gray-200 bg-gray-50 rounded-xl px-4 py-3 text-gray-800 shadow-sm focus:bg-white focus:border-red-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200" placeholder="0812xxxxxx">
                    @error('phone') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block font-semibold text-sm text-gray-700 mb-2">Alamat Lengkap</label>
                    <textarea wire:model="address" rows="2" class="block w-full border-gray-200 bg-gray-50 rounded-xl px-4 py-3 text-gray-800 shadow-sm focus:bg-white focus:border-red-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200" placeholder="Jl. Contoh No 123..."></textarea>
                    @error('address') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="w-full h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>

        <!-- 2. Advanced Info -->
        <div class="group relative z-10">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-2xl bg-gray-100 text-gray-700 flex items-center justify-center font-black text-lg mr-4 shadow-sm border border-gray-200">2</div>
                <div>
                    <h3 class="font-extrabold text-2xl text-gray-900 tracking-tight">Identitas Tambahan</h3>
                    <p class="text-xs text-gray-500 font-medium">Data legalitas dan administrasi</p>
                </div>
            </div>
            <div class="md:pl-14 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-semibold text-sm text-gray-700 mb-2">Nomor KTP (NIK)</label>
                    <input type="text" wire:model="ktp_number" class="block w-full border-gray-200 bg-gray-50 rounded-xl px-4 py-3 text-gray-800 shadow-sm focus:bg-white focus:border-gray-500 focus:ring-2 focus:ring-gray-200 transition-all duration-200" placeholder="16 Digit NIK">
                    @error('ktp_number') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block font-semibold text-sm text-gray-700 mb-2">NPWP Pribadi / Perusahaan</label>
                    <input type="text" wire:model="npwp_number" class="block w-full border-gray-200 bg-gray-50 rounded-xl px-4 py-3 text-gray-800 shadow-sm focus:bg-white focus:border-gray-500 focus:ring-2 focus:ring-gray-200 transition-all duration-200" placeholder="Opsional jika tidak ada">
                    @error('npwp_number') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="w-full h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>

        <!-- 3. Role Selection & Info -->
        <div class="group relative z-10">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-2xl bg-yellow-100 text-yellow-700 flex items-center justify-center font-black text-lg mr-4 shadow-sm border border-yellow-200">3</div>
                <div>
                    <h3 class="font-extrabold text-2xl text-gray-900 tracking-tight">Data Peran (Role)</h3>
                    <p class="text-xs text-gray-500 font-medium">Data spesifik sesuai operasional Anda</p>
                </div>
            </div>
            
            <div class="md:pl-14">
                <div class="mb-8 p-6 rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50/50 flex items-center justify-between">
                    <div>
                        <label class="block font-bold text-gray-900 mb-1 text-lg">Peran Anda</label>
                        <p class="text-sm text-gray-500">Peran ini dipilih saat pendaftaran dan tidak dapat diubah.</p>
                    </div>
                    <div class="px-6 py-3 rounded-xl font-black text-lg uppercase tracking-wider shadow-sm {{ $type === 'merchant' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-green-100 text-green-800 border border-green-200' }}">
                        {{ $type === 'merchant' ? '📦 Merchant' : '🚚 Driver' }}
                    </div>
                    <input type="hidden" wire:model="type" value="{{ $type }}">
                </div>

                <!-- Conditional Fields -->
                <div class="transition-all duration-500">
                    @if ($type === 'merchant')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gradient-to-br from-blue-50 to-indigo-50/30 p-8 rounded-[2rem] border border-blue-100 shadow-inner">
                            <div class="md:col-span-2">
                                <h4 class="text-blue-900 font-bold mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    Data Usaha Merchant
                                </h4>
                            </div>
                            <div>
                                <label class="block font-semibold text-sm text-blue-900 mb-2">NIB (Nomor Induk Berusaha)</label>
                                <input type="text" wire:model="nib" class="block w-full border-blue-200 bg-white rounded-xl px-4 py-3 text-gray-800 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                @error('nib') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-semibold text-sm text-blue-900 mb-2">Nama Perusahaan / UMKM</label>
                                <input type="text" wire:model="company_name" class="block w-full border-blue-200 bg-white rounded-xl px-4 py-3 text-gray-800 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                @error('company_name') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif

                    @if ($type === 'driver')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gradient-to-br from-green-50 to-emerald-50/30 p-8 rounded-[2rem] border border-green-100 shadow-inner">
                            <div class="md:col-span-2">
                                <h4 class="text-green-900 font-bold mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                    Data Operasional Armada
                                </h4>
                            </div>
                            <div>
                                <label class="block font-semibold text-sm text-green-900 mb-2">Nomor SIM (B1/B2)</label>
                                <input type="text" wire:model="sim_number" class="block w-full border-green-200 bg-white rounded-xl px-4 py-3 text-gray-800 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all">
                                @error('sim_number') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-semibold text-sm text-green-900 mb-2">Nomor STNK</label>
                                <input type="text" wire:model="stnk_number" class="block w-full border-green-200 bg-white rounded-xl px-4 py-3 text-gray-800 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all">
                                @error('stnk_number') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-semibold text-sm text-green-900 mb-2">Plat Nomor Kendaraan</label>
                                <input type="text" wire:model="vehicle_plate" class="block w-full border-green-200 bg-white rounded-xl px-4 py-3 text-gray-800 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all uppercase font-mono" placeholder="B 1234 CD">
                                @error('vehicle_plate') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block font-semibold text-sm text-green-900 mb-2">Tipe Kendaraan</label>
                                <select wire:model="vehicle_type" class="block w-full border-green-200 bg-white rounded-xl px-4 py-3 text-gray-800 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all cursor-pointer">
                                    <option value="">-- Pilih Tipe Armada --</option>
                                    <option value="Blindvan">Blindvan - Box (700–800 kg | 3–4 CBM)</option>
                                    <option value="Granmax / L300 Box">Granmax / L300 Box - Bak & Box (800–1.000 kg | 4–6 CBM)</option>
                                    <option value="CDE">CDE - Bak & Box (2.000–2.500 kg | 6–9 CBM)</option>
                                    <option value="CDE Long">CDE Long - Bak & Box (2.000–2.500 kg | 10–14 CBM)</option>
                                    <option value="CDD">CDD - Bak & Box (4.000–5.000 kg | 13–18 CBM)</option>
                                    <option value="CDD Long">CDD Long - Bak & Box (5.000–6.000 kg | 22–28 CBM)</option>
                                    <option value="Fuso">Fuso - Bak & Box (8.000–10.000 kg | 25–35 CBM)</option>
                                    <option value="Fuso Long / Wingbox">Fuso Long / Wingbox - Bak & Box (8.000–10.000 kg | 35–40 CBM)</option>
                                    <option value="Tronton">Tronton - Bak & Box (15.000–20.000 kg | 40–50 CBM)</option>
                                    <option value="Tronton Wingbox">Tronton Wingbox - Box (18.000–25.000 kg | 45–55 CBM)</option>
                                    <option value="Trailer 20">Trailer 20" - Container (25.000 - 27.000 kg | 20 Feet)</option>
                                    <option value="Trailer 40">Trailer 40" - Container (30.000 - 32.000 kg | 40 Feet)</option>
                                </select>
                                <p class="text-xs text-green-700 mt-2 italic">*Kapasitas muatan dan volume merupakan estimasi rata-rata</p>
                                @error('vehicle_type') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block font-semibold text-sm text-green-900 mb-2">Kapasitas Maksimal (Ton)</label>
                                <div class="relative w-full md:w-1/2">
                                    <input type="number" step="0.1" wire:model="vehicle_capacity" class="block w-full border-green-200 bg-white rounded-xl px-4 py-3 pr-12 text-gray-800 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-500 font-bold">Ton</div>
                                </div>
                                @error('vehicle_capacity') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-8 mt-4">
            <button type="submit" class="bg-gradient-to-r from-blue-800 to-blue-600 hover:from-blue-900 hover:to-blue-700 text-white font-bold py-4 px-10 rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 text-lg flex items-center group">
                <span>Simpan Profil</span>
                <svg class="w-6 h-6 ml-3 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </div>
    </form>
</div>
