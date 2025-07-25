    <!-- sidenav  -->
    <aside class="max-w-62.5 ease-nav-brand z-990 fixed inset-y-0 my-4 ml-4 block w-full -translate-x-full flex-wrap items-center justify-between overflow-y-auto rounded-2xl border-0 bg-white p-0 antialiased transition-transform duration-200 xl:left-0 xl:translate-x-0 ps xl:bg-white shadow-soft-xl">
        <div class="h-19.5">
          <i class="absolute top-0 right-0 hidden p-4 opacity-50 cursor-pointer fas fa-times text-slate-400 xl:hidden" sidenav-close></i>
          <a class="block px-8 py-6 m-0 text-sm whitespace-nowrap text-slate-700" href="javascript:;" target="_blank">
            <img src="../assets/img/logo-ct.png" class="inline h-full max-w-full transition-all duration-200 ease-nav-brand max-h-8" alt="main_logo" />
            <span class="ml-3 font-semibold transition-all duration-200 ease-nav-brand text-lg">Karir<span class="italic text-[#CB0C9F]">WAY</span></span>
          </a>
        </div>
  
        <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent" />
  
        <div class="items-center block w-auto max-h-screen h-full overflow-auto grow basis-full">
          <ul class="flex flex-col pl-0 mb-0">
            <li class="mt-0.5 w-full">
                <a class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors {{ request()->routeIs('dashboard') ? 'bg-white rounded-lg font-semibold text-slate-700 shadow-soft-xl' : ''  }}" href="{{ route('dashboard') }}" wire:navigate>
                  <div class="{{ request()->routeIs('dashboard')  ? 'bg-gradient-to-tl from-purple-700 to-pink-500' : ''  }} shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                    <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                      <title>shop</title>
                      <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF" fill-rule="nonzero">
                          <g transform="translate(1716.000000, 291.000000)">
                            <g transform="translate(0.000000, 148.000000)">
                              <path
                                class="{{ request()->routeIs('dashboard') ? '' :  'fill-slate-800'}} opacity-60"
                                d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z"
                              ></path>
                              <path
                                class="{{ request()->routeIs('dashboard') ? '' :  'fill-slate-800'}}"
                                d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z"
                              ></path>
                            </g>
                          </g>
                        </g>
                      </g>
                    </svg>
                  </div>
                  <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Dashboard</span>
                </a>
            </li>
  
            <li class="mt-0.5 w-full" 
                x-data="{ open: {{ (request()->routeIs('status-abk') || request()->routeIs('detail-abk') || request()->routeIs('tambah-abk') || request()->routeIs('update-pegawai') || request()->routeIs('update-nomenklatur')) ? 'true' : 'false' }} }">
                
                <a @click="open = !open"
                class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center justify-between whitespace-nowrap px-4 transition-colors cursor-pointer 
                        {{ (request()->routeIs('status-abk') || request()->routeIs('detail-abk') || request()->routeIs('tambah-abk') || request()->routeIs('update-pegawai') || request()->routeIs('update-nomenklatur')) ? 'rounded-lg bg-white font-semibold text-slate-700 shadow-soft-xl' : '' }}">
                    <div class="flex items-center">
                        <div class="{{ (request()->routeIs('status-abk') || request()->routeIs('detail-abk') || request()->routeIs('tambah-abk') || request()->routeIs('update-pegawai') || request()->routeIs('update-nomenklatur')) ? 'bg-gradient-to-tl from-purple-700 to-pink-500' : '' }} shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                            <svg width="12px" height="12px" viewBox="0 0 42 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>office</title>
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-1869.000000, -293.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                        <g transform="translate(1716.000000, 291.000000)">
                                            <g transform="translate(153.000000, 2.000000)">
                                                <path class="{{ (request()->routeIs('status-abk') || request()->routeIs('detail-abk') || request()->routeIs('tambah-abk') || request()->routeIs('update-pegawai') || request()->routeIs('update-nomenklatur')) ? '' :  'fill-slate-800 '}} opacity-60" d="M12.25,17.5 L8.75,17.5 L8.75,1.75 C8.75,0.78225 9.53225,0 10.5,0 L31.5,0 C32.46775,0 33.25,0.78225 33.25,1.75 L33.25,12.25 L29.75,12.25 L29.75,3.5 L12.25,3.5 L12.25,17.5 Z"></path>
                                                <path class="{{ (request()->routeIs('status-abk') || request()->routeIs('detail-abk') || request()->routeIs('tambah-abk') || request()->routeIs('update-pegawai') || request()->routeIs('update-nomenklatur')) ? '' :  'fill-slate-800'}}" d="M40.25,14 L24.5,14 C23.53225,14 22.75,14.78225 22.75,15.75 L22.75,38.5 L19.25,38.5 L19.25,22.75 C19.25,21.78225 18.46775,21 17.5,21 L1.75,21 C0.78225,21 0,21.78225 0,22.75 L0,40.25 C0,41.21775 0.78225,42 1.75,42 L40.25,42 C41.21775,42 42,41.21775 42,40.25 L42,15.75 C42,14.78225 41.21775,14 40.25,14 Z M12.25,36.75 L7,36.75 L7,33.25 L12.25,33.25 L12.25,36.75 Z M12.25,29.75 L7,29.75 L7,26.25 L12.25,26.25 L12.25,29.75 Z M35,36.75 L29.75,36.75 L29.75,33.25 L35,33.25 L35,36.75 Z M35,29.75 L29.75,29.75 L29.75,26.25 L35,26.25 L35,29.75 Z M35,22.75 L29.75,22.75 L29.75,19.25 L35,19.25 L35,22.75 Z"></path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">ABK</span>
                    </div>
                    <svg x-bind:class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
                
                <ul x-show="open" x-transition class="ml-10 mt-1 space-y-0">
                    <li>
                        <a href="{{ route('status-abk') }}" wire:navigate
                        class="block border-l-2 py-2 px-4 text-sm hover:bg-gray-100 
                                {{ request()->routeIs('status-abk') ? 'font-semibold border-[#CB0C9F]' : '' }}">
                            Status ABK
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('detail-abk') }}" wire:navigate
                        class="block border-l-2 py-2 px-4 text-sm hover:bg-gray-100 
                                {{ request()->routeIs('detail-abk') ? 'font-semibold border-[#CB0C9F]' : '' }}">
                            Detail ABK
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tambah-abk') }}" wire:navigate
                        class="block border-l-2 py-2 px-4 text-sm hover:bg-gray-100 
                                {{ request()->routeIs('tambah-abk') ? 'font-semibold border-[#CB0C9F]' : '' }}">
                            Tambah Jabatan
                        </a>
                    </li>
                    <li>
                      <a href="{{ route('update-pegawai') }}" wire:navigate
                      class="block border-l-2 py-2 px-4 text-sm hover:bg-gray-100 
                              {{ request()->routeIs('update-pegawai') ? 'font-semibold border-[#CB0C9F]' : '' }}">
                            Update Pegawai
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('update-nomenklatur') }}" wire:navigate
                      class="block border-l-2 py-2 px-4 text-sm hover:bg-gray-100 
                              {{ request()->routeIs('update-nomenklatur') ? 'font-semibold border-[#CB0C9F]' : '' }}">
                            Update Nomenklatur
                      </a>
                    </li>
                </ul>
            </li>

            <li class="mt-0.5 w-full" 
                x-data="{ open: {{ (request()->routeIs('simulasi-pegawai')) || request()->routeIs('simulasi-kepala') || request()->routeIs('usul-mutasi') ? 'true' : 'false' }} }">
                
                <a @click="open = !open"
                class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center justify-between whitespace-nowrap px-4 transition-colors cursor-pointer 
                        {{ (request()->routeIs('simulasi-pegawai')) || request()->routeIs('simulasi-kepala') || request()->routeIs('usul-mutasi') ? 'rounded-lg bg-white font-semibold text-slate-700 shadow-soft-xl' : '' }}">
                    <div class="flex items-center">
                        <div class="{{ (request()->routeIs('simulasi-pegawai')) || request()->routeIs('simulasi-kepala') || request()->routeIs('usul-mutasi') ? 'bg-gradient-to-tl from-purple-700 to-pink-500' : '' }} shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                            <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                              <title>credit-card</title>
                              <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                  <g transform="translate(1716.000000, 291.000000)">
                                    <g transform="translate(453.000000, 454.000000)">
                                      <path class="{{ (request()->routeIs('simulasi-pegawai')) || request()->routeIs('simulasi-kepala') || request()->routeIs('usul-mutasi') ? '' :  'fill-slate-800 '}} opacity-60" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z"></path>
                                      <path class="{{ (request()->routeIs('simulasi-pegawai')) || request()->routeIs('simulasi-kepala') || request()->routeIs('usul-mutasi') ? '' :  'fill-slate-800'}}" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
                                    </g>
                                  </g>
                                </g>
                              </g>
                            </svg>
                        </div>
                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Mutasi</span>
                    </div>
                    <svg x-bind:class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
                
                <ul x-show="open" x-transition class="ml-10 mt-1 space-y-0">
                    <li>
                      <a href="{{ route('usul-mutasi') }}" wire:navigate
                      class="block border-l-2 py-2 px-4 text-sm hover:bg-gray-100 
                              {{ request()->routeIs('usul-mutasi') ? 'font-semibold border-[#CB0C9F]' : '' }}">
                          Usul Mutasi
                      </a>
                    </li>
                    <li>
                        <a href="{{ route('simulasi-pegawai') }}" wire:navigate
                        class="block border-l-2 py-2 px-4 text-sm hover:bg-gray-100 
                                {{ request()->routeIs('simulasi-pegawai') ? 'font-semibold border-[#CB0C9F]' : '' }}">
                            Simulasi Pegawai
                        </a>
                    </li>
                    <li>
                      <a href="{{ route('simulasi-kepala') }}" wire:navigate
                      class="block border-l-2 py-2 px-4 text-sm hover:bg-gray-100 
                              {{ request()->routeIs('simulasi-kepala') ? 'font-semibold border-[#CB0C9F]' : '' }}">
                          Simulasi Kepala
                      </a>
                  </li>
                </ul>
            </li>

            <li class="mt-0.5 w-full" 
                x-data="{ open: {{ (request()->routeIs('angka-kredit') || request()->routeIs('upload-angka-kredit') || request()->routeIs('buat-pak') || request()->routeIs('hasil-pak') || request()->routeIs('update-kinerja')) ? 'true' : 'false' }} }">
                
                <a @click="open = !open"
                class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center justify-between whitespace-nowrap px-4 transition-colors cursor-pointer 
                        {{ (request()->routeIs('angka-kredit') || request()->routeIs('upload-angka-kredit') || request()->routeIs('buat-pak') || request()->routeIs('hasil-pak') || request()->routeIs('update-kinerja')) ? 'rounded-lg bg-white font-semibold text-slate-700 shadow-soft-xl' : '' }}">
                    <div class="flex items-center">
                        <div class="{{ (request()->routeIs('angka-kredit') || request()->routeIs('upload-angka-kredit') || request()->routeIs('buat-pak') || request()->routeIs('hasil-pak') || request()->routeIs('update-kinerja')) ? 'bg-gradient-to-tl from-purple-700 to-pink-500' : '' }} shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                          <svg width="12px" height="12px" viewBox="0 0 40 44" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>document</title>
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                              <g transform="translate(-1870.000000, -591.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                <g transform="translate(1716.000000, 291.000000)">
                                  <g transform="translate(154.000000, 300.000000)">
                                    <path class="{{ (request()->routeIs('angka-kredit') || request()->routeIs('upload-angka-kredit') || request()->routeIs('buat-pak') || request()->routeIs('hasil-pak') || request()->routeIs('update-kinerja')) ? '' :  'fill-slate-800'}} opacity-60" d="M40,40 L36.3636364,40 L36.3636364,3.63636364 L5.45454545,3.63636364 L5.45454545,0 L38.1818182,0 C39.1854545,0 40,0.814545455 40,1.81818182 L40,40 Z"></path>
                                    <path class="{{ (request()->routeIs('angka-kredit') || request()->routeIs('upload-angka-kredit') || request()->routeIs('buat-pak') || request()->routeIs('hasil-pak') || request()->routeIs('update-kinerja')) ? '' :  'fill-slate-800'}}" d="M30.9090909,7.27272727 L1.81818182,7.27272727 C0.814545455,7.27272727 0,8.08727273 0,9.09090909 L0,41.8181818 C0,42.8218182 0.814545455,43.6363636 1.81818182,43.6363636 L30.9090909,43.6363636 C31.9127273,43.6363636 32.7272727,42.8218182 32.7272727,41.8181818 L32.7272727,9.09090909 C32.7272727,8.08727273 31.9127273,7.27272727 30.9090909,7.27272727 Z M18.1818182,34.5454545 L7.27272727,34.5454545 L7.27272727,30.9090909 L18.1818182,30.9090909 L18.1818182,34.5454545 Z M25.4545455,27.2727273 L7.27272727,27.2727273 L7.27272727,23.6363636 L25.4545455,23.6363636 L25.4545455,27.2727273 Z M25.4545455,20 L7.27272727,20 L7.27272727,16.3636364 L25.4545455,16.3636364 L25.4545455,20 Z"></path>
                                  </g>
                                </g>
                              </g>
                            </g>
                          </svg>
                        </div>
                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Angka Kredit</span>
                    </div>
                    <svg x-bind:class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
                
                <ul x-show="open" x-transition class="ml-10 mt-1 space-y-0">
                    <li>
                      <a href="{{ route('angka-kredit') }}" wire:navigate
                      class="block border-l-2 py-2 px-4 text-sm hover:bg-gray-100 
                              {{ request()->routeIs('angka-kredit') ? 'font-semibold border-[#CB0C9F]' : '' }}">
                          Daftar Angka Kredit
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('upload-angka-kredit') }}" wire:navigate
                      class="block border-l-2 py-2 px-4 text-sm hover:bg-gray-100 
                              {{ request()->routeIs('upload-angka-kredit') ? 'font-semibold border-[#CB0C9F]' : '' }}">
                          Approval Angka Kredit
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('buat-pak') }}" wire:navigate
                      class="block border-l-2 py-2 px-4 text-sm hover:bg-gray-100 
                              {{ request()->routeIs('buat-pak') || request()->routeIs('hasil-pak') ? 'font-semibold border-[#CB0C9F]' : '' }}">
                          Buat PAK
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('update-kinerja') }}" wire:navigate
                      class="block border-l-2 py-2 px-4 text-sm hover:bg-gray-100 
                              {{ request()->routeIs('update-kinerja') ? 'font-semibold border-[#CB0C9F]' : '' }}">
                          Update Nilai Kinerja
                      </a>
                    </li>
                </ul>
            </li>

            <li class="mt-0.5 w-full" 
                x-data="{ open: {{ (request()->routeIs('karier') || request()->routeIs('admin-minat-karier') || request()->routeIs('tambah-rekom-karier') || request()->routeIs('edit-rekom-karier')) ? 'true' : 'false' }} }">
                
                <a @click="open = !open"
                class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center justify-between whitespace-nowrap px-4 transition-colors cursor-pointer 
                        {{ (request()->routeIs('karier') || request()->routeIs('admin-minat-karier') || request()->routeIs('tambah-rekom-karier') || request()->routeIs('edit-rekom-karier')) ? 'rounded-lg bg-white font-semibold text-slate-700 shadow-soft-xl' : '' }}">
                    <div class="flex items-center">
                        <div class="{{ (request()->routeIs('karier') || request()->routeIs('admin-minat-karier') || request()->routeIs('tambah-rekom-karier') || request()->routeIs('edit-rekom-karier')) ? 'bg-gradient-to-tl from-purple-700 to-pink-500' : '' }} shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                          <svg width="12px" height="12px" viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>settings</title>
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                              <g transform="translate(-2020.000000, -442.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                <g transform="translate(1716.000000, 291.000000)">
                                  <g transform="translate(304.000000, 151.000000)">
                                    <polygon class="{{ request()->routeIs('admin-minat-karier') || request()->routeIs('karier') || request()->routeIs('tambah-rekom-karier') || request()->routeIs('edit-rekom-karier') ? '' :  'fill-slate-800'}} opacity-60" points="18.0883333 15.7316667 11.1783333 8.82166667 13.3333333 6.66666667 6.66666667 0 0 6.66666667 6.66666667 13.3333333 8.82166667 11.1783333 15.315 17.6716667"></polygon>
                                    <path class="{{ request()->routeIs('admin-minat-karier') || request()->routeIs('karier') || request()->routeIs('tambah-rekom-karier') || request()->routeIs('edit-rekom-karier') ? '' :  'fill-slate-800'}} opacity-60" d="M31.5666667,23.2333333 C31.0516667,23.2933333 30.53,23.3333333 30,23.3333333 C29.4916667,23.3333333 28.9866667,23.3033333 28.48,23.245 L22.4116667,30.7433333 L29.9416667,38.2733333 C32.2433333,40.575 35.9733333,40.575 38.275,38.2733333 L38.275,38.2733333 C40.5766667,35.9716667 40.5766667,32.2416667 38.275,29.94 L31.5666667,23.2333333 Z"></path>
                                    <path class="{{ request()->routeIs('admin-minat-karier') || request()->routeIs('karier') || request()->routeIs('tambah-rekom-karier') || request()->routeIs('edit-rekom-karier') ? '' :  'fill-slate-800'}}" d="M33.785,11.285 L28.715,6.215 L34.0616667,0.868333333 C32.82,0.315 31.4483333,0 30,0 C24.4766667,0 20,4.47666667 20,10 C20,10.99 20.1483333,11.9433333 20.4166667,12.8466667 L2.435,27.3966667 C0.95,28.7083333 0.0633333333,30.595 0.00333333333,32.5733333 C-0.0583333333,34.5533333 0.71,36.4916667 2.11,37.89 C3.47,39.2516667 5.27833333,40 7.20166667,40 C9.26666667,40 11.2366667,39.1133333 12.6033333,37.565 L27.1533333,19.5833333 C28.0566667,19.8516667 29.01,20 30,20 C35.5233333,20 40,15.5233333 40,10 C40,8.55166667 39.685,7.18 39.1316667,5.93666667 L33.785,11.285 Z"></path>
                                  </g>
                                </g>
                              </g>
                            </g>
                          </svg>
                        </div>
                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Karier</span>
                    </div>
                    <svg x-bind:class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
                
                <ul x-show="open" x-transition class="ml-10 mt-1 space-y-0">
                    <li>
                      <a href="{{ route('karier') }}" wire:navigate
                      class="block border-l-2 py-2 px-4 text-sm hover:bg-gray-100 
                              {{ request()->routeIs('karier') || request()->routeIs('tambah-rekom-karier') || request()->routeIs('edit-rekom-karier') ? 'font-semibold border-[#CB0C9F]' : '' }}">
                          Rekomendasi Karier
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('admin-minat-karier') }}" wire:navigate
                      class="block border-l-2 py-2 px-4 text-sm hover:bg-gray-100 
                              {{ request()->routeIs('admin-minat-karier') ? 'font-semibold border-[#CB0C9F]' : '' }}">
                          Tes Minat Karier
                      </a>
                    </li>
                </ul>
            </li> 

            <li class="mt-0.5 w-full" 
                x-data="{ open: {{ (request()->routeIs('jadwal-ukom') || request()->routeIs('info-ukom') || request()->routeIs('detail-info') || request()->routeIs('tambah-info-ukom') || request()->routeIs('edit-info-ukom')) ? 'true' : 'false' }} }">
                
                <a @click="open = !open"
                class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center justify-between whitespace-nowrap px-4 transition-colors cursor-pointer 
                        {{ (request()->routeIs('jadwal-ukom') || request()->routeIs('info-ukom') || request()->routeIs('detail-info') || request()->routeIs('tambah-info-ukom') || request()->routeIs('edit-info-ukom')) ? 'rounded-lg bg-white font-semibold text-slate-700 shadow-soft-xl' : '' }}">
                    <div class="flex items-center">
                        <div class="{{ (request()->routeIs('jadwal-ukom') || request()->routeIs('info-ukom') || request()->routeIs('detail-info') || request()->routeIs('tambah-info-ukom') || request()->routeIs('edit-info-ukom')) ? 'bg-gradient-to-tl from-purple-700 to-pink-500' : '' }} shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                          <svg width="12px" height="20px" viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>spaceship</title>
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                              <g transform="translate(-1720.000000, -592.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                <g transform="translate(1716.000000, 291.000000)">
                                  <g transform="translate(4.000000, 301.000000)">
                                    <path
                                      class="{{ (request()->routeIs('jadwal-ukom') || request()->routeIs('info-ukom') || request()->routeIs('detail-info') || request()->routeIs('tambah-info-ukom') || request()->routeIs('edit-info-ukom')) ? '' :  'fill-slate-800'}}"
                                      d="M39.3,0.706666667 C38.9660984,0.370464027 38.5048767,0.192278529 38.0316667,0.216666667 C14.6516667,1.43666667 6.015,22.2633333 5.93166667,22.4733333 C5.68236407,23.0926189 5.82664679,23.8009159 6.29833333,24.2733333 L15.7266667,33.7016667 C16.2013871,34.1756798 16.9140329,34.3188658 17.535,34.065 C17.7433333,33.98 38.4583333,25.2466667 39.7816667,1.97666667 C39.8087196,1.50414529 39.6335979,1.04240574 39.3,0.706666667 Z M25.69,19.0233333 C24.7367525,19.9768687 23.3029475,20.2622391 22.0572426,19.7463614 C20.8115377,19.2304837 19.9992882,18.0149658 19.9992882,16.6666667 C19.9992882,15.3183676 20.8115377,14.1028496 22.0572426,13.5869719 C23.3029475,13.0710943 24.7367525,13.3564646 25.69,14.31 C26.9912731,15.6116662 26.9912731,17.7216672 25.69,19.0233333 L25.69,19.0233333 Z"
                                    ></path>
                                    <path class="{{ (request()->routeIs('jadwal-ukom') || request()->routeIs('info-ukom') || request()->routeIs('detail-info') || request()->routeIs('tambah-info-ukom') || request()->routeIs('edit-info-ukom')) ? '' :  'fill-slate-800'}} opacity-60" d="M1.855,31.4066667 C3.05106558,30.2024182 4.79973884,29.7296005 6.43969145,30.1670277 C8.07964407,30.6044549 9.36054508,31.8853559 9.7979723,33.5253085 C10.2353995,35.1652612 9.76258177,36.9139344 8.55833333,38.11 C6.70666667,39.9616667 0,40 0,40 C0,40 0,33.2566667 1.855,31.4066667 Z"></path>
                                    <path class="{{ (request()->routeIs('jadwal-ukom') || request()->routeIs('info-ukom') || request()->routeIs('detail-info') || request()->routeIs('tambah-info-ukom') || request()->routeIs('edit-info-ukom')) ? '' :  'fill-slate-800'}} opacity-60" d="M17.2616667,3.90166667 C12.4943643,3.07192755 7.62174065,4.61673894 4.20333333,8.04166667 C3.31200265,8.94126033 2.53706177,9.94913142 1.89666667,11.0416667 C1.5109569,11.6966059 1.61721591,12.5295394 2.155,13.0666667 L5.47,16.3833333 C8.55036617,11.4946947 12.5559074,7.25476565 17.2616667,3.90166667 L17.2616667,3.90166667 Z"></path>
                                    <path class="{{ (request()->routeIs('jadwal-ukom') || request()->routeIs('info-ukom') || request()->routeIs('detail-info') || request()->routeIs('tambah-info-ukom') || request()->routeIs('edit-info-ukom')) ? '' :  'fill-slate-800'}} opacity-60" d="M36.0983333,22.7383333 C36.9280725,27.5056357 35.3832611,32.3782594 31.9583333,35.7966667 C31.0587397,36.6879974 30.0508686,37.4629382 28.9583333,38.1033333 C28.3033941,38.4890431 27.4704606,38.3827841 26.9333333,37.845 L23.6166667,34.53 C28.5053053,31.4496338 32.7452344,27.4440926 36.0983333,22.7383333 L36.0983333,22.7383333 Z"></path>
                                  </g>
                                </g>
                              </g>
                            </g>
                          </svg>
                        </div>
                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Ujian Kompetensi</span>
                    </div>
                    <svg x-bind:class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
                
                <ul x-show="open" x-transition class="ml-10 mt-1 space-y-0">
                    <li>
                      <a href="{{ route('jadwal-ukom') }}" wire:navigate
                      class="block border-l-2 py-2 px-4 text-sm hover:bg-gray-100 
                              {{ request()->routeIs('jadwal-ukom') ? 'font-semibold border-[#CB0C9F]' : '' }}">
                          Jadwal Ukom
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('info-ukom') }}" wire:navigate
                      class="block border-l-2 py-2 px-4 text-sm hover:bg-gray-100 
                              {{ request()->routeIs('info-ukom') || request()->routeIs('detail-info') || request()->routeIs('tambah-info-ukom') || request()->routeIs('edit-info-ukom') ? 'font-semibold border-[#CB0C9F]' : '' }}">
                          Informasi Ukom
                      </a>
                    </li>
                </ul>
            </li>
          </ul>
        </div>
  
        
      </aside>
  
      <!-- end sidenav -->