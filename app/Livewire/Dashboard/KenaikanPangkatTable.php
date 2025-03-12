<?php

namespace App\Livewire\Dashboard;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\AngkaKredit;
use App\Models\Satker;
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Builder;

class KenaikanPangkatTable extends DataTableComponent
{
    protected $model = Satker::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']; 

        $columns = [
            Column::make("Id", "id")->sortable(),
            Column::make("Created at", "created_at")->sortable(),
            Column::make("Updated at", "updated_at")->sortable(),
        ];

        // Looping untuk membuat kolom bulan secara dinamis
        foreach ($months as $index => $month) {
            $columns[] = Column::make($month)
                ->label(fn($row) => $this->getDataByMonth($row, $index + 1));
        }

        return $columns;
    }

    public function getDataByMonth($row, $month)
    {
        // Contoh logika: Ambil jumlah data dari tabel berdasarkan bulan
        return 3;
    }
}
