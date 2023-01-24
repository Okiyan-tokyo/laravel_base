<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nowlists22;
use Nowlists22Controller;
use App\Models\Teamname;
use App\Enums\PublishStateType;

class JteamController extends Controller
{
    public function index(){
        $lists=Nowlists22::groupBy("team")->get("team"); 
        return view('index')->with(["lists"=>$lists]);
    }

    public function select_team(Request $request){
        $team=$request->teamselect;
        $type=$request->typeselect;
        return view("game")->with(["team"=>Teamname::where("eng_name","=",$team)->get("jpn_name"),
         "type"=>$type,
         "lists"=>Nowlists22::where("team","=",$team)->get()
        ]);
    }
}
