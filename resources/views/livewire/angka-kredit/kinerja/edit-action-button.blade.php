<div>
    <button wire:click="openModalEdit('{{ $data->id }}', '{{ $data->nama }}', '{{ $data->nilai_perilaku }}', '{{ $data->nilai_kinerja }}', '{{ $data->predikat }}', '{{ $data->tahun }}')"
        class="px-3 py-1 bg-[#FB8A33] text-white rounded hover:bg-[#e8863c]">
        Ubah
    </button>

    <div x-data="{ open: @entangle('showModalEdit') }">
        <div x-show="open" class="fixed z-50 inset-0 flex items-center justify-center bg-transparent backdrop-blur-sm">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96 max-h-72 overflow-auto">
                <div class="flex flex-row justify-between mb-4 border-b-2 pb-2">
                    <h2 class="text-lg font-semibold">Edit Nilai Kinerja</h2> <hr>
                    <button @click="open = false" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>
                
                <div class="w-full mb-3">
                    <label class="text-xs font-normal">Nama</label>
                    <input type="text" readonly wire:model="nama" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />

                    @error('nama')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="w-full mb-3">
                    <label class="text-xs font-normal">Nilai Perilaku</label>
                    <input type="number" step="0.001" wire:model="nilai_perilaku" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />

                    @error('nilai_perilaku')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="w-full mb-3">
                    <label class="text-xs font-normal">Nilai Kinerja</label>
                    <input type="number" step="0.001" wire:model="nilai_kinerja" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />

                    @error('nilai_perilaku')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                    <div class="w-full mb-3 relative">
                        <label class="text-xs">Predikat</label>
                        <select wire:model="predikat" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow pr-10" >
                            <option value='Sangat Kurang'>Sangat Kurang</option>
                            <option value='Kurang'>Kurang</option>
                            <option value='Cukup'>Cukup</option>
                            <option value='Baik'>Baik</option>
                            <option value='Sangat Baik'>Sangat Baik</option>
                        </select>   
                        <!-- Chevron Icon -->
                        <div class="absolute top-6 inset-y-0 right-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500 transition-all" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
        
                        @error('predikat')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                <div class="w-full mb-3">
                    <label class="text-xs font-normal">Tahun</label>
                    <input type="number" wire:model="tahun" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />

                    @error('tahun')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4 flex justify-end space-x-2">
                    <button @click="open = false" class="px-3 py-1 bg-gray-500 text-white rounded font-medium hover:bg-gray-600">
                        Batal
                    </button>
                    <button wire:click="saveEdit" class="px-3 py-1 bg-[#CB0C9F] hover:bg-[#b42f95] font-medium text-white rounded">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>