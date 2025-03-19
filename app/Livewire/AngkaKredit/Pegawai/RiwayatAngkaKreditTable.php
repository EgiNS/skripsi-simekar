<?php

namespace App\Livewire\AngkaKredit\Pegawai;

use Carbon\Carbon;
use App\Models\AngkaKredit;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Illuminate\Database\Eloquent\Builder;

class RiwayatAngkaKreditTable extends DataTableComponent
{
    protected $model = AngkaKredit::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder
    {
        return AngkaKredit::where('nip', '12345');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()
                ->hideIf(true),
            Column::make("jenis", "jenis")
                ->sortable()
                ->hideIf(true),
            Column::make("Jenis")
                ->html()
                ->label(fn($row) => $this->showJenis($row->jenis))
                ->searchable(),
            Column::make("Angka Kredit", "nilai")
                ->sortable(),
            Column::make("start", "periode_start")
                ->sortable()
                ->hideIf(true),
            Column::make("end", "periode_end")
                ->sortable()
                ->hideIf(true),
            Column::make("link", "link_pak")
                ->sortable()
                ->hideIf(true),
            Column::make("status", "status")
                ->sortable()
                ->hideIf(true),
            Column::make("Periode PAK")
                ->html() // Tambahkan ini agar HTML tidak dianggap teks biasa
                ->label(fn($row) => $this->showPeriode($row->jenis, $row->periode_start, $row->periode_end)),
            Column::make("Link PAK")
                ->html() // Tambahkan ini agar HTML tidak dianggap teks biasa
                ->label(fn($row) => $this->showLink($row->link_pak)),
            Column::make("Tanggal Upload", "created_at")
                ->sortable(),
            Column::make("Status")
                ->html() // Tambahkan ini agar HTML tidak dianggap teks biasa
                ->label(fn($row) => $this->showStatus($row->status))
                ->sortable(),
        ];
    }

    public function  showPeriode($jenis, $start, $end)
    {
        if ($jenis == 3) {
            $text = Carbon::parse($end)->format('Y');
            return $text;
        } elseif ($jenis == 1 || $jenis == 2 || $jenis == 4) {
            // Carbon::setLocale('id');
            
            $tgl_start = Carbon::parse($start)->locale('id');
            $tgl_end = Carbon::parse($end)->locale('id');

            $tgl_start->settings(['formatFunction' => 'translatedFormat']);
            $tgl_end->settings(['formatFunction' => 'translatedFormat']);

            return "{$tgl_start->format('j M Y')} - {$tgl_end->format('j M Y')}";
        }
    }

    public function showLink($link)
    {
        return "<a href='{$link}'>{$link}</a>";
    }

    public function showJenis($jenis)
    {
        if ($jenis == 1) {
            return '<span class="text-xs px-2 rounded-lg bg-blue-500 text-white">Integrasi</span>';
        } elseif ($jenis == 2) {
            return '<span class="text-xs px-2 rounded-lg bg-orange-300 text-white">Praintegrasi</span>';
        } elseif ($jenis == 3) {
            return '<span class="text-xs px-2 rounded-lg bg-fuchsia-400 text-white">Konversi Tahunan</span>';
        } elseif ($jenis == 4) {
            return '<span class="text-xs px-2 rounded-lg bg-teal-500 text-white">Konversi Periodik</span>';
        }
    }

    public function showStatus($status) 
    {  
        if ($status ==  1) {
            return '<span class="text-xs px-2 rounded-lg bg-yellow-400 text-white">Menunggu</span>';
        } elseif ($status == 2) {
            return '<span class="text-xs px-2 rounded-lg bg-green-400 text-white">Diterima</span>';
        } elseif ($status == 3) {
            return '<span class="text-xs px-2 rounded-lg bg-[#F53939] text-white">Ditolak</span>';
        }
    }
}
