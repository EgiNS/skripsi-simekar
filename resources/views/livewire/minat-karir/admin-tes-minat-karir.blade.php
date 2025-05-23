<div class="flex flex-wrap -mx-3">
    @section('beforeTitle', 'Karier')
    @section('title', 'Tes Minat Karer')

    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-5 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <p class="font-semibold text-lg text-[#252F40]">Tes Minat Karier</p>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-5">
                    <p class="text-sm mb-3">Silakan isikan pernyataan-pernyataan beserta jabatannya yang sesuai</p>
                    <div>
                        @foreach ($rows as $index => $row)
                            <div class="flex space-x-3 mb-3 relative">
                                <textarea 
                                    wire:model="rows.{{ $index }}.soal" 
                                    placeholder="Soal" 
                                    rows="1"
                                    x-data
                                    x-init="$nextTick(() => { $el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px' })"
                                    @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'"
                                    class="w-full resize-none overflow-hidden focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                                ></textarea>                            
                                                                                        
                                <div class="w-full flex items-stretch" x-data="{ open: false }">
                                    <input 
                                        type="text"
                                        wire:model.debounce.300ms="rows.{{ $index }}.jabatan"
                                        wire:keydown.debounce.300ms="suggestJabatan({{ $index }})"
                                        placeholder="Jabatan"
                                        @input="open = true"
                                        @focus="open = true"
                                        @click.away="open = false"
                                        class="h-full focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                                    />
                                    
                                    @if (!empty($suggestions[$index]))
                                        <div 
                                            x-show="open"
                                            class="absolute z-50 bg-white border w-full mt-1 rounded shadow"
                                        >
                                            @foreach ($suggestions[$index] as $suggestion)
                                                <div 
                                                    class="p-2 hover:bg-gray-200 cursor-pointer"
                                                    wire:click="selectJabatan({{ $index }}, '{{ addslashes($suggestion) }}')"
                                                    @click="open = false"
                                                >
                                                    {{ $suggestion }}
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>                                                
                                                        
                                @if (count($rows) > 1)
                                    <button wire:click.prevent="removeRow({{ $index }})" class="bg-red-500 text-white px-2 py-1 rounded">-</button>
                                @endif
                            </div>
                        @endforeach
                    
                        <div class="flex space-x-2 mt-2">
                            <button wire:click.prevent="addRow" class="mt-3 px-4 py-2 bg-gradient-to-br from-[#A8B8D8] to-[#627594] hover:scale-105 transition text-white rounded-lg text-sm font-medium">Tambah Baris</button>
                            <button wire:click.prevent="save" class="mt-3 px-4 py-2 bg-gradient-to-br from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-white rounded-lg text-sm font-medium">Simpan</button>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:load', () => {
            Livewire.hook('message.processed', () => {
                document.querySelectorAll('textarea').forEach(el => {
                    el.style.height = 'auto';
                    el.style.height = el.scrollHeight + 'px';
                });
            });
        });
    </script>
    
    
</div>