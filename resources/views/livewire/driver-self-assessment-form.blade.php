<div class="max-w-[90rem] mx-auto p-4 sm:p-6 lg:p-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form Column -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg p-6 mb-6 text-center border-t-4 border-brand-blue">
                <h2 class="text-2xl font-bold text-gray-800">HSE Audit Assessment</h2>
                <p class="text-sm text-gray-500 mt-2">Form ini digunakan untuk kebutuhan audit HSE secara mendalam.</p>
            </div>

    <!-- Basic Vehicle Info -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Informasi Kendaraan Anda</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block font-semibold text-sm text-gray-700 mb-2">Plat Nomor Kendaraan</label>
                <input type="text" wire:model="license_plate" class="block w-full border-gray-300 rounded-md px-4 py-2 text-gray-800 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition uppercase font-mono" placeholder="B 1234 CD">
                @error('license_plate') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-semibold text-sm text-gray-700 mb-2">Tipe Kendaraan</label>
                <select wire:model="vehicle_type" class="block w-full border-gray-300 rounded-md px-4 py-2 text-gray-800 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition cursor-pointer">
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
                @error('vehicle_type') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="border-b border-gray-200 mb-6 bg-white shadow rounded-t-lg px-4">
        <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
            @foreach($criteriaGrouped as $category => $items)
                <button 
                    wire:click="$set('activeTab', '{{ $category }}')"
                    type="button"
                    class="{{ $activeTab === $category ? 'border-brand-blue text-brand-blue' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm transition-colors duration-150 flex items-center">
                    {{ $category }}
                    <span class="ml-2 {{ $activeTab === $category ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-900' }} py-0.5 px-2 rounded-full text-xs">{{ count($items) }}</span>
                </button>
            @endforeach
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="bg-white shadow rounded-b-lg p-6">
        <form wire:submit.prevent="submit">
            <div class="space-y-4">
                <!-- Desktop Header (hidden on mobile) -->
                <div class="hidden md:grid grid-cols-12 gap-4 bg-gray-50 p-4 rounded-t-lg border-b border-gray-200">
                    <div class="col-span-4 font-semibold text-sm text-gray-900">Kriteria Penilaian</div>
                    <div class="col-span-2 font-semibold text-sm text-gray-900 text-center">Wajib?</div>
                    <div class="col-span-3 font-semibold text-sm text-gray-900">Upload Bukti / Foto</div>
                    <div class="col-span-3 font-semibold text-sm text-gray-900">Keterangan Tambahan</div>
                </div>

                <!-- Mobile Header -->
                <div class="md:hidden flex justify-between items-center bg-gray-50 p-4 rounded-t-lg border-b border-gray-200">
                    <span class="font-semibold text-sm text-gray-900">Kriteria Penilaian</span>
                    <span class="font-semibold text-sm text-gray-900">Wajib?</span>
                </div>

                <div class="flex flex-col gap-4">
                    @if(isset($criteriaGrouped[$activeTab]))
                        @foreach($criteriaGrouped[$activeTab] as $criterion)
                            <div class="bg-white border-b md:border border-gray-200 md:rounded-lg pb-4 md:p-4 hover:bg-gray-50 transition md:grid md:grid-cols-12 md:gap-4 md:items-start flex flex-col gap-4">
                                <!-- Mobile View: Title & Mandatory -->
                                <div class="md:hidden flex justify-between items-start gap-4">
                                    <div class="flex-1">
                                        <div class="font-bold text-gray-900 text-sm">{{ $criterion->code }} - {{ $criterion->name }}</div>
                                        <div class="text-xs text-gray-500 mt-1">Bobot: {{ $criterion->weight }}%</div>
                                    </div>
                                    <div class="shrink-0">
                                        @if($criterion->is_mandatory)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-800">
                                                Wajib
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                Opsional
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Desktop View: Title & Mandatory -->
                                <div class="hidden md:block md:col-span-4">
                                    <div class="font-bold text-gray-900 text-sm">{{ $criterion->code }} - {{ $criterion->name }}</div>
                                    <div class="text-xs text-gray-500 mt-1">Bobot: {{ $criterion->weight }}%</div>
                                </div>
                                <div class="hidden md:flex md:col-span-2 justify-center items-start pt-1">
                                    @if($criterion->is_mandatory)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-800">
                                            Wajib
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            Opsional
                                        </span>
                                    @endif
                                </div>

                                <!-- File Upload -->
                                <div class="md:col-span-3">
                                    <div class="mt-1 flex justify-center px-4 py-4 border-2 border-gray-300 border-dashed rounded-md relative overflow-hidden bg-white hover:bg-gray-50 transition">
                                        @if(isset($answers[$criterion->id]['evidence']) && $answers[$criterion->id]['evidence'])
                                            <div class="text-center w-full">
                                                <span class="block text-green-500 mb-1">
                                                    <svg class="mx-auto h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </span>
                                                <span class="text-xs font-medium text-gray-900">File terpilih</span>
                                            </div>
                                        @else
                                            <div class="space-y-1 text-center">
                                                <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <div class="flex text-sm text-gray-600 justify-center">
                                                    <label class="relative cursor-pointer bg-white rounded-md font-medium text-brand-blue hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-brand-blue">
                                                        <span>Upload file</span>
                                                        <input type="file" wire:model="answers.{{ $criterion->id }}.evidence" class="sr-only">
                                                    </label>
                                                </div>
                                                <p class="text-xs text-gray-500">PNG, JPG up to 5MB</p>
                                            </div>
                                        @endif
                                        
                                        <div wire:loading wire:target="answers.{{ $criterion->id }}.evidence" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center">
                                            <span class="text-xs font-bold text-brand-blue">Uploading...</span>
                                        </div>
                                    </div>
                                    @error('answers.'.$criterion->id.'.evidence') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Notes -->
                                <div class="md:col-span-3">
                                    <textarea wire:model="answers.{{ $criterion->id }}.notes" rows="3" class="shadow-sm focus:ring-brand-blue focus:border-brand-blue block w-full sm:text-sm border-gray-300 rounded-md placeholder-gray-400" placeholder="Opsional: Tambahkan catatan jika perlu..."></textarea>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                <button type="button" class="bg-white py-2 px-6 border border-gray-300 rounded-lg shadow-sm text-sm font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-blue transition">
                    Batal
                </button>
                <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-800 border border-transparent rounded-lg shadow-md py-2 px-8 inline-flex justify-center text-sm font-bold text-white hover:from-blue-700 hover:to-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-blue transition transform hover:-translate-y-0.5">
                    Kirim Data untuk Verifikasi HSE
                </button>
            </div>
        </form>
    </div>
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

                <h4 class="font-bold mb-3 text-sm uppercase tracking-wider text-blue-200">Benefit Member Resmi:</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-yellow-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Akses Bidding LTL & Prioritas Muatan
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-yellow-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Komisi Referral / Agen Langsung
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-yellow-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Klaim Biaya Branding (Stiker & Pajak)
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-yellow-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Akses Fitur Rating & Sosial
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
