<div class="flex flex-wrap -mx-3">
    @section('beforeTitle', 'Pages')
    @section('title', 'Dashboard')
    
    <div class="flex-none w-full max-w-full px-3">
        @if($passwordDefault)
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded mb-4" role="alert">
                <p class="font-bold">Perhatian!</p>
                <p>Password Anda masih default (sama seperti NIP). Demi keamanan, silakan segera ubah password Anda melalui menu <a href="{{ route('profile') }}" class="font-bold hover:underline" wire:navigate>Profile</a>.</p>
            </div>
        @endif
        <div class="relative flex flex-col min-w-0 mb-6 break-words border-0 border-transparent border-solid rounded-2xl">
            <div class="p-5 mb-0 bg-white rounded-2xl shadow-soft-xl">
                <p class="font-semibold text-lg text-[#252F40]">Halo, {{ $this->user->nama }}</p>
                <p class="text-sm">{{ $this->user->jabatan }}</p>
            </div>
        </div>
    </div>
</div>