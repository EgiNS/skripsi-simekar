<?php

namespace App\Livewire\Mutasi\MutasiPegawai;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\UsulMutasi;
use Illuminate\Database\Eloquent\Builder;

class RiwayatPengajuanTable extends DataTableComponent
{
    protected $model = UsulMutasi::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder
    {
        return UsulMutasi::where('usul_mutasi.nip', '12345');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()
                ->hideIf(true),
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
        ];
    }
}
