<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Verifikasi Data Diri Driver (Tiering)</h2>
        <p class="text-sm text-gray-500 mt-1">Review data pendaftaran driver. Centang "Data Valid" pada setiap section jika bukti sesuai.</p>
        
        <div class="mt-4 p-4 bg-blue-50 border border-blue-100 rounded-lg flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <div class="font-bold text-lg text-blue-900">{{ $verificationRequest->user->name }}</div>
                <div class="text-sm text-blue-700">{{ $verificationRequest->user->email }}</div>
            </div>
            <div class="text-left sm:text-right">
                <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full uppercase">Status: {{ $verificationRequest->status }}</span>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="submit" class="space-y-6">
        
        <!-- 1. Identity Verification -->
        <div class="bg-white shadow rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <h3 class="font-bold text-gray-800">1. Identity Verification <span class="text-sm font-normal text-gray-500">(Bobot 20%)</span></h3>
                <label class="flex items-center space-x-2 bg-white px-3 py-1 border rounded shadow-sm cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" wire:model="verify_identity" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <span class="text-sm font-bold text-green-700">Data Valid (+20%)</span>
                </label>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li><strong>Nama:</strong> {{ $data['identity']['full_name'] ?? 'Budi Santoso' }}</li>
                        <li><strong>NIK:</strong> {{ $data['identity']['nik'] ?? '3171234567890001' }}</li>
                        <li><strong>Tgl Lahir:</strong> {{ $data['identity']['dob'] ?? '15 Agustus 1990' }}</li>
                        <li><strong>No HP:</strong> {{ $data['identity']['phone'] ?? '081234567890' }}</li>
                        <li><strong>Alamat:</strong> {{ $data['identity']['address'] ?? 'Jl. Merdeka No.45, RT 01/RW 02, Kebayoran Baru, Jakarta Selatan' }}</li>
                        <li><strong>Emergency Contact:</strong> {{ $data['identity']['emergency_contact'] ?? '081987654321 (Istri)' }}</li>
                    </ul>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    @if(isset($data['identity']['ktp_photo']))
                        <div>
                            <span class="block text-xs font-bold text-gray-500 mb-1">Foto KTP</span>
                            <a href="{{ Storage::url($data['identity']['ktp_photo']) }}" target="_blank">
                                <img src="{{ Storage::url($data['identity']['ktp_photo']) }}" class="h-24 object-cover rounded border hover:opacity-75 transition">
                            </a>
                        </div>
                    @endif
                    @if(isset($data['identity']['profile_photo']))
                        <div>
                            <span class="block text-xs font-bold text-gray-500 mb-1">Foto Profil</span>
                            <a href="{{ Storage::url($data['identity']['profile_photo']) }}" target="_blank">
                                <img src="{{ Storage::url($data['identity']['profile_photo']) }}" class="h-24 object-cover rounded border hover:opacity-75 transition">
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 2. SIM Verification -->
        <div class="bg-white shadow rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <h3 class="font-bold text-gray-800">2. SIM Verification <span class="text-sm font-normal text-gray-500">(Bobot 20%)</span></h3>
                <label class="flex items-center space-x-2 bg-white px-3 py-1 border rounded shadow-sm cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" wire:model="verify_sim" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <span class="text-sm font-bold text-green-700">Data Valid (+20%)</span>
                </label>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li><strong>Jenis SIM:</strong> {{ $data['sim']['sim_type'] ?? 'B1 Umum' }}</li>
                        <li><strong>No SIM:</strong> {{ $data['sim']['sim_number'] ?? '900812345678' }}</li>
                        <li><strong>Masa Berlaku:</strong> {{ $data['sim']['sim_validity'] ?? '15 Agustus 2028' }}</li>
                        <li><strong>Pengalaman:</strong> {{ $data['sim']['driving_experience'] ?? '5' }} Tahun</li>
                        <li><strong>Area Ops:</strong> {{ $data['sim']['operational_area'] ?? 'Jabodetabek & Jawa Barat' }}</li>
                        <li><strong>Jenis Muatan:</strong> {{ $data['sim']['cargo_type'] ?? 'General Cargo, FMCG' }}</li>
                    </ul>
                </div>
                <div>
                    @if(isset($data['sim']['sim_photo']))
                        <span class="block text-xs font-bold text-gray-500 mb-1">Foto SIM Fisik</span>
                        <a href="{{ Storage::url($data['sim']['sim_photo']) }}" target="_blank">
                            <img src="{{ Storage::url($data['sim']['sim_photo']) }}" class="h-24 object-cover rounded border hover:opacity-75 transition">
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- 3. Vehicle Verification -->
        <div class="bg-white shadow rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <h3 class="font-bold text-gray-800">3. Vehicle Verification <span class="text-sm font-normal text-gray-500">(Bobot 20%)</span></h3>
                <label class="flex items-center space-x-2 bg-white px-3 py-1 border rounded shadow-sm cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" wire:model="verify_vehicle" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <span class="text-sm font-bold text-green-700">Data Valid (+20%)</span>
                </label>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li><strong>Nopol:</strong> <span class="font-mono uppercase">{{ $data['vehicle']['license_plate'] ?? 'B 9123 CDE' }}</span></li>
                        <li><strong>Tipe Kendaraan:</strong> {{ $data['vehicle']['vehicle_type'] ?? 'CDE Engkel Box' }}</li>
                        <li><strong>Merk/Tahun:</strong> {{ $data['vehicle']['vehicle_brand'] ?? 'Mitsubishi Canter' }} ({{ $data['vehicle']['vehicle_year'] ?? '2019' }})</li>
                        <li><strong>Kapasitas:</strong> {{ $data['vehicle']['capacity_kg'] ?? '2000' }} KG | {{ $data['vehicle']['capacity_cbm'] ?? '8' }} CBM</li>
                        <li><strong>Dimensi:</strong> {{ $data['vehicle']['box_dimension'] ?? '3.1m x 1.7m x 1.7m' }}</li>
                        <li><strong>GPS:</strong> {{ $data['vehicle']['gps_device'] ?? 'Terpasang (TK303)' }}</li>
                    </ul>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    @if(isset($data['vehicle']['stnk_photo']))
                        <div>
                            <span class="block text-xs font-bold text-gray-500 mb-1">Foto STNK</span>
                            <a href="{{ Storage::url($data['vehicle']['stnk_photo']) }}" target="_blank">
                                <img src="{{ Storage::url($data['vehicle']['stnk_photo']) }}" class="h-24 object-cover rounded border hover:opacity-75 transition">
                            </a>
                        </div>
                    @endif
                    @if(isset($data['vehicle']['kir_photo']))
                        <div>
                            <span class="block text-xs font-bold text-gray-500 mb-1">Foto KIR</span>
                            <a href="{{ Storage::url($data['vehicle']['kir_photo']) }}" target="_blank">
                                <img src="{{ Storage::url($data['vehicle']['kir_photo']) }}" class="h-24 object-cover rounded border hover:opacity-75 transition">
                            </a>
                        </div>
                    @endif
                    @if(isset($data['vehicle']['vehicle_photo']))
                        <div class="col-span-2">
                            <span class="block text-xs font-bold text-gray-500 mb-1">Foto Kendaraan</span>
                            <a href="{{ Storage::url($data['vehicle']['vehicle_photo']) }}" target="_blank">
                                <img src="{{ Storage::url($data['vehicle']['vehicle_photo']) }}" class="h-32 w-full object-cover rounded border hover:opacity-75 transition">
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 4. Vehicle Compliance -->
        <div class="bg-white shadow rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <h3 class="font-bold text-gray-800">4. Vehicle Compliance <span class="text-sm font-normal text-gray-500">(Bobot 20%)</span></h3>
                <label class="flex items-center space-x-2 bg-white px-3 py-1 border rounded shadow-sm cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" wire:model="verify_compliance" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <span class="text-sm font-bold text-green-700">Data Valid (+20%)</span>
                </label>
            </div>
            <div class="p-6">
                <ul class="flex gap-6 text-sm text-gray-700">
                    <li><span class="{{ ($data['compliance']['apar'] ?? true) ? 'text-green-600 font-bold' : 'text-red-500' }}">●</span> APAR</li>
                    <li><span class="{{ ($data['compliance']['stopper'] ?? true) ? 'text-green-600 font-bold' : 'text-red-500' }}">●</span> Stopper</li>
                    <li><span class="{{ ($data['compliance']['kanebo'] ?? true) ? 'text-green-600 font-bold' : 'text-red-500' }}">●</span> Kanebo/Majun</li>
                    <li><span class="{{ ($data['compliance']['safety_tools'] ?? true) ? 'text-green-600 font-bold' : 'text-red-500' }}">●</span> Alat Keamanan</li>
                </ul>
            </div>
        </div>

        <!-- 5. Risk Screening -->
        <div class="bg-white shadow rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <h3 class="font-bold text-gray-800">5. Risk Screening <span class="text-sm font-normal text-gray-500">(Bobot 10%)</span></h3>
                <label class="flex items-center space-x-2 bg-white px-3 py-1 border rounded shadow-sm cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" wire:model="verify_risk" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <span class="text-sm font-bold text-green-700">Data Valid (+10%)</span>
                </label>
            </div>
            <div class="p-6">
                @if(isset($data['risk']['skck_photo']))
                    <span class="block text-xs font-bold text-gray-500 mb-1">Foto SKCK</span>
                    <a href="{{ Storage::url($data['risk']['skck_photo']) }}" target="_blank">
                        <img src="{{ Storage::url($data['risk']['skck_photo']) }}" class="h-24 object-cover rounded border hover:opacity-75 transition">
                    </a>
                @else
                    <span class="block text-xs font-bold text-gray-500 mb-1">Foto SKCK (Sample)</span>
                    <a href="#" target="_blank">
                        <img src="https://ui-avatars.com/api/?name=SKCK+Valid&background=0D8ABC&color=fff&size=200" class="h-24 object-cover rounded border hover:opacity-75 transition">
                    </a>
                @endif
            </div>
        </div>

        <!-- 6. Area Coverage -->
        <div class="bg-white shadow rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <h3 class="font-bold text-gray-800">6. Area Coverage <span class="text-sm font-normal text-gray-500">(Bobot 5%)</span></h3>
                <label class="flex items-center space-x-2 bg-white px-3 py-1 border rounded shadow-sm cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" wire:model="verify_area" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <span class="text-sm font-bold text-green-700">Data Valid (+5%)</span>
                </label>
            </div>
            <div class="p-6 text-sm text-gray-700">
                <strong>Domisili Saat Ini:</strong> {{ $data['area']['domicile'] ?? 'DKI Jakarta' }}
            </div>
        </div>

        <!-- 7. Bank/Payment Verification -->
        <div class="bg-white shadow rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <h3 class="font-bold text-gray-800">7. Bank / Payment <span class="text-sm font-normal text-gray-500">(Bobot 5%)</span></h3>
                <label class="flex items-center space-x-2 bg-white px-3 py-1 border rounded shadow-sm cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" wire:model="verify_bank" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <span class="text-sm font-bold text-green-700">Data Valid (+5%)</span>
                </label>
            </div>
            <div class="p-6">
                @if(isset($data['bank']['bank_account_photo']))
                    <span class="block text-xs font-bold text-gray-500 mb-1">Buku Rekening / Mutasi</span>
                    <a href="{{ Storage::url($data['bank']['bank_account_photo']) }}" target="_blank">
                        <img src="{{ Storage::url($data['bank']['bank_account_photo']) }}" class="h-24 object-cover rounded border hover:opacity-75 transition">
                    </a>
                @else
                    <span class="block text-xs font-bold text-gray-500 mb-1">Buku Rekening / Mutasi (Sample)</span>
                    <a href="#" target="_blank">
                        <img src="https://ui-avatars.com/api/?name=Buku+Rekening&background=0D8ABC&color=fff&size=200" class="h-24 object-cover rounded border hover:opacity-75 transition">
                    </a>
                @endif
            </div>
        </div>


        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 sm:gap-4 mt-8 pt-6 border-t border-gray-200">
            <button type="button" wire:click="reject" wire:confirm="Yakin ingin menolak pendaftaran ini?" class="w-full sm:w-auto bg-white py-2 px-6 border border-red-300 rounded-lg shadow-sm text-sm font-bold text-red-700 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                Tolak Pendaftaran
            </button>
            <button type="submit" class="w-full sm:w-auto bg-green-600 border border-transparent rounded-lg shadow-md py-2 px-8 inline-flex justify-center text-sm font-bold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition transform hover:-translate-y-0.5">
                Approve & Hitung Tier
            </button>
        </div>

    </form>
</div>
