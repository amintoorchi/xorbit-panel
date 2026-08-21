<?php

namespace App\Livewire\Pages;

use App\Models\Server;
use Flux\Flux;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Manage Servers')]
class Servers extends Component
{
    public string $name = '';

    public string $host = '';

    public string $token = '';

    public ?Server $createdServer = null;

    public function resetForm(): void
    {
        $this->reset([
            'name',
            'host',
        ]);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'host' => ['required', 'ip', 'unique:servers,host'],
        ]);

        $validated['token'] = 'xorbit_'.Str::random(32);

        $this->createdServer = Server::create($validated);

        $this->resetForm();

        Flux::modal('add-server')->close();

        Flux::toast(
            heading: 'Server added',
            text: 'The server has been added successfully.',
            variant: 'success',
        );

        Flux::modal('show-credential')->show();
    }

    #[Computed]
    public function servers()
    {
        return Server::latest()->get();
    }

    #[Computed]
    public function count()
    {
        return Server::count();
    }

    public function render()
    {
        return view('pages.servers');
    }
}
