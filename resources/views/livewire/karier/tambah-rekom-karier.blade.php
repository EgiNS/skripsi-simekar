<div class="flex flex-wrap -mx-3">
    @section('beforeTitle', 'Karier')
    @section('title', 'Rekomendasi Karier')

    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-5 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <p class="font-semibold text-lg text-[#252F40]">Tambah Rekomendasi Karier</p>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-5 overflow-x-auto">
                    <div x-data="{ open: false }" class="relative w-full">
                        <div class="text-sm">
                            <label class="mb-1">Rumpun Fungsional</label>
                            <div class="w-full">
                                <input 
                                    type="text" 
                                    wire:model.live="jabatan" 
                                    x-on:input="open = true"
                                    x-on:focus="open = true"
                                    x-on:click.away="open = false"
                                    class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                                />
                                
                                <!-- Dropdown untuk rekomendasi -->
                                <div x-show="open && @this.suggestions.length" class="absolute z-20 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg">
                                    <ul>
                                        @foreach($suggestions as $suggestion)
                                            <li 
                                                class="px-3 py-2 cursor-pointer hover:bg-gray-200" 
                                                x-on:click="@this.selectJabatan('{{ $suggestion }}'); open = false"
                                            >
                                                {{ $suggestion }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @error('jabatan')
                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- <p class="text-[#252F40] text-sm font-medium mt-3 mb-2">Angka Kredit Minimal Kenaikan Jenjang Jabatan (Pola Integrasi)</p>
                        <div class="grid grid-cols-5 gap-x-4 mb-3 mt-1">
                            <div class="">
                                <label class="mb-1 text-sm">Terampil</label>
                                <input type="text" wire:model='angkaKredit.terampil' class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                            
                                @error('angkaKredit.terampil')
                                    <p class="text-red-500 text-xs">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="">
                                <label class="mb-1 text-sm">Mahir</label>
                                <input type="text" wire:model='angkaKredit.mahir' class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                            
                                @error('angkaKredit.mahir')
                                    <p class="text-red-500 text-xs">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="">
                                <label class="mb-1 text-sm">Ahli Pertama</label>
                                <input type="text" wire:model='angkaKredit.ahli_pertama' class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                            
                                @error('angkaKredit.ahli_pertama')
                                    <p class="text-red-500 text-xs">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="">
                                <label class="mb-1 text-sm">Ahli Muda</label>
                                <input type="text" wire:model='angkaKredit.ahli_muda' class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                            
                                @error('angkaKredit.ahli_muda')
                                    <p class="text-red-500 text-xs">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="">
                                <label class="mb-1 text-sm">Ahli Madya</label>
                                <input type="text" wire:model='angkaKredit.ahli_madya' class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                            
                                @error('angkaKredit.ahli_madya')
                                    <p class="text-red-500 text-xs">{{ $message }}</p>
                                @enderror
                            </div>
                        </div> --}}
                        
                        <div class="mb-3 mt-2">
                            <label class="mb-1 text-sm flex items-center justify-between">
                                <span>Syarat</span>
                                <button type="button" wire:click="addSyarat" class="text-white font-medium bg-gray-500 hover:bg-gray-600 px-2 py-1 rounded-lg text-xs">+</button>
                            </label>
                        
                            @foreach ($syaratList as $index => $syarat)
                                <div class="flex items-center mb-2 space-x-2">
                                    <input type="text" wire:model="syaratList.{{ $index }}"
                                        class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                        
                                    @if(count($syaratList) > 1)
                                        <button type="button" wire:click="removeSyarat({{ $index }})" class="text-gray-700 font-medium border border-solid border-gray-300 px-2 py-1 rounded-lg">-</button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        
                        <div wire:ignore>
                            <label class="mb-1 text-sm">Rekomendasi</label>
                            <textarea id="editor" wire:model.defer="rekomendasi" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" cols="30" rows="15"></textarea>
                        
                            @error('rekomendasi')
                                <p class="text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="w-full flex justify-end mt-5">
                        <a href="{{ url()->previous() }}" wire:navigate class="bg-gradient-to-br mr-3 from-[#A8B8D8] to-[#627594] hover:scale-105 transition text-sm font-semibold text-white px-4 py-2 rounded-lg">
                            Kembali
                        </a>
                        <button wire:click='create' class="bg-gradient-to-br from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-sm font-semibold text-white px-4 py-2 rounded-lg">
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
                        @this.set('rekomendasi', editor.getContent()); // Kirim data ke Livewire
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