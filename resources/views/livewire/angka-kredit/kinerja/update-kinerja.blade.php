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
                    <p>Silakan upload file Excel nilai kinerja pegawai terbaru. Pastikan urutan kolom pada file adalah sebagai berikut:</p>
                    <ol class="list-decimal pl-8 text-[#252F40] flex flex-col flex-wrap max-h-48 mt-2">
                        <li>NIP</li>
                        <li>Nama</li>
                        <li>Nilai Perilaku</li>
                        <li>Nilai Kinerja</li>
                        <li>Predikat Kinerja</li>
                        <li>Tahun</li>
                    </ol>
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