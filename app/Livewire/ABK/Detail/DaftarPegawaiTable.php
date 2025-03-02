<?php

namespace App\Livewire\ABK\Detail;

use Carbon\Carbon;
use App\Models\Profile;
use Illuminate\Support\Facades\Log;
use App\Exports\DaftarPegawaiExport;
use Maatwebsite\Excel\Facades\Excel;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Traits\WithBulkActions;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class DaftarPegawaiTable extends DataTableComponent
{
    use WithBulkActions;
    
    protected $model = Profile::class;

    public function configure(): void
    {
        $this->setPrimaryKey('nip')
             ->setBulkActions([
                'exportSelectedXlsx' => 'Export ke Excel',
                ])
             ->setSearchEnabled();
    }

    public function query()
    {
        return Profile::query()->orderBy('id_satker', 'asc');
    }

    public function columns(): array
    {
        return [
            Column::make("NIP", "nip")
                ->sortable()
                ->searchable(),
            Column::make("Nama", "nama")
                ->sortable()
                ->searchable(),
            Column::make("Kode Org", "kode_org")
                ->sortable()
                ->searchable()
                ->deselected(),
            Column::make("Jabatan", "jabatan")
                ->sortable()
                ->searchable(),
            Column::make("Satker", "satker.nama")
                ->sortable()
                ->searchable(),
            Column::make("TMT Jabatan", "tmt_jab")
                ->sortable()
                ->searchable()
                ->deselected(),
            Column::make("Masa Kerja Jabatan") // Kolom khusus untuk masa kerja
                ->label(fn($row) => $this->hitungMasaKerja($row->tmt_jab))
                ->sortable()
                ->deselected(),
            Column::make("Golongan Akhir", "golongan.nama")
                ->sortable()
                ->searchable()
                ->deselected(),
            Column::make("TMT Golongan", "tmt_gol")
                ->sortable()
                ->searchable()
                ->deselected(),
            Column::make("Status", "status")
                ->sortable()
                ->searchable()
                ->deselected(),
            Column::make("Pendidikan (SK)", "pendidikan")
                ->sortable()
                ->searchable()
                ->deselected(),
            Column::make("Tanggal Ijazah", "tgl_ijazah")
                ->sortable()
                ->searchable()
                ->deselected(),
            Column::make("TMT CPNS", "tmt_cpns")
                ->sortable()
                ->searchable()
                ->deselected(),
            Column::make("Masa Kerja Keseluruhan") // Kolom khusus untuk masa kerja
                ->label(fn($row) => $this->hitungMasaKerja($row->tmt_cpns))
                ->sortable()
                ->deselected(),
            Column::make("Tempat Lahir", "tempat_lahir")
                ->sortable()
                ->searchable()
                ->deselected(),
            Column::make("Tanggal Lahir", "tgl_lahir")
                ->sortable()
                ->searchable()
                ->deselected(),
            Column::make("Jenis Kelamin", "jk")
                ->sortable()
                ->searchable()
                ->deselected(),
            Column::make("Agama", "agama")
                ->sortable()
                ->searchable()
                ->deselected(),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Satker')
                ->options(
                    Profile::join('satker', 'profile.id_satker', '=', 'satker.id')
                        ->distinct()
                        ->pluck('satker.nama', 'profile.id_satker')
                        ->toArray()
                )
                ->filter(function (Builder $query, $value) {
                    if ($value) {
                        $query->where('id_satker', $value);
                    }
                }),
        ];
    }

    public function exportSelectedXlsx()
    {
        if (!$this->getSelected()) {
            return;
        }
        
        try {
            return Excel::download(new DaftarPegawaiExport($this->getSelected()), 'DaftarPegawai.xlsx');
        } catch (\Exception $e) {
            Log::error("Gagal ekspor: " . $e->getMessage());
        }
    }

    private function hitungMasaKerja($tgl)
    {
        if (!$tgl) return '-';

        $tmt = Carbon::parse($tgl);
        $sekarang = Carbon::now();
        $selisih = $tmt->diff($sekarang);

        return "{$selisih->y} tahun {$selisih->m} bulan {$selisih->d} hari";
    }
}
