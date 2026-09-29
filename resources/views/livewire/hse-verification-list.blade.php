<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <div class="bg-blue-900 text-white p-4 rounded-t-lg text-center shadow mb-6">
        <h1 class="text-2xl font-bold">DAFTAR VERIFIKASI HSE</h1>
        <p class="text-sm mt-1 text-blue-100">Daftar antrean form self-assessment driver yang membutuhkan verifikasi.</p>
    </div>

    <!-- Pending Assessments -->
    <div class="bg-white shadow rounded-lg overflow-hidden border-t-4 border-yellow-400">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">Menunggu Verifikasi HSE</h3>
            <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full">{{ $pendingAssessments->count() }} Pending</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Submit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Driver</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($pendingAssessments as $assessment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $assessment->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $assessment->driver ? $assessment->driver->name : 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-bold">{{ $assessment->status }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.assessment.form', ['assessment_id' => $assessment->id]) }}" class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded shadow text-xs font-bold transition">Verifikasi & Nilai</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center italic">
                                Tidak ada data driver yang menunggu verifikasi saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
