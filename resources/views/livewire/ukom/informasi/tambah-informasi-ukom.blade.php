<div class="flex flex-wrap -mx-3">
    @section('beforeTitle', 'Ujian Kompetensi')
    @section('title', 'Informasi Ukom')

    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-5 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <p class="font-semibold text-lg text-[#252F40]">Tambah Informasi Ukom</p>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-5 overflow-x-auto" x-data="{ showUpload: false }">
                    <div class="mb-3 mt-1">
                        <label class="mb-1 text-sm">Judul</label>
                        <input type="text" wire:model='judul' class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                    
                        @error('judul')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    <div wire:ignore>
                        <label class="mb-1 text-sm">Konten</label>
                        <textarea id="editor" wire:model.defer="isi" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" cols="30" rows="15"></textarea>
                    
                        @error('isi')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-3 mb-1">
                        <label class="flex items-center space-x-2 text-sm">
                            <input type="checkbox" x-model="showUpload" class="form-checkbox text-blue-500">
                            <span>Upload Files?</span>
                        </label>
                    </div>
                
                    <!-- Input file hanya muncul jika checkbox dicentang -->
                    <div x-show="showUpload" class="mt-3 text-sm">
                        <label class="block mb-1">Pilih File:</label>
                        <input type="file" multiple wire:model="files" class="block w-full border rounded-lg p-2">
                        @error('files.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="w-full flex justify-end mt-5">
                        <a href="{{ url()->previous() }}" wire:navigate class="bg-gradient-to-br mr-3 from-[#A8B8D8] to-[#627594] hover:scale-105 transition text-sm font-semibold text-white px-4 py-2 rounded">
                            Kembali
                        </a>
                        <button wire:click='createInfo' class="bg-gradient-to-br from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-sm font-semibold text-white px-4 py-2 rounded">
                            Kirim
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function initTinyMCE() {
            tinymce.remove('#editor'); // Hapus instance lama agar tidak duplikat
            tinymce.init({
                selector: '#editor',
                plugins: 'advlist autolink lists link image charmap print preview anchor',
                toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat',
                setup: function (editor) {
                    editor.on('change', function () {
                        @this.set('isi', editor.getContent()); // Kirim data ke Livewire
                    });
                }
            });
        }
    
        document.addEventListener('DOMContentLoaded', function () {
            initTinyMCE();
        });
    
        document.addEventListener('livewire:navigated', function () {
            initTinyMCE(); // Re-inisialisasi TinyMCE setelah navigasi
        });
    </script>
</div>