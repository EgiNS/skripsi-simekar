<div>
    <button wire:click="loadPegawai('{{ $id_satker }}', '{{ $jabatan }}')"
        class="px-3 py-1 bg-[#17C1E8] text-white rounded hover:bg-[#3ab6d2]">
        Info
    </button>

    <button wire:click="openModalEdit('{{ $id }}', '{{ $formasi }}')"
        class="px-3 py-1 bg-[#FB8A33] text-white rounded hover:bg-[#e8863c]">
        Ubah
    </button>

    <button wire:click="openModalDelete('{{ $id }}', '{{ $nama_satker }}', '{{ $jabatan }}')"
        class="px-3 py-1 bg-[#F53939] text-white rounded hover:bg-[#e83e3e]">
        Hapus
    </button>

    <div x-data="{ open: @entangle('showModalInfo') }">
        <div x-show="open" class="fixed z-50 inset-0 flex items-center justify-center bg-transparent backdrop-blur-sm">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96 max-h-72 overflow-auto">
                <div class="flex flex-row justify-between mb-4 border-b-2 pb-2">
                    <h2 class="text-lg font-semibold">Daftar Pegawai</h2> <hr>
                    <button @click="open = false" class="text-gray-500 hover:text-gray-700">&times;</button>     
                </div>
                <ol class="list-decimal px-4">
                    @forelse($pegawaiList as $pegawai)
                        <li class="text-gray-700 mb-1">
                            {{ $pegawai['nama'] }} <br> 
                            <span class="text-sm font-light">NIP: {{ $pegawai['nip'] }}</span>
                        </li>
                    @empty
                        <li class="text-gray-500">Tidak ada pegawai</li>
                    @endforelse
                </ol>

                <div class="w-full flex justify-end items-end flex-row">
                    <button @click="open = false" class="mt-4 px-3 py-1 bg-[#8392AB] text-white rounded hover:bg-[#79869d]">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div x-data="{ open: @entangle('showModalEdit') }">
        <div x-show="open" class="fixed z-50 inset-0 flex items-center justify-center bg-transparent backdrop-blur-sm">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96 max-h-72 overflow-auto">
                <div class="flex flex-row justify-between mb-4 border-b-2 pb-2">
                    <h2 class="text-lg font-semibold">Edit Formasi</h2> <hr>
                    <button @click="open = false" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>
                
                <label class="text-xs font-normal">Formasi</label>
                <input type="text" wire:model="formasi" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />

                @error('formasi')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror

                <div class="mt-4 flex justify-end space-x-2">
                    <button @click="open = false" class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Batal
                    </button>
                    <button wire:click="saveEdit" class="px-3 py-1 bg-[#CB0C9F] text-white rounded hover:bg-[#b42f95]">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div x-data="{ open: @entangle('showModalDelete') }" wire:key="hapusId">
        <div x-show="open" class="fixed z-50 inset-0 flex items-center justify-center bg-transparent backdrop-blur-sm">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96 max-h-72">
                <div class="flex flex-row justify-between mb-4 border-b-2 pb-2">
                    <h2 class="text-lg font-semibold">Hapus ABK</h2> <hr>
                    <button @click="open = false" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>
                
                <p class="break-words whitespace-normal">Apakah Anda yakin menghapus jabatan <span class="font-semibold" x-text="$wire.jabatan"></span> di <span class="font-semibold" x-text="$wire.nama_satker"></span>?</p>

                <div class="mt-4 flex justify-end space-x-2">
                    <button @click="open = false" class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Batal
                    </button>
                    <button wire:click="delete" class="px-3 py-1 bg-[#F53939] text-white rounded hover:bg-[#e83e3e]">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
