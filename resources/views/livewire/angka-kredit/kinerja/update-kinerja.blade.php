<div class="flex flex-wrap -mx-3">
    @section('beforeTitle', 'Angka Kredit')
    @section('title', 'Update Nilai Kinerja')

    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-5 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <p class="font-semibold text-lg text-[#252F40]">Update Nilai Kinerja Pegawai</p>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-5 overflow-x-auto text-sm">
                    <p>Silakan upload file Excel nilai kinerja pegawai terbaru sesuai dengan template berikut ini.</p>
                    {{-- <ol class="list-decimal pl-8 text-[#252F40] flex flex-col flex-wrap max-h-48 mt-2">
                        <li>NIP</li>
                        <li>Nama</li>
                        <li>Nilai Perilaku</li>
                        <li>Nilai Kinerja</li>
                        <li>Predikat Kinerja</li>
                        <li>Tahun</li>
                    </ol> --}}
                    <div class="flex items-center gap-x-2 mt-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 fill-slate-500" viewBox="0 0 384 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M64 0C28.7 0 0 28.7 0 64L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-288-128 0c-17.7 0-32-14.3-32-32L224 0 64 0zM256 0l0 128 128 0L256 0zM155.7 250.2L192 302.1l36.3-51.9c7.6-10.9 22.6-13.5 33.4-5.9s13.5 22.6 5.9 33.4L221.3 344l46.4 66.2c7.6 10.9 5 25.8-5.9 33.4s-25.8 5-33.4-5.9L192 385.8l-36.3 51.9c-7.6 10.9-22.6 13.5-33.4 5.9s-13.5-22.6-5.9-33.4L162.7 344l-46.4-66.2c-7.6-10.9-5-25.8 5.9-33.4s25.8-5 33.4 5.9z"/></svg>
                        <a href="" class="text-blue-500 underline">Template Excel Nilai Kinerja</a>
                    </div>
                    <div class="w-full flex gap-x-4 mt-6" wire:ignore>
                        <input type="file" wire:model.defer="up_file"
                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow">
                    
                        <button wire:click="import" wire:loading.attr="disabled"
                            class="bg-gradient-to-br font-medium from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-white px-5 py-2 text-sm rounded-lg">
                            Impor
                        </button>
                    </div>
                    
                    <div wire:loading wire:target="import" class="text-gray-600 text-sm">Mengimpor data...</div>                    
                </div>
            </div>
        </div>

        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-5 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <p class="font-semibold text-lg text-[#252F40]">Nilai Kerja Pegawai</p>
                        @if (isset($this->latest))
                            <p class="text-sm">Update terakhir: {{ $this->latest->format('d-m-Y H:i') }}</p>
                        @endif
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-5 overflow-x-hidden">
                            <livewire:Angka-Kredit.Kinerja.Kinerja-Table />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>