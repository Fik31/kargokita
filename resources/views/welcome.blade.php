<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KargoKita - Solusi Logistik B2B Terbaik & Transparan</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('assets/images/icon.jpg') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="antialiased bg-gray-50 text-gray-900 selection:bg-blue-200 selection:text-blue-900 overflow-x-hidden">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-xl border-b border-gray-100 transition-all duration-300 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="#" class="flex-shrink-0 flex items-center gap-3 group">
                    <img src="{{ asset('assets/images/logo-white3.png') }}" alt="Kargokita Logo" class="w-48 sm:w-64 md:w-[320px] h-auto object-cover object-left">
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#tentang" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Tentang</a>
                    <a href="#masalah" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Solusi</a>
                    <a href="#pengguna" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Mitra</a>
                    <a href="#alur" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Cara Kerja</a>
                    
                    @auth
                        <a href="{{ url('/feed') }}" class="font-bold text-gray-900 hover:text-blue-600 transition-colors">Dashboard Anda</a>
                    @else
                        <div class="flex items-center space-x-4 pl-4 border-l border-gray-200">
                            <a href="{{ route('login') }}" class="text-gray-900 font-medium hover:text-blue-600 transition-colors">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-full font-bold transition-all shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5">Daftar Gratis</a>
                            @endif
                        </div>
                    @endauth
                </div>
                
                <!-- Mobile Menu Toggle -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-gray-600 hover:text-blue-600 focus:outline-none p-2">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Dropdown -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-b border-gray-100 shadow-xl absolute top-20 left-0 w-full z-40">
            <div class="px-4 pt-2 pb-6 space-y-2 flex flex-col">
                <a href="#tentang" class="block px-4 py-3 text-gray-700 font-medium rounded-lg hover:bg-blue-50 hover:text-blue-600 transition">Tentang</a>
                <a href="#masalah" class="block px-4 py-3 text-gray-700 font-medium rounded-lg hover:bg-blue-50 hover:text-blue-600 transition">Solusi</a>
                <a href="#pengguna" class="block px-4 py-3 text-gray-700 font-medium rounded-lg hover:bg-blue-50 hover:text-blue-600 transition">Mitra</a>
                <a href="#alur" class="block px-4 py-3 text-gray-700 font-medium rounded-lg hover:bg-blue-50 hover:text-blue-600 transition">Cara Kerja</a>
                <div class="border-t border-gray-100 my-2 pt-4 flex flex-col gap-3">
                    @auth
                        <a href="{{ url('/feed') }}" class="block px-4 py-3 font-bold text-white bg-blue-600 text-center rounded-xl shadow-md">Dashboard Anda</a>
                    @else
                        <a href="{{ route('login') }}" class="block px-4 py-3 text-gray-700 font-medium text-center rounded-xl border border-gray-200 hover:bg-gray-50 transition">Masuk</a>
                        <a href="{{ route('register') }}" class="block px-4 py-3 bg-blue-600 text-white font-bold rounded-xl text-center shadow-lg shadow-blue-600/30 transition">Daftar Gratis</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <script>
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            var menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
        
        // Hide mobile menu on click
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('mobile-menu').classList.add('hidden');
            });
        });
    </script>

    <!-- Hero Section -->
    <div class="relative pt-28 pb-16 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-b from-blue-50/80 via-white to-gray-50"></div>
            <!-- Decorative blobs -->
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-[300px] md:w-[600px] h-[300px] md:h-[600px] rounded-full bg-blue-100/50 blur-[60px] md:blur-[100px] opacity-70 animate-pulse-slow"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-[250px] md:w-[500px] h-[250px] md:h-[500px] rounded-full bg-blue-200/40 blur-[50px] md:blur-[80px] opacity-60"></div>
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-12 items-center">
                <div class="text-center lg:text-left pt-4 lg:pt-0">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-100/80 text-blue-700 font-semibold text-xs sm:text-sm mb-6 border border-blue-200 shadow-sm">
                        <span class="flex h-2 w-2 rounded-full bg-blue-600 animate-pulse"></span>
                        Platform Lelang Kargo No.1 di Indonesia
                    </div>
                    <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tight text-gray-900 mb-6 leading-[1.2] lg:leading-[1.1]">
                        Hubungkan Muatan & Armada dalam <br class="hidden lg:block" />
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-blue-400">Satu Ketukan.</span>
                    </h1>
                    <p class="mt-4 max-w-2xl text-lg sm:text-xl text-gray-600 mb-8 sm:mb-10 leading-relaxed mx-auto lg:mx-0">

                        Maksimalkan efisiensi pengiriman barang Anda dengan sistem lelang cerdas, harga kompetitif, dan pelacakan real-time. Tidak ada lagi truk yang pulang kosong!
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center lg:justify-start items-center gap-4">
                        <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-full font-bold text-lg transition-all shadow-xl shadow-blue-600/30 hover:shadow-blue-600/50 hover:-translate-y-1 flex items-center justify-center gap-2 group">
                            Mulai Berbisnis
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                        <a href="#masalah" class="w-full sm:w-auto px-8 py-4 bg-white text-gray-900 hover:text-blue-600 rounded-full font-bold text-lg border-2 border-gray-200 hover:border-blue-200 transition-all shadow-sm">
                            Lihat Keunggulan
                        </a>
                    </div>
                    
                    <div class="mt-12 grid grid-cols-3 gap-4 border-t border-gray-200 pt-8 max-w-xl mx-auto lg:mx-0">
                        <div>
                            <div class="text-3xl font-black text-gray-900">10k+</div>
                            <div class="text-sm font-medium text-gray-500 mt-1">Transaksi Berhasil</div>
                        </div>
                        <div>
                            <div class="text-3xl font-black text-gray-900">5k+</div>
                            <div class="text-sm font-medium text-gray-500 mt-1">Armada Aktif</div>
                        </div>
                        <div>
                            <div class="text-3xl font-black text-gray-900">24/7</div>
                            <div class="text-sm font-medium text-gray-500 mt-1">Dukungan Sistem</div>
                        </div>
                    </div>
                </div>
                
                <div class="relative hidden lg:block">
                    <div class="absolute inset-0 bg-gradient-to-tr from-blue-600 to-blue-400 rounded-[2.5rem] transform rotate-3 opacity-20 scale-105"></div>
                    <img src="https://images.unsplash.com/photo-1519003722824-194d4455a60c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Logistik Truk" class="relative rounded-[2.5rem] shadow-2xl object-cover h-[600px] w-full border-8 border-white" />
                    
                    <!-- Floating Badge -->
                    <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-2xl shadow-xl border border-gray-100 flex items-center gap-4 animate-bounce-slow">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900">Deal Tercepat</div>
                            <div class="text-sm text-gray-500">Rata-rata di bawah 5 menit</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Apa itu KargoKita Section -->
    <section id="tentang" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-blue-600 font-bold tracking-wider uppercase text-sm mb-3">TENTANG KARGOKITA</h2>
                <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Logistics Provider (LP) Digital Masa Depan</h3>
                <p class="text-lg text-gray-600 leading-relaxed">
                    KargoKita adalah platform B2B yang merevolusi cara kerja pengiriman barang antar kota dan pulau. Kami tidak memiliki armada sendiri, melainkan bertindak sebagai <strong>penghubung pintar</strong> antara pemilik muatan dengan pemilik armada melalui sistem lelang transparan (bidding).
                </p>
            </div>
        </div>
    </section>

    <!-- Masalah yang Kami Selesaikan (The Problems) -->
    <section id="masalah" class="py-24 bg-gray-50 border-y border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-blue-600 font-bold tracking-wider uppercase text-sm mb-3">MASALAH YANG KAMI SELESAIKAN</h2>
                <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Mengubah Pain Points Menjadi Keuntungan</h3>
                <p class="text-lg text-gray-600">Industri logistik tradisional penuh dengan inefisiensi. KargoKita hadir untuk menyelesaikan masalah-masalah utama berikut:</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Problem 1 -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl hover:border-blue-100 transition-all group">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Truk Pulang Kosong (Empty Return)</h4>
                    <p class="text-gray-600">Truk sering kali kembali ke gudang tanpa muatan, membuang bahan bakar dan waktu.</p>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <span class="text-sm font-bold text-green-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Solusi KargoKita: Pasar lelang muatan
                        </span>
                    </div>
                </div>

                <!-- Problem 2 -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl hover:border-blue-100 transition-all group">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Harga Tidak Transparan</h4>
                    <p class="text-gray-600">Proses tawar-menawar lewat telepon memakan waktu berjam-jam tanpa jaminan harga terbaik.</p>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <span class="text-sm font-bold text-green-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Solusi KargoKita: Bidding Real-time
                        </span>
                    </div>
                </div>

                <!-- Problem 3 -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl hover:border-blue-100 transition-all group">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Ketiadaan Pelacakan (Blank Spot)</h4>
                    <p class="text-gray-600">Pemilik barang tidak tahu dimana posisi armada setelah barang diberangkatkan dari gudang.</p>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <span class="text-sm font-bold text-green-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Solusi KargoKita: Live Tracking via Aplikasi
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Siapa yang Bisa Pakai (User Roles) -->
    <section id="pengguna" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-blue-600 font-bold tracking-wider uppercase text-sm mb-3">PENGGUNA PLATFORM KAMI</h2>
                <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Siapa Saja yang Bisa Bergabung?</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Merchant Card -->
                <div class="flex flex-col bg-blue-50 rounded-3xl p-8 border border-blue-100">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 bg-blue-600 text-white rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-2xl font-black text-gray-900">Merchant / Shipper</h4>
                            <p class="text-blue-700 font-medium">Pemilik Pabrik, Gudang, atau Distributor</p>
                        </div>
                    </div>
                    <ul class="space-y-4 mb-8 flex-1">
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-gray-700">Buat permintaan pengiriman (posting muatan) kapan saja.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-gray-700">Dapatkan puluhan tawaran harga terbaik dari berbagai driver terverifikasi.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-gray-700">Lacak perjalanan kargo Anda secara langsung melalui Cockpit tracking.</span>
                        </li>
                    </ul>
                </div>

                <!-- Driver Card -->
                <div class="flex flex-col bg-gray-900 rounded-3xl p-8 border border-gray-800 shadow-xl text-white">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 bg-white text-gray-900 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-2xl font-black text-white">Driver / Transporter</h4>
                            <p class="text-gray-400 font-medium">Pemilik Armada Independen atau Perusahaan Ekspedisi</p>
                        </div>
                    </div>
                    <ul class="space-y-4 mb-8 flex-1">
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-gray-300">Cari muatan di pasar lelang untuk arah pulang atau rute reguler Anda.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-gray-300">Tawar harga sesuai keinginan Anda (Bidding) untuk memenangkan order.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-gray-300">Nikmati keuntungan tambahan dengan program Deposit Jaminan dan Iklan Top Bid.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- App Flow Section -->
    <section id="alur" class="py-24 bg-gray-50 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-blue-600 font-bold tracking-wider uppercase text-sm mb-3">BAGAIMANA CARA KERJANYA?</h2>
                <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Sistem yang Sederhana & Aman</h3>
                <p class="text-lg text-gray-600">Alur yang dirancang sesederhana mungkin untuk menghubungkan Merchant dan Driver dengan cepat dan terlindungi 100%.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 relative">
                <!-- Step 1 -->
                <div class="relative z-10 flex flex-col items-center text-center">
                    <div class="w-20 h-20 bg-white rounded-3xl shadow-lg flex items-center justify-center border border-gray-100 mb-6 group hover:border-blue-600 hover:bg-blue-600 transition-all duration-300">
                        <span class="text-3xl font-black text-gray-300 group-hover:text-white transition-colors">1</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Registrasi</h3>
                    <p class="text-gray-600 text-sm">Buat akun Merchant/Driver. Tim Admin KargoKita akan memverifikasi identitas Anda demi keamanan bersama.</p>
                </div>
                
                <!-- Step 2 -->
                <div class="relative z-10 flex flex-col items-center text-center">
                    <div class="w-20 h-20 bg-white rounded-3xl shadow-lg flex items-center justify-center border border-gray-100 mb-6 group hover:border-blue-600 hover:bg-blue-600 transition-all duration-300">
                        <span class="text-3xl font-black text-gray-300 group-hover:text-white transition-colors">2</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Posting & Bidding</h3>
                    <p class="text-gray-600 text-sm">Merchant memposting detail muatan. Muatan tersebut masuk ke Pasar Lelang, dan Driver mulai memberikan penawaran harga.</p>
                </div>
                
                <!-- Step 3 -->
                <div class="relative z-10 flex flex-col items-center text-center">
                    <div class="w-20 h-20 bg-white rounded-3xl shadow-lg flex items-center justify-center border border-gray-100 mb-6 group hover:border-blue-600 hover:bg-blue-600 transition-all duration-300">
                        <span class="text-3xl font-black text-gray-300 group-hover:text-white transition-colors">3</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Deal Ditetapkan</h3>
                    <p class="text-gray-600 text-sm">Admin LP menyeleksi dan menetapkan pemenang lelang (bid terbaik). Status berubah menjadi Proses Trip.</p>
                </div>
                
                <!-- Step 4 -->
                <div class="relative z-10 flex flex-col items-center text-center">
                    <div class="w-20 h-20 bg-white rounded-3xl shadow-lg flex items-center justify-center border border-gray-100 mb-6 group hover:border-blue-600 hover:bg-blue-600 transition-all duration-300">
                        <span class="text-3xl font-black text-gray-300 group-hover:text-white transition-colors">4</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Pengiriman Selesai</h3>
                    <p class="text-gray-600 text-sm">Driver mengantarkan barang. Merchant melacak perjalanan via aplikasi. Jika selesai, riwayat tersimpan permanen.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Marketing Pitch / Final CTA -->
    <section class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-gray-900"></div>
        <div class="absolute inset-0 bg-blue-600 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h2 class="text-4xl md:text-5xl font-black text-white mb-6 leading-tight">
                Tinggalkan Cara Lama.<br />Beralih ke Logistik Digital Sekarang!
            </h2>
            <p class="text-xl text-gray-300 mb-10 leading-relaxed">
                Ribuan merchant telah menghemat biaya logistik mereka, dan ribuan driver telah melipatgandakan pendapatan mereka. Jangan biarkan kompetitor Anda melangkah lebih dulu. Buat akun Anda dalam 2 menit, <strong>GRATIS selamanya</strong>.
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-10 py-5 bg-blue-600 hover:bg-blue-700 text-white rounded-full font-black text-xl transition-all shadow-xl shadow-blue-600/50 hover:scale-105">
                    Daftar Akun Gratis
                </a>
            </div>
            <p class="mt-6 text-sm text-gray-400">Termasuk fitur dasar untuk Bidding, Chat, dan Tracking.</p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-950 text-white py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center md:text-left">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/images/logo-white2.png') }}" alt="Kargokita Logo" class="h-8 w-auto">
                </div>
                
                <div class="flex gap-6 text-sm font-medium text-gray-400">
                    <a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-white transition-colors">Pusat Bantuan</a>
                </div>
            </div>
            
            <div class="mt-8 pt-8 border-t border-gray-800 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} Kargokita. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>
