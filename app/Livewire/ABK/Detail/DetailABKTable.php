<?php

namespace App\Livewire\ABK\Detail;

use App\Models\ABK;
use App\Models\Profile;
use App\Exports\DetailABKExport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Traits\WithBulkActions;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class DetailABKTable extends DataTableComponent
{
    use WithBulkActions;
    
    protected $model = ABK::class;

    public $selectedSatker;
    public $selectedJabatan;
    public $pegawaiList = [];
    public $showModalInfo = false;
    public $showModalEdit = false;
    public $showModalDelete = false;
    public $id_satker, $jabatan, $nama_satker;
    public $formasi, $editId, $hapusId;

    protected $rules = [
        'formasi' => 'required|integer',
    ];

    protected $listeners = ['loadPegawai'];

    public function configure(): void
    {
        $this->setPrimaryKey('id')
             ->setBulkActions([
                'exportSelectedXlsx' => 'Export ke Excel',
                ])
             ->setSearchEnabled()
             ->setFooterTrAttributes(function($rows) {
                    return ['class' => 'text-[#CB0C9F]'];
                });
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->hideIf(true),

            Column::make('Kode Satker', 'id_satker')
                ->sortable()
                ->searchable()
                ->hideIf(true),

            Column::make('Satker', 'satker.nama')
                ->sortable()
                ->searchable()
                ->footer(function($rows) {
                    return 'Subtotal Pegawai';
                }),

            Column::make('Nama Jabatan', 'jabatan')
                ->sortable()
                ->searchable(),

            Column::make('Formasi', 'formasi')  
                ->format(fn($value) => '<span class="block text-center">'.$value.'</span>')
                ->html()
                ->footer(function($rows) {
                    return '<span class="block text-center">'.$rows->sum('formasi').'</span>';
                }),
            
            Column::make('Eksisting')
                ->label(fn($row, Column $column) => $this->getEksistingLabel($row))
                ->html()
                ->footer(function($rows) {
                    return '<span class="block text-center">'.$rows->reduce(fn($total, $row) => $total + $this->getEksisting($row->jabatan, $row->id_satker), 0).'</span>';
                }),

            Column::make('Selisih')
                ->label(fn($row, Column $column) => $this->getSelisihLabel($row))
                ->html(),

            // Column::make('Action')
            //     ->label(fn($row) => view('livewire.a-b-k.components.action-button', ['row' => $row]))
            //     ->html(),

            Column::make('Aksi')
            ->setLabelAttributes(['class' => 'block text-center'])
            ->label(fn($row) => view('livewire.abk.detail.detail-action-button', [
                'id' => $row->id,
                'id_satker' => $row->id_satker,
                'jabatan' => $row->jabatan,
                'nama_satker' => $row->satker->nama,
                'formasi' => $row->formasi,
                'pegawaiList' => $this->pegawaiList,
            ])),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Satker')
                ->options(
                    ABK::join('satker', 'abk.id_satker', '=', 'satker.id')
                        ->distinct()
                        ->pluck('satker.nama', 'abk.id_satker')
                        ->toArray()
                )
                ->filter(function (Builder $query, $value) {
                    if ($value) {
                        $query->where('id_satker', $value);
                    }
                }),
        ];
    }

    public function exportSelectedXlsx()
    {
        if (!$this->getSelected()) {
            return;
        }

        try {
            return Excel::download(new DetailABKExport($this->getSelected()), 'DetailABK.xlsx');
        } catch (\Exception $e) {
            Log::error("Gagal ekspor: " . $e->getMessage());
        }
    }

    public function getEksistingLabel($row)
    {
        $eksisting = $this->getEksisting($row->jabatan, $row->id_satker);
        $warna = $eksisting > $row->formasi ? 'text-red-500' : '';

        return "<span class='{$warna} block text-center'>{$eksisting}</span>";
    }

    // Fungsi untuk menghitung jumlah pegawai di tabel Profile
    public function getEksisting($jabatan, $satker)
    {
        return Profile::where(['jabatan'=>$jabatan, 'id_satker'=>$satker, 'active'=>1])
            ->count();
    }

    public function getSelisihLabel($row)
    {
        $eksisting = $this->getEksisting($row->jabatan, $row->id_satker);
        $warna = $eksisting < $row->formasi ? 'text-green-500' : '';
        $selisih = $row->formasi - $eksisting;
        
        return "<span class='{$warna} block text-center'>{$selisih}</span>";
    }

    public function loadPegawai($id_satker, $jabatan)
    {
        $this->id_satker = $id_satker;
        $this->jabatan = $jabatan;

        // Ambil pegawai sesuai id_satker & jabatan
        $this->pegawaiList = Profile::where(['id_satker'=>$id_satker, 'active'=>1])
            ->where('jabatan', $jabatan)
            ->get(['nama', 'nip']) // Sesuaikan dengan nama kolom di database
            ->toArray();

        // Tampilkan modal
        $this->showModalInfo = true;
    }

    public function openModalEdit($id, $formasi)
    {
        $this->editId = $id;
        $this->formasi = $formasi;
        $this->showModalEdit = true;
    }

    public function saveEdit()
    {
        $this->validate();

        if ($this->editId) {
            $data = ABK::find($this->editId);
            $data->formasi = $this->formasi;
            $data->save();
        }

        $this->showModalEdit = false;
        $this->dispatch('showFlashMessage', 'Formasi berhasil diperbarui!', 'success');
    }

    public function openModalDelete($id, $satkerDelete, $jabatanDelete)
    {
        $this->hapusId = $id;
        $this->nama_satker = $satkerDelete;
        $this->jabatan = $jabatanDelete;
        $this->showModalDelete = false; // Tutup dulu untuk memicu update
        $this->showModalDelete = true;
    }

    public function delete()
    {
        ABK::find($this->hapusId)->delete();
        $this->showModalDelete = false; 
        $this->dispatch('showFlashMessage', 'ABK berhasil dihapus!', 'success');
    }
} 
