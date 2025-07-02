<div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
    <div class="p-5 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
        <p class="font-semibold text-lg text-[#252F40]">Informasi Ujian Kompetensi</p>
    </div>

    <div class="p-5 pb-0">
        <input type="text" placeholder="Cari info ..." class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
    </div>

    <div class="flex-auto px-0 pt-0 pb-2">
        <div class="p-5 overflow-x-auto">
            @forelse ($info as $item)
                <div class="w-full bg-[#F8F9FA] rounded-lg p-6 mb-3">
                    <p class="font-semibold text-[#252F40]">{{ $item->judul }}</p>
                    <p class="text-[#252F40] text-sm">Diposting: {{ $item->created_at }}</p>
                    <div class="mt-5">
                        <p class="leading-5 text-sm">{{ \Illuminate\Support\Str::limit(strip_tags($item->isi), 250) }}</p>
                    </div>
                    <div class="w-full flex justify-end mt-4">
                        <a href="{{ route('detail-info', $item->id) }}" wire:navigate
                            class="rounded-lg px-3 py-2 bg-gradient-to-br from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-white text-sm shadow-sm font-semibold">
                            Selengkapnya
                         </a>                                 
                    </div>
                </div>
            @empty
                <p>Belum ada informasi ukom</p>
            @endforelse
        </div>
    </div>
</div>