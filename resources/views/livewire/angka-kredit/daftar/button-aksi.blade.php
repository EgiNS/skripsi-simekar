<div>
    <button wire:click="openModalEdit('{{ $id }}')"
        class="px-3 py-1 bg-[#FB8A33] text-white rounded hover:bg-[#e8863c]">
        Ubah
    </button>

    <button wire:click="openModalDelete('{{ $id }}')"
        class="px-3 py-1 bg-[#F53939] text-white rounded hover:bg-[#e83e3e]">
        Hapus
    </button>

    <div x-data="{ open: @entangle('showModalEdit') }">
        <div x-show="open" class="fixed z-50 inset-0 flex items-center justify-center bg-transparent backdrop-blur-sm">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96 max-h-80 overflow-auto">
                <div class="flex flex-row justify-between mb-4 border-b-2 pb-2">
                    <h2 class="text-lg font-semibold">Edit Angka Kredit</h2> <hr>
                    <button @click="open = false" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>
                
                <label class="text-xs font-normal">Angka Kredit</label>
                <input type="text" wire:model="angka_kredit" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow mb-3" />

                @error('angka_kredit')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror

                <label class="text-xs font-normal">Total Angka Kredit</label>
                <input type="text" wire:model="total_ak" class="mb-3 focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />

                @error('total_ak')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror

                    <div class="w-full mb-3">
                        <label class="text-xs">Periode Mulai</label>
                        <input 
                            type="month" 
                            wire:model="mulai_periode"
                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" 
                        />

                        @error('akhir')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full mb-3">
                        <label class="text-xs">Periode Akhir</label>
                        <input 
                            type="month" 
                            wire:model="akhir_periode"
                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" 
                        />

                        @error('akhir')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                <label class="text-xs font-normal">Link PAK</label>
                <input type="text" wire:model="link_pak" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />

                @error('link_pak')
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
                        <h2 class="text-lg font-semibold">Hapus Riwayat Angka Kredit</h2> <hr>
                        <button @click="open = false" class="text-gray-500 hover:text-gray-700">&times;</button>
                    </div>
                    
                    <p class="break-words whitespace-normal">Apakah Anda yakin menghapus riwayat angka kredit ini?</p>

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