<?php

namespace App\Livewire\AngkaKredit\Daftar;

use Carbon\Carbon;
use App\Models\AngkaKredit;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class DaftarAngkaKreditTable extends DataTableComponent
{
    protected $model = AngkaKredit::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()
                ->hideIf(true),
            Column::make("NIP", "profile.nip")
                ->sortable()
                ->searchable(),
            Column::make("Nama", "profile.nama")
                ->sortable()
                ->searchable(),
            Column::make("Jabatan", "profile.jabatan")
                ->sortable()
                ->searchable(),
            Column::make("Satker", "profile.satker.nama ")
                ->sortable()
                ->searchable(),
            Column::make("Angka Kredit", "nilai")
                ->sortable(),
            Column::make("start", "periode_start")
                ->sortable()
                ->hideIf(true),
            Column::make("end", "periode_end")
                ->sortable()
                ->hideIf(true),
            Column::make("jenis", "jenis")
                ->sortable()
                ->hideIf(true),
            Column::make("link", "link_pak")
                ->sortable()
                ->hideIf(true),
            Column::make("Periode PAK")
                ->html() // Tambahkan ini agar HTML tidak dianggap teks biasa
                ->label(fn($row) => $this->showPeriode($row->jenis, $row->periode_start, $row->periode_end)),
            Column::make("Link PAK")
                ->html() // Tambahkan ini agar HTML tidak dianggap teks biasa
                ->label(fn($row) => $this->showLink($row->link_pak)),
            Column::make("Perkiraan Kenaikan Pangkat")
                ->label(fn() => '2 tahun 3 bulan'),
            Column::make("Perkiraan Kenaikan Jenjang")
                ->label(fn() => '1 tahun 3 bulan'),
            Column::make("Waktu Upload", "created_at")
                ->sortable(),
        ];
    }

    public function  showPeriode($jenis, $start, $end)
    {
        if ($jenis == 1) {
            $text = Carbon::parse($end)->format('Y');
            return $text;
        } elseif ($jenis == 2 || $jenis == 3 || $jenis == 4) {
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
}
