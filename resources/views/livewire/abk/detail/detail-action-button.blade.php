<div>
    <button wire:click="loadPegawai('{{ $id_satker }}', '{{ $jabatan }}')"
        class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
        Info
    </button>

    <div x-data="{ open: @entangle('showModal') }">
        <div x-show="open" class="fixed z-50 inset-0 flex items-center justify-center bg-transparent backdrop-blur-sm">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h2 class="text-lg font-semibold mb-4">Daftar Pegawai</h2>
                <ul>
                    @forelse($pegawaiList as $pegawai)
                        <li class="text-gray-700">{{ $pegawai }}</li>
                    @empty
                        <li class="text-gray-500">Tidak ada pegawai</li>
                    @endforelse
                </ul>

                <button @click="open = false" class="mt-4 px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
