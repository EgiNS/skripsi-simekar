<div class="flex flex-wrap -mx-3">
    @section('beforeTitle', 'Angka Kredit')
    @section('title', 'Daftar Angka Kredit')

    <div class="flex-none w-full max-w-full px-3" x-data="{ showTambah: false }">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-5 pb-0 mb-0 flex justify-between bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <p class="font-semibold text-lg text-[#252F40]">Daftar Angka Kredit Pegawai</p>
                <button class="bg-gradient-to-br from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-white px-5 font-semibold py-2 text-sm rounded-lg" @click="showTambah = true">Tambah</button>
                <span hidden class='bg-red-400 text-white px-2 rounded' href=a>aa</span>
                <span hidden class='bg-[#17C1E8] text-white px-2 rounded' href=a>aa</span>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-5 overflow-x-auto">
                    <livewire:AngkaKredit.Daftar.Daftar-Angka-Kredit-Table />
                </div>
            </div>
        </div>

        <div x-show="showTambah" x-on:close-modal.window="showTambah = false" class="fixed inset-0 flex items-center justify-center bg-transparent backdrop-blur-sm z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96 max-h-96 overflow-y-auto relative">
                <!-- Header -->
                <div class="flex justify-between items-center border-b pb-2">
                    <h2 class="text-lg font-semibold">Upload Angka Kredit</h2>
                    <button @click="showTambah = false" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>

                <div class="w-full mt-3">
                    <div class="w-full mb-3" x-data="{ open: false }">
                        <label class="text-xs mb-1">Nama</label>
                        <input 
                            type="text" 
                            wire:model.live="nama" 
                            x-on:input="open = true"
                            x-on:focus="open = true"
                            x-on:click.away="open = false"
                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                        />
                        
                        <!-- Dropdown untuk rekomendasi -->
                        <div x-show="open && @this.suggestionsNama.length" class="relative w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg z-10">
                            <ul>
                                @foreach($suggestionsNama as $suggestion)
                                    <li 
                                        class="px-3 py-2 cursor-pointer hover:bg-gray-200" 
                                        x-on:click="@this.selectNama('{{ $suggestion }}'); open = false"
                                    >
                                        {{ $suggestion }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        @error('nip')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="w-full mb-3">
                        <label class="text-xs">NIP</label>
                        <input type="text" wire:model="nip" readonly class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                    
                        @error('nama')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="w-full mb-3">
                        <label class="text-xs">Jabatan</label>
                        <input type="text" wire:model="jabatan" readonly class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                    
                        @error('jabatan')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full mb-3">
                        <label class="text-xs">Satker</label>
                        <input type="text" wire:model="satker" readonly class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                    
                        @error('satker')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full mt-3">
                        <div class="w-full mb-3 relative">
                            <label class="text-xs">Jenis Angka Kredit</label>
                            <select wire:model="jenis" wire:change='changeJenis' class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow pr-10" >
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

                    <div x-show="@this.jenis == 'Tahunan'" class="w-full mb-3">
                        <label class="text-xs">Tahun PAK</label>
                        <input type="integer" wire:model="tahun" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                    
                        @error('tahun')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
    
                    <div x-show="@this.jenis == 'Periodik' || @this.jenis == 'Perpindahan Jabatan' || @this.jenis == 'Pengangkatan Kembali'" class="w-full mb-3">
                        <label class="text-xs">Periode Mulai</label>
                        <input 
                            type="month" 
                            wire:model="periodeMulai"
                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" 
                        />
    
                        @error('periodeMulai')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-show="@this.jenis == 'Periodik' || @this.jenis == 'Perpindahan Jabatan' || @this.jenis == 'Pengangkatan Kembali'" class="w-full mb-3">
                        <label class="text-xs">Periode Akhir</label>
                        <input 
                            type="month" 
                            wire:model="periodeAkhir"
                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" 
                        />
    
                        @error('periodeAkhir')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-show="@this.jenis == 'Pengangkatan Pertama'" class="w-full mb-3">
                        <label class="text-xs">TMT Golongan</label>
                        <input 
                            type="date" 
                            wire:model="tmt_gol"
                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" 
                        />
    
                        @error('akhir')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
    
                    <div x-show="@this.jenis == 'Pengangkatan Pertama'" class="w-full mb-3">
                        <label class="text-xs">Tanggal Pengangkatan</label>
                        <input 
                            type="date" 
                            wire:model="tgl_pengangkatan"
                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" 
                        />
    
                        @error('akhir')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full mb-3">
                        <label class="text-xs">Angka Kredit yang Diperoleh</label>
                        <input type="text" wire:model="nilai" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                    
                        @error('nilai')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full mb-3">
                        <label class="text-xs">Total Angka Kredit Sementara</label>
                        <input type="text" wire:model="total_ak" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                    
                        @error('total_ak')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full mb-3">
                        <label class="text-xs">Link PAK (Google Drive)</label>
                        <input type="text" wire:model="link_pak" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                    
                        @error('link_pak')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-4 flex justify-end space-x-2 text-sm">
                        <button @click="showTambah = false" class="px-3 py-1 font-medium bg-gray-500 text-white rounded hover:bg-gray-600">
                            Batal
                        </button>
                        <button wire:click="createAngkaKredit" class="px-3 py-1 bg-[#CB0C9F] font-medium hover:bg-[#b42f95] text-white rounded">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>