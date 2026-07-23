<?php

namespace App\Livewire\Pages;

use App\Models\Server as ModelsServer;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Manage Server')]
class Server extends Component
{
    public string $tab = 'overview';

    public ModelsServer $server;

    public function mount(ModelsServer $server): void
    {
        $this->server = $server;
    }

    public function render()
    {
        return view('pages.server');
    }
}
