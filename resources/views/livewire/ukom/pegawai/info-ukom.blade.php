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
    @section('beforeTitle', 'Pages')
    @section('title', 'Ujian Kompetensi')

    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-5 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <p class="font-semibold text-lg text-[#252F40]">Jadwal Ujian Kompetensi</p>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-5 overflow-x-auto">
                    <div id="calendar" class="relative z-0" wire:ignore></div>
                </div>
            </div>
        </div>

        <livewire:Ukom.Pegawai.Postingan-Info />

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
        }

    </script>
</div>
