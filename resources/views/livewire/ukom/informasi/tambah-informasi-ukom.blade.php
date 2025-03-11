<div class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-5 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <p class="font-semibold text-lg text-[#252F40]">Tambah Informasi Ukom</p>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-5 overflow-x-auto">
                    <div class="mb-3 mt-1">
                        <label class="mb-1">Judul</label>
                        <input type="text" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                    </div>
                    <div>
                        <label class="mb-1">Konten</label>
                        <textarea class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" cols="30" rows="15"></textarea>
                    </div>
                    <div class="w-full flex justify-end mt-5">
                        <a href="{{ url()->previous() }}" wire:navigate class="bg-gradient-to-br mr-3 from-[#A8B8D8] to-[#627594] hover:scale-105 transition text-sm font-semibold text-white px-4 py-2 rounded">
                            Kembali
                        </a>
                        <button class="bg-gradient-to-br from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-sm font-semibold text-white px-4 py-2 rounded">
                            Kirim
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>