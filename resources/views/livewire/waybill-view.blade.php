<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6 no-print">
        <h2 class="text-2xl font-bold text-gray-800">Surat Jalan Elektronik</h2>
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-sm flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print PDF
        </button>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 no-print">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white shadow-lg rounded-lg border border-gray-200 overflow-hidden print:shadow-none print:border-none print:w-full p-8 print:p-0">
        <!-- Document Header -->
        <div class="flex justify-between items-start border-b-2 border-gray-800 pb-6 mb-6">
            <div>
                <img src="{{ asset('assets/images/logo-black.png') }}" alt="Kargokita Logo" class="h-10 w-auto mb-2" onerror="this.src='https://via.placeholder.com/150x40?text=KARGOKITA'">
                <h1 class="text-3xl font-bold text-gray-900 uppercase tracking-widest mt-2">SURAT JALAN</h1>
            </div>
            <div class="text-right">
                <p class="text-sm font-bold text-gray-600">No. Trip: <span class="text-gray-900">TRP-{{ str_pad($trip->id, 5, '0', STR_PAD_LEFT) }}</span></p>
                <p class="text-sm font-bold text-gray-600">No. Order: <span class="text-gray-900">FLS-{{ str_pad($trip->cargo->id, 5, '0', STR_PAD_LEFT) }}</span></p>
                <p class="text-sm font-bold text-gray-600">Tanggal: <span class="text-gray-900">{{ now()->format('d M Y') }}</span></p>
            </div>
        </div>

        <!-- Info Section -->
        <div class="grid grid-cols-2 gap-8 mb-8">
            <div>
                <h3 class="text-sm font-bold text-gray-800 border-b border-gray-300 pb-1 mb-2">PENGIRIM / MERCHANT</h3>
                <p class="font-bold text-gray-900 text-lg">{{ $trip->cargo->merchant->name }}</p>
                <p class="text-sm text-gray-700">{{ $trip->cargo->sender_phone ?? $trip->cargo->merchant->phone ?? '-' }}</p>
                <p class="text-sm text-gray-700 mt-1">{{ $trip->cargo->sender_address ?? 'Alamat Pengirim' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-800 border-b border-gray-300 pb-1 mb-2">PENERIMA</h3>
                <p class="font-bold text-gray-900 text-lg">{{ $trip->cargo->receiver_name ?? 'Penerima' }}</p>
                <p class="text-sm text-gray-700">{{ $trip->cargo->receiver_phone ?? '-' }}</p>
                <p class="text-sm text-gray-700 mt-1">{{ $trip->cargo->receiver_address ?? 'Alamat Penerima' }}</p>
            </div>
        </div>

        <!-- Cargo Table -->
        <div class="mb-8">
            <table class="min-w-full divide-y divide-gray-800 border border-gray-800">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-800 uppercase border-r border-gray-800">No.</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-800 uppercase border-r border-gray-800">Nama Barang / Muatan</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-800 uppercase border-r border-gray-800">Berat / Koli</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-800 uppercase">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-800">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-800">1</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900 border-r border-gray-800">{{ $trip->cargo->title ?? $trip->cargo->item_name ?? 'Muatan' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900 border-r border-gray-800">{{ $trip->cargo->weight_kg ?? '-' }} KG / {{ $trip->cargo->koli ?? '-' }} Koli</td>
                        <td class="px-6 py-4 text-sm text-gray-900">-</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Signatures -->
        <div class="grid grid-cols-2 gap-8 mt-12 text-center">
            <!-- Pengirim -->
            <div class="flex flex-col items-center">
                <p class="text-sm font-bold text-gray-800 mb-4">Pengirim / Merchant</p>
                @if($waybill->merchant_signature)
                    <img src="{{ $waybill->merchant_signature }}" class="h-24 object-contain mb-2">
                    <p class="text-xs text-green-600 font-bold mb-1">Ditandatangani secara elektronik</p>
                    <div class="flex items-center justify-center gap-2 mt-2 pt-2 border-t border-gray-800">
                        <p class="text-sm font-bold uppercase">{{ $trip->cargo->merchant->name }}</p>
                        @if($trip->cargo->merchant->tier)
                            <x-tier-badge :tier="$trip->cargo->merchant->tier" class="scale-[0.7]" />
                        @endif
                    </div>
                @else
                    <div class="h-24 w-full flex items-center justify-center mb-2 text-gray-400 text-xs border border-dashed border-gray-300 no-print"
                         x-data="{ signatureData: @entangle('merchant_signature_data') }"
                         x-init="
                            const pad = new SignaturePad($refs.canvas);
                            $refs.saveBtn.addEventListener('click', () => {
                                if(!pad.isEmpty()) {
                                    signatureData = pad.toDataURL();
                                    $wire.saveMerchantSignature();
                                }
                            });
                         "
                    >
                        <div class="w-full h-full relative flex flex-col items-center">
                            <canvas x-ref="canvas" class="w-full h-full cursor-crosshair border border-gray-200" width="300" height="100"></canvas>
                            <button x-ref="saveBtn" class="mt-2 bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1 rounded">Simpan TTD</button>
                        </div>
                    </div>
                    <div class="flex items-center justify-center gap-2 mt-2 pt-2 border-t border-gray-800">
                        <p class="text-sm font-bold uppercase">{{ $trip->cargo->merchant->name }}</p>
                        @if($trip->cargo->merchant->tier)
                            <x-tier-badge :tier="$trip->cargo->merchant->tier" class="scale-[0.7]" />
                        @endif
                    </div>
                @endif
            </div>

            <!-- Driver -->
            <div class="flex flex-col items-center">
                <p class="text-sm font-bold text-gray-800 mb-4">Driver / Pengemudi</p>
                @if($waybill->driver_signature)
                    <img src="{{ $waybill->driver_signature }}" class="h-24 object-contain mb-2">
                    <div class="flex items-center justify-center gap-2 mt-2 pt-2 border-t border-gray-800">
                        <p class="text-sm font-bold uppercase">{{ $trip->driver->name }}</p>
                        @if($trip->driver->tier)
                            <x-tier-badge :tier="$trip->driver->tier" class="scale-[0.7]" />
                        @endif
                    </div>
                @else
                    <div class="h-24 w-full flex items-center justify-center mb-2 text-gray-400 text-xs border border-dashed border-gray-300 no-print"
                         x-data="{ signatureData: @entangle('driver_signature_data') }"
                         x-init="
                            const pad = new SignaturePad($refs.canvas);
                            $refs.saveBtn.addEventListener('click', () => {
                                if(!pad.isEmpty()) {
                                    signatureData = pad.toDataURL();
                                    $wire.saveDriverSignature();
                                }
                            });
                         "
                    >
                        <div class="w-full h-full relative flex flex-col items-center">
                            <canvas x-ref="canvas" class="w-full h-full cursor-crosshair border border-gray-200" width="300" height="100"></canvas>
                            <button x-ref="saveBtn" class="mt-2 bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1 rounded">Simpan TTD</button>
                        </div>
                    </div>
                    <div class="flex items-center justify-center gap-2 mt-2">
                        <p class="text-sm font-bold uppercase">{{ $trip->driver->name }}</p>
                        @if($trip->driver->tier)
                            <x-tier-badge :tier="$trip->driver->tier" class="scale-[0.7]" />
                        @endif
                    </div>
                @endif
            </div>
        </div>
        
        <div class="mt-16 text-xs text-gray-500 text-center border-t border-gray-200 pt-4">
            Dokumen ini merupakan surat jalan elektronik sah yang diterbitkan oleh sistem Kargokita.
        </div>
    </div>
    
    <!-- Include signature pad JS -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; }
        }
    </style>
</div>
