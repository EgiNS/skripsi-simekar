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
    public $editId;

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

    public function openModalEdit($id, $nama_simpeg, $konversi, $nama_umum)
    {
        $this->editId = $id;
        $this->nama_simpeg = $nama_simpeg;
        $this->konversi = $konversi;
        $this->nama_umum = $nama_umum;
        $this->showModalEdit = true;
    }

    public function saveEdit()
    {
        $this->validate([
            'konversi'  => 'required|string|max:255',
            'nama_simpeg' => 'required|string|max:255',
            'nama_umum' => 'required|string|max:255',
        ]);

        if ($this->editId) {
            $data = Jabatan::find($this->editId);
            $data->konversi = $this->konversi;
            $data->nama_simpeg = $this->nama_simpeg;
            $data->nama_umum = $this->nama_umum;
            $data->save();
        }

        $this->showModalEdit = false;
        $this->dispatch('showFlashMessage', 'Jabatan berhasil diperbarui!', 'success');
    }
}
