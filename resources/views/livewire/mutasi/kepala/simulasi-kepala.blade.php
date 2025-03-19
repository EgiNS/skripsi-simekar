<div class="flex flex-wrap -mx-3">
    @section('beforeTitle', 'Mutasi')
    @section('title', 'Simulasi Kepala')

    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-5 overflow-x-auto">
                    @if ($step == 1)
                        <p class="font-semibold text-lg text-[#252F40] mb-5">Daftar Kepala BPS Kabupaten/Kota Se-Aceh</p>
                        <livewire:Mutasi.Kepala.Kepala-Table />
                    @else
                        <div x-data="{ showSatker: false }">
                            <p class="font-semibold text-lg text-[#252F40] mb-4">Simulasi Rotasi Kepala</p>

                                <div class="grid grid-cols-3 gap-2">
                                    @foreach ($selectedData as $kepala)
                                        <div x-data="{ isDisabled: false }" :class="{ 'opacity-50': isDisabled }" class="p-4 text-sm border border-gray-300 rounded-lg shadow-md flex flex-col justify-between">
                                            <div class="flex flex-col justify-between">
                                                <div>
                                                    <p>Nama: <span class="text-[#252F40] font-semibold ml-2">{{ $kepala['nama'] }}</span></p>
                                                    <p>NIP: <span class="text-[#252F40] font-semibold ml-2">{{ $kepala['nip'] }}</span></p>
                                                    <p>Jabatan: <span class="text-[#252F40] font-semibold">{{ $kepala['jabatan'] }}</span></p>
                                                    <p>Satker saat ini: <span class="text-[#252F40] font-semibold">{{ $kepala['satker_asal'] }} {{ $kepala['zona'] ? '(Zona ' . $kepala['zona'] . ')' : '' }}</span></p>
                                                    <p>Masa kerja jabatan saat ini: <br> <span class="text-[#252F40] font-semibold">{{ $kepala['tmt_jab'] }}</span></p>
                                                    <p>Riwayat jabatan: -</p>
                                                </div>
                                            
                                                <div class="border-t mt-2 pt-2 relative w-full">
                                                    <label class="mb-1">Pilih satker tujuan:</label>
                                                    <select :disabled="isDisabled" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow pr-10">
                                                        @foreach ($allSatker as $satker)
                                                            <option value="{{ $satker['id'] }}">{{ $satker['nama'] . ' (Zona ' . $satker['zona'] . ')' }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500 transition-all" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                            
                                                    @if ($kepala['jabatan'] == 'Kepala BPS Kabupaten/Kota')
                                                        <button 
                                                            @click="isDisabled = !isDisabled" 
                                                            class="bg-[#EF5350] place-self-end text-white rounded-lg px-3 py-1 text-sm font-semibold mt-3"
                                                            :class="isDisabled ? 'bg-green-500' : 'bg-[#EF5350]'"
                                                        >
                                                            <span x-text="isDisabled ? 'Aktifkan Sebagai Kepala' : 'Nonaktifkan Sebagai Kepala'"></span>
                                                        </button>
                                                
                                                    @else
                                                        <button wire:click="hapusData('{{ $kepala['nip'] }}')" class="bg-[#EF5350] place-self-end text-white rounded-lg px-3 py-1 text-sm font-semibold mt-3">
                                                            Hapus
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <div class="w-full flex justify-between mt-12 gap-x-4">
                                    <button wire:click="prevPage" class="mt-3 px-4 py-2 bg-[#8392AB] text-white rounded-lg text-sm hover:scale-105 transition font-medium">Kembali</button>
                                    <button class="mt-3 px-4 py-2 bg-gradient-to-br from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-white rounded-lg text-sm font-medium" @click="showSatker = true">Tambah Kandidat</button>
                                </div>

                                <div x-data="{ search: '' }" x-show="showSatker" class="fixed inset-0 flex items-center justify-center bg-transparent backdrop-blur-sm">
                                    <div class="bg-white p-6 rounded-lg shadow-lg w-96 relative">
                                        <!-- Header -->
                                        <div class="flex justify-between items-center border-b pb-2">
                                            <h2 class="text-lg font-semibold">Tambah Kandidat</h2>
                                            <button @click="showSatker = false" class="text-gray-500 hover:text-gray-700">&times;</button>
                                        </div>
                                
                                        <!-- Input Pencarian -->
                                        <div x-data="{ open: false }" class="relative w-full">
                                            <div class="w-full align-middle content-center">
                                                <p class="text-sm my-2">Kandidat berasal dari pegawai fungsional dengan golongan minimal III/D</p>
                                                <div class="col-span-6 w-full">
                                                    <input 
                                                        type="text" 
                                                        wire:model.live="kandidat" 
                                                        x-on:input="open = true"
                                                        x-on:focus="open = true"
                                                        x-on:click.away="open = false"
                                                        class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                                                        placeholder="Cari Pegawai..."
                                                    />
                                                    
                                                    <!-- Dropdown untuk rekomendasi -->
                                                    <div x-show="open && @this.suggestions.length" class="relative w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg">
                                                        <ul>
                                                            @foreach($suggestions as $suggestion)
                                                                <li 
                                                                    class="px-3 py-2 cursor-pointer hover:bg-gray-200" 
                                                                    x-on:click="@this.selectKandidat('{{ $suggestion }}'); open = false"
                                                                >
                                                                    {{ $suggestion }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>