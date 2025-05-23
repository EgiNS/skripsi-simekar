<?php

namespace App\Livewire\AngkaKredit\Upload;

use Carbon\Carbon;
use App\Models\AngkaKredit;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class MonitoringAngkaKredit extends DataTableComponent
{
    public $status, $editId;
    public $showModalEdit = false;

    protected $model = AngkaKredit::class;

    protected $listeners = ['refreshTable' => '$refresh'];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder
    {
        return AngkaKredit::where('angka_kredit.status', '1');
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
            Column::make("Satker", "profile.satker.nama ")
                ->sortable()
                ->searchable(),
            Column::make("Angka Kredit", "nilai")
                ->sortable(),
            Column::make("Jenis", "jenis")
                ->sortable()
                ->searchable(),
            Column::make("start", "periode_start")
                ->sortable()
                ->hideIf(true),
            Column::make("end", "periode_end")
                ->sortable()
                ->hideIf(true),
            Column::make("link", "link_pak")
                ->sortable()
                ->hideIf(true),
            Column::make("status", "status")
                ->sortable()
                ->hideIf(true),
            Column::make("Periode PAK")
                ->html() // Tambahkan ini agar HTML tidak dianggap teks biasa
                ->label(fn($row) => $this->showPeriode($row->jenis, $row->periode_start, $row->periode_end)),
            Column::make("Link PAK")
                ->html() // Tambahkan ini agar HTML tidak dianggap teks biasa
                ->label(fn($row) => $this->showLink($row->link_pak)),
            Column::make("Tanggal Upload", "created_at")
                ->sortable(),
            Column::make("Status")
                ->html() // Tambahkan ini agar HTML tidak dianggap teks biasa
                ->label(fn($row) => $this->showStatus($row->status))
                ->sortable(),
            Column::make('Aksi')
                ->label(fn($row) => view('livewire.angka-kredit.upload.status-action-button', [
                    'id' => $row->id,
                ]))
        ];
    }

    public function  showPeriode($jenis, $start, $end)
    {
        if ($jenis == 'Konversi Tahunan') {
            $text = Carbon::parse($end)->format('Y');
            return $text;
        } else {
            $tgl_start = Carbon::parse($start)->locale('id');
            $tgl_end = Carbon::parse($end)->locale('id');

            $tgl_start->settings(['formatFunction' => 'translatedFormat']);
            $tgl_end->settings(['formatFunction' => 'translatedFormat']);

            return "{$tgl_start->format('j M Y')} - {$tgl_end->format('j M Y')}";
        }
    }

    public function showLink($link)
    {
        return "<a href='{$link}'>{$link}</a>";
    }

    public function showStatus($status) 
    {  
        if ($status ==  1) {
            return '<span class="text-xs px-2 rounded-lg bg-yellow-400 text-white">Menunggu</span>';
        } elseif ($status == 2) {
            return '<span class="text-xs px-2 rounded-lg bg-green-400 text-white">Diterima</span>';
        } elseif ($status == 3) {
            return '<span class="text-xs px-2 rounded-lg bg-[#F53939] text-white">Ditolak</span>';
        }
    }

    public function openModalEdit($id)
    {
        $this->editId = $id;
        $this->showModalEdit = true;
    }

    public function saveEdit()
    {
        if ($this->editId) {
            $data = AngkaKredit::find($this->editId);
            $data->status = $this->status;
            $data->updated_at = Carbon::now();
            $data->save();
        }

        $this->showModalEdit = false;
        $this->dispatch('showFlashMessage', 'Status berhasil diperbarui!', 'success');
    }
}   
