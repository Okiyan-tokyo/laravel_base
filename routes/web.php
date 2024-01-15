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




// 以下５つ、アップ時に消すこと！

// 選手をSQL登録
Route::get('/teamgo',[Nowlists23Controller::class,"create_new_player_sql"]
);

// 選手をシーズン途中でのアップロード
Route::get('/update_player_list',[Nowlists23Controller::class,"update_player_sql"]);

// チーム名をSQL登録
Route::get('/nowteam/teamname/team_to_sql',[Nowlists23Controller::class,"teamname_to_sql"]
);

// 背番号が重なっていないか？
Route::get("testpage",function(){
  return view("testpage");
});

// 年度の変更
Route::get("yearChange",[Nowlists23Controller::class,""])
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

// 正解に1をプラスする
Route::post("/posts.result",[JteamController::class,"result_plus"])->name("plusroute");

// エラーページ
Route::get("error",[JteamController::class,"whenerror"])
->name("errorroute");


