<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JteamController;
use App\Http\Controllers\Nowlists23Controller;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// スタート画面
Route::get('/',[JteamController::class,"index"])->name("indexroute");

// チームを選んでゲーム開始
Route::post('/select.team',[JteamController::class,"select_team"])->name("selectteamroute");




// 以下６つ、アップ時に消すこと！

// // 選手をSQL登録
Route::get('/teamgo',[Nowlists23Controller::class,"create_new_player_sql"]
);

// // 選手をシーズン途中でのアップロード
Route::get('/update_player_list',[Nowlists23Controller::class,"update_player_sql"]);

// // チーム名をSQL登録(これは選手をsql登録と一緒にやろう。つまり単独では必要ない。むしろ単独ではエラーになる)
Route::get('/nowteam/teamname/team_to_sql',[Nowlists23Controller::class,"teamname_to_sql"]
);

// // 背番号が重なっていないか？
Route::get("testpage",function(){
  return view("testpage");
});

// イレギュラーな名前がリストに登録されていないか？
Route::get("config/view_irregular",[Nowlists23Controller::class,"irregular_name_check"])
->name("show_irregular_inTxt_route");

// // 年度の変更(確認)
Route::get("year_change_confirm",
  function(){
    return view("config/year_change_confirm")->with([
      "pastYear"=>date("Y",time())-1,
      "thisYear"=>date("Y",time())
    ]);
  }
);

// // 年度の変更(本番)
Route::patch("year_change",[Nowlists23Controller::class,"year_change"])
->name("year_change_route");






// フルネームが正しいか
Route::post("/posts.full",[JteamController::class,"answer_full"])
->name("fullroute");


// 名前の一部分が正しいか
Route::post("/posts.part",[JteamController::class,"answer_part"])
->name("partroute");


// 背番号セットが正しいか
Route::post("/posts.withnum",[JteamController::class,"answer_withnum"])
->name("withnumroute");

// 成績表
Route::get("record",[JteamController::class,"record"])
->name("recordroute");

// 30位以下を表示
Route::get("record/all/{season}/{rank_kind}",[JteamController::class,"over30view"])
->name("over_30_route");

// 過去の成績表の年度選択
Route::get("archive_year_choice",[JteamController::class,"archive"])
->name("archiveroute");

// 過去の成績表
Route::post("archive",[JteamController::class,"view_archive"])
->name("archive_decide_route");

// 正解に1をプラスする
Route::post("/posts.result",[JteamController::class,"result_plus"])->name("plusroute");

// エラーページ
Route::get("error",[JteamController::class,"whenerror"])
->name("errorroute");


