<div class="flex flex-wrap -mx-3">
    @section('beforeTitle', 'Angka Kredit')
    @section('title', 'Upload Angka Kredit')

            <span class="text-xs hidden px-2 rounded-lg bg-yellow-400 text-white">Menunggu</span>
            <span class="text-xs hidden px-2 rounded-lg bg-green-400 text-white">Diterima</span>
            <span class="text-xs hidden px-2 rounded-lg bg-[#F53939] text-white">Ditolak</span>

    <div class="flex-none w-full max-w-full px-3" x-data="{ showTambah: false }">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-5 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex flex-row justify-between">
                <p class="font-semibold text-lg text-[#252F40]">Approval Angka Kredit Pegawai</p>
            </div>
            <div class="flex justify-between p-5 pb-0">
                <input 
                    type="text" 
                    placeholder="Cari ..." 
                    wire:model.live.debounce.300ms="search"
                    class="focus:shadow-soft-primary-outline w-2/5 text-sm leading-5.6 ease-soft block appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" 
                />                
                {{-- <button class="bg-gradient-to-br from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-white px-5 font-semibold py-2 text-sm rounded-lg" @click="showTambah = true">Tambah</button> --}}
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-5 grid grid-cols-3 space-x-3">
                    @forelse ($allApproval as $item)
                        <div wire:key="{{ $item->id }}" class="relative p-4 bg-white rounded-xl shadow-md">             

                            <!-- Header -->
                            <div class="mb-2 pb-2 border-b">
                              <h2 class="text-lg font-semibold text-gray-800">
                                {{ $item->profile->nama }}
                              </h2>
                              <p class="text-sm">{{ $item->profile->nip }} | {{ $item->profile->jabatan }}</p>
                            </div>
                          
                            <!-- Body -->
                            <div class="text-sm text-gray-700 space-y-1">
                              <div class="flex gap-x-2">
                                <span class="font-semibold text-gray-600">Satker Asal:</span>
                                <span>{{ $item->profile->satker->nama }}</span>
                              </div>
                              <div class="flex gap-x-2">
                                <span class="font-semibold text-gray-600">Angka Kredit:</span>
                                <span>{{ $item->nilai }}</span>
                              </div>
                              <div class="flex gap-x-2">
                                <span class="font-semibold text-gray-600">Jenis:</span>
                                <span>{{ $item->jenis }}</span>
                              </div>
                              <div class="flex gap-x-2">
                                <span class="font-semibold text-gray-600">Periode:</span>
                                <span>{{ $item->periode }}</span>
                              </div>
                              <div class="flex gap-x-2">
                                <span class="font-semibold text-gray-600">Link PAK:</span>
                                <span>{{ $item->link_pak }}</span>
                              </div>
                              <div class="flex gap-x-2">
                                <span class="font-semibold text-gray-600">Tanggal Upload:</span>
                                <span>{{ $item->created_at->format('d-m-Y') }}</span>
                              </div>
                            </div>
                          
                            <!-- Footer -->
                            <div class="mt-3 flex items-center justify-between">
                              <div>    
                                @if ($item->status == 1)
                                    <span class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-600 rounded-full">
                                        Menunggu
                                    </span>
                                @elseif ($item->status == 2)
                                    <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-600 rounded-full">
                                        Diterima
                                    </span>
                                @elseif ($item->status == 3)
                                    <span class="px-3 py-1 text-xs font-medium bg-red-100 text-red-600 rounded-full">
                                        Ditolak
                                    </span>
                                @endif
                              </div>
                              <button wire:click="openModalEdit('{{ $item->id }}')" class="border border-[#CB0C9F] text-[#CB0C9F] font-medium shadow-sm text-sm px-3 py-1 rounded-lg hover:bg-[#CB0C9F] hover:text-white transition">
                                Ubah Status
                              </button>
                            </div>
                          </div>
                    @empty
                        <p>Belum Ada Pengajuan Angka Kredit</p>
                    @endforelse

                </div>
                <div class="p-5 w-full">
                    {{ $allApproval->links() }}

                </div>
            </div>
        </div>

        {{-- <div x-show="showTambah" x-on:close-modal.window="showTambah = false" class="fixed inset-0 flex items-center justify-center bg-transparent backdrop-blur-sm z-50">
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
        </div> --}}

        <div x-data="{ open: @entangle('showModalEdit') }">
            <div x-show="open" class="fixed z-50 inset-0 flex items-center justify-center bg-transparent backdrop-blur-sm">
                <div class="bg-white p-6 rounded-lg shadow-lg w-96 max-h-72 overflow-auto relative">
                    <div class="flex justify-between items-center border-b pb-2 mb-3">
                        <h2 class="text-lg font-semibold">Ubah Status</h2>
                        <button @click="open = false" class="text-gray-500 hover:text-gray-700">&times;</button>
                    </div>
                    
                    <div class="relative w-full">
                        <select wire:model='status' class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow pr-10" >
                            <option value=1>Menunggu</option>
                            <option value=2>Terima</option>
                            <option value=3>Tolak</option>
                        </select>
                        <!-- Chevron Icon -->
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500 transition-all" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
    
                    <div class="mt-4 flex justify-end space-x-2">
                        <button @click="open = false" class="px-3 py-1 text-sm font-medium bg-gray-500 text-white rounded hover:bg-gray-600">
                            Batal
                        </button>
                        <button wire:click="saveEdit" class="px-3 py-1 text-sm font-medium bg-[#CB0C9F] hover:bg-[#b42f95] text-white rounded">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>