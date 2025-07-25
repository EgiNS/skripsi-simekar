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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            console.log("Inisialisasi awal dengan event:", @json($events));
            initCalendar(@json($events)); // Inisialisasi awal
        });

        Livewire.on('initCalendar', (data) => {
            console.log("Livewire menerima event baru:", data);
            initCalendar(data[0].events); // Jalankan ulang setelah navigasi
        });

        function initCalendar(events) {
            var calendarEl = document.getElementById('calendar');
            var calendar = new window.FullCalendar.Calendar(calendarEl, {
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
                selectable: true
            });
            calendar.render();

            Livewire.on('eventAdded', (newEvent) => {
                console.log('Event sebelum konversi:', newEvent);

                // console.log(newEvent[0].title)

                // let start = newEvent.start + "T00:00:00"; // Tambahkan waktu agar valid
                // let end = newEvent.end + "T23:59:59"; // Akhiri di akhir hari

                // console.log('Event setelah konversi:', { title: newEvent.title, start, end });

                calendar.addEvent({
                    title: newEvent[0].title,
                    start: newEvent[0].start,
                    end: newEvent[0].end
                });
                console.log('tambah');

                calendar.refetchEvents();
                // calendar.render();
            });
        }

    </script>
</div>
