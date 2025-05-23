<div class="flex flex-wrap -mx-3">
    @section('beforeTitle', 'ABK')
    @section('title', 'Status ABK')

    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-5 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <p class="font-semibold text-lg text-[#252F40]">Status Ketersediaan Formasi</p>
                <p class="text-sm">Berdasarkan Perka BPS No.182 tahun 2024</p>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-5 overflow-hidden">
                    <livewire:ABK.Status.Status-ABK-Table />
                </div>
            </div>
        </div>
    </div>
</div>