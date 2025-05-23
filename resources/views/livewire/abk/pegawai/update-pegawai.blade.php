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
                    <ol class="list-decimal pl-8 text-[#252F40] flex flex-col flex-wrap max-h-48 mt-2">
                        <li>NIP BPS</li>
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
                        <li>Username</li>
                    </ol>
                    <div class="w-full flex gap-x-4 mt-6" wire:ignore>
                        <input type="file" wire:model.defer="csv_file"
                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow">
                    
                        <button wire:click="import" wire:loading.attr="disabled"
                            class="bg-gradient-to-br font-medium from-[#FF0080] to-[#7928CA] hover:scale-105 transition text-white px-5 py-2 text-sm rounded-lg">
                            Impor
                        </button>
                    </div>
                    
                    <div wire:loading wire:target="import" class="text-gray-600 text-sm">Mengimpor data...</div>                    
                </div>
            </div>
        </div>

        <div class="col-span-2 mb-5 bg-white rounded-2xl shadow-soft-xl">
            <p class="font-semibold text-lg p-5 pb-0 text-[#252F40]">Riwayat Impor Data</p>
            <div class="flex-auto p-6 px-0 pb-2 max-h-96 overflow-y-auto">
                <div class="overflow-x-auto">
                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                        <thead class="align-bottom">
                            <tr>
                                <th class="px-6 py-2 font-bold tracking-normal text-center uppercase align-middle bg-transparent border-b border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                    No
                                </th>
                                <th class="px-6 py-2 font-bold tracking-normal text-center uppercase align-middle bg-transparent border-b border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                    Tanggal
                                </th>
                                <th class="px-6 py-2 font-bold tracking-normal text-center uppercase align-middle bg-transparent border-b border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                    Status
                                </th>
                                <th class="px-6 py-2 font-bold tracking-normal text-center uppercase align-middle bg-transparent border-b border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($riwayat as $index => $item)
                                <tr class="border">
                                    <td class="leading-normal text-center align-middle bg-transparent border-b text-sm whitespace-nowrap">
                                        <span class="mb-0 leading-normal text-sm">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>

                                    <td class="leading-normal text-center align-middle bg-transparent border-b text-sm whitespace-nowrap">
                                        <span class="mb-0 leading-normal text-sm">
                                            {{  $item->created_at->format('d-m-Y H:i') }}
                                        </span>
                                    </td>

                                    @if($item->active)
                                        <td class="leading-normal text-center align-middle bg-transparent border-b text-sm whitespace-nowrap">
                                            <span class="mb-0 leading-normal text-xs font-medium px-3 bg-green-100 text-green-500 rounded-lg">
                                                Aktif
                                            </span>
                                        </td>
                                    @else
                                        <td class="leading-normal text-center align-middle bg-transparent border-b text-sm whitespace-nowrap">
                                            <span class="mb-0 leading-normal text-xs font-medium px-3 bg-red-100 text-red-400 rounded-lg">
                                                Tidak Aktif
                                            </span>
                                        </td>
                                    @endif

                                    <td class="leading-normal text-center align-middle bg-transparent border-b text-xs whitespace-nowrap rounded-lg px-1 py-2 font-normal transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow">
                                        <select wire:change="updateStatus({{ $item->flag }})" class="block w-full p-1 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-300 focus:border-fuchsia-300">
                                            <option value="" disabled selected>Ubah Status</option>
                                            <option value="1">Aktif</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>