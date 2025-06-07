<?php

namespace App\Livewire\AngkaKredit\Daftar;

use Carbon\Carbon;
use App\Models\AngkaKredit;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class DaftarAngkaKreditTable extends DataTableComponent
{
    protected $model = AngkaKredit::class;

    public array $nilaiJenjang = [
        'terampil' => 5,
        'mahir' => 12.5,
        'ahli pertama' => 12.5,
        'penyelia' => 25,
        'ahli muda' => 25,
        'ahli madya' => 37.5,
        'ahli utama' => 50,
    ];

    public array $gol_jenjang = [
        'II/c' => ['terampil'],
        'II/d' => ['terampil'],
        'III/a' => ['ahli pertama', 'mahir'],
        'III/b' => ['ahli pertama', 'mahir'],
        'III/c' => ['ahli muda', 'penyelia'],
        'III/d' => ['ahli muda', 'penyelia'],
        'IV/a' => ['ahli madya'],
        'IV/b' => ['ahli madya'],
        'IV/c' => ['ahli madya'],
        'IV/d' => ['ahli utama'],
        'IV/e' => ['ahli utama'],
    ]; 

    public array $ak_jenjang = [
        'II/c' => 40,
        'II/d' => 40,
        'III/a' => 100,
        'III/b' => 100,
        'III/c' => 200,
        'III/d' => 200,
        'IV/a' => 450,
        'IV/b' => 450,
        'IV/c' => 450,
    ];

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setTdAttributes(function(Column $column, $row, $columnIndex, $rowIndex) {
            if ($columnIndex == 2 || $columnIndex == 4 || $columnIndex == 5) {
                return [
                  'default' => false,
                  'class' => 'px-3 py-3 text-sm font-medium dark:text-white text-center',
                ];
            }

            return [
                'default' => false,
                'class' => 'px-3 py-3 text-sm font-medium dark:text-white',
            ];
        });

        $this->setThAttributes(function(Column $column) {
            if ($column->isField('total_ak')) {
                return [
                  'default' => false,
                  'class' => 'text-gray-500 dark:bg-gray-800 dark:text-gray-400 py-3 text-center text-xs font-medium uppercase tracking-wider w-2',
                ];
            }

            return [
                'default' => false,
                'class' => 'text-gray-500 dark:bg-gray-800 dark:text-gray-400 px-6 py-3 text-center text-xs font-medium uppercase tracking-wider w-5',
            ];
        });
    }

    public function builder(): Builder
    {
        return AngkaKredit::where('angka_kredit.status', '2')->orderBy('id', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()
                ->hideIf(true),
            Column::make("NIP", "profile.nip")
                ->sortable()
                ->searchable()
                ->deselected(),
            Column::make("Nama", "profile.nama")
                ->sortable()
                ->searchable(),
            Column::make("Jabatan", "profile.jabatan")
                ->sortable()
                ->searchable(),
            Column::make("Gol", "profile.golongan.nama")
                ->searchable(),
            Column::make("Satker", "profile.satker.nama ")
                ->sortable()
                ->searchable(),
            Column::make("Angka Kredit", "total_ak")
                ->hideIf(true),
            Column::make("Angka Kredit")
                ->html() // Tambahkan ini agar HTML tidak dianggap teks biasa
                ->label(fn($row) => $this->showAk($row->total_ak)),
            Column::make("start", "periode_start")
                ->sortable()
                ->hideIf(true),
            Column::make("end", "periode_end")
                ->sortable()
                ->hideIf(true),
            Column::make("Jenis PAK", "jenis")
                ->sortable()
                ->searchable()
                ->deselected(),
            Column::make("link", "link_pak")
                ->sortable()
                ->hideIf(true),
            Column::make("id_pegawai", "id_pegawai")
                ->sortable()
                ->hideIf(true),
            Column::make("Periode PAK")
                ->html() // Tambahkan ini agar HTML tidak dianggap teks biasa
                ->label(fn($row) => $this->showPeriode($row->jenis, $row->periode_start, $row->periode_end)),
            Column::make("Link PAK")
                ->html() // Tambahkan ini agar HTML tidak dianggap teks biasa
                ->label(fn($row) => $this->showLink($row->link_pak))
                ->deselected(),
            Column::make("Perkiraan Kenaikan Pangkat")
                ->label(fn($row) => $this->naikPangkat($row->total_ak, $row->id_pegawai, $row->periode_end))
                ->sortable(),
            Column::make("Perkiraan Kenaikan Jenjang")
                ->label(fn($row) => $this->naikJenjang($row->total_ak, $row->id_pegawai, $row->periode_end))
                ->sortable(),
            Column::make("Waktu Upload", "created_at")
                ->sortable()
                ->deselected(),
        ];
    }

    public function  showPeriode($jenis, $start, $end)
    {
        $tgl_start = Carbon::parse($start)->locale('id');
        $tgl_end = Carbon::parse($end)->locale('id');

        $tgl_start->settings(['formatFunction' => 'translatedFormat']);
        $tgl_end->settings(['formatFunction' => 'translatedFormat']);

        return "{$tgl_start->format('j M Y')} - {$tgl_end->format('j M Y')}";
    }

    public function showLink($link)
    {
        return "<a href='{$link}'>{$link}</a>";
    }

    public function showAk($ak)
    {
        return rtrim(rtrim(number_format($ak, 3, '.', ''), '0'), '.');
    }

    public function naikPangkat($ak, $id_pegawai, $periode_end)
    {
        $profile = Profile::where('id', $id_pegawai)->first();

        $ak_kp = $profile->golongan->ak_minimal;
        $ak_kj = isset($this->ak_jenjang[$profile->golongan->nama]) ? $this->ak_jenjang[$profile->golongan->nama] : '-';

        $jenjang = $this->gol_jenjang[$profile->golongan->nama][0];
        $ak_tahunan = $this->nilaiJenjang[$jenjang];

        $pred_kp = ceil(($ak_kp - $ak) / $ak_tahunan * 12);

        $perkiraan_kp = Carbon::parse($periode_end)->startOfMonth()->addMonths($pred_kp);

        return $perkiraan_kp->format('F Y');
    }

    public function naikJenjang($ak, $id_pegawai, $periode_end)
    {
        $profile = Profile::where('id', $id_pegawai)->first();

        $ak_kj = isset($this->ak_jenjang[$profile->golongan->nama]) ? $this->ak_jenjang[$profile->golongan->nama] : '-';

        $jenjang = $this->gol_jenjang[$profile->golongan->nama][0];
        $ak_tahunan = $this->nilaiJenjang[$jenjang];

        $pred_kj = ceil(($ak_kj - $ak) / $ak_tahunan * 12);

        $perkiraan_kj = Carbon::parse($periode_end)->startOfMonth()->addMonths($pred_kj);

        return $perkiraan_kj->format('F Y');
    }

    public function showJenis($jenis)
    {
        if ($jenis == 1) {
            return 'Integrasi';
        } elseif ($jenis == 2) {
            return 'Praintegrasi';
        } elseif ($jenis == 3) {
            return 'Konversi Tahunan';
        } elseif ($jenis == 4) {
            return 'Konversi Periodik';
        }
    }
}
