<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <div class="bg-white shadow rounded-lg p-6 mb-6 text-center border-t-4 border-brand-blue">
        <h2 class="text-2xl font-bold text-gray-800">Form Pendaftaran & Verifikasi Driver</h2>
        <p class="text-sm text-gray-500 mt-2">Lengkapi data diri dan kendaraan Anda untuk diverifikasi oleh tim HSE. Semakin lengkap dokumen yang Anda unggah, semakin besar peluang mendapatkan Tier Gold/Silver.</p>
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
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3 px-4 text-left text-sm font-semibold text-gray-900">Kriteria Penilaian</th>
                            <th scope="col" class="py-3 px-4 text-center text-sm font-semibold text-gray-900 w-32">Wajib?</th>
                            <th scope="col" class="py-3 px-4 text-left text-sm font-semibold text-gray-900 w-1/3">Upload Bukti / Foto</th>
                            <th scope="col" class="py-3 px-4 text-left text-sm font-semibold text-gray-900 w-1/4">Keterangan Tambahan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @if(isset($criteriaGrouped[$activeTab]))
                            @foreach($criteriaGrouped[$activeTab] as $criterion)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-4 text-sm align-top">
                                        <div class="font-bold text-gray-900">{{ $criterion->code }} - {{ $criterion->name }}</div>
                                        <div class="text-xs text-gray-500 mt-1">Bobot: {{ $criterion->weight }}%</div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-center align-top">
                                        @if($criterion->is_mandatory)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-800">
                                                Wajib
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                Opsional
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500 align-top">
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
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500 align-top">
                                        <textarea wire:model="answers.{{ $criterion->id }}.notes" rows="3" class="shadow-sm focus:ring-brand-blue focus:border-brand-blue block w-full sm:text-sm border-gray-300 rounded-md placeholder-gray-400" placeholder="Opsional: Tambahkan catatan jika perlu..."></textarea>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
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
