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
            ["sapporo","札幌","J1", 215, 0,15],       
            ["kashima","鹿島","J1",183 ,24,64],       
            ["urawa","浦和","J1",231,0,43],      
            ["f_tokyo","FC東京","J1",33,65,152],
            ["kawasaki","川崎","J1",53,160,217],
            ["yokohama_fm","横浜FM","J1",0,57,137],
            ["syonan","湘南","J1",103,180,100],
            ["nagoya","名古屋","J1",218,54,27],
            ["gamba","G大阪","J1",9,63,166],
        ]; 
        foreach($lists as $list){
            $tnsets=new Teamname();
            $tnsets->eng_name=$list[0];
            $tnsets->jpn_name=$list[1];
            $tnsets->cate=$list[2];
            $tnsets->red=$list[3];
            $tnsets->green=$list[4];
            $tnsets->blue=$list[5];
            $tnsets->save();
        }
    }

}
