@push('style')
    <style>
        .isi ul {
            list-style-type: disc; /* Pastikan bullet list muncul */
            padding-top: 10px;
            padding-left: 30px; /* Beri jarak dari tepi */
        }
        
        .isi ol {
            list-style-type: decimal; /* Pastikan numbering muncul */
            padding-top: 10px;
            padding-left: 30px;
        }

        .isi li {
            margin-bottom: 5px;
        }
    </style>
@endpush

<div class="flex flex-wrap -mx-3">
    @section('beforeTitle', 'Ujian  Kompetensi')
    @section('title', 'Informasi Ukom')

    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-5 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <p class="font-semibold text-lg text-[#252F40]">{{ $info->judul }}</p>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-5 overflow-x-auto">
                    <p class="text-sm text-gray-600 -mt-3">Diposting: {{ $info->created_at }}</p>
                    <div class="mt-4 isi text-sm">
                        {!! $info->isi !!}
                    </div>
                    @if (!empty($info->files))
                        @php
                            $files = json_decode($info->files, true);
                        @endphp

                        @if (count($files) != 0)
                            <div class="mt-4 text-sm">
                                <p class="font-semibold">Lampiran pendukung:</p>
                                <ul class="list-disc ml-5">
                                    @foreach ($files as $file)
                                        <li>
                                            <a href="{{ asset('storage/uploads/' . $file) }}" target="_blank" class="text-blue-500 hover:underline">
                                                {{ $file }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endif
                    <div class="mt-6">
                        <a href="{{ url()->previous() }}" wire:navigate class="bg-gradient-to-br mr-3 from-[#A8B8D8] to-[#627594] hover:scale-105 transition text-sm font-semibold text-white px-4 py-2 rounded-lg">
                            Kembali
                        </a>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>