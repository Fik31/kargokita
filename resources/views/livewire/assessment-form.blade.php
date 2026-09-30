<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 bg-white shadow rounded-lg">
    <div class="mb-6 flex justify-between items-center border-b pb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Verifikasi Assessment Driver (HSE)</h2>
            <p class="text-sm text-gray-500">Beri penilaian berdasarkan bukti yang diunggah oleh driver.</p>
        </div>
        <div class="flex gap-4">
            @if($assessment)
            <div class="bg-blue-50 p-3 rounded-lg border border-blue-100 text-sm">
                <div><strong>Driver:</strong> {{ $assessment->driver ? $assessment->driver->name : 'N/A' }}</div>
                <div><strong>Tanggal Submit:</strong> {{ $assessment->date }}</div>
                <div><strong>Status saat ini:</strong> <span class="font-bold text-blue-700">{{ $assessment->status }}</span></div>
            </div>
            @endif
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            @foreach($criteriaGrouped as $category => $items)
                <button 
                    wire:click="$set('activeTab', '{{ $category }}')"
                    type="button"
                    class="{{ $activeTab === $category ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150">
                    {{ $category }}
                    <span class="ml-2 bg-gray-100 text-gray-900 py-0.5 px-2 rounded-full text-xs">{{ count($items) }}</span>
                </button>
            @endforeach
        </nav>
    </div>

    <!-- Tab Content -->
    <div>
        @if(session()->has('message'))
            <div class="mb-4 p-4 text-green-700 bg-green-100 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="submit">
            <div class="overflow-x-auto ring-1 ring-black ring-opacity-5 rounded-lg mb-8">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 w-16">Kode</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-1/3">Kriteria & Bukti dari Driver</th>
                            <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900 w-24">Mandatory</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-48">Penilaian HSE</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Catatan & Corrective Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @if(isset($criteriaGrouped[$activeTab]))
                            @foreach($criteriaGrouped[$activeTab] as $criterion)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 align-top">
                                        {{ $criterion->code }}
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-500 align-top">
                                        <div class="font-bold text-gray-900 mb-1">{{ $criterion->name }}</div>
                                        <div class="text-xs text-gray-400 mb-2">Bobot: {{ $criterion->weight }}%</div>
                                        
                                        <!-- Master Criteria Details -->
                                        <div class="bg-blue-50 border border-blue-100 p-2 rounded text-xs mb-3 space-y-1">
                                            @if($criterion->evidence_minimum)
                                                <div><span class="font-semibold text-blue-800">Bukti Minimum:</span> <span class="text-blue-600">{{ $criterion->evidence_minimum }}</span></div>
                                            @endif
                                            @if($criterion->pic_verification)
                                                <div><span class="font-semibold text-blue-800">PIC Verifikasi:</span> <span class="text-blue-600">{{ $criterion->pic_verification }}</span></div>
                                            @endif
                                            @if($criterion->frequency)
                                                <div><span class="font-semibold text-blue-800">Frekuensi:</span> <span class="text-blue-600">{{ $criterion->frequency }}</span></div>
                                            @endif
                                            @if($criterion->notes)
                                                <div><span class="font-semibold text-red-600">Catatan:</span> <span class="text-red-500 font-bold">{{ $criterion->notes }}</span></div>
                                            @endif
                                        </div>
                                        
                                        <!-- Tampilkan Bukti dari Driver -->
                                        <div class="bg-gray-50 p-2 rounded border border-gray-200">
                                            <div class="text-xs font-semibold text-gray-700 mb-1">Bukti Terlampir:</div>
                                            @if($answers[$criterion->id]['evidence_path'])
                                                <a href="{{ Storage::url($answers[$criterion->id]['evidence_path']) }}" target="_blank" class="text-brand-blue hover:underline font-medium text-xs flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                    Lihat File Bukti
                                                </a>
                                            @else
                                                <span class="text-xs text-red-500 italic">Tidak ada bukti diunggah</span>
                                            @endif
                                            @if($answers[$criterion->id]['notes'])
                                                <div class="mt-2 text-xs text-gray-600 bg-white p-1 rounded italic">
                                                    "{{ $answers[$criterion->id]['notes'] }}" - Driver
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-3 py-4 text-sm text-center align-top">
                                        @if($criterion->is_mandatory)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">YES</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">NO</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-500 align-top">
                                        <select wire:model.live="answers.{{ $criterion->id }}.status" class="block w-full pl-3 pr-8 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md font-bold
                                            {{ $answers[$criterion->id]['status'] == 'COMPLY' ? 'text-green-700 bg-green-50' : 
                                              ($answers[$criterion->id]['status'] == 'NON-COMPLY' ? 'text-red-700 bg-red-50' : 'text-gray-700') }}">
                                            <option value="COMPLY">COMPLY</option>
                                            <option value="NON-COMPLY">NON-COMPLY</option>
                                            <option value="N/A">N/A</option>
                                        </select>
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-500 align-top">
                                        <div class="space-y-3">
                                            <!-- Conditional Fields for Findings & Corrective Action -->
                                            @if($answers[$criterion->id]['status'] == 'NON-COMPLY')
                                                <div class="bg-red-50 p-3 rounded-md border border-red-100 space-y-3">
                                                    <div>
                                                        <label class="block text-xs font-medium text-red-700 mb-1">Catatan Temuan HSE <span class="text-red-500">*</span></label>
                                                        <textarea wire:model="answers.{{ $criterion->id }}.notes" rows="2" class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-red-300 rounded-md" placeholder="Instruksi perbaikan..." required></textarea>
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-3">
                                                        <div>
                                                            <label class="block text-xs font-medium text-red-700 mb-1">Due Date CA (Opsional)</label>
                                                            <input type="date" wire:model="answers.{{ $criterion->id }}.due_date" class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-red-300 rounded-md">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-medium text-red-700 mb-1">PIC CA</label>
                                                            <input type="text" wire:model="answers.{{ $criterion->id }}.pic_ca" class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-red-300 rounded-md" placeholder="Nama PIC">
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-xs text-gray-400 italic">Tidak perlu tindakan perbaikan</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end gap-3 pt-5 border-t">
                <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Simpan Draft
                </button>
                <button type="submit" class="bg-blue-600 border border-transparent rounded-md shadow-sm py-2 px-6 inline-flex justify-center text-sm font-bold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Simpan Verifikasi & Hitung Tier
                </button>
            </div>
        </form>
    </div>
</div>
