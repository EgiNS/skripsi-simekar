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
        <div class="relative flex flex-col min-w-0 md:mb-6 mb-3 break-words border-0 border-transparent border-solid rounded-2xl">
            <div class="p-5 mb-0 bg-white rounded-2xl shadow-soft-xl">
                <p class="font-semibold text-lg text-[#252F40]">Halo, {{ $this->user->nama }}</p>
                <p class="text-sm">{{ $this->user->jabatan }}</p>
            </div>
        </div>
        <div class="grid md:grid-cols-2 grid-cols-1 md:gap-x-3 md:gap-y-0 gap-y-3">
            <div class="flex flex-col gap-y-3 mb-2 md:mb-0">
                <div class="p-5 mb-0 bg-white rounded-2xl shadow-soft-xl flex flex-row items-center justify-between">
                    <div class="flex flex-col gap-y-3">
                        <p class="self-end leading-none font-medium text-end">Angka Kredit</p>
                        @if ($this->ak)
                            <p class="text-[#252F40] md:text-2xl text-xl font-semibold">{{ number_format($this->ak->total_ak, 3, ',', '.') }}</p>
                        @else
                            <p class="text-[#252F40] md:text-2xl text-xl font-semibold">-</p>
                        @endif
                    </div>
                    <div class="w-16 h-full text-white flex justify-center items-center text-4xl font-semibold text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                       <svg xmlns="http://www.w3.org/2000/svg" class="fill-white w-7" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M184 48l144 0c4.4 0 8 3.6 8 8l0 40L176 96l0-40c0-4.4 3.6-8 8-8zm-56 8l0 40L64 96C28.7 96 0 124.7 0 160l0 96 192 0 128 0 192 0 0-96c0-35.3-28.7-64-64-64l-64 0 0-40c0-30.9-25.1-56-56-56L184 0c-30.9 0-56 25.1-56 56zM512 288l-192 0 0 32c0 17.7-14.3 32-32 32l-64 0c-17.7 0-32-14.3-32-32l0-32L0 288 0 416c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-128z"/></svg>
                    </div>
                </div>
                <div class="p-5 mb-0 bg-white rounded-2xl shadow-soft-xl flex flex-row items-center justify-between">
                    <div class="flex flex-col gap-y-3">
                        <p class="self-end leading-none font-medium text-end">Perkiraan Kenaikan Pangkat</p>
                        <p class="text-[#252F40] md:text-2xl text-xl font-semibold">{{ $this->kp }}</p>
                    </div>
                    <div class="w-16 h-full text-white flex justify-center items-center text-4xl font-semibold text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                       <svg xmlns="http://www.w3.org/2000/svg" class="fill-white w-5" viewBox="0 0 320 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M52.5 440.6c-9.5 7.9-22.8 9.7-34.1 4.4S0 428.4 0 416L0 96C0 83.6 7.2 72.3 18.4 67s24.5-3.6 34.1 4.4l192 160L256 241l0-145c0-17.7 14.3-32 32-32s32 14.3 32 32l0 320c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-145-11.5 9.6-192 160z"/></svg>
                    </div>
                </div>
                <div class="p-5 mb-0 bg-white rounded-2xl shadow-soft-xl flex flex-row items-center justify-between">
                    <div class="flex flex-col gap-y-3">
                        <p class="self-end leading-none font-medium text-end">Perkiraan Kenaikan Jenjang</p>
                        <p class="text-[#252F40] md:text-2xl text-xl font-semibold">{{ $this->kj }}</p>
                    </div>
                    <div class="w-16 h-full text-white flex justify-center items-center text-4xl font-semibold text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                       <svg xmlns="http://www.w3.org/2000/svg" class="fill-white w-7" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M18.4 445c11.2 5.3 24.5 3.6 34.1-4.4L224 297.7 224 416c0 12.4 7.2 23.7 18.4 29s24.5 3.6 34.1-4.4L448 297.7 448 416c0 17.7 14.3 32 32 32s32-14.3 32-32l0-320c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 118.3L276.5 71.4c-9.5-7.9-22.8-9.7-34.1-4.4S224 83.6 224 96l0 118.3L52.5 71.4c-9.5-7.9-22.8-9.7-34.1-4.4S0 83.6 0 96L0 416c0 12.4 7.2 23.7 18.4 29z"/></svg>
                    </div>
                </div>
                <div class="p-5 mb-0 bg-white rounded-2xl shadow-soft-xl flex flex-row items-center justify-between">
                    <div class="flex flex-col gap-y-3">
                        <p class="self-end leading-none font-medium text-end">Perkiraan Masa Pensiun</p>
                        <p class="text-[#252F40] md:text-2xl text-xl font-semibold">{{ $this->pensiun }}</p>
                    </div>
                    <div class="w-16 h-full text-white flex justify-center items-center text-4xl font-semibold text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                       <svg xmlns="http://www.w3.org/2000/svg" class="fill-white w-6" viewBox="0 0 384 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M32 0C14.3 0 0 14.3 0 32S14.3 64 32 64l0 11c0 42.4 16.9 83.1 46.9 113.1L146.7 256 78.9 323.9C48.9 353.9 32 394.6 32 437l0 11c-17.7 0-32 14.3-32 32s14.3 32 32 32l32 0 256 0 32 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l0-11c0-42.4-16.9-83.1-46.9-113.1L237.3 256l67.9-67.9c30-30 46.9-70.7 46.9-113.1l0-11c17.7 0 32-14.3 32-32s-14.3-32-32-32L320 0 64 0 32 0zM96 75l0-11 192 0 0 11c0 25.5-10.1 49.9-28.1 67.9L192 210.7l-67.9-67.9C106.1 124.9 96 100.4 96 75z"/></svg>
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
                                <p class="mt-1 mb-0 font-semibold leading-tight md:text-sm text-lg text-slate-400">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d F Y') }}</p>
                              </div>
                            </div>
                          @else
                            <div class="relative mb-4 mt-0 after:clear-both after:table after:content-['']">
                              <span class="w-5 h-5 text-base bg-slate-100 absolute left-4 z-10 inline-flex -translate-x-1/2 items-center justify-center rounded-full text-center font-semibold">
                                  {{-- <span class="bg-white p-1 rounded-full"></span> --}}
                              </span>
                              <div class="ml-11.252 pt-1.4 lg:max-w-120 relative -top-1.5 w-auto">
                                <h6 class="mb-0 font-semibold leading-normal text-slate-700">{{ $item->judul }}</h6>
                                <p class="mt-1 mb-0 font-semibold leading-tight md:text-sm text-base text-slate-400">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d F Y') }}</p>
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