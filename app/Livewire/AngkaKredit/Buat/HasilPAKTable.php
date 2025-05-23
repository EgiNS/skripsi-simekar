<?php

namespace App\Livewire\AngkaKredit\Buat;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Components\ArrayTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\AngkaKredit;

class HasilPAKTable extends ArrayTableComponent
{
    public array $inputs = [];

    public function mount()
    {
        $this->inputs = session()->pull('selectedProfiles', []);
    }

    public function array(): array
    {
        return $this->inputs;
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id"),
            Column::make("Nama", "nama"),
            Column::make("Jabatan", "jabatan"),
            Column::make("Angka Kredit", "angka_kredit"),
        ];
    }
}
