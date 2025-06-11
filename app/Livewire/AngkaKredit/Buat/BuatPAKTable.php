<?php

namespace App\Livewire\AngkaKredit\Buat;

use Carbon\Carbon;
use App\Models\Profile;
use App\Models\AngkaKredit;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Traits\WithBulkActions;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

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

    public function filters(): array
    {
        return [
            SelectFilter::make('Jenis Jabatan')
                ->options([
                    '' => 'Semua',
                    'Fungsional' => 'Fungsional',
                    'Struktural' => 'Struktural',
                    'Pelaksana'  => 'Pelaksana',
                ])
                ->filter(function (Builder $query, $value) {
                    if ($value) {
                        $query->where(function ($q) use ($value) {
                            if ($value === 'Struktural') {
                                $q->whereRaw("LOWER(jabatan) LIKE ?", ['%kepala%']);
                            }

                            if ($value === 'Fungsional') {
                                $q->where('id_golongan', '!=', 14)
                                  ->where(function ($sub) {
                                    $sub->whereRaw("LOWER(jabatan) LIKE '%terampil%'")
                                        ->orWhereRaw("LOWER(jabatan) LIKE '%mahir%'")
                                        ->orWhereRaw("LOWER(jabatan) LIKE '%penyelia%'")
                                        ->orWhereRaw("LOWER(jabatan) LIKE '%ahli pertama%'")
                                        ->orWhereRaw("LOWER(jabatan) LIKE '%ahli muda%'")
                                        ->orWhereRaw("LOWER(jabatan) LIKE '%ahli madya%'")
                                        ->orWhereRaw("LOWER(jabatan) LIKE '%ahli utama%'");
                                });
                            }

                            if ($value === 'Pelaksana') {
                                $q->where('id_golongan', '!=', 14)
                                    ->whereRaw("LOWER(jabatan) NOT LIKE '%terampil%'")
                                    ->whereRaw("LOWER(jabatan) NOT LIKE '%mahir%'")
                                    ->whereRaw("LOWER(jabatan) NOT LIKE '%penyelia%'")
                                    ->whereRaw("LOWER(jabatan) NOT LIKE '%ahli pertama%'")
                                    ->whereRaw("LOWER(jabatan) NOT LIKE '%ahli muda%'")
                                    ->whereRaw("LOWER(jabatan) NOT LIKE '%ahli madya%'")
                                    ->whereRaw("LOWER(jabatan) NOT LIKE '%ahli utama%'")
                                    ->whereRaw("LOWER(jabatan) NOT LIKE '%kepala%'");
                            }
                        });
                    }
                }),
        ];
    }

    public function getAngkaKredit($nip)
    {
        return isset($this->kreditMap[$nip]['total_ak'])
            ? rtrim(rtrim(number_format($this->kreditMap[$nip]['total_ak'], 3, '.', ''), '0'), '.')
            : '-';
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
