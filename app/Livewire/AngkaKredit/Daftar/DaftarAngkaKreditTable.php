<?php

namespace App\Livewire\AngkaKredit\Daftar;

use Carbon\Carbon;
use App\Models\Profile;
use App\Models\AngkaKredit;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class DaftarAngkaKreditTable extends DataTableComponent
{
    public $showModalEdit = false;
    public $angka_kredit_id;
    public $angka_kredit;
    public $total_ak;
    public $mulai_periode;
    public $akhir_periode;
    public $link_pak;
    public $showModalDelete = false;
    public $hapusId;
    public $entries;

    protected $model = AngkaKredit::class;

    public array $nilaiJenjang = [
        'terampil' => 5,
        'mahir' => 12.5,
        'ahli pertama' => 12.5,
        'penyelia' => 25,
        'ahli muda' => 25,
        'ahli madya' => 37.5,
        'ahli utama' => 50,
    ];

    public array $gol_jenjang = [
        'II/c' => ['terampil'],
        'II/d' => ['terampil'],
        'III/a' => ['ahli pertama', 'mahir'],
        'III/b' => ['ahli pertama', 'mahir'],
        'III/c' => ['ahli muda', 'penyelia'],
        'III/d' => ['ahli muda', 'penyelia'],
        'IV/a' => ['ahli madya'],
        'IV/b' => ['ahli madya'],
        'IV/c' => ['ahli madya'],
        'IV/d' => ['ahli utama'],
        'IV/e' => ['ahli utama'],
    ]; 

    public array $ak_jenjang = [
        'II/c' => 40,
        'II/d' => 40,
        'III/a' => 100,
        'III/b' => 100,
        'III/c' => 200,
        'III/d' => 200,
        'IV/a' => 450,
        'IV/b' => 450,
        'IV/c' => 450,
    ];

    protected $listeners = ['refreshTable' => '$refresh'];

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setTdAttributes(function(Column $column, $row, $columnIndex, $rowIndex) {
            if ($columnIndex == 2 || $columnIndex == 4 || $columnIndex == 5) {
                return [
                  'default' => false,
                  'class' => 'py-3 text-sm font-medium dark:text-white text-center',
                ];
            }

            return [
                'default' => false,
                'class' => 'px-2 py-3 text-sm font-medium dark:text-white',
            ];
        });

        $this->setThAttributes(function(Column $column) {
            // if ($column->isField('total_ak')) {
            //     return [
            //       'default' => false,
            //       'class' => 'text-gray-500 dark:bg-gray-800 dark:text-gray-400 py-3 text-center text-xs font-medium uppercase tracking-wider w-2',
            //     ];
            // }

            return [
                'default' => false,
                'class' => 'text-gray-500 dark:bg-gray-800 dark:text-gray-400 px-2 py-3 text-center text-xs font-medium uppercase tracking-wider w-3',
            ];
        });
    }
    
    public function builder(): Builder
    {

        return AngkaKredit::query()
                ->orderBy('angka_kredit.id', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()
                ->hideIf(true),
            Column::make("NIP", "profile.nip")
                ->sortable()
                ->searchable()
                ->deselected(),
            Column::make("Nama", "profile.nama")
                ->sortable()
                ->searchable(),
            Column::make("Jabatan", "profile.jabatan")
                ->sortable()
                ->searchable(),
            Column::make("Gol", "profile.golongan.nama")
                ->searchable(),
            Column::make("Satker", "profile.satker.nama ")
                ->sortable()
                ->searchable(),
            Column::make("Angka Kredit Total", "total_ak")
                ->hideIf(true),
            Column::make("Angka Kredit", "nilai")
                ->hideIf(true),
            Column::make("Angka Kredit")
                ->html() // Tambahkan ini agar HTML tidak dianggap teks biasa
                ->label(fn($row) => $this->showAk($row->nilai)),
            Column::make("Angka Kredit Total")
                ->html() // Tambahkan ini agar HTML tidak dianggap teks biasa
                ->label(fn($row) => $this->showAk($row->total_ak)),
            Column::make("start", "periode_start")
                ->sortable()
                ->hideIf(true),
            Column::make("end", "periode_end")
                ->sortable()
                ->hideIf(true),
            Column::make("Jenis PAK", "jenis")
                ->sortable()
                ->searchable()
                ->deselected(),
            Column::make("link", "link_pak")
                ->sortable()
                ->hideIf(true),
            Column::make("id_pegawai", "id_pegawai")
                ->sortable()
                ->hideIf(true),
            Column::make("Periode PAK")
                ->html() // Tambahkan ini agar HTML tidak dianggap teks biasa
                ->label(fn($row) => $this->showPeriode($row->jenis, $row->periode_start, $row->periode_end)),
            Column::make("PAK")
                ->html() // Tambahkan ini agar HTML tidak dianggap teks biasa
                ->label(fn($row) => $this->showLink($row->link_pak)),
            Column::make("Waktu Upload", "created_at")
                ->sortable()
                ->deselected(),
            Column::make('Aksi')
                ->setLabelAttributes(['class' => 'block text-center'])
                ->label(fn($row) => view('livewire.angka-kredit.daftar.button-aksi', [
                    'id' => $row->id,

                ])),
        ];
    }

    public function  showPeriode($jenis, $start, $end)
    {
        $tgl_start = Carbon::parse($start)->locale('id');
        $tgl_end = Carbon::parse($end)->locale('id');

        $tgl_start->settings(['formatFunction' => 'translatedFormat']);
        $tgl_end->settings(['formatFunction' => 'translatedFormat']);

        return "{$tgl_start->format('j M Y')} - {$tgl_end->format('j M Y')}";
    }

    public function showLink($link)
    {
        if ($link) {
            return "<a class='bg-[#17C1E8] block whitespace-nowrap text-center text-sm text-white py-1 rounded-lg' href='{$link}' target='_blank'>Lihat</a>";
        } else {
            return "<span class='bg-[#FB8A33] block whitespace-nowrap text-center text-sm text-white py-1 rounded-lg'>Belum Upload</span>";
        }
    }

    public function showAk($ak)
    {
        return rtrim(rtrim(number_format($ak, 3, '.', ''), '0'), '.');
    }

    public function naikPangkat($ak, $id_pegawai, $periode_end)
    {
        $profile = Profile::where('id', $id_pegawai)->first();

        $ak_kp = $profile->golongan->ak_minimal;
        $ak_kj = isset($this->ak_jenjang[$profile->golongan->nama]) ? $this->ak_jenjang[$profile->golongan->nama] : '-';

        $jenjang = $this->gol_jenjang[$profile->golongan->nama][0];
        $ak_tahunan = $this->nilaiJenjang[$jenjang];

        $pred_kp = ceil(($ak_kp - $ak) / $ak_tahunan * 12);

        $perkiraan_kp = Carbon::parse($periode_end)->startOfMonth()->addMonths($pred_kp);

        return $perkiraan_kp->format('F Y');
    }

    public function naikJenjang($ak, $id_pegawai, $periode_end)
    {
        $profile = Profile::where('id', $id_pegawai)->first();

        $ak_kj = isset($this->ak_jenjang[$profile->golongan->nama]) ? $this->ak_jenjang[$profile->golongan->nama] : '-';

        $jenjang = $this->gol_jenjang[$profile->golongan->nama][0];
        $ak_tahunan = $this->nilaiJenjang[$jenjang];

        $pred_kj = ceil(($ak_kj - $ak) / $ak_tahunan * 12);

        $perkiraan_kj = Carbon::parse($periode_end)->startOfMonth()->addMonths($pred_kj);

        return $perkiraan_kj->format('F Y');
    }

    public function showJenis($jenis)
    {
        if ($jenis == 1) {
            return 'Integrasi';
        } elseif ($jenis == 2) {
            return 'Praintegrasi';
        } elseif ($jenis == 3) {
            return 'Konversi Tahunan';
        } elseif ($jenis == 4) {
            return 'Konversi Periodik';
        }
    }

    public function openModalEdit($id)
    {
        $this->resetValidation();

        $item = AngkaKredit::findOrFail($id);

        $this->angka_kredit_id = $item->id;
        $this->angka_kredit     = $item->nilai;
        $this->total_ak         = $item->total_ak;
        $this->mulai_periode    = Carbon::parse($item->periode_start)->format('Y-m');
        $this->akhir_periode    = Carbon::parse($item->periode_end)->format('Y-m');
        $this->link_pak         = $item->link_pak;

        $this->entries = AngkaKredit::where('nip', $item->nip)
                                    ->where('status',2)
                                    ->orderBy('periode_start', 'asc')
                                    ->get();

        $this->showModalEdit = true;
    }

    public function saveEdit()
    {
        $this->validate([
            'angka_kredit' => 'required|numeric',
            'mulai_periode' => 'required|date_format:Y-m',
            'akhir_periode' => 'required|date_format:Y-m',
            'link_pak' => 'required',
        ]);

        // Temukan entri yang sedang diedit
        $currentEntry = AngkaKredit::find($this->angka_kredit_id);

        // Simpan nilai_ak yang lama sebelum diubah
        $oldNilaiAk = $currentEntry->nilai;
        // Hitung selisih perubahan
        $difference = $this->angka_kredit - $oldNilaiAk;

        // Perbarui ak dari entri saat ini
        $currentEntry->nilai = $this->angka_kredit;
        $currentEntry->periode_start = $this->mulai_periode . '-01';
        $currentEntry->periode_end   = $this->akhir_periode . '-01';
        $currentEntry->link_pak      = $this->link_pak;
        $currentEntry->save();

        // --- Logika Update total_ak Berantai ---

        // Iterasi ke depan untuk memperbarui total_ak
        // Mulai dari entri yang baru saja diupdate
        $entryToUpdate = $currentEntry;
        $continueUpdate = true;

        while ($entryToUpdate && $continueUpdate) {
            // Dapatkan entri sebelumnya untuk perhitungan total_ak
            $previousEntry = AngkaKredit::where('nip', $currentEntry->nip)
                                        ->where('status',2)
                                        ->where('periode_start', '<', $entryToUpdate->periode_start)
                                        ->orderBy('periode_start', 'desc')
                                        ->first();

            // Hitung total_ak yang diharapkan untuk entri saat ini
            $expectedTotalAk = ($previousEntry ? $previousEntry->total_ak : 0) + $entryToUpdate->nilai;

            // Jika ini adalah entri yang baru saja kita edit, atau jika total_ak-nya tidak sesuai dengan yang diharapkan
            // maka kita harus memperbarui total_ak saat ini dan melanjutkan ke entri berikutnya
            if ($entryToUpdate->id === $currentEntry->id) {
                // Untuk entri yang diedit, total_ak harus dihitung ulang.
                $entryToUpdate->total_ak = $expectedTotalAk;
                $entryToUpdate->save();
            } else {
                // Untuk entri setelah yang diedit, cek apakah perlu diupdate atau ada reset.
                // Jika total_ak saat ini sudah beda dari expectedTotalAk sebelum perubahan ini (reset),
                // maka kita berhenti memperbarui berantai.
                if ($entryToUpdate->total_ak !== $expectedTotalAk) {
                    $continueUpdate = false; // Deteksi reset, hentikan iterasi
                }

                // Jika tidak ada reset, perbarui total_ak
                if ($continueUpdate) {
                    $entryToUpdate->total_ak = $expectedTotalAk;
                    $entryToUpdate->save();
                }
            }

            // Lanjutkan ke entri berikutnya hanya jika masih perlu update
            if ($continueUpdate) {
                $entryToUpdate = AngkaKredit::where('nip', $currentEntry->nip)
                                            ->where('status',2)
                                            ->where('periode_start', '>', $entryToUpdate->id)
                                            ->orderBy('periode_start', 'asc')
                                            ->first();
            }
        }


        // $item = AngkaKredit::findOrFail($this->angka_kredit_id);

        // // Update nilai dan periode
        // $item->nilai         = $this->angka_kredit;
        // $item->periode_start = $this->mulai_periode . '-01';
        // $item->periode_end   = $this->akhir_periode . '-01';
        // $item->link_pak      = $this->link_pak;
        // $item->save();

        // $riwayat = AngkaKredit::where('nip', $item->nip)
        //     ->where('status', 2)
        //     ->orderBy('periode_start') // urut dari paling awal
        //     ->get();

        // // Cari indeks baris yang diedit
        // $index = $riwayat->search(fn($item) => $item->id == $item->id);

        // if ($index === false) {
        //     return; // tidak ditemukan
        // }

        // // Update nilai di baris yang diedit
        // $edited = $riwayat[$index];
        // $edited->nilai = $item->nilai;

        // // Jika ini reset, total_ak = nilai baru
        // $prev = $index > 0 ? $riwayat[$index - 1] : null;

        // if (!$prev || $edited->total_ak < $prev->total_ak) {
        //     $total = $item->nilai;
        // } else {
        //     $total = $prev->total_ak + $item->nilai;
        // }

        // $edited->total_ak = $total;
        // $edited->save();

        // // Hitung ulang baris-baris setelahnya
        // for ($i = $index + 1; $i < $riwayat->count(); $i++) {
        //     $current = $riwayat[$i];
        //     $prev = $riwayat[$i - 1];

        //     // Jika baris ini reset (total_ak lebih kecil dari sebelumnya)
        //     if ($current->total_ak < $prev->total_ak) {
        //         break; // tidak perlu dihitung ulang ke bawah
        //     }

        //     $total += $current->nilai;
        //     $current->total_ak = $total;
        //     $current->save();
        // }

        $this->showModalEdit = false;

        $this->dispatch('refreshTable');

        $this->dispatch('showFlashMessage', 'Riwayat Angka Kredit berhasil diperbarui!', 'success');
    }

    public function openModalDelete($id)
    {
        $this->hapusId = $id;
        $this->showModalDelete = true;
    }

    public function delete()
    {
        $item = AngkaKredit::findOrFail($this->hapusId);
        $item->delete();

        $this->reset(['hapusId', 'showModalDelete']);

        $this->dispatch('refreshTable');

        $this->dispatch('showFlashMessage', 'Riwayat Angka Kredit berhasil dihapus!', 'success');
    }

}
