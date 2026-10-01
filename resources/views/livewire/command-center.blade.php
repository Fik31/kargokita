<div class="h-[calc(100vh-80px)] w-full flex flex-col xl:flex-row bg-[#0b0f19] text-gray-200 overflow-hidden shadow-2xl rounded-xl border border-gray-800" x-data="commandCenter()">
    <!-- Left: Live Tracking Map -->
    <div class="w-full xl:w-2/3 h-[50vh] xl:h-full relative border-b xl:border-b-0 xl:border-r border-gray-800 flex flex-col">
        <div class="absolute top-4 left-4 z-[400] bg-black/70 backdrop-blur-md px-4 py-3 rounded-lg border border-gray-700 shadow-xl pointer-events-none">
            <h2 class="font-bold text-lg text-white flex items-center gap-2">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                </span>
                LIVE FLEET RADAR
            </h2>
            <div class="flex gap-4 mt-2 text-xs text-gray-300">
                <p>Armada Aktif: <span class="text-blue-400 font-bold" x-text="fleets.length"></span></p>
                <p>Target Lelang: <span class="text-emerald-400 font-bold" x-text="activeAuctions"></span></p>
            </div>
        </div>
        
        <!-- Interactive Broadcast Panel Over Map -->
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-[400] bg-black/80 backdrop-blur-md p-3 rounded-xl border shadow-2xl w-11/12 max-w-lg transition-all" :class="isBroadcasting ? 'border-emerald-500 shadow-[0_0_15px_rgba(16,185,129,0.5)]' : 'border-gray-700'">
            <div class="flex gap-2 mb-2">
                <input type="text" x-model="newRoute" placeholder="Rute (Cth: JKT-BDG)" :disabled="simulationStatus === 'running'" class="w-1/2 bg-gray-900 border border-gray-700 rounded text-sm text-white px-3 py-2 focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50">
                <input type="text" x-model="newCargo" placeholder="Muatan (Cth: 2 Ton Besi)" :disabled="simulationStatus === 'running'" class="w-1/2 bg-gray-900 border border-gray-700 rounded text-sm text-white px-3 py-2 focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50">
            </div>
            <button @click="broadcastOrder()" :disabled="simulationStatus === 'running' || !newRoute" class="w-full py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded text-sm transition-all flex justify-center items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed" :class="isBroadcasting ? 'bg-emerald-600' : ''">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                <span x-text="isBroadcasting ? 'Order Ter-Broadcast! Radar mencari driver...' : 'Broadcast Order ke Radar'"></span>
            </button>
        </div>
        
        <!-- Map Container -->
        <div id="cc-map" class="flex-1 w-full bg-[#0b0f19] z-0" wire:ignore></div>
    </div>

    <!-- Right: Real-time Bidding Arena -->
    <div class="w-full xl:w-1/3 h-[50vh] xl:h-full flex flex-col bg-[#111827]">
        <div class="p-4 border-b border-gray-800 shrink-0 bg-[#0f1523]">
            <h2 class="font-bold text-lg text-white tracking-wider flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    BIDDING ARENA
                </span>
                <span x-show="simulationStatus === 'running'" class="text-xs bg-emerald-500/20 text-emerald-400 px-2 py-1 rounded-full border border-emerald-500/30 flex items-center gap-1 animate-pulse">
                    Menerima Bid...
                </span>
                <span x-show="simulationStatus === 'stopped'" class="text-xs bg-red-500/20 text-red-400 px-2 py-1 rounded-full border border-red-500/30">
                    Lelang Ditutup
                </span>
                <span x-show="simulationStatus === 'idle'" class="text-xs bg-gray-500/20 text-gray-400 px-2 py-1 rounded-full border border-gray-500/30">
                    Menunggu Order
                </span>
            </h2>
        </div>

        <!-- Chart Section -->
        <div class="h-40 p-4 border-b border-gray-800 shrink-0 bg-[#0f1523]/50" wire:ignore>
            <div class="flex justify-between items-center mb-2">
                <span class="text-xs text-gray-500 font-bold uppercase tracking-wide">Tren Penurunan Harga</span>
                <span class="text-xs text-emerald-400 font-bold">-14.2%</span>
            </div>
            <div class="h-28">
                <canvas id="biddingChart"></canvas>
            </div>
        </div>

        <!-- Live Bid Feed -->
        <div class="flex-1 p-4 overflow-hidden flex flex-col relative">
            <div class="absolute inset-x-0 bottom-0 h-12 bg-gradient-to-t from-[#111827] to-transparent z-10 pointer-events-none"></div>
            
            <h3 class="text-xs font-bold text-gray-500 mb-3 uppercase tracking-wider shrink-0 flex justify-between">
                <span>Incoming Bids Stream</span>
                <span class="text-[0.65rem] bg-gray-800 px-2 py-0.5 rounded text-gray-400 border border-gray-700">Real-time</span>
            </h3>
            
            <div class="flex-1 overflow-y-auto space-y-3 pr-2 scrollbar-hide pb-16" id="bid-stream">
                <template x-for="bid in bids" :key="bid.id">
                    <div class="bg-gray-800/60 p-3 rounded-lg border animate-[fade-in-up_0.3s_ease-out] flex justify-between items-center transition relative group" 
                         :class="bid.status === 'won' ? 'border-emerald-500 bg-emerald-900/20 shadow-[0_0_15px_rgba(16,185,129,0.2)]' : (bid.status === 'lost' ? 'border-gray-700/50 opacity-50' : 'border-gray-700/50 hover:border-blue-500 hover:scale-[1.02]')">
                        
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <img :src="`https://ui-avatars.com/api/?name=${bid.driver}&background=0D8ABC&color=fff`" class="w-8 h-8 rounded-full border border-gray-600">
                                <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-emerald-500 border-2 border-gray-800 rounded-full" x-show="bid.status !== 'lost'"></div>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-100 leading-none" x-text="bid.driver"></p>
                                <p class="text-[0.65rem] text-blue-400 mt-1 font-medium" x-text="bid.route"></p>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <p class="text-sm font-bold text-emerald-400 font-mono tracking-tight" x-text="`Rp ${bid.amount.toLocaleString('id-ID')}`"></p>
                            <p class="text-[0.65rem] text-gray-500 mt-0.5" x-text="bid.time"></p>
                        </div>

                        <!-- Action Overlay on Hover -->
                        <div x-show="simulationStatus === 'running' && bid.status === 'pending'" class="absolute inset-0 bg-gray-900/90 backdrop-blur-sm rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <button @click="acceptBid(bid.id)" class="px-5 py-2 bg-emerald-500 hover:bg-emerald-400 text-white text-sm font-bold rounded shadow-lg transform transition hover:scale-105">
                                Terima Bid Ini
                            </button>
                        </div>
                        
                        <div x-show="bid.status === 'won'" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 rotate-[-15deg] border-2 border-emerald-500 text-emerald-500 font-bold px-3 py-1 text-sm rounded bg-black/50 pointer-events-none">
                            WINNER
                        </div>
                    </div>
                </template>
                
                <template x-if="bids.length === 0">
                    <div class="h-full flex flex-col items-center justify-center text-gray-500 text-sm gap-2">
                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        Tunggu broadcast order...
                    </div>
                </template>
            </div>
            
            <!-- Success Assignment Overlay -->
            <div x-show="simulationStatus === 'stopped'" class="absolute inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-gray-900 border border-emerald-500 rounded-xl p-5 shadow-2xl text-center w-full max-w-sm pointer-events-auto">
                    <div class="w-14 h-14 bg-emerald-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-1">Driver Dialokasikan!</h3>
                    <p class="text-[0.75rem] text-gray-300 mb-4">Order dimenangkan oleh <strong class="text-emerald-400" x-text="winnerDriver"></strong>. Sistem radar sedang merutekan armada menuju titik penjemputan.</p>
                    <button @click="simulationStatus = 'monitoring'" class="px-5 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded font-bold text-sm w-full transition shadow-lg mb-2">Tutup & Monitor Map</button>
                    <button @click="resetSimulation()" class="text-xs text-gray-400 hover:text-white underline transition">Batalkan / Buat Lelang Baru</button>
                </div>
            </div>
            
            <!-- Arrival Modal Overlay -->
            <div x-show="hasArrived" class="absolute inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center p-4" style="display: none;" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                <div class="bg-gray-900 border border-blue-500 rounded-xl p-6 shadow-[0_0_30px_rgba(59,130,246,0.3)] text-center w-full max-w-sm pointer-events-auto">
                    <div class="w-16 h-16 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-4 border border-blue-500/50">
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Armada Telah Tiba!</h3>
                    <p class="text-sm text-gray-300 mb-5 leading-relaxed">Truk <strong class="text-emerald-400" x-text="winnerDriver"></strong> sudah berada di lokasi penjemputan. Segera arahkan tim gudang untuk mulai memuat kargo.</p>
                    <a href="{{ route('3d-optimizer') }}" class="block px-5 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded font-bold text-sm w-full transition shadow-lg mb-3">
                        Buka Panduan Muat (3D Optimizer) &rarr;
                    </a>
                    <button @click="resetSimulation()" class="text-xs text-gray-500 hover:text-gray-300 underline transition">Tutup & Kembali ke Radar</button>
                </div>
            </div>
            
            <!-- Restart Simulation Button -->
            <button x-show="simulationStatus === 'stopped' || simulationStatus === 'monitoring'" @click="resetSimulation()" class="absolute bottom-4 left-4 right-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded text-sm transition-all z-20 shadow-[0_4px_14px_0_rgba(37,99,235,0.39)]">
                Buat Lelang Baru
            </button>

<style>
    @keyframes fade-in-up {
        0% { opacity: 0; transform: translateY(-10px) scale(0.95); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    @keyframes spin { 100% { transform: rotate(360deg); } }
    
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    
    /* Dark Mode OSM Trick (Free API-less map) */
    .leaflet-layer,
    .leaflet-control-zoom-in,
    .leaflet-control-zoom-out,
    .leaflet-control-attribution {
      filter: invert(100%) hue-rotate(180deg) brightness(95%) contrast(90%);
    }
    .leaflet-container { background: #0b0f19 !important; }
    .custom-div-icon { background: transparent; border: none; }
    
    /* Routing dash animation */
    .routing-line-anim {
        animation: dash 20s linear infinite;
    }
    @keyframes dash {
        to { stroke-dashoffset: -1000; }
    }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('commandCenter', () => ({
            bids: [],
            fleets: [],
            map: null,
            chart: null,
            simulationStatus: 'idle', // idle, running, stopped
            activeAuctions: 0,
            isBroadcasting: false,
            newRoute: 'JKT - SBY',
            newCargo: '15 Box Elektronik',
            simulationInterval: null,
            targetMarker: null,
            winnerDriver: '',
            routingLine: null,
            winnerFleet: null,
            hasArrived: false,
            
            init() {
                // Initialize map if L is available
                if(typeof L !== 'undefined') {
                    this.initMap();
                } else {
                    let checkMap = setInterval(() => {
                        if(typeof L !== 'undefined') {
                            clearInterval(checkMap);
                            this.initMap();
                        }
                    }, 200);
                }
                this.initChart();
            },
            
            initMap() {
                this.map = L.map('cc-map', { 
                    zoomControl: false,
                    attributionControl: false 
                }).setView([-6.200000, 106.816666], 11);
                
                // OpenStreetMap (Free, No API Key) + CSS Filter for Dark Mode
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                }).addTo(this.map);

                // Initial fleets (Dummy data)
                for(let i=0; i<15; i++) {
                    this.addFleet();
                }

                // Simulate movement
                setInterval(() => {
                    this.fleets.forEach(fleet => {
                        let newLat = fleet.lat + fleet.latDir * 0.0005;
                        let newLng = fleet.lng + fleet.lngDir * 0.0005;
                        
                        fleet.marker.setLatLng([newLat, newLng]);
                        fleet.lat = newLat;
                        fleet.lng = newLng;
                        
                        if(this.simulationStatus === 'stopped' && this.winnerFleet && this.winnerFleet.id === fleet.id) {
                            // If this is the winning fleet, drive it specifically towards target
                            let targetLatLng = this.targetMarker.getLatLng();
                            let dx = targetLatLng.lat - fleet.lat;
                            let dy = targetLatLng.lng - fleet.lng;
                            
                            // Check if arrived (radius increased slightly to catch fast movement)
                            if(Math.abs(dx) < 0.008 && Math.abs(dy) < 0.008 && !this.hasArrived) {
                                this.hasArrived = true;
                                fleet.latDir = 0;
                                fleet.lngDir = 0;
                                // Snap exactly to center just in case
                                fleet.lat = targetLatLng.lat;
                                fleet.lng = targetLatLng.lng;
                            } else if (!this.hasArrived) {
                                // Move towards target VERY fast for presentation (takes ~3-4 seconds)
                                fleet.latDir = dx * 450;
                                fleet.lngDir = dy * 450;
                            }
                        } else {
                            if(Math.random() > 0.8) {
                                fleet.latDir = (Math.random() - 0.5);
                                fleet.lngDir = (Math.random() - 0.5);
                            }
                        }
                    });
                }, 1000);
            },

            addFleet() {
                const colors = ['#3b82f6', '#10b981', '#f59e0b', '#ec4899', '#06b6d4']; 
                let color = colors[Math.floor(Math.random() * colors.length)];
                
                const icon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="background-color: ${color}; width: 14px; height: 14px; border-radius: 50%; box-shadow: 0 0 10px ${color}; border: 2px solid rgba(255,255,255,0.8);"></div>`,
                    iconSize: [14, 14],
                    iconAnchor: [7, 7]
                });

                let lat = -6.200000 + (Math.random() - 0.5) * 0.25;
                let lng = 106.816666 + (Math.random() - 0.5) * 0.25;
                let marker = L.marker([lat, lng], {icon: icon}).addTo(this.map);
                
                this.fleets.push({ 
                    id: Math.random(), 
                    marker: marker, 
                    lat: lat, lng: lng,
                    latDir: (Math.random() - 0.5),
                    lngDir: (Math.random() - 0.5)
                });
            },

            initChart() {
                const ctx = document.getElementById('biddingChart').getContext('2d');
                let gradient = ctx.createLinearGradient(0, 0, 0, 150);
                gradient.addColorStop(0, 'rgba(16, 185, 129, 0.4)'); 
                gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

                this.chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Start', '', '', '', '', 'Live'],
                        datasets: [{
                            label: 'Avg Bid Price',
                            data: [15.2, 15.2, 15.2, 15.2, 15.2, 15.2],
                            borderColor: '#34d399', 
                            backgroundColor: gradient,
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 1,
                            pointRadius: 3,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false }, tooltip: { enabled: false } },
                        scales: {
                            y: { 
                                grid: { color: 'rgba(55, 65, 81, 0.3)', borderDash: [2, 2] }, 
                                border: { display: false },
                                ticks: { color: '#6b7280', font: { size: 10 } } 
                            },
                            x: { 
                                grid: { display: false },
                                border: { display: false },
                                ticks: { color: '#6b7280', font: { size: 10 } }
                            }
                        },
                        animation: { duration: 800, easing: 'easeOutQuart' }
                    }
                });
            },

            broadcastOrder() {
                this.isBroadcasting = true;
                this.activeAuctions++;
                
                // Add target on map
                if(this.targetMarker) this.map.removeLayer(this.targetMarker);
                let lat = -6.200000 + (Math.random() - 0.5) * 0.15;
                let lng = 106.816666 + (Math.random() - 0.5) * 0.15;
                
                const targetIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="width: 30px; height: 30px; border-radius: 50%; border: 2px dashed #10b981; animation: spin 4s linear infinite;"></div><div style="position:absolute; top:11px; left:11px; background-color: #10b981; width: 8px; height: 8px; border-radius: 50%; box-shadow: 0 0 10px #10b981;"></div>`,
                    iconSize: [30, 30],
                    iconAnchor: [15, 15]
                });
                
                this.targetMarker = L.marker([lat, lng], {icon: targetIcon}).addTo(this.map);
                this.map.flyTo([lat, lng], 13, { duration: 1 }); // zoom into the order
                
                setTimeout(() => {
                    this.isBroadcasting = false;
                    this.simulationStatus = 'running';
                    this.startBidStream();
                }, 1500);
            },

            startBidStream() {
                const drivers = [
                    'Budi Trans', 'Agus Setiawan', 'CV Makmur Express', 'Joko Winarto', 
                    'PT Sinar Logistik', 'Hendra M.', 'Tono R.', 'Bintang Cargo', 'Lion Parcel Partner'
                ];
                
                let basePrice = 15.2; // starting price for chart in million
                
                this.simulationInterval = setInterval(() => {
                    if(Math.random() > 0.4) {
                        // Generate decreasing bids
                        basePrice = basePrice - (Math.random() * 0.3);
                        if(basePrice < 8.0) basePrice = 8.0 + (Math.random() * 0.5); // bounce
                        
                        let amount = Math.round((basePrice * 1000000) / 50000) * 50000;
                        let id = Date.now();
                        
                        this.bids.unshift({
                            id: id,
                            driver: drivers[Math.floor(Math.random() * drivers.length)],
                            route: this.newRoute + ' (' + this.newCargo + ')',
                            amount: amount,
                            status: 'pending', // pending, won, lost
                            time: new Date().toLocaleTimeString('id-ID', { hour12: false })
                        });

                        if(this.bids.length > 20) this.bids.pop();

                        // Update Chart
                        let currentData = this.chart.data.datasets[0].data;
                        currentData.shift();
                        currentData.push(basePrice);
                        this.chart.update();
                    }
                }, 1500);
            },
            
            acceptBid(id) {
                this.simulationStatus = 'stopped';
                clearInterval(this.simulationInterval);
                this.activeAuctions = Math.max(0, this.activeAuctions - 1);
                
                let acceptedBid = this.bids.find(b => b.id === id);
                this.winnerDriver = acceptedBid.driver;

                // Mark bids
                this.bids = this.bids.map(b => {
                    b.status = (b.id === id) ? 'won' : 'lost';
                    return b;
                });
                
                // --- Map Automation ---
                // Change target marker color to blue
                if(this.targetMarker) {
                    const winIcon = L.divIcon({
                        className: 'custom-div-icon',
                        html: `<div style="background-color: #3b82f6; width: 20px; height: 20px; border-radius: 50%; box-shadow: 0 0 15px #3b82f6; border: 3px solid #fff;"></div>`,
                        iconSize: [20, 20],
                        iconAnchor: [10, 10]
                    });
                    this.targetMarker.setIcon(winIcon);
                }

                // Find a random fleet to assign
                let fleet = this.fleets[Math.floor(Math.random() * this.fleets.length)];
                this.winnerFleet = fleet;

                // Make the fleet marker glow green
                const fleetWinIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="background-color: #10b981; width: 18px; height: 18px; border-radius: 50%; box-shadow: 0 0 20px #10b981; border: 3px solid #fff;"></div>`,
                    iconSize: [18, 18],
                    iconAnchor: [9, 9]
                });
                fleet.marker.setIcon(fleetWinIcon);

                // Draw a dashed routing line
                if(this.routingLine) this.map.removeLayer(this.routingLine);
                let targetLatLng = this.targetMarker.getLatLng();
                
                this.routingLine = L.polyline([[fleet.lat, fleet.lng], [targetLatLng.lat, targetLatLng.lng]], {
                    color: '#10b981',
                    weight: 3,
                    dashArray: '10, 15',
                    className: 'routing-line-anim'
                }).addTo(this.map);

                // Zoom out map to show both truck and target
                this.map.fitBounds(this.routingLine.getBounds(), { padding: [50, 50], duration: 1.5 });
            },
            
            resetSimulation() {
                this.simulationStatus = 'idle';
                this.bids = [];
                this.hasArrived = false;
                if(this.targetMarker) {
                    this.map.removeLayer(this.targetMarker);
                    this.targetMarker = null;
                }
                if(this.routingLine) {
                    this.map.removeLayer(this.routingLine);
                    this.routingLine = null;
                }
                if(this.winnerFleet) {
                    // Reset fleet icon color back to random
                    const colors = ['#3b82f6', '#10b981', '#f59e0b', '#ec4899', '#06b6d4']; 
                    let color = colors[Math.floor(Math.random() * colors.length)];
                    const icon = L.divIcon({
                        className: 'custom-div-icon',
                        html: `<div style="background-color: ${color}; width: 14px; height: 14px; border-radius: 50%; box-shadow: 0 0 10px ${color}; border: 2px solid rgba(255,255,255,0.8);"></div>`,
                        iconSize: [14, 14],
                        iconAnchor: [7, 7]
                    });
                    this.winnerFleet.marker.setIcon(icon);
                    this.winnerFleet = null;
                }
                this.map.flyTo([-6.200000, 106.816666], 11, { duration: 1 });
                
                // Reset chart
                let currentData = this.chart.data.datasets[0].data;
                for(let i=0; i<currentData.length; i++) currentData[i] = 15.2;
                this.chart.update();
            }
        }));
    });
</script>
@endpush
