<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Profile as ProfileModel;
use Illuminate\Validation\ValidationException;

class Profile extends Component
{
    public $profile;
    public $pass, $new_pass1, $new_pass2;

    public function mount()
    {
        $this->profile = ProfileModel::where(['username'=>Auth::user()->username, 'active'=>1])->first();
    }

    public function editPass()
    {
        // Validasi input
        $this->validate([
            'pass' => 'required|string',
            'new_pass1' => 'required|string|min:8|different:pass',
            'new_pass2' => 'required|same:new_pass1',
        ], [
            'pass.required' => 'Password saat ini wajib diisi.',
            'new_pass1.required' => 'Password baru wajib diisi.',
            'new_pass1.min' => 'Password baru minimal 8 karakter.',
            'new_pass1.different' => 'Password baru harus berbeda dari yang lama.',
            'new_pass2.same' => 'Ulangi password harus sama dengan password baru.',
        ]);

        $user = Auth::user();

        // Verifikasi password lama
        if (!Hash::check($this->pass, $user->password)) {
            throw ValidationException::withMessages([
                'pass' => ['Password saat ini salah.'],
            ]);
        }

        // Update password
        $user->password = Hash::make($this->new_pass1);
        $user->save();

        // Reset form
        $this->reset(['pass', 'new_pass1', 'new_pass2']);

        // Optional: notifikasi success
       $this->dispatch('showFlashMessage', 'Password berhasil diubah!', 'success');
    }

    public function render()
    {
        return view('livewire.profile.profile')->extends('layouts.user');
    }
}
