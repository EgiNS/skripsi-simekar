<div class="flex flex-wrap -mx-3">
    @section('beforeTitle', 'ABK')
    @section('title', 'Update Pegawai')

    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-5 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <p class="font-semibold text-lg text-[#252F40]">Update Data Pegawai</p>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-5 overflow-x-auto text-sm">
                    <p>Silakan upload file CSV data pegawai terbaru. Pastikan urutan kolom pada file adalah sebagai berikut:</p>
                    <ol class="list-decimal pl-8 text-[#252F40] flex flex-col flex-wrap max-h-44 mt-2">
                        <li>NIP</li>
                        <li>Nama</li>
                        <li>Kode Org</li>
                        <li>Jabatan</li>
                        <li>Wilayah</li>
                        <li>TMT Jabatan</li>
                        <li>Golongan Akhir</li>
                        <li>TMT Golongan</li>
                        <li>Status</li>
                        <li>Pendidikan (SK)</li>
                        <li>Tanggal Ijazah</li>
                        <li>TMT CPNS</li>
                        <li>Tempat Lahir</li>
                        <li>Tanggal Lahir</li>
                        <li>Jenis Kelamin</li>
                        <li>Agama</li>
                    </ol>
                    <div class="w-full flex gap-x-4 mt-6">
                        <input type="file" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow">
                        <button class="bg-gradient-to-br font-medium from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-white px-5 py-2 text-sm rounded-lg">Kirim</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>