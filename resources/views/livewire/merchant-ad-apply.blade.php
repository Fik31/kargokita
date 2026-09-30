<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Tingkatkan Visibilitas Order Anda</h2>
        <p class="mt-4 text-lg text-gray-500">Pasang Iklan di Flash Market dan dapatkan driver lebih cepat dengan jaminan tampil di urutan paling atas.</p>
    </div>

    @if (session()->has('message'))
        <div class="p-4 mb-6 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200 shadow-sm text-center">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 mb-6 text-sm text-blue-800 rounded-xl bg-blue-50 border border-blue-200 shadow-sm text-center">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 flex flex-col md:flex-row">
        
        <!-- Left Side: Pricing & Benefits -->
        <div class="bg-gray-900 text-white p-8 md:w-1/2 flex flex-col justify-center">
            <h3 class="text-2xl font-bold mb-2">Paket Iklan Flash Market</h3>
            <p class="text-gray-400 mb-6">Cocok untuk muatan prioritas (urgent)</p>
            
            <div class="mb-8">
                <span class="text-5xl font-extrabold">Rp 50.000</span>
                <span class="text-xl text-gray-400">/minggu</span>
            </div>
            
            <ul class="space-y-4 mb-8">
                <li class="flex items-center">
                    <svg class="h-6 w-6 text-green-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Posisi fixed (tidak bergeser) di paling atas
                </li>
                <li class="flex items-center">
                    <svg class="h-6 w-6 text-green-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Desain mencolok (highlight khusus)
                </li>
                <li class="flex items-center">
                    <svg class="h-6 w-6 text-green-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Potensi mendapatkan driver 3x lebih cepat
                </li>
            </ul>
        </div>

        <!-- Right Side: Application Status / Form -->
        <div class="p-8 md:w-1/2 flex flex-col justify-center bg-gray-50">
            @if($activeApplication)
                <div class="text-center">
                    @if($activeApplication->status === 'pending')
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 text-yellow-600 mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-2">Menunggu Persetujuan</h4>
                        <p class="text-gray-500 mb-6">Pengajuan iklan Anda sedang ditinjau oleh Administrator. Silakan cek secara berkala.</p>
                        <a href="{{ route('bidding') }}" class="text-brand-blue font-medium hover:underline">Kembali ke Bursa Muatan &rarr;</a>
                    @elseif($activeApplication->status === 'approved')
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 text-green-600 mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-2">Iklan Aktif</h4>
                        <p class="text-gray-500 mb-6">Iklan Anda saat ini sedang tayang di Flash Market. Silakan cek di halaman bursa.</p>
                        <a href="{{ route('bidding') }}" class="text-brand-blue font-medium hover:underline">Lihat Flash Market &rarr;</a>
                    @elseif($activeApplication->status === 'rejected')
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-2">Pengajuan Ditolak</h4>
                        <p class="text-gray-500 mb-4">Maaf, pengajuan iklan Anda sebelumnya ditolak oleh Administrator.</p>
                        
                        <div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl text-sm mb-6 text-left">
                            <strong>Alasan Penolakan:</strong><br>
                            {{ $activeApplication->admin_notes ?? 'Tidak ada alasan yang diberikan.' }}
                        </div>
                        
                        <button wire:click="applyForAd" class="w-full bg-brand-blue hover:bg-blue-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg transition-transform transform hover:scale-105 mb-4">
                            Ajukan Ulang Pemasangan Iklan
                        </button>
                        
                        <div>
                            <a href="{{ route('bidding') }}" class="text-gray-500 text-sm hover:text-gray-700 hover:underline">Kembali ke Bursa Muatan &rarr;</a>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center">
                    <h4 class="text-xl font-bold text-gray-900 mb-4">Mulai Pasang Iklan</h4>
                    <p class="text-gray-500 mb-8 text-sm">Dengan menekan tombol di bawah, Anda mengajukan pemasangan iklan ke Administrator. Tagihan (dummy) akan dikirimkan menyusul.</p>
                    
                    <button wire:click="applyForAd" class="w-full bg-brand-blue hover:bg-blue-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg transition-transform transform hover:scale-105">
                        Ajukan Pemasangan Iklan
                    </button>
                    
                    <div class="mt-6">
                        <a href="{{ route('bidding') }}" class="text-gray-500 text-sm hover:text-gray-700 hover:underline">Kembali</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
