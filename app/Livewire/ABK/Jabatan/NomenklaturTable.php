<?php

namespace App\Livewire\ABK\Jabatan;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Jabatan;

class NomenklaturTable extends DataTableComponent
{
    protected $model = Jabatan::class;

    public $nama_simpeg, $konversi, $nama_umum;
    public $showModalEdit = false;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->hideIf(true),

            Column::make("Nama Simpeg", "nama_simpeg")
                ->sortable()
                ->searchable(),

            Column::make("Konversi", "konversi")
                ->sortable()
                ->searchable(),

            Column::make("Nama Umum", "nama_umum")
                ->sortable()
                ->searchable(),

            Column::make('Aksi')
                ->label(fn($row) => view('livewire.abk.jabatan.jabatan-action-button', [
                    'data' => $row
                ])),
        ];
    }

    public function openModalEdit($nama_simpeg, $konversi, $nama_umum)
    {
        $this->nama_simpeg = $nama_simpeg;
        $this->konversi = $konversi;
        $this->nama_umum = $nama_umum;
        $this->showModalEdit = true;
    }
}
