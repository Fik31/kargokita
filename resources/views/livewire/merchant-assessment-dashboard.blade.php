<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <div class="bg-indigo-900 text-white p-6 rounded-lg shadow-lg mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold">DASHBOARD MERCHANT ADMINISTRATOR</h1>
            <p class="text-sm mt-2 text-indigo-100">Overview hasil verifikasi dan tiering profil bisnis merchant.</p>
        </div>
        <div class="text-right hidden sm:block">
            <div class="text-xs text-indigo-200 uppercase tracking-widest font-bold">Rata-rata Skor</div>
            <div class="text-4xl font-black text-yellow-400">{{ $avgScore }}</div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Grade A: Trusted -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col items-center justify-center relative overflow-hidden group hover:shadow-md transition">
            <div class="absolute top-0 w-full h-1 bg-yellow-400"></div>
            <div class="bg-yellow-100 text-yellow-600 p-3 rounded-full mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            </div>
            <h3 class="text-gray-500 text-sm font-bold uppercase tracking-wider mb-1">Grade A (Trusted)</h3>
            <span class="text-3xl font-black text-gray-900">{{ $trustedCount }}</span>
        </div>

        <!-- Grade B: Verified -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col items-center justify-center relative overflow-hidden group hover:shadow-md transition">
            <div class="absolute top-0 w-full h-1 bg-gray-400"></div>
            <div class="bg-gray-100 text-gray-600 p-3 rounded-full mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-gray-500 text-sm font-bold uppercase tracking-wider mb-1">Grade B (Verified)</h3>
            <span class="text-3xl font-black text-gray-900">{{ $verifiedCount }}</span>
        </div>

        <!-- Grade C: Basic -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col items-center justify-center relative overflow-hidden group hover:shadow-md transition">
            <div class="absolute top-0 w-full h-1 bg-orange-400"></div>
            <div class="bg-orange-100 text-orange-600 p-3 rounded-full mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-gray-500 text-sm font-bold uppercase tracking-wider mb-1">Grade C (Basic)</h3>
            <span class="text-3xl font-black text-gray-900">{{ $basicCount }}</span>
        </div>

        <!-- Grade D: Restricted -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col items-center justify-center relative overflow-hidden group hover:shadow-md transition">
            <div class="absolute top-0 w-full h-1 bg-red-500"></div>
            <div class="bg-red-100 text-red-600 p-3 rounded-full mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
            </div>
            <h3 class="text-gray-500 text-sm font-bold uppercase tracking-wider mb-1">Grade D (Restricted)</h3>
            <span class="text-3xl font-black text-gray-900">{{ $restrictedCount }}</span>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Verifikasi Tertunda -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg overflow-hidden border-t-4 border-indigo-500">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800">Menunggu Verifikasi</h3>
                    <a href="{{ route('admin.merchant-assessment.verification-list') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800">Lihat Semua &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Merchant</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($pendingAssessments->take(5) as $assessment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $assessment->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $assessment->driver ? $assessment->driver->name : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.merchant-assessment.form', ['assessment_id' => $assessment->id]) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1 rounded">Proses</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center italic">
                                        Hore! Tidak ada merchant yang menunggu verifikasi saat ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar / Tindak Lanjut CA -->
        <div>
            <div class="bg-white shadow rounded-lg overflow-hidden border-t-4 border-red-500">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800">Status Tindak Lanjut (CA)</h3>
                    <p class="text-xs text-gray-500 mt-1">Dokumen / perbaikan profil yang perlu dilengkapi merchant</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Open -->
                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-100">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <span class="font-bold text-red-800 text-sm">OPEN (Belum Ditindaklanjuti)</span>
                            </div>
                            <span class="font-black text-red-600">{{ $caOpen }}</span>
                        </div>
                        
                        <!-- In Progress -->
                        <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-100">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                                <span class="font-bold text-yellow-800 text-sm">IN PROGRESS (Sedang Diproses)</span>
                            </div>
                            <span class="font-black text-yellow-600">{{ $caInProgress }}</span>
                        </div>

                        <!-- Closed -->
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-100">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                <span class="font-bold text-green-800 text-sm">CLOSED (Selesai)</span>
                            </div>
                            <span class="font-black text-green-600">{{ $caClosed }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
