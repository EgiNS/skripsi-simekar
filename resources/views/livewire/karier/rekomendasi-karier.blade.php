<div class="flex flex-wrap -mx-3">
  @section('beforeTitle', 'Pages')
  @section('title', 'Karier')

    <div class="flex-none w-full max-w-full px-3">
        <div class="relative text-[#252F40] text-sm flex flex-col min-w-0 mb-6 break-words border-0 border-transparent border-solid rounded-2xl">
            <div class="mb-5 bg-white rounded-2xl shadow-soft-xl">
                  <div class="p-5 pb-3">
                      <p>Halo, <span class="font-medium">{{ $user->nama }}</span> ! Jabatan anda saat ini adalah <span class="font-medium">{{ $user->jabatan }}</span></p>
                      @if ($rekom)
                      <p class="mt-2">Berdasarkan <span class="font-medium">Peraturan XXX</span>, syarat kenaikan jenjang ke <span class="font-medium">{{ $nextJabatan }}</span> :</p>
                      <div class="mt-3">
                        <p class="mb-2 bg-[#FAFAFA] p-2 rounded"><span class="text-xs text-white font-medium rounded-full bg-[#cb0c9ec2] mr-2 py-1 px-2">1.</span> Angka kredit minimal {{ $akMinimal }}</p>
                        @foreach ($rekom->syarat['syarat'] as $index => $item)
                            <p class="mb-2 bg-[#FAFAFA] p-2 rounded"><span class="text-xs text-white font-medium rounded-full bg-[#cb0c9ec2] mr-2 py-1 px-2">{{ $index+2 }}.</span> {{ $item }}</p>
                        @endforeach
                      </div>
                      @endif
                  </div>
              </div>
            <div class="p-5 mb-5 bg-white rounded-2xl shadow-soft-xl">
              <p>Perkiraan kenaikan pangkat: <span class="font-semibold">{{ $this->perkiraan_kp }}</span></p>
              <p>Perkiraan kenaikan jenjang: <span class="font-semibold">{{ $this->perkiraan_kj }}</span></p>  
              <p>Prediksi periode kenaikan pangkat terdekat pada {{ $this->periode_kp['periode'] }}. Harap kumpulkan berkas paling lambat {{ $this->periode_kp['deadline'] }}</p>
            </div>
            @if ($rekom)
              <div class="p-5 mb-5 bg-white rounded-2xl shadow-soft-xl">
                  {{-- <p>Jika syarat ketiga terpenuhi, maka seluruh syarat untuk kenaikan pangkat telah <span class="font-medium">TERPENUHI</span>. Silakan mendaftar Ujian Kompetensi Terdekat bagi Pranata Komputer pada <span class="font-medium">Agustus 2024</span> dan mengajukan kenaikan pangkat pada <span class="font-medium">Desember 2024</span> bila dinyatakan <span class="font-medium">LULUS</span> Ujian Kompetensi. </p>
                  <p class="mt-4">Untuk <span class="font-medium">Kenaikan Pangkat Periode Desember 2024</span> <br> 
                      Batas Akhir Pengiriman Usulan KP dan upload data E-Files tanggal <span class="font-medium">1 September 2024</span> (termasuk Rekon SKP Tahun 2022 dan 2023).
                  </p> --}}
                  {!! $rekom->rekomendasi !!}
              </div>
            @endif
            <div class="grid md:grid-cols-2 md:gap-x-5 grid-cols-1 gap-x-0">
                <div class="p-5 mb-5 bg-white rounded-2xl shadow-soft-xl max-h-72 overflow-auto">
                    <p class="mb-4">Formasi yang tersedia untuk <span class="font-medium">{{ $user->jabatan }}</span></p>
                    <table class="w-full">
                        <th class="bg-[#F5F5F5] font-normal py-1">Satker</th>
                        <th class="bg-[#F5F5F5] font-normal py-1">Jumlah formasi tersedia</th>
                        @forelse ($formasiSaatIni as $item)
                          <tr class="border-b border-[#F0F0F0]">
                            <td class="py-2">{{ $item['nama'] }}</td>
                            <td class="text-center py-2">{{ $item['formasi'] }}</td>
                          </tr>
                        @empty
                            <td>Tidak ada formasi tersedia</td>
                        @endforelse
                    </table>
                </div>
                <div class="p-5 mb-5 bg-white rounded-2xl shadow-soft-xl max-h-72 overflow-auto">
                    <p class="mb-4">Formasi yang tersedia untuk <span class="font-medium">{{ $nextJabatan }}</span></p>
                    <table class="w-full">
                      <th class="bg-[#F5F5F5] font-normal py-1">Satker</th>
                      <th class="bg-[#F5F5F5] font-normal py-1">Jumlah formasi tersedia</th>
                      @forelse ($formasiNextJenjang as $item)
                        <tr class="border-b border-[#F0F0F0]">
                          <td class="py-2">{{ $item['nama'] }}</td>
                          <td class="text-center py-2">{{ $item['formasi'] }}</td>
                        </tr>
                      @empty
                          <td>Tidak ada formasi tersedia</td>
                      @endforelse
                  </table>
                </div>
            </div>
            <div class="mb-5 bg-white rounded-2xl shadow-soft-xl">
                <p class="font-medium p-5 text-lg">Perkiraan Kenaikan Pangkat</p>
                <div class="p-0 overflow-x-auto">
                    <table class="items-center justify-center w-full mb-0 align-top border-gray-200">
                      <thead class="align-bottom text-slate-500">
                        <tr>
                          <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Golongan</th>
                          <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Jabatan</th>
                          <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Kebutuhan Angka Kredit</th>
                          <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Perkiraan Terpenuhi</th>
                      </thead>
                      <tbody>
                        @foreach ($this->all_pred as $pred)
                          <tr>
                            <td class="p-3 pl-5 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                              <p class="">{{ $pred['gol'] }}</p>
                            </td>
                            <td class="p-3 pl-5 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                              <p class="">{{ $pred['jenjang'] }}</p>
                            </td>
                            <td class="p-3 pl-5 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                              <p class="">{{ $pred['ak_min'] }}</p>
                            </td>
                            <td class="p-3 pl-5 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                              <p class="">{{ $pred['perkiraan_kp'] }}</p>
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