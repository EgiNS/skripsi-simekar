<?php

namespace App\Livewire\Abk\Formasi;

use App\Models\ABK;
use App\Models\Profile;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class FormasiBySatker extends DataTableComponent
{
    protected $model = ABK::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setTdAttributes(function(Column $column, $row, $columnIndex, $rowIndex) {
            if ($columnIndex == 2) {
                return [
                  'default' => false,
                  'class' => 'px-6 py-4 whitespace-nowrap text-sm font-medium dark:text-white text-center',
                ];
            }

            return [
                'default' => false,
                'class' => 'px-6 py-4 whitespace-nowrap text-sm font-medium dark:text-white',
            ];
        });
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->hideIf(true),

            Column::make('Kode Satker', 'id_satker')
                ->sortable()
                ->searchable()
                ->hideIf(true),

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

            Column::make('Selisih')
                ->label(fn($row, Column $column) => $this->getSelisihLabel($row))
                ->html(),
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

    public function getEksistingLabel($row)
    {
        $eksisting = $this->getEksisting($row->jabatan, $row->id_satker);
        $warna = $eksisting > $row->formasi ? 'text-red-500' : '';

        return "<span class='{$warna} font-semibold text-center block'>{$eksisting}</span>";
    }

    // Fungsi untuk menghitung jumlah pegawai di tabel Profile
    public function getEksisting($jabatan, $satker)
    {
        return Profile::where(['jabatan'=>$jabatan, 'id_satker'=>$satker, 'active'=>1])
            ->count();
    }

    public function getSelisihLabel($row)
    {
        $eksisting = $this->getEksisting($row->jabatan, $row->id_satker);
        $warna = $eksisting < $row->formasi ? 'text-green-500' : '';
        $selisih = $row->formasi - $eksisting;
        
        return "<span class='{$warna} block text-center'>{$selisih}</span>";
    }
}
