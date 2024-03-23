<?php

// /nowteam/teamname/team_to_sql

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teamname;
use App\Models\Nowlists23;
use App\Models\Archive;
use App\Enums\PublishStateType;
use Illuminate\Support\Facades\DB;

class Nowlists23Controller extends Controller
{
    
    public function teamname_to_sql(){
        Teamname::truncate();

        // トランザクションの成否
        $transactionMessage="";
        DB::transaction(function()use(&$transactionMessage){
            try{
                $lists=[
                // J1
                ["sapporo","札幌","J1", 215, 0,15],       
                ["kashima","鹿島","J1",183 ,24,64],       
                ["urawa","浦和","J1",231,0,43],      
                ["kashiwa","柏","J1",255,241,0],
                ["fc_tokyo","FC東京","J1",33,65,152],
                ["tokyo_v","東京V","J1",3,118,75],
                ["machida","町田","J1",0,35,106],
                ["kawasaki","川崎","J1",53,160,217],
                ["yokohama_fm","横浜FM","J1",0,57,137],
                ["shonan","湘南","J1",103,180,100],
                ["niigata","新潟","J1",255,102,0],
                ["iwata","磐田","J1",110,157,211],
                ["nagoya","名古屋","J1",218,54,27],
                ["kyoto","京都","J1",116,0,107],
                ["g_osaka","G大阪","J1",9,63,166],
                ["c_osaka","C大阪","J1",212,0,105],
                ["kobe","神戸","J1",143,10,31],
                ["hiroshima","広島","J1",80,49,143],
                ["fukuoka","福岡","J1",0,64,127],
                ["tosu","鳥栖","J1",0,150,210],

                // J2
                ["sendai","仙台","J2",252,204,0],
                ["akita","秋田","J2",0,91,171],
                ["yamagata","山形","J2",15,34,139],
                ["iwaki","いわき","J2",192,23,48],
                ["mito","水戸","J2",29,31,144],
                ["tochigi","栃木","J2",245,241,12],
                ["gunma","群馬","J2",1,62,116],
                ["yokohama_fc","横浜FC","J2",0,160,228],
                ["chiba","千葉","J2",254,225,0],
                ["kofu","甲府","J2",0,91,172],
                ["shimizu","清水","J2",250,165,40],
                ["fujieda","藤枝","J2",134,52,124],
                ["okayama","岡山","J2",181,1,62],
                ["yamaguchi","山口","J2",235,94,2],
                ["ehime","愛媛","J2",255,102,0],
                ["tokushima","徳島","J2",17,17,131],
                ["nagasaki","長崎","J2",243,152,0],
                ["kumamoto","熊本","J2",186,26,20],
                ["oita","大分","J2",20,11,140],
                ["kagoshima","鹿児島","J2",22,51,95],
                
                // J3
                ["hachinoe","八戸","J3",20,168,59],
                ["iwate","岩手","J3",255,255,255],
                ["fukushima","福島","J3",230,0,18],
                ["omiya","大宮","J3",245,105,0],
                ["ys_yokohama","YS横浜","J3",91,211,229],
                ["sagamihara","相模原","J3",39,142,66],
                ["matsumoto","松本","J3",2,61,29],
                ["nagano","長野","J3",235,97,0],
                ["toyama","富山","J3",17,25,135],
                ["kanazawa","金沢","J3",229,0,9],
                ["numazu","沼津","J3",0,30,179],
                ["gifu","岐阜","J3",0,64,23],
                ["fc_osaka","FC大阪","J3",126,201,240],
                ["nara","奈良","J3",1,29,100],
                ["tottori","鳥取","J3",144,238,144],
                ["sanuki","讃岐","J3",101,170,221],
                ["imabari","今治","J3",23,98,97],
                ["kitakyushu","北九州","J3",255,241,0],
                ["miyazaki","宮崎","J3",255,255,255],
                ["ryukyu","琉球","J3",152,7,71],
            ]; 
            foreach($lists as $list){
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
        // $ptn_name="/[0-9]+ ([ぁ-ん]|[ァ-ヴー]|[一-龠]|　)+]/u";
        $ptn_num="/(?:[0-9])+/u";
        $ptn_name="/(?:[ぁ-ん]|[ァ-ヴー]|[一-龠﨑々ヶ㟢（）]|　)+/u";
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
    public function create_new_player_sql(){
    
        // 例外表示と訂正



        // 2種の番号なしの選手は1000番にすること！
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

    // シーズン途中でのアップロード
    public function update_player_sql(){

    // 例外表示と訂正





    // 2種の番号なしの選手は1000番にすること！
        $playerlists=$this->player_info_from_text();
        $alldata=Nowlists23::all();
        
        $out_information=[];
        $in_information=[];
        

        // sqlリストにあり＝選手名鑑になし＝データ削除 
        foreach($alldata as $data){
          foreach($playerlists as $player){
                // チームも登録名も背番号も同じ＝そのまま
                // 全て同じ選手が移籍してきた時に未対応
                if(
                    $player["team"]===$data->team &&
                    $player["full"]===$data->full &&
                    $player["part"]===$data->part &&
                    intval($player["num"])===$data->num
                    ){
                        // 同じペアがあればループを抜ける
                        goto ok_1;
                    }
            }
     
            $out_information[]=$data;
            $data->delete();    
         ok_1:
        }

         // sqlリストになし＝選手名鑑にあり＝データ挿入
        foreach($playerlists as $player){
            foreach($alldata as $data){
                if(
                    $player["team"]===$data->team &&
                    $player["full"]===$data->full &&
                    $player["part"]===$data->part &&
                    intval($player["num"])===$data->num
                    ){
                        // 同じペアがあればループを抜ける
                        goto ok_2;
                    }                      
            }
            
            // 挿入
            $in_information[]=$player;
  
            // １行ずつIDをつけて挿入する方法
            // idが重なる対策に、10000人以上登録はいないと言う前提だが・・・
            $player["id"]=$player["id"]+10000;
            DB::table("nowlists23s")->insertGetId($player);
            ok_2:
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
                if($yr->right_part>0 || $yr->right_full || $yr->right_withnum>0){
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
              $messageFromTransaction=$e->getMessage();
            }    
        });

        if(!empty($messageFromTransaction)){
            return $this->config_to_error($messageFromTransaction);
            exit;
        }        

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


        $message="旧年をアーカイブ登録し\n新年度を登録しました！";

        return view("config/sign")->with([
            "message"=>"旧年をアーカイブ登録し\n新年度を登録しました！"
        ]);

    }

    // 全ての名前上のイレギュラーパターンを取得
    public function irregular_name_check(){
        // ファイルにある全選手の取得(配列で変換)
        $all_player_lists=$this->player_info_from_text();

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



    
    //未配備！！！！！！！！！！！！！！！！
    // 背番号がない（＝２種）時は1000番代(txtファイル登録で)
    public function no_number_players($players){
        $no_number_players=[];
        $regex="/^[1-9]/";
        foreach($players as $player){
            if(!preg_match($regex,$player["num"])){
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


    // エラーページへ
    public function config_to_error($message){
        return view("error")->with([
            "ptn"=>$message
        ]);
    }


    
}
