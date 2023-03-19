<?php

// このページで背番号の例外を訂正

use App\Models\Nowlists23;



$alldata=new Nowlists23();

$alllists=$alldata->all();

foreach ($alllists as $each1){
  foreach ($alllists as $each2){
// 背番号の重なりがないかのチェック
  if($each1["team"]===$each2["team"] && $each1["num"]===$each2["num"] && $each1["id"]<$each2["id"]){
    // echo nl2br($each1["full"]."...".$each2["full"]."/".$each1["team"]."\n");

    // 上記確認後、右側がユース組で間違いがなければ、下記で２種の選手の背番号を１０００にする
    // $player=$alldata->find($each2["id"]);
    // DB::transaction(function()use($player){
    //   $player->num=1000;
    //   $player->save();
    // });
  }
}





  // 背番号がない選手がいないかのチェック
  if(empty($each1["num"])){
    echo nl2br($each1["full"]."...".$each1["team"]."\n");
    // $player2=$alldata->find($each1["id"]);
    // DB::transaction(function()use($player2){
    //   $player2->num=1000;
    //   $player2->save();
    // });

  }
}

echo ("個別対応（前段階はそうだったので確認しよう）\n長崎の２番相当はユース組");

