<?php

namespace App\Livewire\Abk\Status;

use App\Models\ABK;
use App\Models\Satker;
use App\Models\Jabatan;
use App\Models\Profile;
use App\Exports\TabelABKExport;
use Maatwebsite\Excel\Facades\Excel;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Traits\WithBulkActions;

class StatusABKTable extends DataTableComponent
{
    use WithBulkActions;
    
    protected $model = Satker::class;
    public array $namaUmumList = [];
    public string $judulTabel = 'Status Ketersediaan Pegawai';

    public function mount()
    {
        // Ambil semua nama umum yang unik dari tabel Jabatan
        $this->namaUmumList = Jabatan::distinct()->pluck('nama_umum')->toArray();
    }

    public function query()
    {
        return Satker::query()->select('id', 'nama'); // Hanya ambil id dan nama satker
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
             ->setColumnSelectStatus(true) // Aktifkan fitur pilih kolom
             ->setSearchEnabled() // Aktifkan fitur pencarian
             ->setBulkActions([
                'exportSelectedXlsx' => 'Export ke Excel',
             ]);

        $this->setThAttributes(function(Column $column) {
            return [
                'default' => false,
                'class' => 'text-gray-500 dark:bg-gray-800 dark:text-gray-400 px-3 py-3 text-center text-xs font-medium uppercase tracking-wider w-5',
            ];
        });
    }

    public function columns(): array
    {
        $columns = [
            Column::make('Kode', 'id')
                ->sortable()
                ->searchable()
                ->hideIf(true), 
            Column::make('Satuan Kerja', 'nama')
                ->sortable()
                ->searchable(),
        ];
    
        foreach ($this->namaUmumList as $namaUmum) {
            $columns[] = Column::make($namaUmum)
                ->html()
                ->setLabelAttributes(['class' => 'text-center block'])
                ->label(fn($row) => $this->hitungSelisih($row->id, $namaUmum));
        }
    
        return $columns;
    }

    public function exportSelectedXlsx()
    {
        return Excel::download(
            new TabelABKExport(
                Satker::whereIn('id', $this->getSelected())->get()->toArray(), 
                $this->namaUmumList
            ), 
            'tabel_abk.xlsx'
        );
    }

    public function hitungSelisih($id_satker, $nama_umum)
    {
        // Cari semua jabatan dengan nama_umum yang sesuai
        $jabatanKonversiList = Jabatan::where('nama_umum', $nama_umum)
            ->pluck('konversi'); // Ambil daftar konversi sebagai array

        // Hitung jumlah pegawai berdasarkan konversi jabatan dan id_satker
        $jumlahPegawai = Profile::whereIn('jabatan', $jabatanKonversiList)
            ->where(['id_satker'=>$id_satker, 'active'=>1])
            ->count();

        // Hitung jumlah formasi berdasarkan konversi jabatan dan id_satker
        $jumlahFormasi = ABK::whereIn('jabatan', $jabatanKonversiList)
            ->where('id_satker', $id_satker)
            ->sum('formasi');

        // Jika tidak ada formasi, tampilkan "-"
        if ($jumlahFormasi == 0) {
            return "<span class='text-center block'>-</span>";
        }

        // Jika tidak ada pegawai, tampilkan selisih sebagai -jumlahFormasi
        if ($jumlahPegawai == 0) {
            return "<span class='text-center block'>-$jumlahFormasi</span>";
        }

        // Hitung selisih pegawai - formasi
        $selisih = $jumlahPegawai - $jumlahFormasi;

        return $selisih == 0 
            ? "<span class='text-center block'>0</span>"
            : ($selisih > 0 
                ? "<span class='text-red-500 text-center block'>+$selisih</span>" 
                : "<span class='text-center block'>$selisih</span>");
    }
}
