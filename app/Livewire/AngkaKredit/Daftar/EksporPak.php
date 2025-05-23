<?php

namespace App\Livewire\AngkaKredit\Daftar;

use App\Models\Profile;
use Livewire\Component;
use PhpOffice\PhpWord\TemplateProcessor;

class EksporPak extends Component
{
    public function export()
    {
        $profile = Profile::where('nip', '198906132012111001')->firstOrFail();

        // Data yang akan di-inject ke template Word
        $data = [
            'nama' => $profile->nama,
            'nip' => $profile->nip,
            'tempat_lahir' => $profile->tempat_lahir,
            'tgl_lahir' => $profile->tgl_lahir,
            'jk' => $profile->jk,
            'gol' => $profile->golongan->nama,
            'tmt_gol' => $profile->tmt_gol,
            'jabatan' => $profile->jabatan,
            'tmt_jab' => $profile->tmt_jab,   
            'satker' =>$profile->satker->nama,
        ];

        $templatePath = storage_path('app/template_pak_bps.docx');
        $templateProcessor = new TemplateProcessor($templatePath);

        foreach ($data as $key => $value) {
            $templateProcessor->setValue($key, $value);
        }

        $outputPath = storage_path('app/public/export_pak_' . $profile->nip . '.docx');
        $templateProcessor->saveAs($outputPath);

        return response()->download($outputPath)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.angka-kredit.daftar.ekspor-pak')->extends('layouts.app');
    }
}
