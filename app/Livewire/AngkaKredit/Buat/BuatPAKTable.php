<?php

namespace App\Livewire\AngkaKredit\Buat;

use Carbon\Carbon;
use App\Models\Profile;
use App\Models\AngkaKredit;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Traits\WithBulkActions;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class BuatPAKTable extends DataTableComponent
{
    use WithBulkActions;
    
    public array $kreditMap = [];

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setBulkActions([
                'buatPAK' => 'Buat PAK',
            ]);
            // ->setShouldAlwaysHideBulkActionsDropdownOptionEnabled();
    }

    public function mount()
    {
        // Ambil semua data angka_kredit per nip
        $this->kreditMap = AngkaKredit::where('status', 2)
            ->get()
            ->keyBy('nip')
            ->toArray();
    }

    public function builder(): EloquentBuilder
    {
        return Profile::query()->where('active',1)->orderBy('id_satker', 'asc');
        // dd($this->kreditMap)
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()
                ->hideIf(true),
            Column::make("nip", "nip")
                ->sortable()
                ->searchable()
                ->hideIf(true),
            Column::make("Nama", "nama")
                ->sortable()
                ->searchable(),
            Column::make("Satker", "satker.nama")
                ->sortable()
                ->searchable(),
            Column::make("Jabatan", "jabatan")
                ->sortable()
                ->searchable(),
            Column::make("Golongan", "golongan.nama")
                ->sortable()
                ->searchable(),
            Column::make("TMT Jabatan", "tmt_jab")
                ->sortable()
                ->searchable()
                ->deselected(),
            Column::make("TMT Golongan", "tmt_gol")
                ->sortable()
                ->searchable()
                ->deselected(),
            Column::make("Periode PAK Terakhir")
                ->label(fn($row) => $this->showPeriode($row->nip))
                ->html(),
            Column::make('Angka Kredit')
                ->label(fn($row, Column $column) => $this->getAngkaKredit($row->nip))
                ->html(),
        ];
    }

    public function getAngkaKredit($nip)
    {
        return $this->kreditMap[$nip]['total_ak'] ?? '-';
    }

    public function  showPeriode($nip)
    {
        if (isset($this->kreditMap[$nip])) {
            $ak = $this->kreditMap[$nip];
            $tgl_start = Carbon::parse($ak['periode_start'])->locale('id');
            $tgl_end = Carbon::parse($ak['periode_end'])->locale('id');
    
            $tgl_start->settings(['formatFunction' => 'translatedFormat']);
            $tgl_end->settings(['formatFunction' => 'translatedFormat']);
    
            return "{$tgl_start->format('j M Y')} - {$tgl_end->format('j M Y')}";
        } else {
            return '-';
        }
    }

    public function buatPAK()
    {
        $selected = $this->getSelected();

        if (empty($selected)) {
            return;
        }

        // Ambil data profil yang dipilih
        $profiles = Profile::whereIn('id', $selected)->get();

        // Kirim data ke komponen parent
        $this->dispatch('openModalBuatPAK', $profiles);
    }
}
