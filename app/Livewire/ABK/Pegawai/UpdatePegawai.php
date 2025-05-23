<?php

namespace App\Livewire\Abk\Pegawai;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Satker;
use App\Models\Jabatan;
use App\Models\Profile;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdatePegawai extends Component
{
    use WithFileUploads;

    public $csv_file, $riwayat;

    public function mount()
    {
        $this->loadRiwayat();
    }

    public function loadRiwayat()
    {
        $this->riwayat = Profile::select('flag', DB::raw('MIN(created_at) as created_at'), DB::raw('MAX(active) as active'))
            ->groupBy('flag')
            ->orderByDesc('created_at')
            ->get();
    }

    public function updateStatus($flag)
    {
        // Set semua menjadi tidak aktif
        Profile::query()->update(['active' => 0]);

        // Set hanya flag yang dipilih menjadi aktif
        Profile::where('flag', $flag)->update(['active' => 1]);

        // Refresh data
        $this->loadRiwayat();
    }

    public function updatedCsvFile()
    {
        $this->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $this->loadRiwayat();
    }

    public function confirmUpload()
    {
        $this->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);
        $this->dispatch('showFlashMessage', 'File berhasil diunggah. Klik Import untuk memproses!', 'info');

        $this->loadRiwayat();
    }

    public function import()
    {
        $this->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048', // Validasi file CSV
        ]);

        $path = $this->csv_file->storeAs('csv_uploads', 'pegawai.csv');

        // Buka file yang telah diunggah
        $csvFile = fopen(storage_path("app/$path"), "r");
        $firstline = true;

        // Ambil nilai terakhir dari kolom 'flag' lalu tambahkan 1
        $lastFlag = Profile::max('flag') ?? 0;
        $currentFlag = $lastFlag + 1;

        // Set semua active sebelumnya menjadi 0 (jika ada yang 1)
        Profile::where('active', 1)->update(['active' => 0]);

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Profile::create([
                    "nip_bps" => $data['0'],
                    "nip" => $data['1'],
                    "id_user" => User::where('username', $data['17'])->first()->id ?? null,
                    "id_satker" => Satker::where('wilayah', $data['5'])->first()->id ?? null,
                    "id_golongan" => DB::table('golongan')->where('nama', $data['7'])->first()->id ?? 14,
                    "nama" => $data['2'],
                    "kode_org" => $data['3'],
                    "jabatan" => Jabatan::where('nama_simpeg', $data['4'])->first()->konversi ?? null,
                    "tmt_jab" => Carbon::createFromFormat(strpos($data['6'], '-') !== false ? 'd-m-Y' : 'm/d/Y', trim($data['6']))->format('Y-m-d'),
                    "tmt_gol" => Carbon::createFromFormat(strpos($data['8'], '-') !== false ? 'd-m-Y' : 'm/d/Y', trim($data['8']))->format('Y-m-d'),
                    "status" => $data['9'],
                    "pendidikan" => $data['10'],
                    "tgl_ijazah" => Carbon::createFromFormat(strpos($data['11'], '-') !== false ? 'd-m-Y' : 'm/d/Y', trim($data['11']))->format('Y-m-d'),
                    "tmt_cpns" => Carbon::createFromFormat(strpos($data['12'], '-') !== false ? 'd-m-Y' : 'm/d/Y', trim($data['12']))->format('Y-m-d'),
                    "tempat_lahir" => $data['13'],
                    "tgl_lahir" => Carbon::createFromFormat(strpos($data['14'], '-') !== false ? 'd-m-Y' : 'm/d/Y', trim($data['14']))->format('Y-m-d'),
                    "jk" => $data['15'],
                    "agama" => $data['16'],
                    "username" => $data['17'],
                    "flag" => $currentFlag,
                    "active" => 1,
                ]);
            }
            $firstline = false;
        }

        fclose($csvFile);

        // Hapus file setelah selesai
        Storage::delete($path);
        
        Storage::delete(Storage::files('livewire-tmp'));

        $this->loadRiwayat();
        $this->reset('csv_file');

        // Emit event untuk notifikasi
        $this->dispatch('showFlashMessage', 'Data Pegawai Berhasil Diimpor!', 'success');
    }
    
    public function render()
    {
        return view('livewire.abk.pegawai.update-pegawai')->extends('layouts.app');
    }
}
