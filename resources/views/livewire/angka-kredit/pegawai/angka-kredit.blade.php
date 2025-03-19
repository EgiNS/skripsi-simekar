<div class="flex flex-wrap -mx-3">
    @section('beforeTitle', 'Pages')
    @section('title', 'Angka Kredit')

    <div class="flex-none w-full max-w-full px-3" x-data="{ showTambah: false }">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-5 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex flex-row justify-between">
                <p class="font-semibold text-lg text-[#252F40]">Riwayat Angka Kredit</p>
                <button class="bg-gradient-to-br from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-white px-5 font-semibold py-2 text-sm rounded-lg" @click="showTambah = true">Tambah</button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-5 overflow-x-auto">
                    <livewire:Angka-Kredit.Pegawai.Riwayat-Angka-Kredit-Table />
                </div>
            </div>
        </div>

        <div x-show="showTambah" class="fixed inset-0 flex items-center justify-center bg-transparent backdrop-blur-sm">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96 max-h-96 overflow-y-auto relative">
                <!-- Header -->
                <div class="flex justify-between items-center border-b pb-2">
                    <h2 class="text-lg font-semibold">Tambah Usul Mutasi</h2>
                    <button @click="showTambah = false" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>

                <div class="w-full mt-3">
                    <div class="w-full mb-3 relative">
                        <label class="text-xs">Jenis Angka Kredit</label>
                        <select wire:model="jenisAngkaKredit" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow pr-10" >
                            <option value=1>Integrasi</option>
                            <option value=2>Praintegrasi</option>
                            <option value=3>Konversi Tahunan</option>
                            <option value=4>Konversi Periodik</option>
                        </select>   
                         <!-- Chevron Icon -->
                        <div class="absolute top-6 inset-y-0 right-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500 transition-all" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>

                    <!-- Input Periode Mulai dan Periode Akhir -->
                    <div x-show="@this.jenisAngkaKredit == 1 || @this.jenisAngkaKredit == 2 || @this.jenisAngkaKredit == 4" class="w-full mb-3">
                        <label class="text-xs">Periode Mulai</label>
                        <input 
                            type="date" 
                            wire:model="periodeMulai" 
                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" 
                        />
                    </div>

                    <div x-show="@this.jenisAngkaKredit == 1 || @this.jenisAngkaKredit == 2 || @this.jenisAngkaKredit == 4" class="w-full mb-3">
                        <label class="text-xs">Periode Akhir</label>
                        <input 
                            type="date" 
                            wire:model="periodeAkhir" 
                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" 
                        />
                    </div>

                    <!-- Input Angka Kredit (hanya untuk jenis = 3) -->
                    <div x-show="@this.jenisAngkaKredit == 3" class="w-full mb-3">
                        <label class="text-xs">Tahun</label>
                        <input 
                            type="number" 
                            wire:model="" 
                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" 
                        />
                    </div>

                    <div class="w-full mb-3">
                        <label class="text-xs">Angka Kredit</label>
                        <input type="text" wire:model="" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                    </div>

                    <div class="w-full mb-3">
                        <label class="text-xs">Link PAK (Google Drive)</label>
                        <input type="text" wire:model="" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                    </div>

                    <div class="mt-4 flex justify-end space-x-2">
                        <button @click="showTambah = false" class="px-3 py-1 text-sm font-medium bg-gray-500 text-white rounded hover:bg-gray-600">
                            Batal
                        </button>
                        <button wire:click="" class="px-3 py-1 text-sm font-medium bg-[#CB0C9F] hover:bg-[#b42f95] text-white rounded">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>