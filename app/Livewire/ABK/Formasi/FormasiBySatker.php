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

        return "<span class='{$warna} font-semibold'>{$eksisting}</span>";
    }

    // Fungsi untuk menghitung jumlah pegawai di tabel Profile
    public function getEksisting($jabatan, $satker)
    {
        return Profile::where(['jabatan'=>$jabatan, 'id_satker'=>$satker])
            ->count();
    }
}
