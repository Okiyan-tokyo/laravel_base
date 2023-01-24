<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teamname extends Model
{
    use HasFactory;
    protected $fillable=[
        "eng_name","jpn_name","cate","red","blue","green"
    ];
}
// /Users/okiyan/Desktop/answer_player/database/migrations/2023_01_22_124109_create_nowlists22s_table.php