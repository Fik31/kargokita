<div class="max-w-[90rem] mx-auto p-4 sm:p-6 lg:p-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form Column -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg p-6 mb-6 text-center border-t-4 border-brand-blue">
                <h2 class="text-2xl font-bold text-gray-800">Form Pendaftaran & Verifikasi Driver</h2>
                <p class="text-sm text-gray-500 mt-2">Lengkapi seluruh data secara akurat. Data dikelompokkan (bundling) berdasarkan bobot penilaian. Pastikan tidak ada kolom yang terlewat untuk mendapatkan bobot penuh pada masing-masing bagian.</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 relative">
                    <strong class="font-bold">Oops! Ada data yang belum lengkap.</strong>
                    <span class="block sm:inline">Mohon periksa kembali form di bawah, lengkapi seluruh field untuk memenuhi persyaratan bobot.</span>
                </div>
            @endif

            <form wire:submit.prevent="submit" class="space-y-6">
                <!-- 1. Identity Verification (20%) -->
                <div class="bg-white shadow rounded-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between border-b pb-3 mb-4">
                        <h3 class="text-lg font-bold text-gray-800">1. Identity Verification</h3>
                        <span class="bg-blue-100 text-blue-800 font-bold px-3 py-1 rounded text-sm">Bobot: 20%</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Nama Lengkap</label>
                            <input type="text" wire:model="full_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200">
                            @error('full_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">NIK (KTP)</label>
                            <input type="number" wire:model="nik" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200">
                            @error('nik') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Tanggal Lahir</label>
                            <input type="date" wire:model="dob" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200">
                            @error('dob') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Nomor HP</label>
                            <input type="text" wire:model="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200">
                            @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700">Alamat Lengkap</label>
                            <textarea wire:model="address" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200"></textarea>
                            @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Emergency Contact (Nama & No HP)</label>
                            <input type="text" wire:model="emergency_contact" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200">
                            @error('emergency_contact') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Foto KTP</label>
                            <input type="file" wire:model="ktp_photo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @error('ktp_photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Foto Profil Driver</label>
                            <input type="file" wire:model="profile_photo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @error('profile_photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700">Foto Liveness (Selfie Bebas Bergerak)</label>
                            <input type="file" wire:model="liveness_photo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-1">Gunakan foto selfie terbaru dengan pencahayaan jelas untuk validasi biometrik.</p>
                            @error('liveness_photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- 2. SIM Verification (20%) -->
                <div class="bg-white shadow rounded-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between border-b pb-3 mb-4">
                        <h3 class="text-lg font-bold text-gray-800">2. SIM Verification</h3>
                        <span class="bg-blue-100 text-blue-800 font-bold px-3 py-1 rounded text-sm">Bobot: 20%</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Jenis SIM</label>
                            <select wire:model="sim_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200">
                                <option value="">Pilih Jenis SIM...</option>
                                <option value="SIM A">SIM A</option>
                                <option value="SIM B1">SIM B1</option>
                                <option value="SIM B1 Umum">SIM B1 Umum</option>
                                <option value="SIM B2">SIM B2</option>
                                <option value="SIM B2 Umum">SIM B2 Umum</option>
                            </select>
                            @error('sim_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Nomor SIM</label>
                            <input type="number" wire:model="sim_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200">
                            @error('sim_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Masa Berlaku SIM</label>
                            <input type="date" wire:model="sim_validity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200">
                            @error('sim_validity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Pengalaman Mengemudi (Tahun)</label>
                            <input type="number" wire:model="driving_experience" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200" placeholder="Contoh: 5">
                            @error('driving_experience') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Area Operasional</label>
                            <input type="text" wire:model="operational_area" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200" placeholder="Contoh: Jabodetabek, Jawa-Bali">
                            @error('operational_area') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Jenis Muatan yang biasa dibawa</label>
                            <input type="text" wire:model="cargo_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200" placeholder="Contoh: FMCG, Material Besi">
                            @error('cargo_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700">Foto SIM Fisik</label>
                            <input type="file" wire:model="sim_photo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @error('sim_photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- 3. Vehicle Verification (20%) -->
                <div class="bg-white shadow rounded-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between border-b pb-3 mb-4">
                        <h3 class="text-lg font-bold text-gray-800">3. Vehicle Verification</h3>
                        <span class="bg-blue-100 text-blue-800 font-bold px-3 py-1 rounded text-sm">Bobot: 20%</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Nomor Polisi</label>
                            <input type="text" wire:model="license_plate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200 uppercase font-mono" placeholder="B 1234 CD">
                            @error('license_plate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Jenis Kendaraan</label>
                            <select wire:model="vehicle_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200">
                                <option value="">Pilih Tipe...</option>
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
                            @error('vehicle_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Merk / Model</label>
                            <input type="text" wire:model="vehicle_brand" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200" placeholder="Contoh: Mitsubishi Canter">
                            @error('vehicle_brand') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Tahun Kendaraan</label>
                            <input type="number" wire:model="vehicle_year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200" placeholder="Contoh: 2018">
                            @error('vehicle_year') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Kapasitas Maksimal (KG)</label>
                            <input type="number" wire:model="capacity_kg" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200" placeholder="Contoh: 4000">
                            @error('capacity_kg') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Kapasitas Maksimal (CBM)</label>
                            <input type="number" wire:model="capacity_cbm" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200" placeholder="Contoh: 15">
                            @error('capacity_cbm') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Dimensi Bak (PxLxT)</label>
                            <input type="text" wire:model="box_dimension" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200" placeholder="Contoh: 4m x 2m x 2m">
                            @error('box_dimension') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">GPS/Device Terpasang (Opsional)</label>
                            <input type="text" wire:model="gps_device" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200" placeholder="Ada/Tidak">
                            @error('gps_device') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Foto STNK</label>
                            <input type="file" wire:model="stnk_photo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @error('stnk_photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Foto Bukti KIR</label>
                            <input type="file" wire:model="kir_photo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @error('kir_photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700">Foto Kendaraan (Depan/Samping terlihat jelas)</label>
                            <input type="file" wire:model="vehicle_photo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @error('vehicle_photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- 4. Vehicle Compliance (20%) -->
                <div class="bg-white shadow rounded-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between border-b pb-3 mb-4">
                        <h3 class="text-lg font-bold text-gray-800">4. Vehicle Compliance</h3>
                        <span class="bg-blue-100 text-blue-800 font-bold px-3 py-1 rounded text-sm">Bobot: 20%</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-4">Centang jika kendaraan Anda dilengkapi dengan peralatan keamanan berikut. Wajib dipenuhi seluruhnya.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="apar" id="apar" class="h-5 w-5 text-brand-blue border-gray-300 rounded focus:ring-blue-500">
                            <label for="apar" class="ml-3 block text-sm font-medium text-gray-700">APAR (Alat Pemadam Api Ringan)</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="stopper" id="stopper" class="h-5 w-5 text-brand-blue border-gray-300 rounded focus:ring-blue-500">
                            <label for="stopper" class="ml-3 block text-sm font-medium text-gray-700">Stopper (Pengganjal Ban)</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="kanebo" id="kanebo" class="h-5 w-5 text-brand-blue border-gray-300 rounded focus:ring-blue-500">
                            <label for="kanebo" class="ml-3 block text-sm font-medium text-gray-700">Kanebo / Majun (Kain Pembersih)</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="safety_tools" id="safety_tools" class="h-5 w-5 text-brand-blue border-gray-300 rounded focus:ring-blue-500">
                            <label for="safety_tools" class="ml-3 block text-sm font-medium text-gray-700">Alat Keamanan (Dongkrak, P3K, Segitiga Pengaman)</label>
                        </div>
                    </div>
                    @error('apar') <span class="text-red-500 text-xs block mt-2">{{ $message }}</span> @enderror
                </div>

                <!-- 5. Risk Screening (10%) -->
                <div class="bg-white shadow rounded-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between border-b pb-3 mb-4">
                        <h3 class="text-lg font-bold text-gray-800">5. Risk Screening</h3>
                        <span class="bg-blue-100 text-blue-800 font-bold px-3 py-1 rounded text-sm">Bobot: 10%</span>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Foto SKCK Aktif</label>
                        <input type="file" wire:model="skck_photo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('skck_photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- 6. Area Coverage (5%) -->
                <div class="bg-white shadow rounded-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between border-b pb-3 mb-4">
                        <h3 class="text-lg font-bold text-gray-800">6. Area Coverage</h3>
                        <span class="bg-blue-100 text-blue-800 font-bold px-3 py-1 rounded text-sm">Bobot: 5%</span>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Kota Domisili Saat Ini</label>
                        <input type="text" wire:model="domicile" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200" placeholder="Contoh: Jakarta Barat">
                        @error('domicile') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- 7. Bank/Payment Verification (5%) -->
                <div class="bg-white shadow rounded-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between border-b pb-3 mb-4">
                        <h3 class="text-lg font-bold text-gray-800">7. Bank / Payment Verification</h3>
                        <span class="bg-blue-100 text-blue-800 font-bold px-3 py-1 rounded text-sm">Bobot: 5%</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Nama Bank</label>
                            <input type="text" wire:model="bank_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200" placeholder="Contoh: BCA">
                            @error('bank_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Nomor Rekening</label>
                            <input type="text" wire:model="bank_account_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200" placeholder="Contoh: 1234567890">
                            @error('bank_account_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700">Nama Pemilik Rekening</label>
                            <input type="text" wire:model="bank_account_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200" placeholder="Sesuai dengan nama di buku tabungan">
                            @error('bank_account_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700">Foto Buku Rekening Bank / Mutasi (Halaman Depan)</label>
                            <input type="file" wire:model="bank_account_photo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @error('bank_account_photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                    <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-blue-600 to-blue-800 border border-transparent rounded-lg shadow-md py-3 px-10 text-base font-bold text-white hover:from-blue-700 hover:to-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-blue transition transform hover:-translate-y-0.5">
                        Kirim Form Pendaftaran & Dapatkan Akun Driver
                    </button>
                </div>
            </form>
        </div>

        <!-- Sidebar Deposit Jaminan -->
        <div class="lg:col-span-1">
            <div class="bg-gradient-to-br from-brand-blue to-blue-900 shadow-xl rounded-2xl p-6 text-white sticky top-6">
                <h3 class="text-xl font-bold mb-2">Informasi Penting</h3>
                <p class="text-blue-100 text-sm mb-6">Wajib bagi semua Driver untuk menyetor Deposit Jaminan. Menjamin keseriusan Anda di ekosistem Kargokita.</p>
                
                <div class="mb-6 border-b border-white/20 pb-6">
                    <span class="text-3xl font-extrabold">Rp 1.000.000</span>
                    <span class="text-sm text-blue-200 block mt-2 leading-relaxed">Deposit ini dibayarkan <strong>setelah</strong> Anda mengirimkan form ini. Akun Anda baru akan aktif setelah lunas.</span>
                </div>
                
                <div class="bg-white/10 rounded-xl p-4 mb-6 border border-white/20">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-yellow-300 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm font-medium leading-relaxed">Garansi 100% uang kembali jika selama 1 tahun berturut-turut Anda tidak mendapatkan order muatan di platform ini.</p>
                    </div>
                </div>

                <h4 class="font-bold mb-3 text-sm uppercase tracking-wider text-blue-200">Tahapan Berikutnya:</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-yellow-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Submit Pendaftaran (100% Data Lengkap)
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Membayar Deposit Jaminan
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        HSE Audit Assessment (Review Tim Pusat)
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Aktivasi & Pemberian Tier (Gold/Silver/Bronze)
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
