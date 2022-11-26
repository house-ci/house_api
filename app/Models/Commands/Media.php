<?php

namespace App\Models\Commands;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    protected $table = 'medias';
}
