<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teamname;
use App\Enums\PublishStateType;

class Nowlists22Controller extends Controller
{
    
    public function teamname_to_sql(){
        Teamname::truncate();
        $lists=[
            ["kashima","鹿島"],       
            ["sapporo","札幌"],       
            ["urawa","浦和"]      
        ]; 
        foreach($lists as $list){
            $tnsets=new Teamname();
            $tnsets->eng_name=$list[0];
            $tnsets->jpn_name=$list[1];
            $tnsets->save();
        }
    }

}
