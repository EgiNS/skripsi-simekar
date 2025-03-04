<div class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-5 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <p class="font-semibold text-lg text-[#252F40]">Tambah Kebutuhan ABK</p>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2 mt-4">
                <div class="p-5 overflow-x-auto">
                    <form role="form">
                        <div class="w-full grid grid-cols-7 align-middle content-center mb-4">
                            <label class="text-slate-700">Satker</label>
                            <div class="col-span-6 relative w-full">
                                <select class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow pr-10" 
                                    wire:model="satker">
                                    <option value="" selected disabled>Pilih opsi</option>
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
                        </div>
                        <div x-data="{ open: false }" class="relative w-full">
                            <div class="w-full grid grid-cols-7 align-middle content-center">
                                <label class="text-slate-700">Jabatan</label>
                                <div class="col-span-6 w-full">
                                    <input 
                                        type="text" 
                                        wire:model.live="jabatan" 
                                        x-on:input="open = true"
                                        x-on:focus="open = true"
                                        x-on:click.away="open = false"
                                        class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                                        placeholder="Cari Jabatan..."
                                    />
                                    
                                    <!-- Dropdown untuk rekomendasi -->
                                    <div x-show="open && @this.suggestions.length" class="relative w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg">
                                        <ul>
                                            @foreach($suggestions as $suggestion)
                                                <li 
                                                    class="px-3 py-2 cursor-pointer hover:bg-gray-200" 
                                                    x-on:click="@this.selectJabatan('{{ $suggestion }}'); open = false"
                                                >
                                                    {{ $suggestion }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full grid grid-cols-7 align-middle content-center my-4">
                            <label class="text-slate-700">Jumlah Formasi</label>
                            <div class="col-span-6 relative w-full">
                                <input type="integer" wire:model='formasi' class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                            </div>
                        </div>
                        <div class="w-full flex justify-end">
                            <button class="bg-gradient-to-br from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-white px-5 py-2 text-sm rounded-lg">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>