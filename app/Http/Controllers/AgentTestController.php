<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class AgentTestController extends Controller
{
    /**
     * @throws ConnectionException
     * @throws RequestException
     */
    public function test1()
    {
        $response = Http::timeout(5)
            ->acceptJson()
            ->withHeaders([
                'X-Orbit-Token' => 'xorbit_P1yeYo4QLpyVTKL8Y3DQKqSwiOaEO18q',
            ])
            ->get('http://192.168.2.2:42100/pair');

        $response->throw();

        return response()->json($response->json());
    }

    public function test2()
    {
        $response = Http::timeout(5)
            ->acceptJson()
            ->withHeaders([
                'X-Orbit-Token' => 'xorbit_P1yeYo4QLpyVTKL8Y3DQKqSwiOaEO18q',
            ])
            ->get('http://192.168.2.2:42100/metrics/hw/cpu');

        $response->throw();

        return response()->json($response->json());
    }

    public function test3()
    {
        $response = Http::timeout(5)
            ->acceptJson()
            ->withHeaders([
                'X-Orbit-Token' => 'xorbit_P1yeYo4QLpyVTKL8Y3DQKqSwiOaEO18q',
            ])
            ->get('http://192.168.2.2:42100/exec/reboot');

        $response->throw();

        return response()->json($response->json());
    }
}
