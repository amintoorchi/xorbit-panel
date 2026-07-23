<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'host', 'port', 'username', 'password', 'token'])]
#[Hidden(['password'])]
class Server extends Model
{
    use HasUuids;

    protected function casts(): array
    {
        return [
            'password' => 'encrypted',
        ];
    }
}
