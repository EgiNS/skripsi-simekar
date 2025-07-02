<div class="flex flex-wrap -mx-3">
    @section('beforeTitle', 'Pages')
    @section('title', 'Profile')

    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-5 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex flex-row justify-between">
                <p class="font-semibold text-lg text-[#252F40]">Profil Pegawai</p>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-5 overflow-x-auto">
                    <div class="grid grid-cols-12">
                        <div class="md:col-span-2 col-span-4 text-[#252F40]">
                            Nama
                        </div>
                        <div class="md:col-span-10 col-span-8">
                            : {{ $this->profile->nama }}
                        </div>
                    </div>
                    <div class="grid grid-cols-12">
                        <div class="md:col-span-2 col-span-4 text-[#252F40]">
                            NIP
                        </div>
                        <div class="md:col-span-10 col-span-8">
                            : {{ $this->profile->nip }}
                        </div>
                    </div>
                    <div class="grid grid-cols-12">
                        <div class="md:col-span-2 col-span-4 text-[#252F40]">
                            Jabatan
                        </div>
                        <div class="md:col-span-10 col-span-8">
                            : {{ $this->profile->jabatan }}
                        </div>
                    </div>
                    <div class="grid grid-cols-12">
                        <div class="md:col-span-2 col-span-4 text-[#252F40]">
                            Golongan
                        </div>
                        <div class="md:col-span-10 col-span-8">
                            : {{ $this->profile->golongan->jenis }} ({{ $this->profile->golongan->nama }})
                        </div>
                    </div>
                    <div class="grid grid-cols-12">
                        <div class="md:col-span-2 col-span-4 text-[#252F40]">
                            Satker
                        </div>
                        <div class="md:col-span-10 col-span-8">
                            : {{ $this->profile->satker->nama }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-5 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex flex-row justify-between">
                <p class="font-semibold text-lg text-[#252F40]">Ubah Password</p>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-5 overflow-x-auto">
                    <div class="w-full grid md:grid-cols-7 grid-cols-1 items-center text-sm my-4">
                        <label class="text-slate-700 md:mb-0 mb-1">Password Saat Ini</label>
                        <div class="md:col-span-6 relative w-full">
                            <input type="password" wire:model='pass' class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                            @error('pass')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="w-full grid md:grid-cols-7 grid-cols-1 items-center text-sm my-4">
                        <label class="text-slate-700 md:mb-0 mb-1">Password Baru</label>
                        <div class="md:col-span-6 relative w-full">
                            <input type="password" wire:model='new_pass1' class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                            @error('new_pass1')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="w-full grid md:grid-cols-7 grid-cols-1 items-center text-sm my-4">
                        <label class="text-slate-700 md:mb-0 mb-1">Ulangi Password Baru</label>
                        <div class="md:col-span-6 relative w-full">
                            <input type="password" wire:model='new_pass2' class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                            @error('new_pass2')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="w-full flex justify-end">
                        <button wire:click='editPass' class="bg-gradient-to-br font-medium from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-white px-5 py-2 text-sm rounded-lg">Ubah</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>