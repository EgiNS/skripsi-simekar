<div class="flex flex-wrap -mx-3">
    @section('beforeTitle', 'Pages')
    @section('title', 'Tes Minat Karier')

    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words border-0 border-transparent border-solid rounded-2xl">
            @if ($currentPage == 1)
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

                <div class="col-span-2 mb-5 bg-white rounded-2xl shadow-soft-xl">
                    <p class="font-semibold text-lg p-5 pb-0 text-[#252F40]">Riwayat Impor Data</p>
                    <div class="flex-auto p-6 px-0 pb-2 max-h-96 overflow-y-auto">
                        <div class="overflow-x-auto">
                            <table class="items-center w-full mb-0 align-top text-slate-500">
                                <thead class="align-bottom">
                                    <tr>
                                        <th class="px-6 py-2 font-bold tracking-normal text-center uppercase align-middle bg-transparent border-b border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                            No
                                        </th>
                                        <th class="px-6 py-2 font-bold tracking-normal text-center uppercase align-middle bg-transparent border-b border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                            Tanggal Tes
                                        </th>
                                        <th class="px-6 py-2 font-bold tracking-normal text-center uppercase align-middle bg-transparent border-b border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                            Rekomendasi Jabatan 1
                                        </th>
                                        <th class="px-6 py-2 font-bold tracking-normal text-center uppercase align-middle bg-transparent border-b border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                            Rekomendasi Jabatan 2
                                        </th>
                                        <th class="px-6 py-2 font-bold tracking-normal text-center uppercase align-middle bg-transparent border-b border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                            Rekomendasi Jabatan 3
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($riwayat as $index => $item)
                                        <tr>
                                            <td class="leading-normal py-2 text-center align-middle bg-transparent border-b text-sm whitespace-nowrap">
                                                <span class="mb-0 leading-normal text-sm">
                                                    {{ $index + 1 }}
                                                </span>
                                            </td>
        
                                            <td class="leading-normal py-2 text-center align-middle bg-transparent border-b text-sm whitespace-nowrap">
                                                <span class="mb-0 leading-normal text-sm">
                                                    {{  $item->created_at->format('d-m-Y') }}
                                                </span>
                                            </td>

                                            <td class="leading-normal py-2 text-center align-middle bg-transparent border-b text-sm whitespace-nowrap">
                                                <span class="mb-0 leading-normal text-sm">
                                                    {{  $item->jabatan_1 }} ({{ $item->total_1 }}%)
                                                </span>
                                            </td>

                                            <td class="leading-normal py-2 text-center align-middle bg-transparent border-b text-sm whitespace-nowrap">
                                                <span class="mb-0 leading-normal text-sm">
                                                    {{  $item->jabatan_2 }} ({{ $item->total_2 }}%)
                                                </span>
                                            </td>

                                            <td class="leading-normal py-2 text-center align-middle bg-transparent border-b text-sm whitespace-nowrap">
                                                <span class="mb-0 leading-normal text-sm">
                                                    {{  $item->jabatan_3 }} ({{ $item->total_3 }}%)
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @elseif ($currentPage >= 2 && $currentPage <= $this->getLastSoalPage() + 1)
                {{-- <?php var_dump($selectedOptions) ?> --}}
                <div class="p-5 mb-5 bg-white rounded-2xl shadow-soft-xl"
                    x-data="{
                        currentPage: {{ $currentPage }},
                        selectedStep2: @js($selectedOptions[$currentPage] ?? []),
                        init() {
                            this.$watch('selectedStep2', value => {
                                $wire.set('selectedOptions.' + this.currentPage, value);
                            });
                        }
                    }"
                    x-init="init()"                          
                >                
                    <p class="font-semibold text-lg text-[#252F40] mb-5">Tes Minat Karier</p>
                    <p class="text-sm mb-5 font-medium">
                    Silakan pilih 3 pernyataan yang paling sesuai dengan diri Anda
                    </p>
                    <ol class="ml-5 text-sm" :key="'page-' + currentPage">
                        @foreach ($this->soalPage as $index => $item)
                            <li class="list-decimal">
                                <div class="flex flex-row justify-between border-b pb-2 mb-3">
                                    <p class="text-sm leading-relaxed">
                                        {{ $item->soal }}
                                    </p>
                                    <input type="checkbox"
                                        :value="{{ $item->id }}"
                                        :checked="selectedStep2.includes({{ $item->id }})"
                                       
                                        @change="
                                            if ($event.target.checked) {
                                                if (selectedStep2.length < 3) {
                                                    selectedStep2.push({{ $item->id }});
                                                } else {
                                                    $event.target.checked = false;
                                                }
                                            } else {
                                                selectedStep2 = selectedStep2.filter(id => id !== {{ $item->id }});
                                            }
                                        "
                                    >
                                </div>
                            </li>
                        @endforeach
                        <input type="checkbox" x-show="false">
                    </ol>                    
                    <div class="w-full flex justify-between mt-5">
                        <button wire:click="prevPage" class="px-4 py-2 bg-[#8392AB] text-white rounded-lg text-sm hover:scale-105 font-semibold transition">
                            Kembali
                        </button>
                        <div x-data="{ 
                            showConfirmModal: false, 
                            currentPage: @entangle('currentPage'), 
                            lastPage: {{ $this->getLastSoalPage() + 1 }}
                        }">
                            <button 
                                x-show="currentPage < lastPage"
                                wire:click="nextPage" 
                                class="px-4 py-2 bg-gradient-to-br from-[#FF0080] to-[#7928CA] text-white rounded-lg text-sm hover:scale-105 font-semibold transition">
                                Selanjutnya
                            </button>
                        
                            <button 
                                x-show="currentPage === lastPage"
                                @click="showConfirmModal = true" 
                                class="px-4 py-2 bg-gradient-to-br from-[#FF0080] to-[#7928CA] text-white rounded-lg text-sm hover:scale-105 font-semibold transition">
                                Selesai
                            </button>
                        
                            <!-- Modal Konfirmasi -->
                            <div 
                                x-show="showConfirmModal"
                                x-cloak
                                class="fixed inset-0 flex items-center justify-center bg-transparent backdrop-blur-sm z-50">
                                <div class="bg-white rounded-xl p-6 w-96 text-center shadow-lg">
                                    <p class="text-lg font-medium mb-4">Apakah Anda yakin untuk menyelesaikan tes?</p>
                                    <div class="flex justify-center gap-4">
                                        <button @click="showConfirmModal = false" class="px-4 py-2 bg-gray-300 rounded-lg text-sm font-semibold hover:scale-105">Batal</button>
                                        <button @click="$wire.finishTest()" class="px-4 py-2 bg-gradient-to-br from-[#FF0080] to-[#7928CA] text-white rounded-lg text-sm font-semibold hover:scale-105">Selesai</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                                             
                    </div>                
                </div>    
            @elseif ($currentPage == $this->getLastSoalPage() + 2)
                <div class="p-5 mb-5 bg-white rounded-2xl shadow-soft-xl">
                    <p class="font-semibold text-lg text-[#252F40] mb-5">Hasil Tes Minat Karier</p>
                    <p class="text-sm mb-5 font-medium">
                        Jabatan fungsional yang paling cocok berdasarkan pilihan Anda adalah
                    </p>
                    <div class="grid grid-cols-2 text-sm">
                        @foreach ($hasil as $item)
                            <li class="list-decimal">{{ $item['jabatan'] }}</li>
                            <p>{{ $item['total'] }}%</p>
                        @endforeach
                    </div>
                    <div class="w-full flex justify-between mt-5">
                        <button wire:click="prevPage" class="px-4 py-2 bg-[#8392AB] text-white rounded-lg text-sm hover:scale-105 font-semibold transition">Kembali</button>                    </div>
                </div>
            
            {{-- @elseif ($step == 3)
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
                        <button wire:click="prevPage" class="px-4 py-2 bg-[#8392AB] text-white rounded-lg text-sm hover:scale-105 font-semibold transition">Kembali</button>                    </div>
                </div> --}}
            @endif
        </div>
    </div>

    @push('script')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('tesMinat', {
                    selected: {}, // key = halaman, value = array of ID
                });
            });
        </script>
    @endpush
</div>