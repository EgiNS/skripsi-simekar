<?php

namespace App\Livewire\Mutasi\MutasiPegawai;

use App\Models\Profile;
use App\Models\UsulMutasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class RiwayatPengajuanTable extends DataTableComponent
{
    protected $model = UsulMutasi::class;

    protected $listeners = ['refreshTable' => '$refresh'];

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setTdAttributes(function(Column $column, $row, $columnIndex, $rowIndex) {
            if ($columnIndex == 1) {
                return [
                  'default' => false,
                  'class' => 'px-6 py-4 whitespace-nowrap text-sm font-medium dark:text-white',
                ];
            }

            return [
                'default' => false,
                'class' => 'px-6 py-4 whitespace-nowrap text-sm font-medium dark:text-white',
            ];
        });
        // class="text-gray-500 dark:text-gray-400 flex items-center space-x-1 text-left text-xs leading-4 font-medium uppercase tracking-wider group focus:outline-none"

        $this->setThAttributes(function(Column $column) {
            if ($column->isField('alasan')) {
                return [
                  'default' => false,
                  'class' => 'text-red-500 dark:bg-gray-800 dark:text-gray-400 px-6 py-3 text-center text-xs font-medium uppercase tracking-wider w-32',
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
        $user = Profile::where(['username'=>Auth::user()->username, 'active'=>1])->first();
        return UsulMutasi::where('nip', $user->nip);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()
                ->hideIf(true),
            Column::make("Jenis", "jenis")
                ->sortable()
                ->searchable(),
            Column::make("Status", "status")
                ->sortable()
                ->hideIf(true),
            Column::make("Alasan", "alasan")
                ->sortable()
                ->deselected(),
            Column::make("Provinsi Tujuan", "prov_tujuan")
                ->sortable()
                ->searchable(),
            Column::make("Kabupaten Tujuan", "kab_tujuan")
                ->sortable()
                ->searchable(),
            Column::make("Status")
                ->html() // Tambahkan ini agar HTML tidak dianggap teks biasa
                ->label(fn($row) => $this->showStatus($row->status))
                ->sortable(),
            Column::make("Tanggal Pengajuan", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable()
                ->hideIf(true),
            Column::make("Tanggal Tindaklanjut")
                ->html() // Tambahkan ini agar HTML tidak dianggap teks biasa
                ->label(fn($row) => $this->tglTindak($row->updated_at, $row->created_at))
                ->sortable(),
        ];
    }

    public function showStatus($status) 
    {  
        if ($status ==  1) {
            return '<span class="text-xs px-2 rounded-lg bg-yellow-400 text-white">Belum Ditindaklanjuti</span>';
        } elseif ($status == 2) {
            return '<span class="text-xs px-2 rounded-lg bg-green-400 text-white">Sudah Ditindaklanjuti</span>';
        } elseif ($status == 3) {
            return '<span class="text-xs px-2 rounded-lg bg-[#F53939] text-white">Batal</span>';
        }
    }

    public function tglTindak($usul, $tindak) {
        return $usul == $tindak ? '-' : $tindak;
    }
}
