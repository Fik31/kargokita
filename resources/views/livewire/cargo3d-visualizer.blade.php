<div class="p-4 md:p-6 h-[calc(100vh-80px)] flex flex-col" x-data="optimizerData()">
    <div class="mb-4 flex flex-col md:flex-row justify-between md:items-center gap-4 shrink-0">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">LTL Cargo Load Optimizer 3D <span class="text-xs bg-gradient-to-r from-brand-blue to-purple-600 text-white px-2 py-1 rounded ml-2 shadow">PRO SHOWCASE</span></h2>
            <p class="text-gray-600">Simulasi Cerdas Penataan Muatan Tebengan (Multi-Merchant)</p>
        </div>
    </div>

    <div class="flex-1 flex flex-col xl:flex-row gap-6 min-h-0">
        <!-- Control Panel (Kiri) -->
        <div class="w-full xl:w-80 bg-white rounded-xl shadow-lg p-5 flex flex-col gap-5 overflow-y-auto shrink-0 border border-gray-100">
            
            <!-- Truck Selection -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tipe Armada (Vehicle)</label>
                <div class="relative">
                    <select x-model="truckType" @change="changeTruck()" class="w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:border-brand-blue focus:ring-brand-blue appearance-none bg-gray-50 font-medium text-gray-700">
                        <option value="cde">Engkel (CDE) - 6 Ton</option>
                        <option value="cdd">Double (CDD) - 12 Ton</option>
                        <option value="fuso">Fuso / Tronton - 25 Ton</option>
                    </select>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Add Merchant Load -->
            <div class="border-t border-gray-100 pt-5">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tambah Antrian Muatan</label>
                <div class="space-y-3 bg-blue-50/50 p-3 rounded-lg border border-blue-100">
                    <input type="text" x-model="newMerchantName" placeholder="Nama Merchant (Cth: PT Logistik)" class="w-full text-sm border-gray-300 rounded-md focus:ring-brand-blue focus:border-brand-blue">
                    
                    <div class="flex gap-2 items-center">
                        <div class="relative flex-1">
                            <input type="number" x-model="newBoxCount" placeholder="Est. Jml Box" class="w-full text-sm border-gray-300 rounded-md pl-9 focus:ring-brand-blue focus:border-brand-blue">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                        </div>
                        <input type="color" x-model="newColor" class="w-10 h-10 border-0 rounded cursor-pointer bg-transparent p-0" title="Pilih Warna Label Merchant">
                    </div>
                    
                    <button @click="addMerchantLoad()" class="w-full py-2 bg-white hover:bg-brand-blue hover:text-white text-brand-blue text-sm font-bold rounded-md transition-colors border border-brand-blue shadow-sm">
                        + Masukkan Antrian
                    </button>
                </div>
            </div>

            <!-- Load Queue (Legend) -->
            <div class="flex-1 overflow-y-auto border-t border-gray-100 pt-5 min-h-[150px]">
                <label class="flex justify-between items-center text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    <span>Daftar LTL (Legend)</span>
                    <span class="bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full" x-text="loads.length"></span>
                </label>
                
                <template x-if="loads.length === 0">
                    <div class="text-center py-6 bg-gray-50 rounded-lg border border-dashed border-gray-200">
                        <p class="text-xs text-gray-400 italic">Antrian kosong.<br>Silakan tambah muatan di atas.</p>
                    </div>
                </template>
                
                <div class="space-y-2">
                    <template x-for="(load, index) in loads" :key="index">
                        <div class="flex justify-between items-center p-2.5 bg-white rounded-lg border border-gray-200 shadow-sm hover:border-brand-blue transition-colors group">
                            <div class="flex items-center gap-3">
                                <div class="w-4 h-4 rounded shadow-inner" :style="`background-color: ${load.color}`"></div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800 leading-tight" x-text="load.name"></p>
                                    <p class="text-xs text-gray-500" x-text="`${load.count} boxes`"></p>
                                </div>
                            </div>
                            <button @click="removeLoad(index)" class="text-gray-300 hover:text-red-500 transition-colors bg-gray-50 hover:bg-red-50 p-1.5 rounded-md opacity-0 group-hover:opacity-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-5 border-t border-gray-100 space-y-3 shrink-0">
                <button @click="startSimulation()" :disabled="loads.length === 0 || isAnimating" :class="(loads.length === 0 || isAnimating) ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700 hover:-translate-y-0.5 hover:shadow-lg'" class="w-full py-3 bg-gradient-to-r from-brand-blue to-blue-600 text-white font-bold rounded-lg shadow-md transition-all transform flex justify-center items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Mulai Simulasi 3D
                </button>
                <button @click="resetTruck()" :disabled="isAnimating" class="w-full py-2 bg-white border-2 border-gray-200 text-gray-500 font-bold rounded-lg hover:bg-gray-100 hover:text-gray-700 transition-colors">
                    Reset & Kosongkan Truk
                </button>
            </div>
        </div>

        <!-- 3D Container (Kanan) -->
        <div id="canvas-container" class="flex-1 h-full min-h-[400px] bg-gradient-to-br from-gray-900 to-gray-800 rounded-xl shadow-2xl relative overflow-hidden ring-1 ring-white/10" wire:ignore>
            
            <!-- Blueprint Grid Overlay effect -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAwIDQwIEwgNDAgNDAgNDAgMCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSJyZ2JhKDI1NSwyNTUsMjU1LDAuMDUpIiBzdHJva2Utd2lkdGg9IjEiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JpZCkiLz48L3N2Zz4=')] opacity-30 pointer-events-none"></div>

            <!-- Analytics Dashboard Glass -->
            <div class="absolute top-2 left-2 right-2 md:top-4 md:left-auto md:right-4 bg-black/60 backdrop-blur-xl p-3 md:p-5 rounded-xl border border-white/20 text-white shadow-2xl pointer-events-none z-10 md:w-72">
                <div class="flex items-center gap-2 border-b border-white/20 pb-2 md:pb-3 mb-2 md:mb-3">
                    <svg class="w-4 h-4 md:w-5 md:h-5 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <h3 class="font-bold text-xs md:text-sm tracking-wide">LIVE TELEMETRY</h3>
                </div>
                
                <div class="grid grid-cols-2 gap-2 md:grid-cols-1 md:space-y-3">
                    <div class="flex flex-col md:flex-row justify-between md:items-end">
                        <span class="text-[0.65rem] md:text-xs text-gray-400">Kapasitas Truk</span>
                        <span class="font-bold text-sm md:text-lg" id="stat-capacity">0m³</span>
                    </div>
                    <div class="flex flex-col md:flex-row justify-between md:items-end">
                        <span class="text-[0.65rem] md:text-xs text-gray-400">Volume Terpakai</span>
                        <span class="font-bold text-sm md:text-lg text-emerald-400" id="stat-used">0m³</span>
                    </div>
                    <div class="flex flex-col md:flex-row justify-between md:items-end">
                        <span class="text-[0.65rem] md:text-xs text-gray-400">Total Dimuat</span>
                        <span class="font-bold text-sm md:text-lg text-amber-400" id="stat-items">0 Box</span>
                    </div>
                    <div class="flex flex-col md:flex-row justify-between md:items-end">
                        <span class="text-[0.65rem] md:text-xs text-gray-400">Overflow (Sisa)</span>
                        <span class="font-bold text-sm md:text-lg text-red-400" id="stat-overflow">0 Box</span>
                    </div>
                    <div class="col-span-2 md:col-span-1 flex flex-row justify-between items-end mt-1 md:mt-0">
                        <span class="text-[0.65rem] md:text-xs text-gray-400">Space Efficiency</span>
                        <span class="font-bold text-base md:text-xl text-blue-400 drop-shadow-md" id="stat-efficiency">0%</span>
                    </div>
                </div>

                <div class="mt-2 md:mt-4 pt-2 md:pt-4 border-t border-white/10 hidden md:block">
                    <div class="w-full bg-gray-800 rounded-full h-3 ring-1 ring-inset ring-white/10 overflow-hidden">
                        <div id="stat-bar" class="bg-gradient-to-r from-emerald-400 via-blue-500 to-purple-500 h-3 w-0 transition-all duration-300 relative">
                            <!-- Shine effect on bar -->
                            <div class="absolute inset-0 bg-gradient-to-b from-white/30 to-transparent"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Instructions overlay -->
            <div class="absolute bottom-2 md:bottom-6 left-1/2 -translate-x-1/2 bg-black/60 backdrop-blur-md text-white/80 text-[0.65rem] md:text-xs px-3 md:px-5 py-1.5 md:py-2 rounded-full pointer-events-none z-10 border border-white/10 flex gap-2 md:gap-4 shadow-lg w-max">
                <span class="flex items-center gap-1"><svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg> <span class="hidden md:inline">Kiri: </span>Putar</span>
                <span class="w-px bg-white/20"></span>
                <span class="flex items-center gap-1"><svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg> Zoom</span>
                <span class="w-px bg-white/20"></span>
                <span class="flex items-center gap-1"><svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9V5a2 2 0 012-2h2a2 2 0 012 2v4M14 11h.01M10 15V9m4 6v-6m4 6v-6M4 19v-2a2 2 0 012-2h12a2 2 0 012 2v2"></path></svg> <span class="hidden md:inline">Kanan: </span>Geser</span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script type="importmap">
  {
    "imports": {
      "three": "https://unpkg.com/three@0.160.0/build/three.module.js",
      "three/addons/": "https://unpkg.com/three@0.160.0/examples/jsm/"
    }
  }
</script>
<script type="module">
    import * as THREE from 'three';
    import { OrbitControls } from 'three/addons/controls/OrbitControls.js';

    // Pretty colors for merchants
    const defaultColors = ['#EF4444', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899', '#06B6D4', '#EAB308'];

    // Alpine.js Logic
    document.addEventListener('alpine:init', () => {
        Alpine.data('optimizerData', () => ({
            truckType: 'cdd', // cde, cdd, fuso
            loads: [
                { name: 'PT Makmur Jaya (Laptop & IT)', count: 25, color: '#3B82F6' },
                { name: 'CV Sinar Abadi (Sparepart)', count: 18, color: '#10B981' }
            ],
            newMerchantName: '',
            newBoxCount: '',
            newColor: '#F59E0B',
            isAnimating: false,
            
            init() {
                setTimeout(() => {
                    window.buildTruck(this.truckType);
                }, 100);
            },
            
            addMerchantLoad() {
                if(!this.newMerchantName || !this.newBoxCount) {
                    alert('Harap isi Nama Merchant dan Jumlah Box!');
                    return;
                }
                this.loads.push({
                    name: this.newMerchantName,
                    count: parseInt(this.newBoxCount),
                    color: this.newColor
                });
                this.newMerchantName = '';
                this.newBoxCount = '';
                // Auto-pick next color for convenience
                this.newColor = defaultColors[this.loads.length % defaultColors.length];
            },
            
            removeLoad(index) {
                this.loads.splice(index, 1);
            },
            
            changeTruck() {
                window.buildTruck(this.truckType);
            },
            
            startSimulation() {
                if(this.loads.length === 0) return;
                this.isAnimating = true;
                window.simulateLoading(this.loads, () => {
                    this.isAnimating = false;
                });
            },
            
            resetTruck() {
                window.clearCargo();
            }
        }));
    });

    // ==========================================
    // THREE.JS SETUP & ENGINE
    // ==========================================
    const container = document.getElementById('canvas-container');
    const scene = new THREE.Scene();
    // No background color, make it transparent to show gradient div
    scene.background = null; 
    
    // Add subtle fog to blend the horizon
    scene.fog = new THREE.FogExp2('#1f2937', 0.015);

    const camera = new THREE.PerspectiveCamera(45, container.clientWidth / container.clientHeight, 1, 1000);
    camera.position.set(25, 20, 30);

    const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    renderer.setSize(container.clientWidth, container.clientHeight);
    renderer.shadowMap.enabled = true;
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;
    // Tone mapping for better realistic lighting
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.0;
    container.appendChild(renderer.domElement);

    const controls = new OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.05;
    controls.maxPolarAngle = Math.PI / 2 - 0.02; // Prevents going below ground

    // Lighting (Studio Setup)
    scene.add(new THREE.AmbientLight(0xffffff, 0.4));
    
    const dirLight = new THREE.DirectionalLight(0xffffff, 1.2);
    dirLight.position.set(20, 40, 20);
    dirLight.castShadow = true;
    dirLight.shadow.mapSize.width = 4096;
    dirLight.shadow.mapSize.height = 4096;
    dirLight.shadow.camera.left = -30;
    dirLight.shadow.camera.right = 30;
    dirLight.shadow.camera.top = 30;
    dirLight.shadow.camera.bottom = -30;
    dirLight.shadow.bias = -0.0001; // Reduce shadow acne
    scene.add(dirLight);

    const backLight = new THREE.DirectionalLight(0x60a5fa, 0.5); // bluish back light
    backLight.position.set(-20, 10, -20);
    scene.add(backLight);

    // Ground & Environment
    const gridHelper = new THREE.GridHelper(100, 50, 0x444444, 0x222222);
    gridHelper.position.y = -0.01;
    scene.add(gridHelper);

    // Shadow receiver plane (invisible mostly)
    const groundGeo = new THREE.PlaneGeometry(200, 200);
    const groundMat = new THREE.ShadowMaterial({ opacity: 0.5 });
    const ground = new THREE.Mesh(groundGeo, groundMat);
    ground.rotation.x = -Math.PI / 2;
    ground.receiveShadow = true;
    scene.add(ground);

    let truckGroup = new THREE.Group();
    scene.add(truckGroup);
    
    let cargoGroup = new THREE.Group();
    scene.add(cargoGroup);

    let truckSpecs = { width: 0, length: 0, height: 0, capacity: 0 };
    let currentVolume = 0;
    let boxCount = 0;
    let overflowCount = 0;

    // Build the 3D Truck
    window.buildTruck = function(type) {
        scene.remove(truckGroup);
        truckGroup = new THREE.Group();
        scene.add(truckGroup);
        window.clearCargo();

        // Size configurations (scaled meters)
        // W x H x L (Inner Box dimensions)
        if(type === 'cde') truckSpecs = { width: 4.0, height: 3.5, length: 8.0, capTon: 6, wheels: 2, headColor: '#eab308' }; // yellow
        else if(type === 'cdd') truckSpecs = { width: 5.0, height: 4.0, length: 12.0, capTon: 12, wheels: 3, headColor: '#ef4444' }; // red
        else if(type === 'fuso') truckSpecs = { width: 6.0, height: 4.5, length: 18.0, capTon: 25, wheels: 4, headColor: '#3b82f6' }; // blue
        
        truckSpecs.capacity = truckSpecs.width * truckSpecs.height * truckSpecs.length;
        document.getElementById('stat-capacity').innerHTML = `<span class="text-[0.65rem] md:text-sm">${truckSpecs.capTon} Ton</span> / ${truckSpecs.capacity.toFixed(0)}m³`;

        const chassisHeight = 1.0;
        
        // --- 1. Chassis Frame ---
        const chassis = new THREE.Mesh(
            new THREE.BoxGeometry(truckSpecs.width - 1, 0.4, truckSpecs.length + 4),
            new THREE.MeshStandardMaterial({ color: '#1f2937', metalness: 0.8, roughness: 0.2 }) 
        );
        chassis.position.y = chassisHeight;
        chassis.position.z = 1.5; // shift backward
        chassis.castShadow = true;
        truckGroup.add(chassis);

        // --- 2. Cabin (Head) ---
        const cabinGroup = new THREE.Group();
        const headW = truckSpecs.width;
        const headH = truckSpecs.height * 0.9;
        const headL = 3.5;
        const headZ = -truckSpecs.length/2 - headL/2 - 0.5;

        const cabinMat = new THREE.MeshStandardMaterial({ 
            color: truckSpecs.headColor, 
            roughness: 0.2, 
            metalness: 0.3,
            clearcoat: 1.0
        });

        // Lower Body
        const cabinBody = new THREE.Mesh(new THREE.BoxGeometry(headW, headH * 0.6, headL), cabinMat);
        cabinBody.position.set(0, chassisHeight + (headH * 0.6)/2, headZ);
        cabinBody.castShadow = true;
        cabinBody.receiveShadow = true;
        cabinGroup.add(cabinBody);

        // Upper Cabin (Roof/Windows)
        const cabinTop = new THREE.Mesh(new THREE.BoxGeometry(headW * 0.9, headH * 0.4, headL * 0.7), cabinMat);
        cabinTop.position.set(0, chassisHeight + headH * 0.6 + (headH*0.4)/2, headZ + 0.5);
        cabinTop.castShadow = true;
        cabinGroup.add(cabinTop);
        
        // Front Windshield
        const winMat = new THREE.MeshStandardMaterial({ color: '#111827', roughness: 0.05, metalness: 0.9 });
        const frontWinGeo = new THREE.PlaneGeometry(headW * 0.8, headH * 0.35);
        const frontWin = new THREE.Mesh(frontWinGeo, winMat);
        frontWin.position.set(0, chassisHeight + headH * 0.6 + (headH*0.4)/2, headZ - (headL*0.7)/2 - 0.01);
        frontWin.rotation.x = -Math.PI; // Face front
        cabinGroup.add(frontWin);

        // Grill & Bumper
        const grill = new THREE.Mesh(new THREE.BoxGeometry(headW * 0.6, 0.8, 0.1), new THREE.MeshStandardMaterial({ color: '#374151', metalness: 0.8 }));
        grill.position.set(0, chassisHeight + 0.6, headZ - headL/2 - 0.05);
        cabinGroup.add(grill);

        const bumper = new THREE.Mesh(new THREE.BoxGeometry(headW, 0.4, 0.4), new THREE.MeshStandardMaterial({ color: '#111827', metalness: 0.5 }));
        bumper.position.set(0, chassisHeight + 0.2, headZ - headL/2 - 0.1);
        cabinGroup.add(bumper);

        // Headlights
        const lightMat = new THREE.MeshStandardMaterial({ color: '#ffffff', emissive: '#ffffff', emissiveIntensity: 0.5 });
        const lightGeo = new THREE.CircleGeometry(0.2, 16);
        const lLight = new THREE.Mesh(lightGeo, lightMat);
        lLight.position.set(-headW/2 + 0.5, chassisHeight + 0.6, headZ - headL/2 - 0.06);
        lLight.rotation.y = Math.PI;
        cabinGroup.add(lLight);
        
        const rLight = lLight.clone();
        rLight.position.x = headW/2 - 0.5;
        cabinGroup.add(rLight);

        truckGroup.add(cabinGroup);

        // --- 3. Wheels ---
        const wheelGeo = new THREE.CylinderGeometry(0.7, 0.7, 0.5, 32);
        wheelGeo.rotateZ(Math.PI / 2);
        const wheelMat = new THREE.MeshStandardMaterial({ color: '#000000', roughness: 0.9, metalness: 0.1 });
        const rimMat = new THREE.MeshStandardMaterial({ color: '#e5e7eb', metalness: 0.8, roughness: 0.2 });
        
        const addWheel = (x, z) => {
            const wGroup = new THREE.Group();
            
            const tire = new THREE.Mesh(wheelGeo, wheelMat);
            tire.castShadow = true;
            wGroup.add(tire);
            
            const rim = new THREE.Mesh(new THREE.CylinderGeometry(0.4, 0.4, 0.52, 16), rimMat);
            rim.rotateZ(Math.PI / 2);
            wGroup.add(rim);

            wGroup.position.set(x, 0.7, z);
            truckGroup.add(wGroup);
        };

        // Front wheels (Cabin)
        addWheel(truckSpecs.width/2, headZ);
        addWheel(-truckSpecs.width/2, headZ);
        
        // Rear wheels
        const rearZ = truckSpecs.length/2;
        addWheel(truckSpecs.width/2, rearZ - 1);
        addWheel(-truckSpecs.width/2, rearZ - 1);
        if(truckSpecs.wheels >= 3) {
            addWheel(truckSpecs.width/2, rearZ - 3.5);
            addWheel(-truckSpecs.width/2, rearZ - 3.5);
        }
        if(truckSpecs.wheels >= 4) {
            addWheel(truckSpecs.width/2, rearZ - 6);
            addWheel(-truckSpecs.width/2, rearZ - 6);
        }

        // --- 4. Cargo Bed (Container) ---
        const bedGroup = new THREE.Group();
        bedGroup.position.set(0, chassisHeight + 0.2, 0); 
        
        // Floor
        const floor = new THREE.Mesh(
            new THREE.BoxGeometry(truckSpecs.width, 0.2, truckSpecs.length),
            new THREE.MeshStandardMaterial({ color: '#4b5563', roughness: 0.9 }) 
        );
        floor.position.y = 0.1;
        floor.receiveShadow = true;
        bedGroup.add(floor);

        // Glass Walls for visibility
        const wallMat = new THREE.MeshPhysicalMaterial({ 
            color: '#93c5fd', 
            transparent: true, 
            opacity: 0.2,
            transmission: 0.5,
            roughness: 0.1,
            metalness: 0,
            side: THREE.DoubleSide 
        });
        
        const leftWall = new THREE.Mesh(new THREE.BoxGeometry(0.1, truckSpecs.height, truckSpecs.length), wallMat);
        leftWall.position.set(-truckSpecs.width/2, truckSpecs.height/2 + 0.2, 0);
        leftWall.receiveShadow = true;
        bedGroup.add(leftWall);
        
        const rightWall = new THREE.Mesh(new THREE.BoxGeometry(0.1, truckSpecs.height, truckSpecs.length), wallMat);
        rightWall.position.set(truckSpecs.width/2, truckSpecs.height/2 + 0.2, 0);
        rightWall.receiveShadow = true;
        bedGroup.add(rightWall);
        
        const frontWall = new THREE.Mesh(new THREE.BoxGeometry(truckSpecs.width, truckSpecs.height, 0.1), wallMat);
        frontWall.position.set(0, truckSpecs.height/2 + 0.2, -truckSpecs.length/2);
        frontWall.receiveShadow = true;
        bedGroup.add(frontWall);

        // Frame Edges (Besi Rangka)
        const frameMat = new THREE.MeshStandardMaterial({ color: '#d1d5db', metalness: 0.7, roughness: 0.3 });
        
        const addPillar = (x, z) => {
            const pillar = new THREE.Mesh(new THREE.BoxGeometry(0.2, truckSpecs.height, 0.2), frameMat);
            pillar.position.set(x, truckSpecs.height/2 + 0.2, z);
            pillar.castShadow = true;
            bedGroup.add(pillar);
        };

        addPillar(-truckSpecs.width/2, -truckSpecs.length/2);
        addPillar(truckSpecs.width/2, -truckSpecs.length/2);
        addPillar(-truckSpecs.width/2, truckSpecs.length/2);
        addPillar(truckSpecs.width/2, truckSpecs.length/2);
        
        // Top Rails
        const topRailL = new THREE.Mesh(new THREE.BoxGeometry(0.2, 0.2, truckSpecs.length), frameMat);
        topRailL.position.set(-truckSpecs.width/2, truckSpecs.height + 0.2, 0);
        bedGroup.add(topRailL);
        
        const topRailR = topRailL.clone();
        topRailR.position.x = truckSpecs.width/2;
        bedGroup.add(topRailR);

        truckGroup.add(bedGroup);
        
        // Adjust camera smartly based on truck length
        camera.position.set(truckSpecs.length * 1.2, truckSpecs.height * 2.5, truckSpecs.length * 1.5);
        controls.target.set(0, truckSpecs.height, 0);
        controls.update();
    };

    window.clearCargo = function() {
        while(cargoGroup.children.length > 0){ 
            const b = cargoGroup.children[0];
            cargoGroup.remove(b); 
            b.geometry.dispose();
            b.material.dispose();
            if(b.children.length > 0) b.children[0].material.dispose(); // remove edges
        }
        currentVolume = 0;
        boxCount = 0;
        overflowCount = 0;
        updateStats();
    };

    function updateStats() {
        document.getElementById('stat-items').innerText = boxCount + ' Box';
        document.getElementById('stat-used').innerText = currentVolume.toFixed(1) + 'm³';
        document.getElementById('stat-overflow').innerText = overflowCount + ' Box';
        
        let eff = ((currentVolume / truckSpecs.capacity) * 100);
        eff = Math.max(0, Math.min(eff, 100)); 
        
        document.getElementById('stat-efficiency').innerText = eff.toFixed(1) + '%';
        
        const bar = document.getElementById('stat-bar');
        if(bar) {
            bar.style.width = eff + '%';
            if(eff > 90) bar.className = "bg-gradient-to-r from-red-500 to-red-600 h-3 transition-all duration-300 relative";
            else if(eff > 75) bar.className = "bg-gradient-to-r from-yellow-400 to-orange-500 h-3 transition-all duration-300 relative";
            else bar.className = "bg-gradient-to-r from-emerald-400 via-blue-500 to-purple-500 h-3 transition-all duration-300 relative";
        }
    }

    window.simulateLoading = function(loads, onComplete) {
        window.clearCargo();

        // Flatten loads queue
        let boxQueue = [];
        loads.forEach(load => {
            for(let i=0; i<load.count; i++) {
                // Size mapping: varies randomly but constrained
                let maxW = truckSpecs.width / 4;
                boxQueue.push({
                    w: (Math.random() * 0.6 + 0.4) * maxW,
                    h: (Math.random() * 0.6 + 0.4) * maxW,
                    d: (Math.random() * 0.6 + 0.4) * maxW,
                    color: load.color
                });
            }
        });

        const truckW = truckSpecs.width;
        const truckL = truckSpecs.length;
        const truckH = truckSpecs.height;
        const bedYOffset = 1.0 + 0.2 + 0.1; // chassisHeight + bedGroup offset + floor height

        let currentZ = -truckL/2 + 0.2;
        let currentY = bedYOffset;
        let currentX = -truckW/2 + 0.2;
        let maxHeightInRow = 0;
        let isFull = false;

        const placeNext = (index) => {
            if(index >= boxQueue.length) {
                if(onComplete) onComplete();
                return;
            }

            let b = boxQueue[index];
            
            if (isFull) {
                overflowCount++;
                updateStats();
                placeNext(index + 1);
                return;
            }

            // --- Basic Packing Logic ---
            // Move Right
            if (currentX + b.w > truckW/2) {
                currentX = -truckW/2 + b.w/2;
                currentZ += b.d + 0.1; // Move Forward (Z)
            }
            // Move Up (New Layer)
            if (currentZ + b.d/2 > truckL/2) {
                currentZ = -truckL/2 + b.d/2;
                currentX = -truckW/2 + b.w/2;
                currentY += maxHeightInRow + 0.1;
                maxHeightInRow = 0;
            }
            // Truck Full Check
            if (currentY + b.h > bedYOffset + truckH) {
                isFull = true;
                overflowCount++;
                updateStats();
                placeNext(index + 1); // skip drawing, add to overflow
                return;
            }

            maxHeightInRow = Math.max(maxHeightInRow, b.h);

            // Create Box Mesh
            const boxGeo = new THREE.BoxGeometry(b.w, b.h, b.d);
            const boxMat = new THREE.MeshStandardMaterial({ 
                color: b.color, 
                roughness: 0.5,
                metalness: 0.1,
            });
            const mesh = new THREE.Mesh(boxGeo, boxMat);
            mesh.castShadow = true;
            mesh.receiveShadow = true;
            
            // Box Edges for better visual definition (Carton lines)
            const edges = new THREE.LineSegments(
                new THREE.EdgesGeometry(boxGeo), 
                new THREE.LineBasicMaterial({ color: 0xffffff, opacity: 0.4, transparent: true })
            );
            mesh.add(edges);

            // Calculate Target Position
            const targetY = currentY + b.h/2;
            const targetX = currentX + b.w/2;
            const targetZ = currentZ + b.d/2;
            
            // Start high up for drop animation
            mesh.position.set(targetX, targetY + 12, targetZ);
            
            // Add subtle random rotation for realism before dropping
            mesh.rotation.y = (Math.random() - 0.5) * 0.1;
            
            cargoGroup.add(mesh);

            // --- Drop Animation ---
            const startTime = Date.now();
            const duration = 350; // ms per box drop
            const startY = mesh.position.y;
            
            const drop = () => {
                const elapsed = Date.now() - startTime;
                if (elapsed < duration) {
                    const t = elapsed / duration;
                    // Ease Out Bounce function
                    const bounce = (x) => {
                        const n1 = 7.5625, d1 = 2.75;
                        if (x < 1 / d1) return n1 * x * x; 
                        else if (x < 2 / d1) return n1 * (x -= 1.5 / d1) * x + 0.75; 
                        else if (x < 2.5 / d1) return n1 * (x -= 2.25 / d1) * x + 0.9375; 
                        else return n1 * (x -= 2.625 / d1) * x + 0.984375;
                    };
                    mesh.position.y = startY - (startY - targetY) * bounce(t);
                    requestAnimationFrame(drop);
                } else {
                    mesh.position.y = targetY; // snap to exact target
                    mesh.rotation.y = 0; // straighten out
                }
            };
            drop();

            // Advance coordinates for next box
            currentX += b.w + 0.1;
            boxCount++;
            currentVolume += (b.w * b.h * b.d);
            updateStats();

            // Fire next box drop rapidly
            setTimeout(() => placeNext(index + 1), 80);
        };

        placeNext(0);
    };

    // Render loop
    function animate() {
        requestAnimationFrame(animate);
        controls.update();
        renderer.render(scene, camera);
    }
    animate();

    window.addEventListener('resize', () => {
        if (!container) return;
        camera.aspect = container.clientWidth / container.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(container.clientWidth, container.clientHeight);
    });

</script>
@endpush
