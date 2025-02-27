<?php

namespace App\Livewire\ABK\Detail;

use App\Models\ABK;
use App\Models\Profile;
use App\Exports\DetailABKExport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Traits\WithBulkActions;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class DetailABKTable extends DataTableComponent
{
    use WithBulkActions;
    
    protected $model = ABK::class;

    public $selectedSatker;
    public $selectedJabatan;
    public $pegawaiList = [];
    public $showModal = false;
    public $id_satker = '1100';
    public $jabatan = 'Kepala BPS Provinsi';

    protected $listeners = ['loadPegawai'];

    public function configure(): void
    {
        $this->setPrimaryKey('id')
             ->setBulkActions([
                'exportSelectedXlsx' => 'Export ke Excel',
                ])
             ->setSearchEnabled();
    }

    public function columns(): array
    {
        return [
            Column::make('Kode Satker', 'id_satker')
                ->sortable()
                ->searchable(),

            Column::make('Satker', 'satker.nama')
                ->sortable()
                ->searchable(),

            Column::make('Nama Jabatan', 'jabatan')
                ->sortable()
                ->searchable(),

            Column::make('Formasi', 'formasi')
                ->format(fn($value) => "<span class='font-semibold'>$value</span>")
                ->html(),
            
            Column::make('Eksisting')
                ->label(fn($row, Column $column) => $this->getEksistingLabel($row))
                ->html(),

            // Column::make('Action')
            //     ->label(fn($row) => view('livewire.a-b-k.components.action-button', ['row' => $row]))
            //     ->html(),

            Column::make('Aksi')
            ->label(fn($row) => view('livewire.abk.detail.detail-action-button', [
                'id_satker' => $row->id_satker,
                'jabatan' => $row->jabatan,
                'pegawaiList' => $this->pegawaiList,
            ])),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Satker')
                ->options(
                    ABK::join('satker', 'abk.id_satker', '=', 'satker.id')
                        ->distinct()
                        ->pluck('satker.nama', 'abk.id_satker')
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
            return Excel::download(new DetailABKExport($this->getSelected()), 'DetailABK.xlsx');
        } catch (\Exception $e) {
            Log::error("Gagal ekspor: " . $e->getMessage());
        }
    }

    public function getEksistingLabel($row)
    {
        $eksisting = $this->getEksisting($row->jabatan, $row->id_satker);
        $warna = $eksisting > $row->formasi ? 'text-red-500' : '';

        return "<span class='{$warna} font-semibold'>{$eksisting}</span>";
    }

    // Fungsi untuk menghitung jumlah pegawai di tabel Profile
    public function getEksisting($jabatan, $satker)
    {
        return Profile::where(['jabatan'=>$jabatan, 'id_satker'=>$satker])
            ->count();
    }

    public function loadPegawai($id_satker, $jabatan)
    {
        $this->id_satker = $id_satker;
        $this->jabatan = $jabatan;

        // Ambil pegawai sesuai id_satker & jabatan
        $this->pegawaiList = Profile::where('id_satker', $id_satker)
            ->where('jabatan', $jabatan)
            ->pluck('nama') // Sesuaikan dengan nama kolom di database
            ->toArray();

        // Tampilkan modal
        $this->showModal = true;
    }
}
