<div class="flex flex-wrap -mx-3">
    @section('beforeTitle', 'Mutasi')
    @section('title', 'Usul Mutasi')

    <div class="flex-none w-full max-w-full px-3" x-data="{ showTambah: false, isSimulasi: false, selectedPegawai: [] }">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-5 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex flex-row justify-between">
                <p class="font-semibold text-lg text-[#252F40]">Pengajuan Mutasi Pegawai</p>
            </div>
            <div class="flex justify-between p-5 pb-0">
                <input 
                    type="text" 
                    placeholder="Cari ..." 
                    wire:model.live.debounce.300ms="search"
                    class="focus:shadow-soft-primary-outline w-2/5 text-sm leading-5.6 ease-soft block appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" 
                />                
                <div class="flex flex-row gap-x-3">
                    <div>
                        <!-- Tombol Simulasi -->
                        <button 
                            x-show="!isSimulasi"
                            @click="isSimulasi = true"
                            class="bg-gradient-to-br from-[#A8B8D8] to-[#627594] hover:scale-105 transition text-white px-5 font-semibold py-2 text-sm rounded-lg">
                            Simulasi
                        </button>
                    
                        <!-- Tombol Batal -->
                        <button 
                            x-show="isSimulasi"
                            @click="document.querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked = false); isSimulasi = false; selectedPegawai = []"
                            class="bg-red-600 hover:scale-105 transition text-white px-5 font-semibold py-2 text-sm rounded-lg">
                            Batal
                        </button>
                    
                        <!-- Tombol Tambah -->
                        <button 
                            x-show="!isSimulasi"
                            @click="showTambah = true"
                            class="bg-gradient-to-br from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-white px-5 font-semibold py-2 text-sm rounded-lg">
                            Tambah
                        </button>
                    
                        <!-- Tombol Selanjutnya -->
                        <button 
                            x-show="isSimulasi"
                            @click="$wire.saveSelectedPegawai(selectedPegawai)"
                            class="bg-gradient-to-br from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-white px-5 font-semibold py-2 text-sm rounded-lg">
                            Selanjutnya
                        </button>
                    </div>                    
                </div>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-5 grid grid-cols-3 space-x-3 space-y-3">
                    {{-- <livewire:Mutasi.Usul.Usul-Mutasi-Table /> --}}
                    @forelse ($allUsul as $item)
                        <div wire:key="{{ $item->id }}" class="p-4 bg-white rounded-xl shadow-md"
                            :class="{ 'border border-fuchsia-300 shadow-lg': selectedPegawai.includes('{{ $item->id }}') }">             
                        
                            <!-- Checkbox untuk memilih pegawai -->
                            @if ($item->prov_tujuan == 'ACEH')
                                <input 
                                    type="checkbox" 
                                    x-model="selectedPegawai" 
                                    :value="{{ $item->id }}"
                                    x-show="isSimulasi"
                                    class="relative right-0 w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            @endif

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
                                <span class="font-semibold text-gray-600">Satker Tujuan:</span>
                                <span>BPS {{ ucwords(strtolower($item->kab_tujuan)) }}</span>
                              </div>
                              <div class="flex gap-x-2">
                                <span class="font-semibold text-gray-600">Jenis:</span>
                                <span>{{ $item->jenis }}</span>
                              </div>
                              <div>
                                <span class="font-semibold text-gray-600">Alasan:</span>
                                <p class="text-gray-700">
                                  {{ $item->alasan }}
                                </p>
                              </div>
                              <div class="flex gap-x-2">
                                <span class="font-semibold text-gray-600">Tgl. Pengajuan:</span>
                                <span>{{ $item->created_at->format('d-m-Y') }}</span>
                              </div>
                              <div class="flex gap-x-2">
                                <span class="font-semibold text-gray-600">Tgl. Tindak Lanjut:</span>
                                <span>{{ $item->created_at == $item->updated_at ? "-" : $item->updated_at->format('d-m-Y') }}</span>
                              </div>
                            </div>
                          
                            <!-- Footer -->
                            <div class="mt-3 flex items-center justify-between">
                              <div>    
                                @if ($item->status == 1)
                                    <span class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-600 rounded-full">
                                        Belum Ditindaklanjuti
                                    </span>
                                @elseif ($item->status == 2)
                                    <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-600 rounded-full">
                                        Disetujui
                                    </span>
                                @elseif ($item->status == 3)
                                    <span class="px-3 py-1 text-xs font-medium bg-red-100 text-red-600 rounded-full">
                                        Tidak Disetujui
                                    </span>
                                @elseif ($item->status == 4)
                                    <span class="px-3 py-1 text-xs font-medium bg-yellow-100 text-yellow-600 rounded-full">
                                        Pending
                                    </span>
                                @endif
                              </div>
                              <button wire:click="openModalEdit('{{ $item->id }}')" class="border border-[#CB0C9F] text-[#CB0C9F] font-medium shadow-sm text-sm px-3 py-1 rounded-lg hover:bg-[#CB0C9F] hover:text-white transition">
                                Ubah Status
                              </button>
                            </div>
                          </div>
                    @empty
                        <p>Belum ada pengajuan mutasi</p>
                    @endforelse

                </div>
                <div class="p-5 w-full">
                    {{ $allUsul->links() }}

                </div>
            </div>
        </div>

        <div x-show="showTambah" x-on:close-modal.window="showTambah = false" class="fixed z-50 inset-0 flex items-center justify-center bg-transparent backdrop-blur-sm">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96 max-h-80 overflow-auto relative">
                <!-- Header -->
                <div class="flex justify-between items-center border-b pb-2">
                    <h2 class="text-lg font-semibold">Tambah Usul Mutasi</h2>
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
                    
                        @error('nip')
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
                        <input type="text" wire:model="satker_asal" readonly class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                    
                        @error('satker_asal')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full mb-3 relative">
                        <label class="text-xs">Jenis Usulan</label>
                        <select wire:model="jenis" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow pr-10" >
                            <option value="Atas Permintaan Sendiri">Atas Permintaan Sendiri</option>
                            <option value="Alasan Khusus">Alasan Khusus</option>
                            <option value="Penugasan">Penugasan</option>
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

                    <div class="w-full mb-3">
                        <label class="text-xs">Provinsi</label>
                        <select wire:model.live="provinsi" class="text-gray-700 px-3 py-2 text-sm block w-full p-1 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-300 focus:border-fuchsia-300">
                            <option value="">Pilih Provinsi</option>
                            @foreach($provinsiList as $prov)
                                <option value="{{ $prov['id'] }}">{{ $prov['nama'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full mb-3">
                        <label class= "text-xs">Kabupaten</label>
                        <select wire:model="kabupaten" class="text-gray-700 px-3 py-2 text-sm block w-full p-1 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-300 focus:border-fuchsia-300" wire:key="kabupaten-select">
                            <option value="">Pilih Kabupaten</option>
                            @foreach($kabupatenList as $kab)
                                <option value="{{ $kab['id'] }}">{{ $kab['nama'] }}</option>
                            @endforeach
                        </select>                    
                    </div>

                    <div class="w-full mb-3">
                        <label class="text-xs">Alasan</label>
                        <Textarea wire:model="alasan" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow">
                        </Textarea>

                        @error('alasan')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-4 flex justify-end space-x-2">
                        <button @click="showTambah = false" class="px-3 py-1 text-sm font-medium bg-gray-500 text-white rounded hover:bg-gray-600">
                            Batal
                        </button>
                        <button wire:click="createUsul" class="px-3 py-1 text-sm font-medium bg-[#CB0C9F] hover:bg-[#b42f95] text-white rounded">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-data="{ open: @entangle('showModalEdit') }">
        <div x-show="open" class="fixed z-50 inset-0 flex items-center justify-center bg-transparent backdrop-blur-sm">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96 max-h-72 overflow-auto relative">
                <div class="flex justify-between items-center border-b pb-2">
                    <h2 class="text-lg font-semibold">Ubah Status</h2>
                    <button @click="open = false" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>
                
                <div class="relative w-full mt-5">
                    <select wire:model='status' class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow pr-10" >
                        <option value=1>Belum Ditindaklanjuti</option>
                        <option value=2>Disetujui</option>
                        <option value=3>Tidak Disetujui</option>
                        <option value=4>Pending</option>
                    </select>
                    <!-- Chevron Icon -->
                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500 transition-all" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <div class="mt-4 text-sm flex justify-end space-x-2">
                    <button @click="open = false" class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Batal
                    </button>
                    <button wire:click="saveEdit" class="px-3 py-1 bg-[#CB0C9F] hover:bg-[#b42f95] text-white rounded">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>