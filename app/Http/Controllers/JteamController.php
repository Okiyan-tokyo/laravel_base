<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nowlists23;
use Nowlists23Controller;
use App\Models\Teamname;
use App\Enums\PublishStateType;

class JteamController extends Controller
{
    static public function h($moji){
        return trim(htmlspecialchars($moji));
    }


    public function index(){
        $J1lists=Teamname::orderBy("id")->select("eng_name", "jpn_name", "cate")->where("cate","=","J1")->get(); 
        $J2lists=Teamname::orderBy("id")->select("eng_name", "jpn_name", "cate")->where("cate","=","J2")->get(); 
        $J3lists=Teamname::orderBy("id")->select("eng_name", "jpn_name", "cate")->where("cate","=","J3")->get(); 
        return view('index')->with([
            "J1lists"=>$J1lists,
            "J2lists"=>$J2lists,
            "J3lists"=>$J3lists
            ]);
    }

    public function select_team(Request $request){
        $request->validate(
            ["teamselect"=>"min:1",
             "typeselect"=>"min:1"
             ]
        );
        $team=$request->teamselect;
        $type=$request->typeselect;


        switch($type){
            case "full":
                $formroute="fullroute";
            break;
            case "part":
                $formroute="partroute";
            break;
            case "withnum":
                $formroute="withnumroute";
            break;
            default:
                $formroute="errorroute";
            break;
        }

        return view("game")->with(["teamsets"=>Teamname::where("eng_name","=",$team)->get(),
         "type"=>$type,
         "formroute"=>$formroute,
         "lists"=>Nowlists23::where("team","=",$team)->get()
        ]);
    }

    public function answer_head(){
        $answer=self::h(filter_input(INPUT_POST,"answer"));
        $team=self::h(filter_input(INPUT_POST,"team"));
        // 該当チームのリスト
        $lists=Nowlists23::where("team","=",$team)->get();
        return [$answer,$team,$lists];
    }
    
    public function answer_foot($isok,$numset){
        $json_answer=json_encode(["isok"=>$isok,"numset"=>$numset]);
        header("Content-Type: application/json; charset=utf-8");
        return $json_answer;
    }
    
    
    public function answer_full(){
        // 正解不正解,背番号セットのフラグ    
        $isok="out";
        $numset=[];
        // 共通の変数の取得
        $lists=$this->answer_head();
    
        // lists[2]はそのチームのセット
        foreach($lists[2] as $list){
            if($lists[0]===trim($list->full)){
                    $isok="ok";
                    $numset[]=$list->num;
            }else{
                // 外国人選手など、名前の間に・やスペースをつけた場合（半角全角ごっちゃには未対応）
                $arraypart=explode(",",$list->part);
                $pattern1=implode("・",$arraypart);
                $pattern2=implode("　",$arraypart);
                $pattern3=implode(" ",$arraypart);
                if($lists[0]===$pattern1 || $lists[0]===$pattern2 || $lists[0]===$pattern3){
                    $isok="ok";
                    $numset[]=$list->num;
                }
            }
        }
        // 共通の処理
        return json_encode($this->answer_foot($isok,$numset));
        exit;
    }


    public function answer_part(){
        // 正解不正解,背番号セットのフラグ    
        $isok="out";
        $numset=[];
        // 共通の変数の取得
        $lists=$this->answer_head();

        // listsはそのチームのセット
        foreach($lists[2] as $list){
            $arraypart=explode(",",$list->part);
            // 各部分を見ていく
            foreach($arraypart as $namepart){
                if($lists[0]===$namepart){
                    $isok="ok";
                    $numset[]=$list->num;
                    goto not_require_full;
                }
            } 
            // フルネームと合っているかを見ていく
                if($lists[0]===trim($list->full)){
                    $isok="ok";
                    $numset[]=$list->num;
                }else{
                // 外国人選手など、名前の間に・やスペースをつけた場合（半角全角ごっちゃには未対応）
                        $arraypart=explode(",",$list->part);
                        $pattern1=implode("・",$arraypart);
                        $pattern2=implode("　",$arraypart);
                        $pattern3=implode(" ",$arraypart);
                        if($lists[0]===$pattern1 || $lists[0]===$pattern2 || $lists[0]===$pattern3){
                            $isok="ok";
                            $numset[]=$list->num;
                        }
                }

            not_require_full:
        }
        // 共通の処理
        return json_encode($this->answer_foot($isok,$numset));
        exit;
    }
    

}
