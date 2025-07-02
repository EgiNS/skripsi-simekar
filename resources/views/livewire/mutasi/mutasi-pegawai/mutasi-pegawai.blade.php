<div class="flex flex-wrap -mx-3">
    @section('beforeTitle', 'Pages')
    @section('title', 'Mutasi')

    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words border-0 border-transparent border-solid rounded-2xl">
            <div class="p-5 mb-5 bg-white rounded-2xl shadow-soft-xl">
                <p class="font-semibold text-lg text-[#252F40]">Pengajuan Mutasi</p>
                <div class="mt-5">
                    <div class="w-full mb-3 grid md:grid-cols-7 grid-cols-1 items-center">
                        <label class="text-sm md:mb-0 mb-1">Jenis Usulan</label>
                        <select wire:model.live="jenis" class="md:col-span-6 text-gray-700 px-3 py-2 text-sm block w-full p-1 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-300 focus:border-fuchsia-300">
                            <option value="">Pilih Jenis Usulan</option>
                            <option value="Atas Permintaan Sendiri">Atas Permintaan Sendiri</option>
                            <option value="Alasan Khusus">Alasan Khusus</option>
                            <option value="Penugasan">Penugasan</option>
                        </select>

                        @error('jenis')
                            <p class="text-red-500 text-xs md:col-span-6">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full mb-3 grid md:grid-cols-7 grid-cols-1 items-center">
                        <label class="text-sm md:mb-0 mb-1">Satker Tujuan</label>
                        <select wire:model.live="provinsi" class="md:col-span-6 text-gray-700 px-3 py-2 text-sm block w-full p-1 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-300 focus:border-fuchsia-300">
                            <option value="">Pilih Satker</option>
                            <option value="10">BPS RI/POLSTAT STIS/PUSDIKLAT</option>
                            @foreach($provinsiList as $prov)
                                <option value="{{ $prov['id'] }}">BPS PROVINSI {{ $prov['nama'] }}</option>
                            @endforeach
                        </select>

                        @error('provinsi')
                            <p class="text-red-500 text-xs md:col-span-6">{{ $message }}</p>
                        @enderror
                    </div>

                    <div 
                        x-data 
                        x-show="$wire.provinsi"
                        x-transition 
                        class="w-full mb-3 grid md:grid-cols-7 grid-cols-1 items-center"
                    >
                        <label class="text-sm"></label>
                        <select wire:model="kabupaten" wire:key="kabupaten-select"
                            class="md:col-span-6 text-gray-700 px-3 py-2 text-sm block w-full p-1 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-300 focus:border-fuchsia-300">
                            <option value="">Pilih Satker</option>
                            @if ($id_prov == '10')
                                @foreach($kabupatenList as $kab)
                                    <option value="{{ $kab['id'] }}">{{ $kab['nama'] }}</option>
                                @endforeach
                            @else
                                @foreach($kabupatenList as $kab)
                                    <option value="{{ $kab['id'] }}">BPS {{ $kab['nama'] }}</option>
                                @endforeach
                            @endif
                        </select>

                        @error('kabupaten')
                            <p class="text-red-500 text-xs md:col-span-6">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full mb-3 grid md:grid-cols-7 grid-cols-1">
                        <label class="text-sm md:mb-0 mb-1">Alasan</label>
                        <Textarea wire:model="alasan" rows="5" class="md:col-span-6 focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow">
                        </Textarea>

                        @error('alasan')
                            <p class="text-red-500 text-xs md:col-span-6">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full flex justify-end mt-3">
                        <button wire:click="createUsul" class="bg-gradient-to-br from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-white px-5 font-semibold py-2 text-sm rounded-lg">Kirim</button>
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