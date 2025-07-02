<div class="flex flex-wrap -mx-3">
    @section('beforeTitle', 'Ujian Kompetensi')
    @section('title', 'Informasi Ukom')

    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-5 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex flex-row justify-between w-full">
                <p class="font-semibold text-lg text-[#252F40]">Informasi Ujian Kompetensi</p>
                <a href="{{ route('tambah-info-ukom') }}" wire:navigate class="cursor-pointer bg-gradient-to-br from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-sm font-semibold text-white px-4 py-2 rounded-lg">
                Tambah
                </a>
            </div>

            <div class="p-5 pb-0">
                <input type="text" placeholder="Cari info ..." class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
            </div>

            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-5 overflow-x-auto">
                    @forelse ($info as $item)
                        <div class="w-full bg-[#F8F9FA] rounded-lg p-6 mb-3">
                            <div class="flex flex-row justify-between w-full content-center items-center">
                                <div>
                                    <p class="font-semibold text-[#252F40]">{{ $item->judul }}</p>
                                    <p class="text-[#252F40] text-sm">Diposting: {{ $item->created_at }}</p>
                                </div>
                                <div>
                                    <span wire:click="openModalDelete('{{ $item->id }}')" class="cursor-pointer text-xs text-[#EA0606] font-semibold mr-4">HAPUS</span>
                                    <a href="{{ route('edit-info-ukom', $item->id) }}" wire:navigate class="text-xs text-[#252F40] font-semibold cursor-pointer">EDIT</a>
                                </div>
                            </div>
                            <div class="mt-5">
                                <p class="leading-5 text-sm">{{ \Illuminate\Support\Str::limit(strip_tags($item->isi), 250) }}</p>
                            </div>
                            <div class="w-full flex justify-end mt-4">
                                <a href="{{ route('detail-info', $item->id) }}" wire:navigate
                                    class="rounded-lg px-3 py-2 bg-[#17C1E8] hover:bg-[#37b1cd] transition text-white text-sm shadow-sm font-semibold">
                                    Selengkapnya
                                 </a>                                 
                            </div>
                        </div>

                        <div x-data="{ open: @entangle('showModalDelete') }" wire:key="hapusId">
                            <div x-show="open" class="fixed z-50 inset-0 flex items-center justify-center bg-transparent backdrop-blur-sm">
                                <div class="bg-white p-6 rounded-lg shadow-lg w-96 max-h-72">
                                    <div class="flex flex-row justify-between mb-4 border-b-2 pb-2">
                                        <h2 class="text-lg font-semibold">Hapus Postingan</h2> <hr>
                                        <button @click="open = false" class="text-gray-500 hover:text-gray-700">&times;</button>
                                    </div>
                                    
                                    <p class="break-words whitespace-normal">Apakah Anda yakin menghapus postingan ini?</p>
                    
                                    <div class="mt-4 flex justify-end space-x-2">
                                        <button @click="open = false" class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600">
                                            Batal
                                        </button>
                                        <button wire:click="delete" class="px-3 py-1 bg-[#F53939] text-white rounded hover:bg-[#e83e3e]">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>Belum ada informasi ukom</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>