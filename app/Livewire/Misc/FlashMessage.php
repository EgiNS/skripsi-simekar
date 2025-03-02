<?php

namespace App\Livewire\Misc;

use Livewire\Component;

class FlashMessage extends Component
{
    public $message = '';
    public $type = 'success'; // 'success', 'error', 'warning', dll.
    public $show = false;

    protected $listeners = ['showFlashMessage' => 'showMessage'];

    public function showMessage($message, $type = 'success')
    {
        $this->message = $message;
        $this->type = $type;
        $this->show = true;
    }

    public function render()
    {
        return view('livewire.misc.flash-message');
    }
}
