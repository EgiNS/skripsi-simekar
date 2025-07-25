<?php

namespace App\Livewire\Mutasi\Usul;

use App\Models\Jabatan;
use App\Models\Profile;
use App\Models\Satker;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\UsulMutasi;
use Carbon\Carbon;
use Rappasoft\LaravelLivewireTables\Traits\WithBulkActions;

class UsulMutasiTable extends DataTableComponent
{
    use WithBulkActions;

    public $showModalEdit = false;
    public $editId, $status;
    public $selectedData = [];

    protected $model = UsulMutasi::class;

    protected $listeners = ['refreshTable' => '$refresh'];

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setBulkActions([
                'simpanSelected' => 'Simulasi Mutasi', // Tambahkan aksi
            ]);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()
                ->hideIf(true),
            Column::make("NIP", "profile.nip")
                ->sortable()
                ->searchable(),
            Column::make("Nama", "profile.nama")
                ->sortable()
                ->searchable(),
            Column::make("Jabatan", "profile.jabatan")
                ->sortable()
                ->searchable(),
            Column::make("Satker Asal", "profile.satker.nama")
                ->sortable()
                ->searchable(),
            Column::make("Jenis Usulan", "jenis")
                ->sortable()
                ->searchable(),
            Column::make("Status", "status")
                ->sortable()
                ->hideIf(true),
            Column::make("Alasan", "alasan")
                ->sortable(),
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
                ->label(fn($row) => $this->tglTindak($row->created_at, $row->updated_at))
                ->sortable(),
            Column::make('Aksi')
                ->label(fn($row) => view('livewire.mutasi.usul.status-action-button', [
                    'id' => $row->id
                ]))
        ];
    }

    public function simpanSelected()
    {
        $this->selectedData = [];

        foreach ($this->getSelected() as $selected) {
            $pegawai = UsulMutasi::where('id', $selected)->first();
            
            if ($pegawai) {
                $this->selectedData[] = [
                    'nama' => Profile::where('nip', $pegawai->nip)->value('nama'),
                    'satker' => Satker::where('nama', $pegawai->satker_tujuan)->value('id'),
                ];
            }
        }
    
        session()->put('selectedData', $this->selectedData);

        $this->dispatch('navigateTo', '/simulasi-pegawai');
    }

    public function showJenis($jenis) 
    {
        if ($jenis ==  1) {
            return '<span class="text-xs px-2 rounded-lg bg-blue-500 text-white">Atas Permintaan Sendiri</span>';
        } elseif ($jenis == 2) {
            return '<span class="text-xs px-2 rounded-lg bg-orange-300 text-white">Alasan Khusus</span>';
        } elseif ($jenis == 3) {
            return '<span class="text-xs px-2 rounded-lg bg-fuchsia-400 text-white">Penugasan</span>';
        }
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

    public function openModalEdit($id)
    {
        $this->editId = $id;
        $this->showModalEdit = true;
    }

    public function saveEdit()
    {
        if ($this->editId) {
            $data = UsulMutasi::find($this->editId);
            $data->status = $this->status;
            $data->updated_at = Carbon::now();
            $data->save();
        }

        $this->showModalEdit = false;
        $this->dispatch('showFlashMessage', 'Status berhasil diperbarui!', 'success');
    }
}
