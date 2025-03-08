<?php

namespace App\Livewire\Mutasi\Usul;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\UsulMutasi;

class UsulMutasiTable extends DataTableComponent
{
    public $nama;
    public $showModalEdit = false;

    protected $model = UsulMutasi::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
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
            Column::make("Jenis", "jenis")
                ->sortable()
                ->hideIf(true),
            Column::make("Status", "status")
                ->sortable()
                ->hideIf(true),
            Column::make("Jenis Usulan")
                ->html() // Tambahkan ini agar HTML tidak dianggap teks biasa
                ->label(fn($row) => $this->showJenis($row->jenis)),
            Column::make("Alasan", "alasan")
                ->sortable(),
            Column::make("Satker Tujuan", "satker.nama")
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
            Column::make('Aksi')
                ->label(fn($row) => view('livewire.mutasi.usul.status-action-button', [
                    'nama' => $row->nama,
                ]))
        ];
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

    public function openModalEdit($nama)
    {
        $this->nama = $nama;
        $this->showModalEdit = true;
    }
}
