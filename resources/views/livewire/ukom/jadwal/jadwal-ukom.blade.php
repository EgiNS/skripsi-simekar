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
            <div class="bg-white p-6 rounded-lg w-96 relative z-50">
                <h2 class="text-xl font-bold mb-4">Tambah Event</h2>
                
                <!-- Form Input -->
                <form wire:submit.prevent="tambahEvent">
                    <label class="block mb-2">Judul:</label>
                    <input type="text" wire:model="judul" class="w-full border rounded p-2 mb-2">
                    
                    <label class="block mb-2">Tanggal Mulai:</label>
                    <input type="date" wire:model="tanggal_mulai" class="w-full border rounded p-2 mb-2">
                    
                    <label class="block mb-2">Tanggal Akhir:</label>
                    <input type="date" wire:model="tanggal_akhir" class="w-full border rounded p-2 mb-4">

                    <div class="flex justify-end">
                        <button type="button" wire:click="$set('showModal', false)"
                            class="mr-2 bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
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
                    window.FullCalendar.interactionPlugin
                ],
                initialView: 'dayGridMonth',
                events: events,
                editable: true,
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
