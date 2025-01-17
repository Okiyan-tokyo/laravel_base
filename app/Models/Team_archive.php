<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team_archive extends Model
{
    use HasFactory;
    protected $fillable=[
        "jpn_name","red","green","blue"
    ];
}
