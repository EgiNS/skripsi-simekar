<?php

namespace App\Livewire\MinatKarir;

use App\Models\Jabatan;
use Livewire\Component;
use App\Models\TesMinatKarier;

class AdminTesMinatKarir extends Component
{
    public $rows = [];
    public $suggestions = [];

    public function mount()
    {
        $this->rows = TesMinatKarier::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'soal' => $item->soal,
                'jabatan' => $item->jabatan,
            ];
        })->toArray();

        // Set minimal 1 row kalau kosong
        if (empty($this->rows)) {
            $this->addRow();
        }
    }

    public function suggestJabatan($index)
    {
        $search = $this->rows[$index]['jabatan'] ?? '';
        
        $this->suggestions[$index] = Jabatan::where('nama_umum', 'like', '%' . $search . '%')
            ->distinct()
            ->limit(5)
            ->pluck('nama_umum')
            ->toArray();

        // dd($this->suggestions);
    }
    
    public function selectJabatan($index, $jabatan)
    {
        $this->rows[$index]['jabatan'] = $jabatan;
        $this->suggestions[$index] = [];
    }    

    public function addRow()
    {
        $this->rows[] = [
            'id' => null,
            'soal' => '',
            'jabatan' => '',
        ];
    }

    public function removeRow($index)
    {
        if (isset($this->rows[$index])) {
            unset($this->rows[$index]);
            $this->rows = array_values($this->rows); // reindex
        }
    }

    public function save()
    {
        $existingIds = TesMinatKarier::pluck('id')->toArray();
        $submittedIds = [];

        foreach ($this->rows as $row) {
            if ($row['id']) {
                TesMinatKarier::where('id', $row['id'])->update([
                    'soal' => $row['soal'],
                    'jabatan' => $row['jabatan'],
                ]);
                $submittedIds[] = $row['id'];
            } else {
                $new = TesMinatKarier::create([
                    'soal' => $row['soal'],
                    'jabatan' => $row['jabatan'],
                ]);
                $submittedIds[] = $new->id;
            }
        }

        // Delete rows not submitted
        $toDelete = array_diff($existingIds, $submittedIds);
        TesMinatKarier::whereIn('id', $toDelete)->delete();

        $this->dispatch('showFlashMessage', 'Data Berhasil Disimpan!', 'success');
    }

    public function render()
    {
        return view('livewire.minat-karir.admin-tes-minat-karir')->extends('layouts.app');
    }
}
