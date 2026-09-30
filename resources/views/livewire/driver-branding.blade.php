<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-8" id="tour-branding-header">
        <h2 class="text-2xl font-bold text-gray-900">Program Branding VIP</h2>
        <p class="text-sm text-gray-500">Pasang stiker Lion Parcel di armada Anda dan dapatkan komisi tambahan serta bantuan biaya pajak tahunan.</p>
    </div>

    @if (session()->has('message'))
        <div class="p-4 mb-6 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 mb-6 text-sm text-blue-800 rounded-xl bg-blue-50 border border-blue-200">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Info Card -->
        <div class="lg:col-span-1 space-y-6" id="tour-branding-apply">
            <div class="bg-gradient-to-br from-brand-blue to-blue-900 text-white rounded-3xl p-6 shadow-lg">
                <h3 class="text-xl font-bold mb-4">Keuntungan Branding</h3>
                <ul class="space-y-3 text-sm text-blue-100">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-400 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Tambahan komisi bulanan
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-400 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Subsidi pajak kendaraan
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-400 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Prioritas muatan perusahaan
                    </li>
                </ul>
                <div class="mt-6 pt-6 border-t border-blue-700">
                    <button wire:click="apply" class="w-full bg-white text-brand-blue font-bold py-3 px-4 rounded-xl shadow hover:bg-gray-50 transition">
                        Ajukan Sekarang
                    </button>
                </div>
            </div>
            
            <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-2xl">
                <h4 class="text-sm font-bold text-yellow-800 mb-2">Perhatian</h4>
                <p class="text-xs text-yellow-700">Setelah pengajuan, Anda wajib mengunggah foto bukti pemasangan stiker di armada Anda dalam waktu maksimal 7 hari.</p>
            </div>
        </div>

        <!-- Applications List -->
        <div class="lg:col-span-2" id="tour-branding-history">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900">Riwayat Pengajuan Branding</h3>
                </div>
                
                <div class="divide-y divide-gray-100">
                    @forelse($applications as $app)
                        <div class="p-6">
                            <div class="flex flex-col md:flex-row md:items-center justify-between mb-4">
                                <div>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold mb-2
                                        @if($app->status === 'pengajuan') bg-blue-100 text-blue-800
                                        @elseif($app->status === 'ditinjau') bg-yellow-100 text-yellow-800
                                        @elseif($app->status === 'diterima') bg-green-100 text-green-800
                                        @elseif($app->status === 'dicairkan' || $app->status === 'terkirim') bg-brand-blue text-white
                                        @else bg-gray-100 text-gray-800 @endif
                                    ">
                                        {{ strtoupper($app->status) }}
                                    </span>
                                    <p class="text-sm text-gray-500">Tanggal Pengajuan: {{ $app->applied_at->format('d M Y') }}</p>
                                </div>
                                <div class="mt-2 md:mt-0">
                                    @if($app->status === 'pengajuan')
                                        <p class="text-xs font-bold text-brand-blue">Tenggat Upload: {{ $app->deadline->format('d M Y') }}</p>
                                    @endif
                                </div>
                            </div>

                            @if($app->status === 'pengajuan')
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                    <form wire:submit.prevent="uploadProof({{ $app->id }})">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Unggah Foto Kendaraan (Terlihat Stiker)</label>
                                        <input type="file" wire:model="proof_image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-brand-blue hover:file:bg-blue-100 mb-4" required>
                                        @error('proof_image') <span class="text-red-500 text-xs block mb-2">{{ $message }}</span> @enderror
                                        <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-800">
                                            Kirim Bukti
                                        </button>
                                    </form>
                                </div>
                            @elseif($app->proof_image)
                                <div class="mt-4 flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <p class="text-xs font-medium text-gray-500 mb-1">Bukti Foto</p>
                                        <img src="{{ Storage::url($app->proof_image) }}" class="w-32 h-24 object-cover rounded-lg border border-gray-200">
                                    </div>
                                    @if($app->admin_notes)
                                        <div class="bg-blue-50 p-3 rounded-lg border border-blue-100 flex-1">
                                            <p class="text-xs font-bold text-blue-800 mb-1">Catatan Admin:</p>
                                            <p class="text-sm text-blue-900">{{ $app->admin_notes }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            Belum ada pengajuan branding.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.js.iife.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isDriverUser = @json(Auth::user()->hasRole('driver'));
        
        if (isDriverUser) {
            const driver = window.driver.js.driver;
            
            const driverObj = driver({
                showProgress: true,
                animate: true,
                doneBtnText: 'Oke, Saya Mengerti',
                closeBtnText: 'Skip Tutorial',
                nextBtnText: 'Selanjutnya',
                prevBtnText: 'Kembali',
                steps: [
                    {
                        element: '#tour-branding-header',
                        popover: {
                            title: 'Program Branding VIP',
                            description: 'Bergabung dengan program Branding VIP untuk mendapatkan komisi tambahan dan subsidi pajak kendaraan.',
                            side: "bottom",
                            align: 'start'
                        }
                    },
                    {
                        element: '#tour-branding-apply',
                        popover: {
                            title: 'Pengajuan & Keuntungan',
                            description: 'Anda bisa mengajukan pendaftaran di sini. Jika disetujui, Anda wajib mengunggah bukti foto pemasangan stiker.',
                            side: "bottom",
                            align: 'center'
                        }
                    },
                    {
                        element: '#tour-branding-history',
                        popover: {
                            title: 'Riwayat & Status',
                            description: 'Semua riwayat pengajuan dan status persetujuan dari Admin (termasuk upload bukti foto) akan tampil di kotak ini.',
                            side: "top",
                            align: 'center'
                        }
                    }
                ],
                onDestroyStarted: () => {
                    if (!driverObj.hasNextStep() || confirm("Skip tutorial ini?")) {
                        driverObj.destroy();
                    }
                },
            });
            
            setTimeout(() => {
                driverObj.drive();
            }, 500);
        }
    });
</script>
@endpush
