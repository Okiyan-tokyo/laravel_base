<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Nowlists23;
use Nowlists23Controller;
use App\Models\Teamname;
use App\Enums\PublishStateType;
use Illuminate\Http\JsonResponse;

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

        // チーム名がデータ以外から来ていたらここで変更
        if(!$this->team_isok($team)){
            return redirect()->route("errorroute",[
                "reason"=>"team"
            ]);
       }

        // タイプによってgame画面でのルーティングの仕分け
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
               return redirect()->route("errorroute",[
                   "reason"=>"type"
               ]);
            break;
        }

        return view("game")->with(["teamsets"=>Teamname::where("eng_name","=",$team)->get(),
         "type"=>$type,
         "formroute"=>$formroute,
         "lists"=>Nowlists23::where("team","=",$team)->get(),
         "lists_without_1000"=>Nowlists23::where([
             ["team","=",$team],
             ["num","<>",1000],
         ])->get()
        ]);
    }

    // チーム名の例外処理
    public function team_isok($team){
        // チーム名がデータ以外から来ていたらここで変更
        $baseteams=Teamname::get("eng_name");
        foreach($baseteams as $baseteam){
            if($baseteam["eng_name"]==$team){
                goto okteam;
            }
          }
          return false;
          okteam:
          return true;
    }

    public function answer_head(){
        $answer=self::h(filter_input(INPUT_POST,"answer"));
        $team=self::h(filter_input(INPUT_POST,"team"));

        // チーム名が例外の場合
        if(!$this->team_isok($team)){
            return ["","error",""];
        }else{
            // 該当チームのリスト
            $lists=Nowlists23::where("team","=",$team)->get();
            return [$answer,$team,$lists];
        }
    }
    



    // 結果挿入(part,full)
    public function result_plus(){

        $lists=explode(",",self::h(filter_input(INPUT_POST,"lists")));
        $type=self::h(filter_input(INPUT_POST,"type"));
        $team=self::h(filter_input(INPUT_POST,"team"));

        // // インジェクション対策
        // // チーム名が違っていたら処理を止める
        if(!$this->team_isok($team)){
            exit;
        }
        
        // 渡って来た「part」の値でpartを再代入。それ以外は処理しないだけ。
        if(strpos($type,"part")>0){
            $type="part";
        }else if(strpos($type,"full")>0){
            $type="full";
        }

        try{
            DB::transaction(
                function()use(&$lists,&$type,&$team){
    
                    foreach($lists as $list){
                        // 数字かどうか,インジェクション対策
                        if(is_numeric($list)){
                            $where_array=["num" => intval($list), "team" => $team];                            
                            $data=Nowlists23::where($where_array)->first();
                            switch($type){
                                   case "full":
                                        $data->right_full++;
                                   break;
                                   case "part":
                                        $data->right_part++;
                                   break;
                                   default:
                                   // エラー処理
                                   // 結果を記録登録だけなので無視する
                                    break;
                           }
                           $data->save();
                      }
                    }
                });
        }catch(PDOException $e){
            // エラー処理
            // 結果を記録登録だけなので無視する
        }
        // return response()->json(['result' =>$lists]);
        // exit;
    }

    // 結果挿入2
    public function result_plus_withnum($list){
        try{
            DB::transaction(
                function()use(&$list){
                    $list->right_withnum++;
                    $list->save();
                }
            );
        }catch(PDOException $e){
            // エラー処理
            // 結果を記録登録だけなので無視する
            }
    }
    
    public function answer_full(){
        // 正解不正解,背番号セットのフラグ    
        $isok="out";
        $numset=[];
        // 共通の変数の取得
        $lists=$this->answer_head();


        // チーム名が例外なら終了
        if($lists[1]==="error"){
            return response()->json(["isok"=>"error"]);
            exit;
        }

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
        return response()->json(["isok"=>$isok,"numset"=>$numset]);
        exit;
    }


    public function answer_part(){

        // 正解不正解,背番号セットのフラグ    
        $isok="out";
        $numset=[];
        // 共通の変数の取得
        $lists=$this->answer_head();

        if($lists[1]==="error"){
            return response()->json(["isok"=>"error"]);
            exit;
        }


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
        return response()->json(["isok"=>$isok,"numset"=>$numset]);
        exit;
    }
    
    // 結果登録のみ/背番号セット
    public function answer_withnum(){
        // 共通の変数の取得
        $lists=$this->answer_head();
        // listsはそのチームのセット
        foreach($lists[2] as $list){
            // 回答は正解の背番号セットで送信されてきている
            $numarray=explode(",",$lists[0]);
            foreach($numarray as $num){
             if(intval($num)===$list->num){
                $this->result_plus_withnum($list);
             }
            }
        }
    }

    // 選手が答えられた回数の順位
    public function record($table="nowlists23s", $season=""){

        // フルネーム
        $lists_full=DB::table($table." as n")
        ->select("n.full as full","n.right_full as right_full", "t.jpn_name as team","t.red as r", "t.blue as b","t.green as g")
        ->join("teamnames as t","n.team","=","t.eng_name")
        ->orderBy("right_full","desc")
        ->where("right_full",">",0);
        if($table!=="nowlists23s"){
          $lists_full->where("season","=",$season);            
        }
        $lists_full=$lists_full->get();

        // 名前の一部
        $lists_part=DB::table($table." as n")
        ->select("n.full as full","n.right_part as right_part", "t.jpn_name as team","t.red as r", "t.blue as b","t.green as g")
        ->join("teamnames as t","n.team","=","t.eng_name")
        ->orderBy("right_part","desc")
        ->where("right_part",">",0);
        if($table!=="nowlists23s"){
            $lists_part->where("season","=",$season);            
        }
        $lists_part=$lists_part->get();

        // 背番号とセット
        $lists_withnum=DB::table($table." as n")
        ->select("n.full as full", "t.jpn_name as team", "n.right_withnum as right_withnum","t.red as r", "t.blue as b","t.green as g")
        ->join("teamnames as t","n.team","=","t.eng_name")
        ->orderBy("right_withnum","desc")
        ->where("right_withnum",">",0);
        if($table!=="nowlists23s"){
            $lists_withnum->where("season","=",$season);            
        }
        $lists_withnum=$lists_withnum->get();

      return view("record")->with(["lists_array"=>[[$lists_full,"full"],[$lists_part,"part"],[$lists_withnum,"withnum"]]]);
    }

    // 過去の成績表(年度選択ページ)
    public function archive(){
        return $this->record($table="archives",$season=2023);
    }

    // 過去の成績表（実際に表示するページ）
    public function view_archive(){

    }


    // エラー表示
    public function whenerror(Request $request){
        $ptn=$request->query("reason");
        return view("error")->with(["ptn"=>$ptn]);
    }

}
