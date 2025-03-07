<?php

namespace App\Livewire\Mutasi\Kepala;

use Carbon\Carbon;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Traits\WithBulkActions;

class KepalaTable extends DataTableComponent
{
    use WithBulkActions;
    // protected $model = Profile::class;
    public $step = 1;
    public $selectedData = [];
    public $allSatker = [];

    public function configure(): void
    {
        $this->setPrimaryKey('nip')
             ->setBulkActions([
                'simpanSelected' => 'Simulasi Rotasi', // Tambahkan aksi
             ]);
    }

    public function builder(): Builder
    {
        return Profile::where('jabatan', 'Kepala BPS Kabupaten/Kota')->orderBy('tmt_jab', 'asc');
    }

    public function columns(): array
    {
        return [
            Column::make("NIP", "nip")
                ->sortable(),
            Column::make("Nama", "nama")
                ->sortable(),
            Column::make("Jabatan", "jabatan")
                ->sortable(),
            Column::make("Satker", "satker.nama")
                ->sortable(),
            Column::make("Jab", "tmt_jab")
                ->sortable()
                ->hideIf(true),
            Column::make("Lahir", "tgl_lahir")
                ->sortable()
                ->hideIf(true),
            Column::make("Umur") // Kolom khusus untuk masa kerja
                ->label(fn($row) => $this->hitungMasaKerja($row->tgl_lahir, 'umur'))
                ->sortable(),
            Column::make("Masa Kerja Jabatan") // Kolom khusus untuk masa kerja
                ->label(fn($row) => $this->hitungMasaKerja($row->tmt_jab, 'jab'))
                ->sortable(),
        ];
    }

    public function simpanSelected()
    {
        $this->selectedData = [];
        $this->allSatker = [];
        // $this->selectedData = $this->getSelected();

        foreach ($this->getSelected() as $selected) {
            $profile = Profile::where('nip', $selected)->first();

            $umur = Carbon::parse($profile->tgl_lahir)->diff(Carbon::now());

            if (($umur->y) >= 57) {
                $this->dispatch('showFlashMessage', $profile->nama .' tidak bisa dirotasi karena memasuki usia pensiun!', 'error');
                return;
            }

            $this->selectedData[] = [
                'nama' => $profile->nama,
                'nip' => $profile->nip,
                'jabatan' => $profile->jabatan,
                'satker_asal' => $profile->satker->nama,
                'zona' => $profile->satker->zona,
                'tmt_jab' => $this->hitungMasaKerja($profile->tmt_jab, 'jab'),
            ];

            $this->allSatker[] = [
                'id' => $profile->satker->id,
                'nama' => $profile->satker->nama,
                'zona' => $profile->satker->zona,
            ];
        }

        $this->dispatch('ubahStep', 2, $this->selectedData, $this->allSatker);
    }

    private function hitungMasaKerja($tmt, $jenis)
    {
        if (!$tmt) return '-';

        $tmt = Carbon::parse($tmt);
        $sekarang = Carbon::now();
        $selisih = $tmt->diff($sekarang);
        

        if ($jenis == "jab") {
            return "{$selisih->y} tahun {$selisih->m} bulan {$selisih->d} hari";
        } else {
            return "{$selisih->y} tahun {$selisih->m} bulan";
        }
    }
}
