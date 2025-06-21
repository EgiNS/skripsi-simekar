@push('style')
<style>
    .fc-toolbar-title {
        font-size: 16px !important; /* Ubah sesuai kebutuhan */
        font-weight: normal;
    }
    .fc-button {
        font-size: 12px !important; /* Mengurangi ukuran teks tombol */
        padding: 4px 8px !important; /* Mengurangi padding agar lebih kecil */
        height: auto !important;
    }
    .fc-button-primary {
        background-color: #4A5568 !important; /* Warna gelap */
        border-color: #4A5568 !important;
    }
    .fc-button-primary:hover {
        background-color: #2D3748 !important; /* Warna lebih gelap saat hover */
    }
</style>
@endpush
<div class="flex flex-wrap -mx-3">
    @section('beforeTitle', 'Ujian Kompetensi')
    @section('title', 'Jadwal Ukom')

    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-5 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex flex-row justify-between">
                <p class="font-semibold text-lg text-[#252F40]">Jadwal Ujian Kompetensi</p>
                <button wire:click="$set('showModal', true)"
                    class="bg-gradient-to-br from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-sm font-semibold text-white px-4 py-2 rounded">
                    Tambah Jadwal
                </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-5 overflow-x-auto">
                    <div id="calendar" class="relative z-0" wire:ignore></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div>
        <div x-data x-show="$wire.showModal"
            class="fixed inset-0 flex items-center justify-center bg-transparent backdrop-blur-sm z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96 max-h-96 overflow-y-auto relative">
                <!-- Header -->
                <div class="flex justify-between items-center border-b pb-2 mb-3">
                    <h2 class="text-lg font-semibold">Tambah Jadwal Ukom</h2>
                    <button wire:click="$set('showModal', false)" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>
                
                <!-- Form Input -->
                <form wire:submit.prevent="tambahEvent">
                    <div class="w-full mb-3">
                        <label class="text-xs">Judul</label>
                        <input type="text" wire:model="judul" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                    </div>
                                        
                    <div class="w-full mb-3">
                        <label class="text-xs">Tanggal Mulai</label>
                        <input type="date" wire:model="tanggal_mulai" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                    </div>

                    <div class="w-full mb-3">
                        <label class="text-xs">Tanggal Akhir</label>
                        <input type="date" wire:model="tanggal_akhir" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                    </div>

                    <div class="mt-4 flex justify-end space-x-2 text-sm">
                        <button wire:click="$set('showModal', false)" class="px-3 py-1 font-medium bg-gray-500 text-white rounded hover:bg-gray-600">
                            Batal
                        </button>
                        <button type="submit" class="px-3 py-1 bg-[#CB0C9F] font-medium hover:bg-[#b42f95] text-white rounded">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div 
        x-data="{ open: false }"
        x-on:show-edit-modal.window="open = true"
        x-on:hide-edit-modal.window="open = false"
        x-on:keydown.escape.window="open = false"
        x-show="open"
        x-transition
        class="fixed inset-0 flex items-center justify-center bg-transparent backdrop-blur-sm z-50"
        style="display: none"
    >
        <div class="bg-white p-6 rounded-lg shadow-lg w-96 max-h-96 overflow-y-auto relative">
            <div class="flex justify-between items-center border-b pb-2 mb-3">
                <h2 class="text-lg font-semibold">Edit Jadwal</h2>
                <button @click="open = false" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>

            <div class="w-full mb-3">
                <label class="text-xs">Judul</label>
                <input type="text" wire:model="editTitle"
                    class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
            </div>

            <div class="w-full mb-3">
                <label class="text-xs">Tanggal Mulai</label>
                <input type="date" wire:model="editStart"
                    class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
            </div>

            <div class="w-full mb-3">
                <label class="text-xs">Tanggal Selesai</label>
                <input type="date" wire:model="editEnd"
                    class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
            </div>

            <div class="mt-4 flex justify-between space-x-2 text-sm">
                <div>
                    <button wire:click="deleteEvent" class="px-3 py-1 bg-red-500 font-medium hover:bg-red-600 text-white rounded">
                        Hapus Jadwal
                    </button>
                </div>
                <div>
                    <button @click="open = false" class="px-3 py-1 font-medium bg-gray-500 text-white rounded hover:bg-gray-600">
                        Batal
                    </button>
                    <button wire:click="updateEvent" class="px-3 py-1 bg-[#CB0C9F] font-medium hover:bg-[#b42f95] text-white rounded">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let calendar;

        // Listener hanya didaftarkan SEKALI saat DOM selesai dimuat
        document.addEventListener("DOMContentLoaded", function() {
            initCalendar(@json($events));

            Livewire.on('calendar-refresh', (newEvents) => {
                console.log('Refreshing calendar...');
                if (calendar) {
                    calendar.destroy();
                }
                initCalendar(newEvents[0]);
            });

            Livewire.on('eventAdded', (newEvent) => {
                console.log('Menambahkan event baru ke calendar:', newEvent);

                if (calendar) {
                    calendar.addEvent({
                        title: newEvent[0].title,
                        start: newEvent[0].start,
                        end: newEvent[0].end
                    });

                    calendar.refetchEvents();
                }
            });

            Livewire.on('initCalendar', (data) => {
                console.log("Livewire menerima event baru:", data);
                if (calendar) calendar.destroy();
                initCalendar(data[0].events);
            });
        });

        function initCalendar(events) {
            console.log(events);
            const calendarEl = document.getElementById('calendar');

            calendar = new window.FullCalendar.Calendar(calendarEl, {
                plugins: [
                    window.FullCalendar.dayGridPlugin, 
                    window.FullCalendar.timeGridPlugin,
                    window.FullCalendar.listPlugin,
                    window.FullCalendar.interactionPlugin,
                ],
                locale: window.FullCalendar.idLocale,
                initialView: 'dayGridMonth',
                events: events,
                editable: false,
                selectable: true,

                eventClick: function(info) {
                    const event = info.event;
                    console.log('Klik event:', event);

                    // Kirim ke Livewire
                    Livewire.dispatch('openEditModal', {
                        id: event.id,
                        title: event.title,
                        start: event.startStr,
                        end: event.endStr
                    });
                }
            });

            calendar.render();
        }
    </script>

</div>
