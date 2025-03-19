<div class="flex flex-wrap -mx-3">
    @section('beforeTitle', 'Pages')
    @section('title', 'Tes Minat Karier')

    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words border-0 border-transparent border-solid rounded-2xl">
            @if ($step == 1)
                <div class="p-5 mb-5 bg-white rounded-2xl shadow-soft-xl">
                    <p class="font-semibold text-lg text-[#252F40] mb-5">Tes Minat Karier</p>
                    <p class="text-sm mb-4">
                        Tes Minat Karier ini ditujukan untuk melihat jalur karier mana yang paling sesuai dengan diri Anda selaku ASN di lingkungan Badan Pusat Statistik. Pernyataan-pernyataan dalam tes ini dibuat berdasarkan butir-butir kegiatan beberapa jenis jabatan fungsional. 
                    </p>
                    <p class="text-sm">
                        Dari setiap halaman, silakan pilih tiga pernyataan yang paling sesuai dengan diri Anda. Klik <span class="font-semibold">Mulai</span> jika sudah siap untuk memulai tes
                    </p>
                    <div class="w-full flex justify-end mt-5">
                        <button class="bg-gradient-to-br from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-white px-6 font-semibold py-2 text-sm rounded-lg" wire:click="nextPage">Mulai</button>
                    </div>
                </div>    
            @elseif ($step == 2)
                <div class="p-5 mb-5 bg-white rounded-2xl shadow-soft-xl" x-data="{ selectedStep2: [] }">
                    <p class="font-semibold text-lg text-[#252F40] mb-5">Tes Minat Karier</p>
                    <p class="text-sm mb-5 font-medium">
                    Silakan pilih 3 pernyataan yang paling sesuai dengan diri Anda
                    </p>
                    <ol class="ml-5">
                        <ul>
                            @for ($i = 1; $i <= 7; $i++)
                                <li class="list-decimal">
                                    <div class="flex flex-row justify-between border-b pb-2 mb-3">
                                        <p class="text-sm leading-relaxed">
                                            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptatum, asperiores.
                                        </p>
                                        <input type="checkbox" 
                                            class="checkbox-limit"
                                            :disabled="selectedStep2.length >= 3 && !selectedStep2.includes({{ $i }})"
                                            @change="if ($event.target.checked) { selectedStep2.push({{ $i }}) } else { selectedStep2 = selectedStep2.filter(n => n !== {{ $i }}) }">
                                    </div>
                                </li>   
                            @endfor
                        </ul>                        
                    </ol>
                    <div class="w-full flex justify-between mt-5">
                        <button wire:click="prevPage" class="px-4 py-2 bg-[#8392AB] text-white rounded-lg text-sm hover:scale-105 font-semibold transition">Kembali</button>
                        <button 
                            class="bg-gradient-to-br from-[#FF0080] to-[#7928CA] text-white px-4 font-semibold py-2 text-sm rounded-lg transition hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="selectedStep2.length < 3"
                            wire:click="nextPage">
                            Selanjutnya
                        </button>
                    </div>
                </div>    
            {{-- @elseif ($step == 3)
                <div class="p-5 mb-5 bg-white rounded-2xl shadow-soft-xl" x-data="{ selectedStep3: [], selectedStep2: [] }">
                    <p class="font-semibold text-lg text-[#252F40] mb-5">Tes Minat Karier</p>
                    <p class="text-sm mb-5 font-medium">
                    Silakan pilih 3 pernyataan yang paling sesuai dengan diri Anda
                    </p>
                    <ol class="ml-5">
                        <ul>
                            @for ($i = 1; $i <= 7; $i++)
                                <li class="list-decimal">
                                    <div class="flex flex-row justify-between border-b pb-2 mb-3">
                                        <p class="text-sm leading-relaxed">
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Assumenda, doloribus?
                                        </p>
                                        <input type="checkbox" 
                                            class="checkbox-limit"
                                            :disabled="selectedStep3.length >= 3 && !selectedStep3.includes({{ $i }})"
                                            @change="if ($event.target.checked) { selectedStep3.push({{ $i }}) } else { selectedStep3 = selectedStep3.filter(n => n !== {{ $i }}) }">
                                    </div>
                                </li>   
                            @endfor
                        </ul>                        
                    </ol>
                    <div class="w-full flex justify-between mt-5">
                        <button wire:click="prevPage" class="px-4 py-2 bg-[#8392AB] text-white rounded-lg text-sm hover:scale-105 font-semibold transition">Kembali</button>
                        <button 
                            class="bg-gradient-to-br from-[#FF0080] to-[#7928CA] text-white px-4 font-semibold py-2 text-sm rounded-lg transition hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="selectedStep3.length < 3"
                            wire:click="nextPage">
                            Selanjutnya
                        </button>
                    </div>
                </div> --}}
            @elseif ($step == 3)
                <div class="p-5 mb-5 bg-white rounded-2xl shadow-soft-xl">
                    <p class="font-semibold text-lg text-[#252F40] mb-5">Hasil Tes Minat Karier</p>
                    <p class="text-sm mb-5 font-medium">
                        Jabatan fungsional yang paling cocok berdasarkan pilihan Anda adalah
                    </p>
                    <div class="grid grid-cols-2 text-sm">
                        <li class="list-decimal">Pranata Komputer</li>
                        <p>60%</p>
                        <li class="list-decimal">Analis Data</li>
                        <p>20%</p>
                        <li class="list-decimal">Pengolah Data</li>
                        <p>15%</p>
                        <li class="list-decimal">Lainnya</li>
                        <p>5%</p>
                    </div>
                    <div class="mt-5 text-sm">
                        <p>Informasi lebih lanjut mengenai jabatan Pranata Komputer dapat dilihat pada peraturan XXX atau melalui link .... </p>
                    </div>
                    <div class="w-full flex justify-between mt-5">
                        <button wire:click="prevPage" class="px-4 py-2 bg-[#8392AB] text-white rounded-lg text-sm hover:scale-105 font-semibold transition">Kembali</button>
                        {{-- <button class="bg-gradient-to-br from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-white px-4 font-semibold py-2 text-sm rounded-lg" wire:click="nextPage">Selanjutnya</button> --}}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>