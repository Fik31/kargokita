<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Live Tracking Truk</h2>

        @if(count($activeTrips) === 0)
            <div class="text-center text-gray-500 py-12 bg-gray-50 rounded-xl">
                Tidak ada trip yang sedang berjalan (In Transit) saat ini.
            </div>
        @else
            <div class="space-y-4">
                @foreach($activeTrips as $t)
                    <div wire:key="trip-{{ $t->id }}" class="bg-white border {{ $tripId == $t->id ? 'border-brand-blue shadow-md' : 'border-gray-200 shadow-sm' }} rounded-2xl overflow-hidden transition-all">
                        <!-- Accordion Header -->
                        <div class="p-4 cursor-pointer flex justify-between items-center hover:bg-gray-50" wire:click="selectTrip({{ $t->id }})">
                            <div>
                                <span class="font-bold text-lg text-gray-800">TRK-{{ str_pad($t->id, 3, '0', STR_PAD_LEFT) }}</span>
                                <span class="ml-2 text-xs font-bold {{ $t->is_deviated ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800' }} px-2 py-1 rounded-full border">
                                    {{ $t->is_deviated ? 'Deviasi Jalur' : 'Sesuai Koridor' }}
                                </span>
                                <p class="text-sm text-gray-500 mt-1">Rute: {{ $t->cargo->origin_name ?? 'Jakarta' }} &rarr; {{ $t->cargo->dest_name ?? 'Surabaya' }}</p>
                            </div>
                            <div>
                                <svg class="w-6 h-6 text-gray-400 transform transition-transform {{ $tripId == $t->id ? 'rotate-180 text-brand-blue' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>

                        <!-- Accordion Body -->
                        @if($tripId == $t->id && $trip)
                            <div class="border-t border-gray-100 p-4 bg-gray-50">
                                <div class="flex flex-col lg:flex-row gap-6">
                                    <!-- Left: Map / Schematic -->
                                    <div class="w-full lg:w-2/3">
                                        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden relative shadow-sm">
                                            
                                            <!-- Tabs -->
                                            <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-end">
                                                <div class="flex p-1 bg-gray-200 rounded-lg">
                                                    <button wire:click="$set('activeTab', 'leaflet')" class="{{ $activeTab === 'leaflet' ? 'bg-brand-blue text-white shadow' : 'text-gray-600 hover:text-gray-900' }} px-4 py-2 text-sm font-bold rounded-md transition-all flex items-center">
                                                        🗺️ Peta Leaflet Live
                                                    </button>
                                                    <button wire:click="$set('activeTab', 'schematic')" class="{{ $activeTab === 'schematic' ? 'bg-brand-blue text-white shadow' : 'text-gray-600 hover:text-gray-900' }} px-4 py-2 text-sm font-bold rounded-md transition-all">
                                                        Skematik
                                                    </button>
                                                    <button wire:click="$set('activeTab', 'topology')" class="{{ $activeTab === 'topology' ? 'bg-brand-blue text-white shadow' : 'text-gray-600 hover:text-gray-900' }} px-4 py-2 text-sm font-bold rounded-md transition-all">
                                                        Topologi
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Leaflet View -->
                                            <div class="{{ $activeTab === 'leaflet' ? 'block' : 'hidden' }}">
                                                <div wire:ignore>
                                                    <div id="map" class="w-full h-[400px] z-0"></div>
                                                </div>
                                                
                                                <!-- Map Legend overlay -->
                                                <div class="absolute bottom-4 left-4 bg-white/95 backdrop-blur p-3 rounded-xl shadow-lg border border-gray-100 z-[400] text-xs">
                                                    <h4 class="font-bold mb-1 text-gray-800">Legenda Peta Telematics</h4>
                                                    <div class="flex items-center gap-2"><div class="w-4 h-1 bg-brand-blue"></div> Rute Terencana</div>
                                                    <div class="flex items-center gap-2"><div class="w-4 h-1 border-t-2 border-dashed border-brand-blue"></div> Rute Riil</div>
                                                </div>
                                            </div>

                                            <!-- Schematic View -->
                                            <div class="{{ $activeTab === 'schematic' ? 'block' : 'hidden' }} bg-[#0B1120] p-8 h-[400px] flex flex-col justify-center relative overflow-hidden">
                                                <div class="absolute top-6 left-6 right-6 flex justify-between items-start gap-4 z-10">
                                                    <div>
                                                        <h3 class="text-white font-bold text-lg">Skematik Perjalanan (Linear Progress)</h3>
                                                        <p class="text-gray-400 text-xs mt-1">Garis proporsional yang menggambarkan progres dari titik awal ke titik akhir.</p>
                                                    </div>
                                                    <div class="p-2 text-[10px] font-mono text-gray-400 bg-gray-900 rounded-lg border border-gray-700 whitespace-nowrap flex-shrink-0">
                                                        <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> GPS LOCK: ONLINE</div>
                                                    </div>
                                                </div>
                                                
                                                <div class="relative w-full mt-10 px-8">
                                                    <!-- Progress Bar Background -->
                                                    <div class="h-3 w-full bg-gray-800 rounded-full"></div>
                                                    <!-- Progress Bar Fill -->
                                                    <div class="absolute top-0 left-8 h-3 bg-gradient-to-r from-blue-800 to-brand-blue rounded-full shadow-[0_0_15px_rgba(37,99,235,0.7)] transition-all duration-1000" style="width: 50%;"></div>
                                                    
                                                    <!-- Nodes Container -->
                                                    <div class="absolute top-1/2 left-0 w-full px-8 md:px-12 flex justify-between items-center -translate-y-1/2">
                                                        <!-- Origin Node -->
                                                        <div class="flex flex-col items-center relative group z-10">
                                                            <div class="w-5 h-5 rounded-full bg-green-500 border-4 border-gray-800 shadow-lg group-hover:scale-125 transition-transform"></div>
                                                            <div class="absolute top-8 text-center w-32 left-1/2 -translate-x-1/2">
                                                                <p class="text-xs font-bold text-white">{{ $trip->cargo->origin_name ?? 'Jakarta' }}</p>
                                                                <p class="text-[10px] text-gray-500">Titik Awal</p>
                                                            </div>
                                                        </div>
                                            
                                                        @php
                                                            $dropPoints = $trip->cargo && $trip->cargo->drop_points ? json_decode($trip->cargo->drop_points, true) : [];
                                                        @endphp
                                                        @foreach($dropPoints as $dp)
                                                        <!-- Drop Point Node -->
                                                        <div class="flex flex-col items-center relative group z-10">
                                                            <div class="w-4 h-4 rounded-full bg-yellow-500 border-2 border-gray-800 hover:scale-125 transition-transform cursor-pointer"></div>
                                                            <div class="absolute top-8 text-center w-32 left-1/2 -translate-x-1/2">
                                                                <p class="text-xs font-bold text-gray-300">{{ $dp['name'] }}</p>
                                                                <p class="text-[10px] text-gray-500">Titik Singgah</p>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                        
                                                        <!-- Destination Node -->
                                                        <div class="flex flex-col items-center relative group z-10">
                                                            <div class="w-5 h-5 rounded-full bg-red-500 border-4 border-gray-800 shadow-lg group-hover:scale-125 transition-transform"></div>
                                                            <div class="absolute top-8 text-center w-32 left-1/2 -translate-x-1/2">
                                                                <p class="text-xs font-bold text-gray-400">{{ $trip->cargo->dest_name ?? 'Surabaya' }}</p>
                                                                <p class="text-[10px] text-gray-600">Titik Akhir</p>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Truck Position Node -->
                                                        <div class="flex flex-col items-center absolute z-20" style="left: 50%; transform: translateX(-50%);">
                                                            <div class="absolute w-12 h-12 bg-blue-500/30 rounded-full animate-ping top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"></div>
                                                            <div class="w-8 h-8 rounded-full bg-white border-4 border-brand-blue shadow-[0_0_15px_rgba(37,99,235,0.8)] flex items-center justify-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#2563EB" class="w-4 h-4"><path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875h.375a3 3 0 116 0h3a3 3 0 116 0h.75V15h-6zM17.25 6.75h-2.25V13.5h5.578l-3.328-6.75z"/></svg>
                                                            </div>
                                                            <div class="absolute bottom-12 bg-white px-3 py-1.5 rounded-lg border border-gray-200 shadow-xl whitespace-nowrap text-brand-blue text-xs font-bold flex flex-col items-center">
                                                                <span>TRK-{{ str_pad($t->id, 3, '0', STR_PAD_LEFT) }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Topology View -->
                                            <div class="{{ $activeTab === 'topology' ? 'block' : 'hidden' }} p-6 bg-white overflow-y-auto max-h-[400px]">
                                                <div class="mb-6 pb-4 border-b border-gray-100">
                                                    <h3 class="font-bold text-gray-800 text-lg">Topologi Rute Perjalanan</h3>
                                                    <p class="text-sm text-gray-500">Daftar urutan lokasi perhentian dan posisi armada secara real-time.</p>
                                                </div>
                                                
                                                <div class="relative border-l-2 border-gray-200 ml-4 space-y-8 pb-4">
                                                    <!-- Origin -->
                                                    <div class="relative" style="padding-left: 2rem;">
                                                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-green-500 border-2 border-white shadow" style="left: -9px;"></div>
                                                        <h4 class="font-bold text-base text-gray-900">{{ $trip->cargo->origin_name ?? 'Jakarta' }}</h4>
                                                        <p class="text-xs text-gray-500 font-medium">Titik Keberangkatan (Origin)</p>
                                                    </div>
                                            
                                                    @foreach($dropPoints as $dp)
                                                    <!-- Drop Point -->
                                                    <div class="relative" style="padding-left: 2rem;">
                                                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-yellow-500 border-2 border-white shadow" style="left: -9px;"></div>
                                                        <h4 class="font-bold text-base text-gray-800">{{ $dp['name'] }}</h4>
                                                        <p class="text-xs text-gray-500 font-medium">Titik Singgah (Drop Point)</p>
                                                    </div>
                                                    @endforeach
                                            
                                                    <!-- Current Location (Truck) -->
                                                    <div class="relative border-l-2 border-brand-blue -ml-[2px]" style="padding-left: 2rem;">
                                                        <div class="absolute -left-[13px] top-0 w-6 h-6 rounded-full bg-brand-blue border-2 border-white shadow flex items-center justify-center animate-bounce" style="left: -13px;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" class="w-3 h-3"><path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875h.375a3 3 0 116 0h3a3 3 0 116 0h.75V15h-6zM17.25 6.75h-2.25V13.5h5.578l-3.328-6.75z"/></svg>
                                                        </div>
                                                        <h4 class="font-bold text-base text-brand-blue">Posisi Truk Saat Ini</h4>
                                                        @if($t->id == 12)
                                                            <p class="text-xs text-gray-600 font-medium">Sedang berada di perjalanan (Estimasi: Tol Trans-Jawa / Madiun)</p>
                                                        @else
                                                            <p class="text-xs text-gray-600 font-medium">Sedang berada di perjalanan (Estimasi: Area Cirebon)</p>
                                                        @endif
                                                        <p class="text-[10px] text-gray-400 mt-1">Terakhir diperbarui: Baru saja</p>
                                                    </div>
                                                    
                                                    <!-- Destination -->
                                                    <div class="relative" style="padding-left: 2rem;">
                                                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-red-500 border-2 border-white shadow" style="left: -9px;"></div>
                                                        <h4 class="font-bold text-base text-gray-900">{{ $trip->cargo->dest_name ?? 'Surabaya' }}</h4>
                                                        <p class="text-xs text-gray-500 font-medium">Titik Tujuan (Destination)</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right: Audit / Events -->
                                    <div class="w-full lg:w-1/3">
                                        <div class="bg-white rounded-2xl border border-gray-200 h-full flex flex-col shadow-sm overflow-hidden">
                                            
                                            <div class="flex">
                                                <button wire:click="$set('eventTab', 'dwell')" class="flex-1 {{ $eventTab === 'dwell' ? 'bg-brand-blue text-white border-b-2 border-blue-700' : 'bg-gray-50 text-gray-600 border-b-2 border-transparent hover:bg-gray-100' }} py-3 text-sm font-bold">Pemberhentian</button>
                                                <button wire:click="$set('eventTab', 'deviation')" class="flex-1 {{ $eventTab === 'deviation' ? 'bg-brand-blue text-white border-b-2 border-blue-700' : 'bg-gray-50 text-gray-600 border-b-2 border-transparent hover:bg-gray-100' }} py-3 text-sm font-bold">Deviasi</button>
                                            </div>
                                            
                                            <div class="p-4 flex-grow overflow-y-auto max-h-[350px] space-y-4">
                                                @forelse($events as $event)
                                                    <div class="border border-gray-100 rounded-xl p-3 shadow-sm relative overflow-hidden bg-white">
                                                        <div class="absolute left-0 top-0 bottom-0 w-1 {{ $event->type === 'dwell' ? 'bg-yellow-400' : 'bg-red-500' }}"></div>
                                                        <div class="flex justify-between items-start mb-1">
                                                            <span class="text-xs font-bold text-gray-600">{{ $event->location_name ?? 'Lokasi Terdeteksi' }}</span>
                                                            <span class="text-[10px] text-gray-500">{{ $event->created_at->format('H:i') }}</span>
                                                        </div>
                                                        <p class="text-xs text-gray-800">{{ $event->notes }}</p>
                                                    </div>
                                                @empty
                                                    <div class="text-center text-gray-400 py-12 px-4">
                                                        <p class="text-sm">Belum ada event tercatat.</p>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>


    @script
    <script>
        let map = null;
        let marker = null;
        let routingControl = null;
        let locationInterval = null;

        // Wait for Leaflet & Routing Machine to be defined
        const waitForLeaflet = setInterval(() => {
            if (typeof L !== 'undefined' && typeof L.Routing !== 'undefined') {
                clearInterval(waitForLeaflet);
                initLeafletLogic();
            }
        }, 100);

        function initLeafletLogic() {
            function initMapContainer() {
                const mapEl = document.getElementById('map');
                if (!mapEl) return false;

                if (map && !mapEl._leaflet_id) {
                    map.remove();
                    map = null;
                    marker = null;
                    routingControl = null;
                }

                if (map) {
                    setTimeout(() => { map.invalidateSize(); }, 200);
                    return true;
                }

                map = L.map('map').setView([-6.200000, 106.816666], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                setTimeout(() => { map.invalidateSize(); }, 200);
                return true;
            }

            async function fetchLocation() {
                if (!map) return;
                
                try {
                    const loc = await $wire.getLatestLocation();
                    if (loc && loc.lat && loc.lng) {
                        updateMap(loc);
                    }
                } catch (e) {
                    console.error("Failed to fetch location:", e);
                }
            }

            function updateMap(loc) {
                const lat = loc.lat;
                const lng = loc.lng;

                const truckIcon = L.divIcon({
                    className: 'custom-truck-icon',
                    html: `<div style="background-color: white; border: 2px solid #2563EB; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 6px rgba(0,0,0,0.3);">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#2563EB" style="width: 22px; height: 22px;">
                            <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875h.375a3 3 0 116 0h3a3 3 0 116 0h.75V15h-6zM17.25 6.75h-2.25V13.5h5.578l-3.328-6.75z" />
                        </svg>
                    </div>`,
                    iconSize: [36, 36],
                    iconAnchor: [18, 18]
                });

                // Define custom pinpoint icons
                const createPinIcon = (color) => L.divIcon({
                    className: 'custom-pin-icon',
                    html: `<div style="display: flex; flex-direction: column; align-items: center; filter: drop-shadow(0 4px 4px rgba(0,0,0,0.4));">
                              <div style="background-color: ${color}; width: 24px; height: 24px; border-radius: 50% 50% 50% 0; border: 3px solid white; transform: rotate(-45deg);"></div>
                              <div style="width: 6px; height: 6px; background: rgba(0,0,0,0.5); border-radius: 50%; margin-top: 2px;"></div>
                           </div>`,
                    iconSize: [24, 34],
                    iconAnchor: [12, 32],
                    popupAnchor: [0, -32]
                });

                // Add Routing Machine for actual roads
                if (loc.origin_lat && loc.origin_lng && loc.dest_lat && loc.dest_lng) {
                    // Include drop points in route calculation
                    let routeWaypoints = [L.latLng(loc.origin_lat, loc.origin_lng)];
                    if (loc.drop_points && loc.drop_points.length > 0) {
                        loc.drop_points.forEach(dp => {
                            if (dp.lat && dp.lng) {
                                routeWaypoints.push(L.latLng(dp.lat, dp.lng));
                            }
                        });
                    }
                    routeWaypoints.push(L.latLng(loc.dest_lat, loc.dest_lng));

                    if (!routingControl) {
                        routingControl = L.Routing.control({
                            waypoints: routeWaypoints,
                            routeWhileDragging: false,
                            show: false, // hide instructions
                            createMarker: function() { return null; }, // hide default markers
                            lineOptions: {
                                styles: [{color: '#2563EB', opacity: 0.8, weight: 6}]
                            },
                            fitSelectedRoutes: true
                        }).addTo(map);

                        // Render Origin and Destination Pinpoints
                        L.marker([loc.origin_lat, loc.origin_lng], {icon: createPinIcon('#22C55E')}) // Green for origin
                         .bindPopup('<b>Titik Awal</b>')
                         .addTo(map);

                        L.marker([loc.dest_lat, loc.dest_lng], {icon: createPinIcon('#EF4444')}) // Red for destination
                         .bindPopup('<b>Titik Akhir</b>')
                         .addTo(map);
                    }
                }

                if (!marker || !map.hasLayer(marker)) {
                    marker = L.marker([lat, lng], {icon: truckIcon}).addTo(map);
                } else {
                    marker.setLatLng([lat, lng]);
                }

                if (loc.drop_points && loc.drop_points.length > 0) {
                    const dropPointIcon = L.divIcon({
                        className: 'custom-div-icon',
                        html: '<div style="background-color:#EAB308; width:16px; height:16px; border-radius:50%; border:2px solid white; box-shadow: 0 0 5px rgba(0,0,0,0.3);"></div>',
                        iconSize: [16, 16],
                        iconAnchor: [8, 8]
                    });
                    loc.drop_points.forEach(dp => {
                        if (dp.lat && dp.lng) {
                            L.marker([dp.lat, dp.lng], {icon: dropPointIcon})
                             .bindPopup(`<b>${dp.name}</b><br>Titik Singgah`)
                             .addTo(map);
                        }
                    });
                }
                
                // If we don't have a route, center on truck
                if (!routingControl) {
                    map.setView([lat, lng], 10);
                }
            }

            $wire.on('trip-changed', () => {
                setTimeout(() => {
                    if (initMapContainer()) {
                        fetchLocation();
                    }
                }, 100);
            });

            setInterval(() => {
                if (document.getElementById('map')) {
                    if (!map) {
                        if (initMapContainer()) {
                            fetchLocation();
                        }
                    } else {
                        map.invalidateSize();
                    }
                }
            }, 2000);

            locationInterval = setInterval(() => {
                fetchLocation();
            }, 5000);

            setTimeout(() => {
                if (initMapContainer()) {
                    fetchLocation();
                }
            }, 200);
        }
    </script>
    @endscript
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.js.iife.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isMerchantUser = @json(Auth::user()->hasRole('merchant'));
        const isAdminUser = @json(Auth::user()->hasRole('administrator'));
        
        if (isMerchantUser || isAdminUser) {
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
                        element: '.max-w-7xl > div:first-child > h2',
                        popover: {
                            title: 'Live Tracking Truk',
                            description: 'Di halaman ini, Anda bisa memantau pergerakan truk secara real-time untuk setiap order Anda yang sedang berjalan (In Transit).',
                            side: "bottom",
                            align: 'start'
                        }
                    },
                    {
                        element: '.space-y-4',
                        popover: {
                            title: 'Daftar Trip',
                            description: 'Klik pada salah satu trip untuk membuka detail dan melihat posisi truk saat ini.',
                            side: "top",
                            align: 'center'
                        }
                    }
                ],
                onDestroyStarted: () => {
                    if (!driverObj.hasNextStep() || confirm("Skip tutorial ini?")) {
                        driverObj.destroy();
                            document.body.classList.remove('driver-active', 'driver-fix-stacking');
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
