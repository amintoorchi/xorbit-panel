<?php

namespace App\Livewire\Pages;

use App\Models\Server;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Manage Servers')]
class Servers extends Component
{
    public string $name = '';
    public string $host = '';
    public string $username = '';
    public int $port;
    public string $password = '';


    public function resetForm(): void
    {
        $this->reset([
            'name',
            'host',
            'username',
            'port',
            'password',
        ]);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'host' => ['required', 'ip', 'unique:servers,host'],
            'username' => ['required', 'string', 'max:255'],
            'port' => ['required', 'integer', 'between:1,65535'],
            'password' => ['required', 'string'],
        ]);

        Server::create($validated);

        $this->resetForm();

        Flux::modal('add-server')->close();

        Flux::toast(
            heading: 'Server added',
            text: 'The server has been added successfully.',
            variant: 'success',
        );
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
