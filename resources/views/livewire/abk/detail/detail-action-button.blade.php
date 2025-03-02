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
                    <span @click="open = false" class="bg-[#8392AB] rounded-sm flex items-center justify-center px-1 cursor-pointer">
                        <svg class="w-3 font-light" fill="#ffffff" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                    </span>
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
                    <span @click="open = false" class="bg-[#8392AB] rounded-sm flex items-center justify-center px-1 cursor-pointer">
                        <svg class="w-3 font-light" fill="#ffffff" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                    </span>
                </div>
                
                <label class="block mb-2">Formasi</label>
                <input type="text" wire:model="formasi" class="w-full border rounded p-2">

                @error('formasi')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror

                <div class="mt-4 flex justify-end space-x-2">
                    <button @click="open = false" class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Batal
                    </button>
                    <button wire:click="saveEdit" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
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
                    <span @click="open = false" class="bg-[#8392AB] rounded-sm flex items-center justify-center px-1 cursor-pointer">
                        <svg class="w-3 font-light" fill="#ffffff" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                    </span>
                </div>
                
                <p class="break-words whitespace-normal">Apakah Anda yakin menghapus jabatan <span class="font-semibold" x-text="$wire.jabatan"></span> di <span class="font-semibold" x-text="$wire.nama_satker"></span>?</p>

                <div class="mt-4 flex justify-end space-x-2">
                    <button @click="open = false" class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Batal
                    </button>
                    <button wire:click="saveEdit" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
