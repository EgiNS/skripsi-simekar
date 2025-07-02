<div class="flex flex-wrap -mx-3">
    @section('beforeTitle', 'Pages')
    @section('title', 'Dashboard')

    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words border-0 border-transparent border-solid rounded-2xl">
            <div class="p-5 mb-5 bg-white rounded-2xl shadow-soft-xl">
                <p class="font-semibold text-lg text-[#252F40]">Halo, {{ $this->user->nama }}</p>
                <p class="text-sm">{{ $this->user->jabatan }}</p>
            </div>
            <div class="grid mb-5 grid-cols-3 space-x-2">
                <div class="p-5 mb-0 bg-white rounded-2xl shadow-soft-xl flex flex-row items-center justify-between">
                    <div class="w-20 h-full text-white flex justify-center items-center text-4xl font-semibold text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                       {{ $this->getTotalCountBulanIni() }}
                    </div>
                    <div class="flex flex-col gap-y-3">
                        <p class="text-[#252F40] font-medium text-sm self-end text-end">Nominasi Pegawai Naik Pangkat Periode Selanjutnya</p>
                        <a href="/angka-kredit" wire:navigate class="text-sm self-end hover:underline">Lihat detail →</a> 
                    </div>
                </div>
                <div class="p-5 mb-0 bg-white rounded-2xl shadow-soft-xl flex flex-row items-center justify-between">
                    <div class="w-20 h-full text-white flex justify-center items-center text-4xl font-semibold text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                        {{ $this->getUsulMutasi() }}
                    </div>
                    <div class="flex flex-col gap-y-3">
                        <p class="text-[#252F40] font-medium text-sm self-end text-end">Usul Mutasi Pegawai Belum Ditindaklanjuti</p>
                        <a href="/usul-mutasi" wire:navigate class="text-sm self-end hover:underline">Lihat detail →</a> 
                    </div>
                </div>
                <div class="p-5 mb-0 bg-white rounded-2xl shadow-soft-xl flex flex-row items-center justify-between">
                    <div class="w-20 h-full text-white flex justify-center items-center text-4xl font-semibold text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                        {{ $this->getApprovalPAK() }}
                    </div>
                    <div class="flex flex-col gap-y-3">
                        <p class="text-[#252F40] font-medium text-sm self-end text-end">Approval Arsip PAK Pegawai Masih Menunggu</p>
                        <a href="" class="text-sm self-end hover:underline">Lihat detail →</a> 
                    </div>
                </div>
            </div>
            <div class="p-5 mb-5 bg-white rounded-2xl shadow-soft-xl">
                <div class="flex flex-row justify-between relative mb-3">
                    <p class="font-semibold text-lg mb-5 text-[#252F40]">Pegawai yang Akan Naik Pangkat</p>
                    <div class="relative w-24">
                        <select wire:model='tahun' wire:change='getNaikPangkat' class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow pr-10" >
                            <option value="{{ now()->year }}" selected>{{ now()->year }}</option>
                            <option value="{{ now()->year + 1 }}">{{ now()->year + 1 }}</option>
                            <option value="{{ now()->year + 2 }}">{{ now()->year + 2 }}</option>
                        </select>
                        <!-- Chevron Icon -->
                        <div class="absolute -top-2 inset-y-0 right-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500 transition-all" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    @forelse ($groupedData[$tahun] as $month => $items)
                        <div x-data="{ open: false }" class="text-[#252F40]">
                            <!-- Header Accordion -->
                            <button @click="open = !open" class="w-full rounded-lg shadow px-4 py-2 bg-blue-50 text-left font-medium flex items-center justify-between">
                                <span>{{ $month }}</span>
                                <span x-show="!open">+</span>
                                <span x-show="open">–</span>
                            </button>

                            <!-- Konten Accordion -->
                            <div x-show="open" x-collapse class="p-4 rounded-b-lg shadow">
                                @if($items === null)
                                    <p class="text-gray-500">Tidak ada data</p>
                                @else
                                    @if(count($items))
                                        <ul>
                                            @foreach($items as $item)
                                                @if ($item ==  null)
                                                    <p class="text-gray-500">Tidak ada data</p>
                                                @else
                                                    <li class="flex justify-between mb-2 text-sm">
                                                        <span class="">{{ $item['satker'] }}</span>
                                                        <span>{{ $item['count'] }}</span>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-gray-500">Tidak ada data</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @empty
                        <div x-data="{ open: false }" class="text-[#252F40]">
                            <!-- Header Accordion -->
                            <button @click="open = !open" class="w-full rounded-lg shadow px-4 py-2 bg-blue-50 text-left font-medium flex items-center justify-between">
                                <span>{{ $month }}</span>
                                <span x-show="!open">+</span>
                                <span x-show="open">–</span>
                            </button>

                            <!-- Konten Accordion -->
                            <div x-show="open" x-collapse class="p-4 rounded-b-lg shadow">
                                <p class="text-gray-500">Tidak ada data</p>
                            </div>
                        </div>
                    @endforelse


                </div>             
            </div>
            <div class="grid grid-cols-3 gap-x-5">
                <div class="col-span-2 mb-5 bg-white rounded-2xl shadow-soft-xl">
                    <p class="font-semibold text-lg p-5 pb-0 text-[#252F40]">Keterisian Satker</p>
                    <div class="flex-auto p-6 px-0 pb-2 max-h-96 overflow-y-auto">
                        <div class="overflow-x-auto">
                            <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                                <thead class="align-bottom">
                                    <tr>
                                        <th class="px-6 py-3 font-bold tracking-normal text-left uppercase align-middle bg-transparent border-b border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                            Satker
                                        </th>
                                        <th class="px-6 py-3 font-bold tracking-normal text-center uppercase align-middle bg-transparent border-b border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                            Formasi
                                        </th>
                                        <th class="px-6 py-3 font-bold tracking-normal text-center uppercase align-middle bg-transparent border-b border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                            Eksisting
                                        </th>
                                        <th class="px-6 py-3 font-bold tracking-normal text-center uppercase align-middle bg-transparent border-b border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                            Keterisian
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dataSatker as $data)
                                        @php
                                            // Hitung persentase keterisian
                                            $persen = $data['formasi'] 
                                                ? round(($data['eksisting'] / $data['formasi']) * 100) 
                                                : 0;
                                        @endphp
                                        <tr>
                                            <!-- Nama Satker -->
                                            <td class="align-middle bg-transparent border-b whitespace-nowrap">
                                                <div class="flex px-2 py-1">
                                                    <div class="flex flex-col justify-center">
                                                        <h6 class="mb-0 leading-normal text-sm font-medium">
                                                            {{ $data['nama'] }}
                                                        </h6>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <!-- Formasi -->
                                            <td class="leading-normal text-center align-middle bg-transparent border-b text-sm whitespace-nowrap">
                                                <span class="mb-0 leading-normal text-sm">
                                                    {{ $data['formasi'] }}
                                                </span>
                                            </td>
                                            
                                            <!-- Eksisting -->
                                            <td class="leading-normal text-center align-middle bg-transparent border-b text-sm whitespace-nowrap">
                                                <span class="mb-0 leading-normal text-sm">
                                                    {{ $data['eksisting'] }}
                                                </span>
                                            </td>
                                            
                                            <!-- Keterisian (Progress Bar) -->
                                            <td class="align-middle bg-transparent border-b whitespace-nowrap">
                                                <div class="w-3/4 mx-auto flex flex-col items-center pb-1">
                                                    <!-- Angka Persentase -->
                                                    <div>
                                                        <span class="font-semibold leading-tight text-xs">
                                                            {{ $persen }}%
                                                        </span>
                                                    </div>
                                                    <!-- Progress Bar -->
                                                    <div class="text-xs h-0.75 w-28 m-0 flex overflow-visible rounded-lg bg-gray-200">
                                                        <div class="duration-600 ease-soft bg-gradient-to-tl from-blue-600 to-cyan-400 
                                                                    -mt-0.38 -ml-px flex h-1.5 flex-col justify-center 
                                                                    overflow-hidden whitespace-nowrap rounded text-center text-white transition-all"
                                                             style="width: {{ $persen }}%;"
                                                             role="progressbar" 
                                                             aria-valuenow="{{ $persen }}" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-span-1 p-5 mb-5 bg-white rounded-2xl shadow-soft-xl">
                    <p class="font-semibold text-lg mb-5 text-[#252F40]">Jadwal Ukom Terdekat</p>
                    <div class="flex-auto p-4">
                        <div class="before:border-r-solid relative before:absolute before:top-0 before:left-4 before:h-full before:border-r-2 before:border-r-slate-100 before:content-[''] before:lg:-ml-px">
                            @foreach ($ukom as $index => $item)
                                @if ($index == 0)
                                    <div class="relative mb-4 mt-0 after:clear-both after:table after:content-['']">
                                    <span class="w-5 h-5 text-base bg-gradient-to-tl from-purple-500 to-pink-500 absolute left-4 z-10 inline-flex -translate-x-1/2 items-center justify-center rounded-full text-center font-semibold">
                                        <span class="bg-white p-1 rounded-full"></span>
                                    </span>
                                    <div class="ml-11.252 pt-1.4 lg:max-w-120 relative -top-1.5 w-auto">
                                        <h6 class="mb-0 font-semibold leading-normal text-slate-700">{{ $item->judul }}</h6>
                                        <p class="mt-1 mb-0 font-semibold leading-tight text-sm text-slate-400">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d F Y') }}</p>
                                    </div>
                                    </div>
                                @else
                                    <div class="relative mb-4 mt-0 after:clear-both after:table after:content-['']">
                                    <span class="w-5 h-5 text-base bg-slate-100 absolute left-4 z-10 inline-flex -translate-x-1/2 items-center justify-center rounded-full text-center font-semibold">
                                        {{-- <span class="bg-white p-1 rounded-full"></span> --}}
                                    </span>
                                    <div class="ml-11.252 pt-1.4 lg:max-w-120 relative -top-1.5 w-auto">
                                        <h6 class="mb-0 font-semibold leading-normal text-slate-700">{{ $item->judul }}</h6>
                                        <p class="mt-1 mb-0 font-semibold leading-tight text-sm text-slate-400">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d F Y') }}</p>
                                    </div>
                                    </div>   
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>