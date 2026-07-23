<?php

namespace Database\Seeders;

use App\Models\Server;
use Illuminate\Database\Seeder;

class ServersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Server::create(['name' => 'Test Server', 'password' => 'Mh*318*tor', 'host' => '127.0.0.1']);
    }
}
