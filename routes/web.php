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


// 選手をSQL登録
Route::get('/teamgo',function(){
    return view("now_team/list_to_sql");
}
);

// チーム名をSQL登録
Route::get('/nowteam/teamname/team_to_sql',[Nowlists23Controller::class,"teamname_to_sql"]
);

// 修正ページ：①背番号が重なっていないか？
Route::get("testpage",function(){
  return view("testpage");
});

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


