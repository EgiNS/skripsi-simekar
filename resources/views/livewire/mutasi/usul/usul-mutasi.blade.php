<div class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3" x-data="{ showTambah: false }">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-5 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex flex-row justify-between">
                <p class="font-semibold text-lg text-[#252F40]">Pengajuan Mutasi Pegawai</p>
                <button class="bg-gradient-to-br from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-white px-5 font-semibold py-2 text-sm rounded-lg" @click="showTambah = true">Tambah</button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-5 overflow-x-auto">
                    <livewire:Mutasi.Usul.Usul-Mutasi-Table />
                </div>
            </div>
        </div>
        <div x-show="showTambah" class="fixed inset-0 flex items-center justify-center bg-transparent backdrop-blur-sm">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96 relative">
                <!-- Header -->
                <div class="flex justify-between items-center border-b pb-2">
                    <h2 class="text-lg font-semibold">Tambah Usul Mutasi</h2>
                    <button @click="showTambah = false" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>

                <div class="w-full mt-3">
                    <div class="w-full mb-3">
                        <label class="text-xs mb-1">NIP</label>
                        <input 
                            type="text" 
                            wire:model.live="nip" 
                            x-on:input="open = true"
                            x-on:focus="open = true"
                            x-on:click.away="open = false"
                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                        />
                        
                        <!-- Dropdown untuk rekomendasi -->
                        <div x-show="open && @this.suggestionsNip.length" class="absolute w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg">
                            <ul>
                                @foreach($suggestionsNip as $suggestion)
                                    <li 
                                        class="px-3 py-2 cursor-pointer hover:bg-gray-200" 
                                        x-on:click="@this.selectNip('{{ $suggestion }}'); open = false"
                                    >
                                        {{ $suggestion }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="w-full mb-3">
                        <label class="text-xs">Nama</label>
                        <input type="integer" wire:model='nama' readonly class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                    </div>

                    <div class="w-full mb-3">
                        <label class="text-xs">Jabatan</label>
                        <input type="integer" wire:model='jabatan' readonly class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                    </div>

                    <div class="w-full mb-3 relative">
                        <label class="text-xs">Jenis Usulan</label>
                        <select class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow pr-10" >
                            <option value=1>Atas Permintaan Sendiri</option>
                            <option value=2>Alasan Khusus</option>
                            <option value=3>Penugasan</option>
                        </select>   
                         <!-- Chevron Icon -->
                        <div class="absolute top-6 inset-y-0 right-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500 transition-all" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>

                    <div class="w-full mb-3">
                        <label class="text-xs mb-1">Satker Tujuan</label>
                        <input 
                            type="text" 
                            wire:model.live="satker" 
                            x-on:input="open = true"
                            x-on:focus="open = true"
                            x-on:click.away="open = false"
                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                        />
                        
                        <!-- Dropdown untuk rekomendasi -->
                        <div x-show="open && @this.suggestionsSatker.length" class="absolute w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg">
                            <ul>
                                @foreach($suggestionsSatker as $suggestion)
                                    <li 
                                        class="px-3 py-2 cursor-pointer hover:bg-gray-200" 
                                        x-on:click="@this.selectSatker('{{ $suggestion }}'); open = false"
                                    >
                                        {{ $suggestion }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="w-full mb-3">
                        <label class="text-xs">Alasan</label>
                        <Textarea class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow">
                        </Textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>