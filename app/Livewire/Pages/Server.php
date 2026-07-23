<?php

namespace App\Livewire\Pages;

use App\Models\Server as ModelsServer;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\Attributes\Title;

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
