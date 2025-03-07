<div>
    <button wire:click="openModalEdit('{{ $nama }}')"
        class="px-3 py-1 bg-[#17C1E8] text-white rounded hover:bg-[#35acc7]">
        Ubah Status
    </button>

    <div x-data="{ open: @entangle('showModalEdit') }">
        <div x-show="open" class="fixed z-50 inset-0 flex items-center justify-center bg-transparent backdrop-blur-sm">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96 max-h-72 overflow-auto relative">
                <div class="flex justify-between items-center border-b pb-2">
                    <h2 class="text-lg font-semibold">Ubah Status</h2>
                    <button @click="open = false" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>
                
                <div class="relative w-full">
                    <select class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow pr-10" >
                        <option value=1>Belum Ditindaklanjuti</option>
                        <option value=2>Sudah Ditindaklanjuti</option>
                        <option value=3>Batal Ditindaklanjuti</option>
                    </select>
                    <!-- Chevron Icon -->
                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500 transition-all" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <div class="mt-4 flex justify-end space-x-2">
                    <button @click="open = false" class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Batal
                    </button>
                    <button wire:click="" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>