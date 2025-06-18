<div class="flex flex-wrap -mx-3">
    @section('beforeTitle', 'Mutasi')
    @section('title', 'Simulasi Pegawai')

    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-5 overflow-x-auto">
                    @if ($step == 1)
                        <p class="mb-5 text-sm">Silakan pilih pegawai yang akan dimutasi beserta satker tujuannya</p>
                    
                        <div class="space-y-3">
                            @foreach ($inputs as $index => $input)
                            <div class="grid grid-cols-11 gap-x-3 items-center relative">
                                <!-- Input Nama -->
                                <div x-data="{ open: false }" class="relative w-full col-span-5 z-50">
                                    <input 
                                        type="text" 
                                        wire:model.live="inputs.{{ $index }}.nama"
                                        x-on:input="open = true"
                                        x-on:focus="open = true"
                                        x-on:click.away="open = false"
                                        class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                                        placeholder="Cari Pegawai..."
                                    />
                                    
                                    <!-- Dropdown untuk rekomendasi -->
                                    <div x-show="open && @this.suggestionsNama[{{ $index }}]?.length" class="absolute w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg z-10">
                                        <ul>
                                            @foreach($suggestionsNama[$index] ?? [] as $suggestion)
                                                <li class="px-3 py-2 cursor-pointer hover:bg-gray-200" x-on:click="@this.selectNama({{ $index }}, '{{ $suggestion }}'); open = false">
                                                    {{ $suggestion }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                    
                                <!-- Select Satker -->
                                <div class="relative w-full col-span-5">
                                    <select wire:model="inputs.{{ $index }}.satker" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow pr-10">
                                        <option value="" selected disabled>Pilih tujuan</option>
                                        @foreach ($allSatker as $satker)
                                            <option value="{{ $satker->id }}">{{ $satker->nama }}</option>
                                        @endforeach
                                    </select>
                                    <!-- Chevron Icon -->
                                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500 transition-all" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                    
                                <!-- Tombol Hapus -->
                                <button wire:click="removeInput({{ $index }})" type="button" class="border border-[#F53939] rounded-full w-6 p-1 text-sm font-medium ml-2">
                                    <svg fill="#F53939" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M432 256c0 17.7-14.3 32-32 32L48 288c-17.7 0-32-14.3-32-32s14.3-32 32-32l352 0c17.7 0 32 14.3 32 32z"/></svg>
                                </button>
                            </div>
                            @endforeach
                        </div>
                              
                        <div class="w-full flex justify-end mt-12 gap-x-4">
                            <button wire:click="addInput" type="button" class="font-medium px-3 py-2 text-sm text-white rounded-lg bg-gradient-to-br from-[#A8B8D8] to-[#627594] hover:scale-105 transition">
                                Tambah Pegawai
                            </button>
                            @if(count($inputs) > 0)
                                <button wire:click="nextPage" class="font-medium px-3 py-2 text-sm text-white rounded-lg bg-gradient-to-br from-[#FF0080] to-[#7928CA] hover:scale-105 transition">Selanjutnya</button>
                            @endif
                        </div>
                    @elseif ($step == 2)
                        <p class="font-semibold text-lg text-[#252F40] mb-4">Simulasi Mutasi Pegawai</p>

                        <div class="grid grid-cols-3 gap-2">
                            @foreach ($detailedData as $index => $pegawai)
                            <div class="p-4 text-sm border border-gray-300 rounded-lg shadow-md flex flex-col justify-between" x-data="{ showSatker: false }">
                                <div>
                                    <p>Nama: <span class="text-[#252F40] font-semibold">{{ $pegawai['nama'] }}</span></p>
                                    <p>NIP: <span class="text-[#252F40] font-semibold">{{ $pegawai['nip'] }}</span></p>
                                    <p>Jabatan: <span class="text-[#252F40] font-semibold">{{ $pegawai['jabatan'] }}</span></p>
                                    <p>Satker saat ini: <span class="text-[#252F40] font-semibold">{{ $pegawai['satker_asal'] }}</span></p>
                                    <p>Masa kerja jabatan saat ini: <br> <span class="text-[#252F40] font-semibold">{{ $pegawai['tmt_jab'] }}</span></p>
                                    <p>Masa kerja keseluruhan: <br> <span class="text-[#252F40] font-semibold">{{ $pegawai['tmt_cpns'] }}</span></p>
                                    <p>Nilai Perilaku: <span class="text-[#252F40] font-semibold">{{ $pegawai['nilai_perilaku'] }}</span></p>
                                    <p>Nilai Kinerja: <span class="text-[#252F40] font-semibold">{{ $pegawai['nilai_kinerja'] }}</span></p>
                                    <p class="border-b pb-2">Predikat: <span class="text-[#252F40] font-semibold">{{ $pegawai['predikat'] }}</span></p>

                                    <p class="mt-2">Satker tujuan: <span class="text-[#252F40] font-semibold">{{ $pegawai['satker_tujuan'] }}</span></p>
                                    <p>Formasi: <span class="text-[#252F40] font-semibold">{{ $pegawai['formasi'] }}</span></p>
                                    <p>Eksisting: <span class="text-[#252F40] font-semibold">{{ $pegawai['eksisting'] }}</span></p>
                                    <p>Status: 
                                        <span class="{{ ($pegawai['formasi'] > $pegawai['eksisting']) ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} text-xs px-3 rounded-xl font-semibold">
                                            {{ ($pegawai['formasi'] > $pegawai['eksisting']) ? 'Eligible' : 'Tidak Eligible' }}
                                        </span>
                                    </p>
                                </div>
                                
                                <!-- Tombol Edit untuk membuka modal -->
                                <div class="flex justify-between mt-4">
                                    <button class="w-20 place-self-end border font-semibold border-[#CB0C9F] text-[#CB0C9F] px-2 py-1 rounded-lg text-xs" @click="showSatker = true">
                                        Edit
                                    </button>
    
                                    <select wire:model.defer="detailedData.{{ $index }}.keputusan" class="border px-2 border-gray-300 text-xs rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-300 focus:border-fuchsia-300 focus:transition-shadow">
                                        <option value="" selected>-- Keputusan --</option>
                                        <option value="Disetujui">Disetujui</option>
                                        <option value="Tidak Disetujui">Tidak Disetujui</option>
                                        <option value="Pending">Pending</option>
                                    </select>
                                </div>
                        
                                <!-- Modal -->
                                <div x-show="showSatker" class="fixed inset-0 flex items-center justify-center bg-transparent backdrop-blur-sm"
                                    x-on:close-modal.window="showSatker = false"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-300"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                    x-data="{ selectedSatker: '{{ $pegawai['satker_eligible'][0]['id'] ?? '' }}' }"
                                >
                                    <div class="bg-white p-5 rounded-lg shadow-lg w-1/3 max-h-80 overflow-auto">
                                        <div class="flex flex-row justify-between mb-4 border-b-2 pb-2">
                                            <h2 class="text-lg font-semibold">Edit Satker Tujuan</h2> <hr>
                                            <button @click="showSatker = false" class="text-gray-500 hover:text-gray-700">&times;</button>
                                        </div>

                                        <table class="w-full">
                                            <th class="bg-[#F5F5F5] font-normal py-1">Satker</th>
                                            <th class="bg-[#F5F5F5] font-normal py-1">Formasi</th>
                                            <th class="bg-[#F5F5F5] font-normal py-1">Eksisting</th>
                                            @foreach ($pegawai['satker_eligible'] as $satker)
                                                <tr class="border-b border-[#F5F5F5]">
                                                    <td class="py-1">{{ $satker['nama'] }}</td>
                                                    <td class="text-center py-1">{{ $satker['formasi'] }}</td>
                                                    <td class="text-center py-1">{{ $satker['eksisting'] }}</td>
                                                </tr>
                                            @endforeach
                                        </table>

                                        <div class="mt-4 relative">
                                            <p class="mb-1">Pilih satker tujuan:</p>
                                            <select 
                                                class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow pr-10"
                                                x-model="selectedSatker"
                                                @change="$wire.updateSatkerTujuan({{ $index }}, selectedSatker)"
                                            >
                                                @foreach ($pegawai['satker_eligible'] as $satker)
                                                    <option value="{{ $satker['id'] }}">{{ $satker['nama'] }}</option>
                                                @endforeach
                                            </select>
                                            <!-- Chevron Icon -->
                                            <div class="absolute inset-y-0 right-3 top-6 flex items-center pointer-events-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500 transition-all" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                        
                                        <!-- Tombol Tutup -->
                                        <div class="mt-4 flex justify-end space-x-2">
                                            <button @click="showSatker = false" class="px-3 py-1 text-sm bg-gray-500 text-white rounded hover:bg-gray-600">
                                                Kembali
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>                    
                
                        <div class="w-full flex justify-between mt-12 gap-x-4">
                            <button wire:click="prevPage" class="mt-3 px-4 py-2 bg-[#8392AB] text-white rounded-lg text-sm hover:scale-105 transition font-medium">Kembali</button>
                            <button wire:click="pageHasil" class="mt-3 px-4 py-2 bg-gradient-to-br from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-white rounded-lg text-sm font-medium">Simpan & Lanjutkan</button>
                        </div>
                    
                    @elseif($step==3)
                        <p class="font-semibold text-lg text-[#252F40] mb-10">Hasil Simulasi Mutasi Pegawai</p>

                        <table class="w-full border-collapse border rounded border-gray-300 mt-4 text-sm">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700 text-left">
                                    <th class="border border-gray-300 px-4 py-2 text-center">NIP</th>
                                    <th class="border border-gray-300 px-4 py-2 text-center">Nama</th>
                                    <th class="border border-gray-300 px-4 py-2 text-center">Jabatan</th>
                                    <th class="border border-gray-300 px-4 py-2 text-center">Satker Asal</th>
                                    <th class="border border-gray-300 px-4 py-2 text-center">Satker Tujuan</th>
                                    <th class="border border-gray-300 px-4 py-2 text-center">Keputusan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($detailedData as $index => $item)
                                    <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                                        <td class="border border-gray-300 px-4 py-2">{{ $item['nip'] }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $item['nama'] }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $item['jabatan'] }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $item['satker_asal'] }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $item['satker_tujuan'] }}</td>
                                        <td class="border border-gray-300 px-4 py-2 text-center font-semibold">{{ $item['keputusan'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        

                        <div class="w-full flex justify-between mt-12 gap-x-4">
                            <button wire:click="prevPage" class="mt-3 px-4 py-2 bg-[#8392AB] text-white rounded-lg text-sm hover:scale-105 transition font-medium">Kembali</button>
                            <button wire:click="download" class="mt-3 px-4 py-2 bg-gradient-to-br from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-white rounded-lg text-sm font-medium">Unduh</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>