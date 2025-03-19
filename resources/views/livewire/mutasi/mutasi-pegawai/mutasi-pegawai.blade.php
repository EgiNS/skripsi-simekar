<div class="flex flex-wrap -mx-3">
    @section('beforeTitle', 'Pages')
    @section('title', 'Mutasi')

    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words border-0 border-transparent border-solid rounded-2xl">
            <div class="p-5 mb-5 bg-white rounded-2xl shadow-soft-xl">
                <p class="font-semibold text-lg text-[#252F40]">Pengajuan Mutasi</p>
                <div class="mt-5">
                    <div class="w-full grid grid-cols-7 items-center align-middle mb-3 relative">
                        <label class="text-sm">Jenis Usulan</label>
                        <select class="col-span-6 focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow pr-10" >
                            <option value=1>Atas Permintaan Sendiri</option>
                            <option value=2>Alasan Khusus</option>
                            <option value=3>Penugasan</option>
                        </select>   
                         <!-- Chevron Icon -->
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500 transition-all" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>

                    <div class="w-full relative mb-3 grid grid-cols-7 items-center align-middle" x-data="{ open: false }">
                        <label class="text-sm">Satker Tujuan</label>
                        <div class="col-span-6">
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
                    </div>

                    <div class="w-full mb-3 grid grid-cols-7">
                        <label class="text-sm">Alasan</label>
                        <Textarea rows="5" class="col-span-6 focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow">
                        </Textarea>
                    </div>

                    <div class="w-full flex justify-end mt-3">
                        <button class="bg-gradient-to-br from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-white px-5 font-semibold py-2 text-sm rounded-lg">Kirim</button>
                    </div>
                </div>
            </div>

            <div class="p-5 mb-5 bg-white rounded-2xl shadow-soft-xl">
                <p class="font-semibold text-lg mb-5 text-[#252F40]">Riwayat Pengajuan Mutasi</p>
                <livewire:Mutasi.Mutasi-Pegawai.Riwayat-Pengajuan-Table />
            </div>
        </div>
    </div>
</div>