<?php

// /nowteam/teamname/team_to_sql

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teamname;
use App\Models\Nowlists23;
use App\Models\Archive;
use App\Models\Team_archive;
use App\Enums\PublishStateType;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Nowlists23Controller extends Controller
{

    // 新年度のチーム名をSQL追加
    private function teamname_to_sql(){

        // チームデータ格納庫からデータ取得
        require_once(storage_path('app/files/all_teams_data.php'));


        // アーカイブにセット
        //「過去チーム」に存在しないチームを、アーカイブチームテーブルにセット(前年昇格チームなど)
        if(!$this->store_team_archive()){
            // エラーの場合
            return "store_team_archive";
        }


        // アーカイブチームテーブルの名称変更(例：JFLから同地域のチームが参入時、もしくは色の変更)
        if(!$this->archive_team_data_change(Archive_Change_Data)){
            return "team_archive_data_change";
        }


        // 過去選手データのArchiveのチーム名の変更
        if(!$this->archivePlayer_dataChange_relatedTeam(Archive_Change_Data)){
            return "archivePlayer_relatedTeam_data_change";
        }



        //テーブルの削除
        Teamname::truncate();

        // トランザクションの成否
        $transactionMessage="";
        DB::transaction(function()use(&$transactionMessage){
            try{
                // 定数は無名関数内部でも直接使用可能
                foreach(Team_Data_Lists as $list){
                    $tnsets=new Teamname();
                    $tnsets->eng_name=$list[0];
                    $tnsets->jpn_name=$list[1];
                    $tnsets->cate=$list[2];
                    if(count($list)>3){
                        $tnsets->red=$list[3];
                        $tnsets->green=$list[4];
                        $tnsets->blue=$list[5];
                    }else{
                        $tnsets->red=250;
                        $tnsets->green=250;
                        $tnsets->blue=250;
                    }
                    $tnsets->save();
                }
            }catch(\Throwable $e){
                $transactionMessage="teamdata_update";
            }
        });
        // エラーの場合、元のルートにエラーですよの表示を返す
        return $transactionMessage;
    }

    public function player_info_from_text(){
        // txtのファイルの取得(storage/app)
        // ディレクトリ内のファイル一覧を取得
        $txtfiles = glob(storage_path('app/files/team_name').'/*.txt');

        // 正規表現
        $ptn_num="/^([0-9])+/u";
        $ptn_name="/([ぁ-ん]|[ァ-ヴー]|[一-龠﨑々ヶ㟢（）]|　)+/u";
        $teamnamelists=[];

        // id番号
        $id_n=0;

     // txtファイルを開いて１行ずつ取り出す
     foreach($txtfiles as $txt){
        $lists=file($txt);
        $slashpoint=mb_strpos($txt,"team_name");
        $teamandtxt=mb_substr($txt,$slashpoint+10);
        $team=mb_substr($teamandtxt,0,mb_strlen($teamandtxt)-4);

        $n=0;


        foreach($lists as $list){

            // 初期化
            $fullname="";
            $restname="";
            $partname=[];

            // 選手データは３行に１つ
            if($n%3===0){
            // idはinsertされない
            $id_n++;

            preg_match_all($ptn_num,$list,$numbase);
            preg_match_all($ptn_name,$list,$namebase);

            // 背番号がない場合はここでひとまず1000を格納
            if(count($numbase[0])===0){
                $numbase[0]=[1000];
            }

            // スペースが初めからない時の初期設定
            $fullname=$namebase[0][0];
            $partname[]=$namebase[0][0];
            $restname=$namebase[0][0];

            $spacepoint=mb_strpos($namebase[0][0],"　");
            $repeat=0;

            while(!empty($spacepoint)){
            $partname[]=mb_substr($partname[count($partname)-1],$spacepoint+1);
            array_splice($partname,count($partname)-2,1,mb_substr($restname,0,$spacepoint));
            $restname=mb_substr($restname,$spacepoint+1);
            $fullname=implode("",$partname);
            $spacepoint=mb_strpos($restname,"　");
            }
            // 格納(後にSQL登録or編集)
            $teamnamelists[]=[
                // 複数挿入できるinsertメソッドを使う場合、idとcreated atは手動挿入が必要
                "id"=>$id_n,
                "created_at" => date("Y-m-d H:i:s", time()),
                "updated_at" => date("Y-m-d H:i:s", time()),
                "team"=>$team,
                "num"=>$numbase[0][0],
                "full"=>$fullname,
                "part"=>implode(",",$partname),
                'right_full' => 0,
                'right_part' => 0,
                'right_withnum' => 0
            ];
        }
            $n++;
        }
      }

      return $teamnamelists;
    }

    // 新たに登録する時
    private function create_new_player_sql(){

        // 全データ取得
        $playerlists=$this->player_info_from_text();

        // まずは全件削除
        Nowlists23::truncate();
        // トランザクション成否
        $transactionMessage="";
        // 挿入
        DB::transaction(function()use($playerlists, &$transactionMessage){
         try{
           Nowlists23::insert($playerlists);
         }catch(\Throwable $e){
            $transactionMessage="player_update";
         }
        });

        // 初回のみ
        // return view("now_team.list_to_sql");

        // ２回目からはこれ。これをyear_changeに返す
        if(!empty($transactionMessage)){
           return $transactionMessage;
        }
    }

    // アーカイブにない場合、本年度のチーム情報をセット
    private function store_team_archive(){
        try{
         DB::transaction(function(){
            // 現在過去登録チームに入っているチーム名の取得
            $team_archives=Team_archive::pluck("eng_name");

            // 初回登録ではない時
            if(!$team_archives->isEmpty()){

                // 去年の登録チーム名が過去データに入っていないものを取得
                $lastyearTeamNames_notInArchives=Teamname::whereNotIn("eng_name",$team_archives->toArray())->get();

            // 初回登録の時
            }else{
                $lastyearTeamNames_notInArchives=Teamname::all();
            }


            // 過去データに入っていないチームを過去データに登録
            foreach($lastyearTeamNames_notInArchives as $last_year_team){
                $new_team_archive=new Team_archive();
                $new_team_archive->jpn_name=$last_year_team->jpn_name;
                $new_team_archive->eng_name=$last_year_team->eng_name;
                $new_team_archive->red=$last_year_team->red;
                $new_team_archive->green=$last_year_team->green;
                $new_team_archive->blue=$last_year_team->blue;
                $new_team_archive->save();
             }
           });
          return true;
        }catch(\Throwable $e){
             // 失敗の場合
             Log::info($e->getMessage());
             return false;
        }
    }

    // チームデータの変更
    private function archive_team_data_change($change_data){
        try{
        DB::transaction(function()use(&$change_data){
                // 引数でapp/storageに保存したチームデータが返ってくる
                // keyByで並び替えたら、そのkeyを持つ最後のデータのみ取得されるので、存在する場合は必ず要素は1つになる

                $stored_data_require_chenge=Team_Archive::whereIn("eng_name",array_keys($change_data))->get()->keyBy("eng_name");

                foreach($change_data as $stored_eng_name=>$each_change_data_sets){

                    // 過去チームデータがない時
                    if(!isset($stored_data_require_chenge[$stored_eng_name])){
                        throw new Exception($stored_eng_name."の数が異常です");
                    }
                    // keybyでチームごとに取り出したstoreされているコレクションに対し、モデルインスタンスを取り出す。
                    $team=$stored_data_require_chenge[$stored_eng_name];
                    foreach($each_change_data_sets as $key=>$value){
                        if(!array_key_exists($key,$team->toArray())){
                            throw new Exception("キーがありません");
                        }
                        $team->$key=$value;
                    }
                    // モデルの変更を保存
                    $team->save();
                }
            });
            return true;
        }catch(\Throwable $e){
            Log::info($e->getMessage());
            return false;
        }
    }


    // クイズデータのArchive登録
    public function quiz_data_to_archive($old_year_name,$messageFromTransaction){
        DB::transaction(function()use($old_year_name,&$messageFromTransaction){
            try{
            // 既に過去データが登録後ではないか？
            if(count(Archive::where("season","=",$old_year_name)->get())>0){
                throw new \PDOException("exist");
            }
            // 過去データの登録
            $year_result=Nowlists23::all();
            foreach($year_result as $yr){
                // １度でも回答された選手のみ登録
                if($yr->right_part>0 || $yr->right_full>0 || $yr->right_withnum>0){
                    $archive=new Archive();

                    // 共通カラムをコピー(ダイレクトにコピーするとモデル自体がコピーされるので、１つずつ行う)
                    $commons=["num","team","full","part","right_part","right_full","right_withnum"];
                    foreach($commons as $c){
                        $archive->$c=$yr->$c;
                    }

                    // どのシーズンかを登録
                    $archive["season"]=$old_year_name;
                    $archive->save();

                }
              }
            }catch(\Throwable $e){
              Log::info($e->getMessage());
              $messageFromTransaction="store_quiz_archive";
            }
        });

        if(!empty($messageFromTransaction)){
            return $this->config_to_error($messageFromTransaction);
            exit;
        }
    }

    // 過去クイズ履歴のチーム名変更に伴う処理
    private function archivePlayer_dataChange_relatedTeam($change_data){
        try{
            // change_dataがチーム名
            DB::transaction(function()use(&$change_data){
                // 変更チームデータのそれぞれを抽出
                foreach($change_data as $stored_eng_name=>$each_change_data_sets){
                    // 変更データのeng_name
                    if(array_key_exists("eng_name",$each_change_data_sets)){
                        // 選手のArchiveからチーム名が変更チームのキーに含まれているものを抽出して変更
                        $stored_data_require_chenge=Archive::where("team",$stored_eng_name)->get();
                        foreach($stored_data_require_chenge as $each_stored_data){
                            $each_stored_data->team=$each_change_data_sets["eng_name"];
                            $each_stored_data->save();
                        }
                    }
                }
            });
            return true;
        }catch(\Throwable $e){
            Log::info($e->getMessage());
            return false;
        }
    }


    //sqlリストにあり＝選手名鑑になしの場合チェック(foreach内部の個々)
    private function isTextFileDataExists_inSql($sql_data,$playerlists){
    return collect($playerlists)->contains(function($player)use($sql_data){
        return(
            $player["team"] === $sql_data->team &&
            $player["full"] === $sql_data->full &&
            $player["part"] === $sql_data->part &&
            intval($player["num"]) === $sql_data->num
            );
        });
  }

    //選手名間あり＝sqlになしの場合チェック(foreach内部の個々)
    private function isSqlDataExists_inTextfile($text_data){
        return Nowlists23::where([
            ["team","=",$text_data["team"]],
            ["full","=",$text_data["full"]],
            ["part","=",$text_data["part"]],
            ["num","=",intval($text_data["num"])]
            ])->exists();
    }


    // シーズン途中でのアップロード
    public function update_player_sql(){

        // 例外表示と訂正
        // まずは確認しよう！！（config/view_irregular）

            // 全データ取得
            $playerlists=$this->player_info_from_text();

            $alldata=Nowlists23::all();

            $out_information=[];
            $in_information=[];


            // sqlリストにあり＝選手名鑑になし＝データ削除
            foreach($alldata as $data){
                if(!$this->isTextFileDataExists_inSql($data,$playerlists)){
                    $out_information[]=$data;
                    $data->delete();
                }
            }


            // sqlリストになし＝選手名鑑にあり＝データ挿入
            foreach($playerlists as $player){
                if(!$this->isSqlDataExists_inTextfile($player)){
                    $in_information[]=$player;
                    // １行ずつIDをつけて挿入する方法
                    // idが重なる対策に、10000人以上登録はいないと言う前提だが・・・
                    $player["id"]=$player["id"]+10000;
                    DB::table("nowlists23s")->insert($player);
               }
           }

            return view("now_team.update_playerlists")->with(["in_information"=>$in_information,"out_information"=>$out_information]);

        }




    // 年度の変更
    public function year_change(Request $request){

        // old_year_nameのバリデーションの年度取得
        $pastYear=date("Y",time())-1;
        $thisYear=date("Y",time());
        $regex=$pastYear."|".$thisYear."|".$pastYear."_".$thisYear."|no_store";


        //  バリデーション
        $request->validate([
            "old_year_name" => [
                // regexの内部の|が、requiredとregexの区切りと重なるので、requiredとregexの区切りは配列で行う必要がある
                "required",
                "regex:/^(".$regex.")$/u"
            ],
            "pass"=>"required|regex:/^(".env("YEAR_CHANGE_PASS").")$/"
        ],[
            "old_year_name.required"=>"前年度が入力されていません",
            "old_year_name.regex"=>"前年度の値が不正です",
            "pass.required"=>"パスワードが入力されていません",
            "pass.regex"=>"パスワードの値が不正です",
        ]);

        // どの年度のデータを保存するか？
        $old_year_name=$request->old_year_name;

        // トランザクションがOKか？
        $messageFromTransaction="";



// 後で消す
// goto A;

        // クイズデータのArchive登録
        // エラーなら内部でエラーページが返ってexitする
        // 未確認なので実験必要！！！！！！！！
        $this->quiz_data_to_archive($old_year_name,$messageFromTransaction);


// 後で消す
// A:

        // チームの名前とカテの更新
        // transactionがエラーで返ればエラー
        $methods=[
            $this->teamname_to_sql(),
            $this->create_new_player_sql()
        ];
        foreach($methods as $method){
            $isUpdateOk=$method;
            if(!empty($isUpdateOk)){
               return $this->config_to_error($isUpdateOk);
               exit;
            }
        }

        return view("config/sign")->with([
            "message"=>"旧年をアーカイブ登録し\n新年度を登録しました！"
        ]);

    }


    // エラーページへ
    public function config_to_error($message){
        return view("error")->with([
            "ptn"=>$message
        ]);
    }

}
