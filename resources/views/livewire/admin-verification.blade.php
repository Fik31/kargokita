<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white p-6 rounded-lg shadow border-t-4 border-brand-black">
        <h2 class="text-2xl font-bold text-brand-black mb-6">Daftar Verifikasi Menunggu Persetujuan</h2>

        @if (session()->has('message'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50">
                {{ session('message') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama User</th>
                        <th scope="col" class="px-6 py-3">Tipe Role</th>
                        <th scope="col" class="px-6 py-3">Data Dokumen</th>
                        <th scope="col" class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $req)
                        <tr class="bg-white border-b">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $req->user->name }}<br>
                                <span class="text-xs text-gray-500">{{ $req->user->email }}</span>
                            </td>
                            <td class="px-6 py-4 uppercase">
                                {{ $req->type }}
                            </td>
                            <td class="px-6 py-4">
                                <ul class="list-disc pl-4">
                                    @foreach($req->data as $key => $val)
                                        <li><strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong> {{ $val }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="px-6 py-4 space-x-2">
                                <button wire:click="approve({{ $req->id }})" class="bg-green-600 hover:bg-green-700 text-white py-1 px-3 rounded text-xs font-bold">Terima</button>
                                <button wire:click="reject({{ $req->id }})" class="bg-brand-blue hover:bg-blue-700 text-white py-1 px-3 rounded text-xs font-bold">Tolak</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada pengajuan baru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
