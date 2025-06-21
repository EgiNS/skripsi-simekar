<div class="flex flex-wrap -mx-3">
    @section('beforeTitle', 'Pages')
    @section('title', 'Dashboard')
    
    <div class="flex-none w-full max-w-full px-3">
        @if($passwordDefault)
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded mb-4" role="alert">
                <p class="font-bold">Perhatian!</p>
                <p>Password Anda masih default (sama seperti NIP). Demi keamanan, silakan segera ubah password Anda melalui menu <a href="{{ route('profile') }}" class="font-bold hover:underline" wire:navigate>Profile</a>.</p>
            </div>
        @endif
        <div class="relative flex flex-col min-w-0 mb-6 break-words border-0 border-transparent border-solid rounded-2xl">
            <div class="p-5 mb-0 bg-white rounded-2xl shadow-soft-xl">
                <p class="font-semibold text-lg text-[#252F40]">Halo, {{ $this->user->nama }}</p>
                <p class="text-sm">{{ $this->user->jabatan }}</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-x-3">
            <div class="flex flex-col gap-y-3">
                <div class="p-5 mb-0 bg-white rounded-2xl shadow-soft-xl flex flex-row items-center justify-between">
                    <div class="flex flex-col gap-y-3">
                        <p class="self-end leading-none font-medium text-end">Angka Kredit</p>
                        @if ($this->ak)
                            <p class="text-[#252F40] text-2xl font-semibold">{{ number_format($this->ak->total_ak, 3, ',', '.') }}</p>
                        @else
                            <p class="text-[#252F40] text-2xl font-semibold">-</p>
                        @endif
                    </div>
                    <div class="w-16 h-full text-white flex justify-center items-center text-4xl font-semibold text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                       X
                    </div>
                </div>
                <div class="p-5 mb-0 bg-white rounded-2xl shadow-soft-xl flex flex-row items-center justify-between">
                    <div class="flex flex-col gap-y-3">
                        <p class="self-end leading-none font-medium text-end">Perkiraan Kenaikan Pangkat</p>
                        <p class="text-[#252F40] text-2xl font-semibold">{{ $this->kp }}</p>
                    </div>
                    <div class="w-16 h-full text-white flex justify-center items-center text-4xl font-semibold text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                       X
                    </div>
                </div>
                <div class="p-5 mb-0 bg-white rounded-2xl shadow-soft-xl flex flex-row items-center justify-between">
                    <div class="flex flex-col gap-y-3">
                        <p class="self-end leading-none font-medium text-end">Perkiraan Kenaikan Jenjang</p>
                        <p class="text-[#252F40] text-2xl font-semibold">{{ $this->kj }}</p>
                    </div>
                    <div class="w-16 h-full text-white flex justify-center items-center text-4xl font-semibold text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                       X
                    </div>
                </div>
                <div class="p-5 mb-0 bg-white rounded-2xl shadow-soft-xl flex flex-row items-center justify-between">
                    <div class="flex flex-col gap-y-3">
                        <p class="self-end leading-none font-medium text-end">Perkiraan Masa Pensiun</p>
                        <p class="text-[#252F40] text-2xl font-semibold">{{ $this->pensiun }}</p>
                    </div>
                    <div class="w-16 h-full text-white flex justify-center items-center text-4xl font-semibold text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                       X
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