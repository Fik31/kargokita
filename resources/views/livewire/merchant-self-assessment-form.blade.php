<div class="max-w-[90rem] mx-auto p-4 sm:p-6 lg:p-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form Column -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg p-6 mb-6 text-center border-t-4 border-brand-blue">
                <h2 class="text-2xl font-bold text-gray-800">Form Pendaftaran & Verifikasi Merchant</h2>
                <p class="text-sm text-gray-500 mt-2">Lengkapi profil bisnis Anda untuk diverifikasi oleh tim Administrator. Data dikelompokkan (bundling) berdasarkan bobot penilaian. Semakin lengkap dokumen yang Anda unggah, semakin besar peluang mendapatkan Grade Trusted (Grade A).</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 relative">
                    <strong class="font-bold">Oops! Ada data yang belum lengkap.</strong>
                    <span class="block sm:inline">Mohon periksa kembali form di bawah, lengkapi seluruh field untuk memenuhi persyaratan bobot.</span>
                </div>
            @endif

            <form wire:submit.prevent="submit" class="space-y-6">
                @php $sectionIndex = 1; @endphp
                @foreach($criteriaGrouped as $category => $items)
                    @php
                        $categoryWeight = collect($items)->sum('weight');
                    @endphp
                    <div class="bg-white shadow rounded-lg p-6 border border-gray-100">
                        <div class="flex items-center justify-between border-b pb-3 mb-4">
                            <h3 class="text-lg font-bold text-gray-800">{{ $sectionIndex++ }}. {{ ucwords(strtolower($category)) }}</h3>
                            <span class="bg-blue-100 text-blue-800 font-bold px-3 py-1 rounded text-sm">Bobot: {{ rtrim(rtrim(number_format($categoryWeight, 2), '0'), '.') }}%</span>
                        </div>
                        
                        @if($category === 'LEGAL IDENTITY')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 pb-6 border-b border-gray-100">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Nama Perusahaan / Bisnis <span class="text-red-500 text-xs ml-1">*Wajib</span></label>
                                    <input type="text" wire:model="company_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200" placeholder="PT ABC atau Toko XYZ">
                                    @error('company_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Alamat Lengkap <span class="text-red-500 text-xs ml-1">*Wajib</span></label>
                                    <textarea wire:model="company_address" rows="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200" placeholder="Alamat Perusahaan"></textarea>
                                    @error('company_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Foto Dokumen NIB <span class="text-red-500 text-xs ml-1">*Wajib</span></label>
                                    <input type="file" wire:model="nib_photo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    @error('nib_photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Foto Dokumen NPWP <span class="text-red-500 text-xs ml-1">*Wajib</span></label>
                                    <input type="file" wire:model="npwp_photo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    @error('npwp_photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @elseif($category === 'PIC VERIFICATION')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 pb-6 border-b border-gray-100">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Nama Lengkap PIC <span class="text-red-500 text-xs ml-1">*Wajib</span></label>
                                    <input type="text" wire:model="pic_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200" placeholder="Nama Penanggung Jawab">
                                    @error('pic_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Nomor HP PIC <span class="text-red-500 text-xs ml-1">*Wajib</span></label>
                                    <input type="text" wire:model="pic_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200" placeholder="Contoh: 08123456789">
                                    @error('pic_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Email PIC <span class="text-red-500 text-xs ml-1">*Wajib</span></label>
                                    <input type="email" wire:model="pic_email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200" placeholder="email@contoh.com">
                                    @error('pic_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Kode OTP <span class="text-red-500 text-xs ml-1">*Wajib</span></label>
                                    <input type="text" wire:model="pic_otp" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200" placeholder="Kode verifikasi OTP">
                                    @error('pic_otp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700">Foto KTP / ID PIC <span class="text-red-500 text-xs ml-1">*Wajib</span></label>
                                    <input type="file" wire:model="pic_ktp_photo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    @error('pic_ktp_photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @elseif($category === 'OPERATIONAL READINESS')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 pb-6 border-b border-gray-100">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700">Lokasi Pickup Utama <span class="text-red-500 text-xs ml-1">*Wajib</span></label>
                                    <textarea wire:model="op_pickup_location" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200" placeholder="Alamat gudang / lokasi penjemputan barang"></textarea>
                                    @error('op_pickup_location') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Jam Operasional <span class="text-red-500 text-xs ml-1">*Wajib</span></label>
                                    <input type="text" wire:model="op_operational_hours" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200" placeholder="Senin-Jumat: 08.00-17.00">
                                    @error('op_operational_hours') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Kesiapan Loading <span class="text-red-500 text-xs ml-1">*Wajib</span></label>
                                    <input type="text" wire:model="op_loading_readiness" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200" placeholder="Contoh: Forklift tersedia, space luas">
                                    @error('op_loading_readiness') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Contact Person Lapangan <span class="text-red-500 text-xs ml-1">*Wajib</span></label>
                                    <input type="text" wire:model="op_contact_person" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200" placeholder="Nama & No HP orang lapangan">
                                    @error('op_contact_person') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Foto Lokasi (Gudang/Loading) <span class="text-red-500 text-xs ml-1">*Wajib</span></label>
                                    <input type="file" wire:model="op_location_photo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    @error('op_location_photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($items as $criterion)
                                @if(in_array($criterion->code, ['M01', 'M02', 'M07']))
                                    @continue
                                @endif
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                                        {{ $criterion->name }}
                                        @if($criterion->is_mandatory)
                                            <span class="text-red-500 text-xs ml-1">*Wajib</span>
                                        @else
                                            <span class="text-gray-400 text-xs ml-1">(Opsional)</span>
                                        @endif
                                    </label>
                                    
                                    <div class="mt-2">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Upload Bukti ({{ $criterion->evidence_minimum ?? 'File' }})</label>
                                        <input type="file" wire:model="answers.{{ $criterion->id }}.evidence" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        <div wire:loading wire:target="answers.{{ $criterion->id }}.evidence" class="text-xs text-brand-blue mt-1 font-semibold">Uploading...</div>
                                        @error('answers.'.$criterion->id.'.evidence') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div class="mt-3">
                                        <textarea wire:model="answers.{{ $criterion->id }}.notes" rows="2" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200 text-sm" placeholder="Opsional: Tambahkan keterangan jika perlu..."></textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                    <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-blue-600 to-blue-800 border border-transparent rounded-lg shadow-md py-3 px-10 text-base font-bold text-white hover:from-blue-700 hover:to-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-blue transition transform hover:-translate-y-0.5">
                        Kirim Data untuk Verifikasi Admin
                    </button>
                </div>
            </form>
        </div>

        <!-- Sidebar Deposit Jaminan -->
        <div class="lg:col-span-1">
            <div class="bg-gradient-to-br from-brand-blue to-blue-900 shadow-xl rounded-2xl p-6 text-white sticky top-6">
                <h3 class="text-xl font-bold mb-2">Informasi Penting</h3>
                <p class="text-blue-100 text-sm mb-6">Wajib bagi semua Merchant untuk menyetor Deposit Jaminan. Menjamin keseriusan Anda di ekosistem Kargokita.</p>
                
                <div class="mb-6 border-b border-white/20 pb-6">
                    <span class="text-3xl font-extrabold">Rp 1.000.000</span>
                    <span class="text-sm text-blue-200 block mt-2 leading-relaxed">Deposit ini dibayarkan <strong>setelah</strong> Anda mengirimkan form ini. Akun Anda baru akan aktif setelah lunas.</span>
                </div>
                
                <div class="bg-white/10 rounded-xl p-4 mb-6 border border-white/20">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-yellow-300 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm font-medium leading-relaxed">Garansi 100% uang kembali jika selama 1 tahun berturut-turut Anda tidak mendapatkan armada di platform ini.</p>
                    </div>
                </div>

                <h4 class="font-bold mb-3 text-sm uppercase tracking-wider text-blue-200">Benefit Member Resmi:</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-yellow-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Komisi 10% Member-get-Member
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-yellow-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Dedicated Driver Recommendation
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-yellow-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Prioritas Masuk ke Top Bid & Pasang Iklan
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
