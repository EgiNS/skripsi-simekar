<div class="flex flex-wrap -mx-3">
    @section('beforeTitle', 'Angka Kredit')
    @section('title', 'Daftar Angka Kredit')

    <div class="flex-none w-full max-w-full px-3" x-data="{ showEdit: false, selectedProfile: {} }" x-init="
        window.addEventListener('open-edit-modal', () => {
            showEdit = true;
        });
    ">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-5 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                @if ($jenis == 'Pengangkatan Kembali')
                    <p class="font-semibold text-lg text-[#252F40]">PAK Pengangkatan Kembali ({{ $jenis_angkat_kembali }})</p>
                @else
                    <p class="font-semibold text-lg text-[#252F40]">PAK {{ $jenis }}</p>
                @endif
            </div>
            <div class="px-5 mt-5 flex justify-between">
                <div class="w-1/3">
                    <input type="text" wire:model="search" placeholder="Cari ..." class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                </div>
                <div>
<button
    wire:click="exportAll"
    wire:loading.attr="disabled"
    class="relative bg-gradient-to-br from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-white px-5 font-semibold py-2 text-sm rounded-lg">
    <span wire:loading.remove>Unduh Semua</span>
    <span wire:loading>Memproses...</span>
</button>

                    <button 
                        class="bg-gradient-to-br from-[#98EC2D] to-[#17AD37] hover:scale-105 transition text-white px-5 font-semibold py-2 text-sm rounded-lg">
                        Finalisasi
                    </button>
                </div>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-5 overflow-x-auto">
                    <div class="">
                        <table class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden shadow text-sm">
                            <thead class="bg-gray-50 text-center text-gray-500 text-xs font-medium">
                                <tr>
                                    <td class="px-4 py-2">NAMA</td>
                                    <td class="px-4 py-2">SATKER</td>
                                    <td class="px-4 py-2">JABATAN</td>
                                    @if ($jenis == 'Pengangkatan Pertama')
                                        <td class="px-4 py-2">GOLONGAN</td>
                                        <td class="px-4 py-2">JENJANG TUJUAN</td>
                                    @endif
                                    <td class="px-4 py-2">PREDIKAT</td>
                                    <td class="px-4 py-2">PERIODE</td>
                                    {{-- @if ($jenis == 'Pengangkatan Kembali')
                                        <td class="px-4 py-2">JENJANG JFT LAMA</td>
                                    @endif --}}
                                    <td class="px-4 py-2">ANGKA KREDIT</td>
                                    <td class="px-4 py-2">TOTAL ANGKA KREDIT</td>    
                                    <td class="px-4 py-2">AKSI</td>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($profiles as $profile)
                                    <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} font-semibold">
                                        <td class="px-4 py-2">{{ $profile['nama'] }}</td>
                                        <td class="px-4 py-2">{{ $profile['satker'] }}</td>
                                        <td class="px-4 py-2">{{ $profile['jabatan'] }}</td>

                                        @if ($jenis == 'Pengangkatan Pertama')
                                            <td class="px-4 py-2">{{ $profile['golongan'] }}</td>
                                            <td class="px-4 py-2">{{ ucwords($profile['jenjang_tujuan']) }}</td>
                                        @endif

                                        <td class="px-4 py-2 text-center">{{ $profile['predikat'] }}</td>

                                        @if ($jenis == 'Tahunan')
                                            <td class="px-4 py-2 text-center">{{ $profile['periode'] }}</td>
                                        @elseif ($jenis == 'Periodik' || $jenis == 'Pengangkatan Pertama' || $jenis == 'Perpindahan Jabatan' || $jenis_angkat_kembali == 'Struktural ke JFT' || $jenis_angkat_kembali == 'CLTN' || $jenis_angkat_kembali == 'Tugas Belajar')
                                            <td class="px-4 py-2 text-center">{{ $profile['mulai']->translatedFormat('M Y') . ' - ' . $profile['akhir']->translatedFormat('M Y') }}</td>
                                        @endif

                                        <td class="px-4 py-2 text-center">{{ rtrim(rtrim(number_format($profile['angka_kredit'], 3, '.', ''), '0'), '.') }}</td>

                                        <td class="px-4 py-2 text-center">{{ rtrim(rtrim(number_format($profile['angka_kredit'] + $profile['ak_awal'], 3, '.', ''), '0'), '.') }}</td>

                                        <td class="px-4 py-2 flex justify-center gap-x-2 items-center h-full">
                                            <div class="px-3 py-1 cursor-pointer text-xs bg-[#FB8A33] text-white rounded-lg hover:bg-[#e8863c]" 
                                                wire:click="setEdit('{{ $profile['nip'] }}')"
                                            >
                                                Edit
                                            </div>
                                            <div class="px-3 py-1 text-center cursor-pointer text-xs bg-[#17C1E8] text-white rounded-lg hover:bg-[#35aec9]" 
                                                wire:click="export('{{ $profile['nip'] }}')"
                                            >
                                                Unduh
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class=" px-4 py-4 text-center font-semibold text-gray-500">Tidak ada data yang dipilih.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    
                        <div class="mt-4">
                            {{ $profiles->links() }}
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        <div x-show="showEdit" x-cloak x-on:close-modal.window="showEdit = false" class="fixed inset-0 flex items-center justify-center bg-transparent backdrop-blur-sm z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96 max-h-96 overflow-y-auto relative">
                <!-- Header -->
                <div class="flex justify-between items-center border-b pb-2">
                    <h2 class="text-lg font-semibold">Edit Angka Kredit</h2>
                    <button @click="showEdit = false" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>

                <input type="hidden" wire:model="angka_kredit_awal" />
    
                @if ($jenis == 'Periodik')
                    <div class="w-full mb-3">
                        <label class="text-xs">Periode Mulai</label>
                        <input 
                            type="month" 
                            wire:model="mulai_periode" wire:change="componentChanged"
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
                            wire:model="akhir_periode" wire:change="componentChanged"
                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" 
                        />

                        @error('akhir')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                @elseif ($jenis == 'Pengangkatan Pertama')
                    <div class="w-full mb-3">
                        <label class="text-xs">Golongan</label>
                        <input type="text" wire:model="golongan" wire:change="componentChanged" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                    
                        @error('golongan')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full mb-3 relative">
                        <label class="text-xs">Jenjang Tujuan</label>
                        <select wire:model="jenjang_tujuan" wire:change="componentChanged" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow pr-10" >
                            <option value='terampil'>Terampil</option>
                            <option value='mahir'>Mahir</option>
                            <option value='penyelia'>Penyelia</option>
                            <option value='ahli pertama'>Ahli Pertama</option>
                            <option value='ahli muda'>Ahli Muda</option>
                            <option value='ahli madya'>Ahli Madya</option>
                            <option value='ahli utama'>Ahli Utama</option>
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
                        <label class="text-xs">Periode Mulai</label>
                        <input 
                            type="month" 
                            wire:model="mulai_periode" wire:change="componentChanged"
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
                            wire:model="akhir_periode" wire:change="componentChanged"
                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" 
                        />

                        @error('akhir')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                @elseif ($jenis_angkat_kembali == 'CLTN')
                    <div class="w-full mb-3">
                        <label class="text-xs">Pengangkatan Kembali</label>
                        <input 
                            type="month" 
                            wire:model="mulai_periode" wire:change="componentChanged"
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
                            wire:model="akhir_periode" wire:change="componentChanged"
                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" 
                        />

                        @error('akhir')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                @elseif ($jenis_angkat_kembali == 'Tugas Belajar')
                    <div class="w-full mb-3">
                        <label class="text-xs">Angka Kredit Sebelum TB</label>
                        <input type="text" wire:model="ak_before_tb" wire:change="componentChanged" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                    
                        @error('ak_before_tb')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full mb-3">
                        <label class="text-xs">Lama TB (Bulan)</label>
                        <input type="text" wire:model="lama_tb" wire:change="componentChanged" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                    
                        @error('lama_tb')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                @elseif ($jenis_angkat_kembali == 'Struktural ke JFT')
                    <div class="w-full mb-3">
                        <label class="text-xs">Angka Kredit Sebelum Struktural</label>
                        <input type="text" wire:model="ak_jft" wire:change="componentChanged" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                    
                        @error('ak_jft')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full mb-3 relative">
                        <label class="text-xs">Jenjang JFT Lama</label>
                        <select wire:model="jft_sebelum" wire:change="componentChanged" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow pr-10" >
                            <option value='terampil'>Terampil</option>
                            <option value='mahir'>Mahir</option>
                            <option value='penyelia'>Penyelia</option>
                            <option value='ahli pertama'>Ahli Pertama</option>
                            <option value='ahli muda'>Ahli Muda</option>
                            <option value='ahli madya'>Ahli Madya</option>
                            <option value='ahli utama'>Ahli Utama</option>
                        </select>   
                         <!-- Chevron Icon -->
                        <div class="absolute top-6 inset-y-0 right-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500 transition-all" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
        
                        @error('jft_sebelum')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full mb-3">
                        <label class="text-xs">TMT Jabatan Struktural</label>
                        <input 
                            type="month" 
                            wire:model="mulai_periode" wire:change="componentChanged"
                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" 
                        />

                        @error('akhir')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full mb-3">
                        <label class="text-xs">Pengangkatan Kembali</label>
                        <input 
                            type="month" 
                            wire:model="akhir_periode" wire:change="componentChanged"
                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" 
                        />

                        @error('akhir')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                    <div class="w-full mb-3 relative">
                        <label class="text-xs">Predikat</label>
                        <select wire:model="predikat" wire:change="componentChanged" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow pr-10" >
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
                    <label class="text-xs">Angka Kredit</label>
                    <input type="text" wire:model="angka_kredit" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                
                    @error('angka_kredit')
                        <p class="text-red-500 text-xs">{{ $message }}</p>
                    @enderror
                </div>
    
                <div class="mt-4 flex justify-end space-x-2 text-sm">
                    <button @click="showEdit = false" class="px-3 py-1 font-medium bg-gray-500 text-white rounded hover:bg-gray-600">
                        Batal
                    </button>
                    <button wire:click="updateAngkaKredit" class="px-3 py-1 bg-[#CB0C9F] font-medium hover:bg-[#b42f95] text-white rounded">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>