<?php

namespace App\Livewire\AngkaKredit\Kinerja;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\NilaiKinerja;

class KinerjaTable extends DataTableComponent
{
    protected $model = NilaiKinerja::class;

    public $nama, $nilai_perilaku, $nilai_kinerja, $predikat, $tahun;
    public $showModalEdit = false;
    public $editId;

    protected $listeners = ['refreshTable' => '$refresh'];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->hideIf(true)
                ->sortable(),
            Column::make("NIP", "nip")
                ->sortable(),
            Column::make("Nama", "nama")
                ->sortable(),
            Column::make("Nilai Perilaku", "nilai_perilaku")
                ->sortable(),
            Column::make("Nilai Kinerja", "nilai_kinerja")
                ->sortable(),
            Column::make("Predikat", "predikat")
                ->sortable(),
            Column::make("Tahun", "tahun")
                ->sortable(),
            Column::make('Aksi')
                ->label(fn($row) => view('livewire.angka-kredit.kinerja.edit-action-button', [
                    'data' => $row
                ])),
        ];
    }

    public function openModalEdit($id, $nama, $nilai_perilaku, $nilai_kinerja, $predikat, $tahun)
    {
        $this->editId = $id;
        $this->nama = $nama;
        $this->nilai_perilaku = $nilai_perilaku;
        $this->nilai_kinerja = $nilai_kinerja;
        $this->predikat = $predikat;
        $this->tahun = $tahun;
        $this->showModalEdit = true;
    }

    public function saveEdit()
    {
        $this->validate([
            'nilai_perilaku'  => 'required',
            'nilai_kinerja' => 'required',
            'predikat' => 'required',
            'tahun' => 'required',
        ]);

        if ($this->editId) {
            $data = NilaiKinerja::find($this->editId);
            $data->nilai_perilaku = number_format((float) str_replace(',', '.', $this->nilai_perilaku), 3, '.', '');
            $data->nilai_kinerja  = number_format((float) str_replace(',', '.', $this->nilai_kinerja), 3, '.', '');
            $data->predikat = $this->predikat;
            $data->tahun = $this->tahun;
            $data->save();
        }

        $this->showModalEdit = false;
        $this->dispatch('showFlashMessage', 'Nilai kinerja berhasil diperbarui!', 'success');
    }
}
