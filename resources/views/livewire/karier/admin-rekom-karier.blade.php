<div class="flex flex-wrap -mx-3">
    @section('beforeTitle', 'Karier')
    @section('title', 'Rekomendasi Karier')

    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-5 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex flex-row justify-between w-full">
                <p class="font-semibold text-lg text-[#252F40]">Rekomendasi Karier</p>
                <a href="{{ route('tambah-rekom-karier') }}" wire:navigate class="cursor-pointer bg-gradient-to-br from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-sm font-semibold text-white px-4 py-2 rounded-lg">
                Tambah
                </a>
            </div>

            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-5 grid grid-cols-4 space-x-3">
                    @forelse ($all as $item)
                        <div class="rounded-xl shadow-md p-3">
                            <p class="font-semibold mb-2 text-base">{{ $item->jabatan }}</p>
                            <div class="flex justify-end">
                                <a href="{{ route('edit-rekom-karier', $item->id) }}" wire:navigate class="text-xs hover:text-[#CB0C9F] self-end hover:underline">Selengkapnya →</a>
                            </div>
                        </div>
                    @empty
                        <p>Belum ada data</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>