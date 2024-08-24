<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nowlists23;

class IrregularRegisterController extends Controller
{
       // 全ての名前上のイレギュラーパターンを取得
       public function irregular_name_check(){

        // ファイルにある全選手の取得(配列で変換)
        $nowlists23sController=new Nowlists23Controller();
        $all_player_lists=$nowlists23sController->player_info_from_text();

        // 背番号のエラーチェック
        $no_number_players=$this->no_number_players($all_player_lists);

        // 過去に例外だった名前ではないか？
        $player_name_exceptions=$this->player_name_exceptions($all_player_lists);

        // 「（」がついた選手の取得
        $with_kakko_players=$this->get_with_kakko_players($all_player_lists);

        // カンマがない＝partがfullと同じ、選手たちのリスト
        $no_conma_players=$this->get_no_comma_players($all_player_lists);


        return view("config/show_irregular_players")->with([
            "no_number_players"=>$no_number_players,
            "with_kakko_players"=>$with_kakko_players,
            "no_conma_players"=>$no_conma_players,
            "player_name_exceptions"=>$player_name_exceptions,
        ]);

    }


    // 背番号がない（＝２種）時は1000番代(txtファイル登録で)
    public function no_number_players($players){
        $no_number_players=[];
        $regex="/^[1-9]/";
        foreach($players as $player){
            if(!preg_match($regex,$player["num"]) || $player["num"]===1000){
                $no_number_players[]=[
                    "team"=>$player["team"],
                    "full"=>$player["full"]
                ];
            }
        }
        return $no_number_players;
    }

    // これまでに存在した選手名の例外を訂正(txtファイル登録で)
    public function player_name_exceptions($players){

        // 訂正前のpartを訂正後のpartへ
        $exception_players_list=[
            "ファンウェルメスケルケン際"=>"ファン,ウェルメスケルケン,際",
            "舞行龍ジェームズ"=>"舞行龍,ジェームズ",
            "キアッドティフォーンウドム"=>"キアッドティフォーン・ウドム"
        ];

        // その名前での登録が存在しない場合
        $exception_players_in_txt=[];

        foreach($players as $player){
            if(array_key_exists($player["full"],$exception_players_list)){
                $exception_players_in_txt[]=[
                    "team"=>$player["team"],
                    "full"=>$player["full"]
                ];
            }
        }

        // 見つからなかった選手たちが返還
        return  $exception_players_in_txt;
    }

    // fullに（や(がついた選手の取得(txtファイル登録で)
    public function get_with_kakko_players($players){
        $regex="/(\(|\（)+/u";
        $with_kakko_players=[];
        foreach($players as $player){
            if(preg_match($regex,$player["full"])){
                $with_kakko_players[]=[
                    "team"=>$player["team"],
                    "full"=>$player["full"]
                ];
            }
        }
        return $with_kakko_players;
    }

    // partにカンマがない選手の取得 (txtファイル登録で)
    public function get_no_comma_players($players){
        $regex="/,/u";
        $no_conma_players=[];
        foreach($players as $player){
            if(!preg_match($regex,$player["part"])){
                $no_conma_players[]=[
                    "team"=>$player["team"],
                    "full"=>$player["full"]
                ];
            }
        }
        return $no_conma_players;
    }

    // 背番号の重なりをチェック
    public function duplicated_number_check(){


        $alldata=Nowlists23::all();

        // 背番号重複リストの格納
        $duplicated_lists=
        $alldata->groupBy(function($item){
            return($item["team"]."_".$item["num"]);
        })->filter(function($group){
            return count($group)>1;
        })->map(function($group){
            return $group->first()["team"]."...".$group->first()["num"];
        })->values()->toArray();

        return view("config/duplicated_number_check",[
            "lists"=>$duplicated_lists
        ]);
    }
}
