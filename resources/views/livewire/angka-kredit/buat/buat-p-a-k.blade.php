<div class="flex flex-wrap -mx-3">
    @section('beforeTitle', 'Angka Kredit')
    @section('title', 'Buat PAK Pegawai')

    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-5 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <p class="font-semibold text-lg text-[#252F40]">Buat PAK Pegawai</p>
                
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-5 overflow-x-auto">
                    <livewire:AngkaKredit.Buat.Buat-PAK-Table />
                </div>
            </div>
        </div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 flex items-center justify-center bg-transparent backdrop-blur-sm z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96 max-h-96 overflow-y-auto relative">
                <!-- Header -->
                <div class="flex justify-between items-center border-b pb-2">
                    <h2 class="text-lg font-semibold">Buat PAK</h2>
                    <button wire:click="$set('showModal', false)" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>

                <div class="w-full mt-3">
                    <div class="w-full mb-3 relative">
                        <label class="text-xs">Jenis Angka Kredit</label>
                        <select wire:model="jenis" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow pr-10" >
                            <option value='Tahunan'>Tahunan</option>
                            <option value='Periodik'>Periodik</option>
                            <option value='Pengangkatan Pertama'>Pengangkatan Pertama</option>
                            <option value='Perpindahan Jabatan'>Perpindahan Jabatan</option>
                            <option value='Pengangkatan Kembali'>Pengangkatan Kembali</option>
                        </select>   
                         <!-- Chevron Icon -->
                        <div class="absolute top-6 inset-y-0 right-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500 transition-all" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>

                        @error('jenis')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div x-show="@this.jenis == 'Periodik'" class="w-full mb-3">
                    <label class="text-xs">Periode Akhir</label>
                    <input 
                        type="month" 
                        wire:model="akhir_periode"
                        class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" 
                    />

                    @error('akhir_periode')
                        <p class="text-red-500 text-xs">{{ $message }}</p>
                    @enderror
                </div>

                <div x-show="@this.jenis == 'Pengangkatan Pertama'" class="w-full mb-3">
                    <label class="text-xs">Tanggal Pelantikan</label>
                    <input 
                        type="date" 
                        wire:model="tgl_pengangkatan"
                        class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" 
                    />

                    @error('akhir')
                        <p class="text-red-500 text-xs">{{ $message }}</p>
                    @enderror
                </div>

                <div x-show="@this.jenis == 'Pengangkatan Kembali'" class="w-full mt-3">
                    <div class="w-full mb-3 relative">
                        <label class="text-xs">Jenis Pengangkatan Kembali</label>
                        <select wire:model="jenis_angkat_kembali" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow pr-10" >
                            <option value='CLTN'>CLTN</option>
                            <option value='Struktural ke JFT'>Struktural ke JFT</option>
                            <option value='Tugas Belajar'>Tugas Belajar</option>
                        </select>   
                         <!-- Chevron Icon -->
                        <div class="absolute top-6 inset-y-0 right-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500 transition-all" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>

                        @error('jenis_angkat_kembali')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div x-show="@this.jenis_angkat_kembali == 'Struktural ke JFT'" class="w-full mb-3">
                    <label class="text-xs">Tanggal Pengangkatan Kembali</label>
                    <input 
                        type="date" 
                        wire:model="tgl_pengangkatan"
                        class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" 
                    />

                    @error('akhir')
                        <p class="text-red-500 text-xs">{{ $message }}</p>
                    @enderror
                </div>

                <div x-show="@this.jenis_angkat_kembali == 'Tugas Belajar'" class="w-full mb-3">
                    <label class="text-xs">Tanggal Mulai TB</label>
                    <input 
                        type="date" 
                        wire:model="tgl_mulai_tb"
                        class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" 
                    />

                    @error('tgl_mulai_tb')
                        <p class="text-red-500 text-xs">{{ $message }}</p>
                    @enderror
                </div>

                <div x-show="@this.jenis_angkat_kembali == 'Tugas Belajar'" class="w-full mb-3">
                    <label class="text-xs">Tanggal Pengaktifan Kembali</label>
                    <input type="date" wire:model="tgl_akhir_tb" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                
                    @error('tgl_akhir_tb')
                        <p class="text-red-500 text-xs">{{ $message }}</p>
                    @enderror
                </div>

                <div x-show="@this.jenis_angkat_kembali == 'Tugas Belajar'" class="w-full mb-3 flex items-center gap-x-2">
                    <input type="checkbox" wire:model="is_cumlaude" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                    <label class="text-xs">Cumlaude?</label>
                
                    @error('is_cumlaude')
                        <p class="text-red-500 text-xs">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4 flex justify-end space-x-2 text-sm">
                    <button wire:click="$set('showModal', false)" class="px-3 py-1 font-medium bg-gray-500 text-white rounded hover:bg-gray-600">
                        Batal
                    </button>
                    <button wire:click="createPAK" class="px-3 py-1 bg-[#CB0C9F] font-medium hover:bg-[#b42f95] text-white rounded">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>