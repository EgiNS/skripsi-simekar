<div>
    <button wire:click="openModalEdit('{{ $data->nama_simpeg }}', '{{ $data->konversi }}', '{{ $data->nama_umum }}')"
        class="px-3 py-1 bg-[#FB8A33] text-white rounded hover:bg-[#e8863c]">
        Ubah
    </button>

    <div x-data="{ open: @entangle('showModalEdit') }">
        <div x-show="open" class="fixed z-50 inset-0 flex items-center justify-center bg-transparent backdrop-blur-sm">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96 max-h-72 overflow-auto">
                <div class="flex flex-row justify-between mb-4 border-b-2 pb-2">
                    <h2 class="text-lg font-semibold">Edit Nomenklatur</h2> <hr>
                    <span @click="open = false" class="bg-[#8392AB] rounded-sm flex items-center justify-center px-1 cursor-pointer">
                        <svg class="w-3 font-light" fill="#ffffff" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                    </span>
                </div>
                
                <label class="block mb-2">Nama Simpeg</label>
                <input type="text" wire:model="nama_simpeg" class="w-full border rounded p-2">

                @error('nama_simpeg')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror

                <label class="block mb-2">Nama Konversi</label>
                <input type="text" wire:model="konversi" class="w-full border rounded p-2">

                @error('konversi')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror

                <label class="block mb-2">Nama Umum</label>
                <input type="text" wire:model="nama_umum" class="w-full border rounded p-2">

                @error('nama_umum')
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
</div>