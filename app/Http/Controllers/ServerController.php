<?php

namespace App\Http\Controllers;

use phpseclib3\Net\SSH2;

class ServerController extends Controller
{
    public function index()
    {
        $ssh = new SSH2('2.186.12.227', 922);

        if (! $ssh->login('administrator', 'MoMh*318*tor')) {
            abort(500, $ssh->getLastError());
        }

        $state = trim($ssh->exec("docker ps \
--format '{{.Names}}|{{.Image}}|{{.Status}}'"));

        return view('dashboard', [
            'state' => $state,
        ]);
    }
}