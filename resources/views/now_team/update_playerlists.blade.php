<?php

echo nl2br("データアップロード完了しました。\n完了点は以下の通りです\n\n");



echo "OUT";

echo nl2br("\n");

foreach($out_information as $out){
  echo "名前...".$out["full"];
  echo nl2br("\n");
  echo "チーム...".$out["team"];
  echo nl2br("\n");
}


echo nl2br("\n");
echo nl2br("\n");

echo "IN";
echo nl2br("\n");

foreach($in_information as $in){
  echo "名前...".$in["full"];
  echo nl2br("\n");
  echo "チーム...".$in["team"];
  echo nl2br("\n");
}




// use App\Models\Nowlists23s;

// $lists=Nowlists23s::all();


//  // txtのファイルの取得
//   // laravelならこれ
//   $txtfiles=glob(resource_path()."/views/now_team/team_name/*.txt");
//   // 正規表現
//   // $ptn_name="/[0-9]+ ([ぁ-ん]|[ァ-ヴー]|[一-龠]|　)+]/u";
//   $ptn_num="/(?:[0-9])+/u";
//   $ptn_name="/(?:[ぁ-ん]|[ァ-ヴー]|[一-龠﨑々（）]|　)+/u";

//   $teamnamelists=[];

//   // txtファイルを開いて１行ずつ取り出す
//   foreach($txtfiles as $txt){
//     $lists=file($txt);
//     $slashpoint=mb_strpos($txt,"team_name");
//     $teamandtxt=mb_substr($txt,$slashpoint+10);
//     $team=mb_substr($teamandtxt,0,mb_strlen($teamandtxt)-4);

//     $teamnamelists[$team]=[];

//     $n=0;   

//     foreach($lists as $list){

//       // 初期化
//       $fullname="";
//       $restname="";
//       $partname=[];

//       if($n%3===0){
//         preg_match_all($ptn_num,$list,$numbase);
//         preg_match_all($ptn_name,$list,$namebase);

    
//         // echo($numbase[0][0]);

//         // スペースが初めからない時の初期設定
//         $fullname=$namebase[0][0];
//         $partname[]=$namebase[0][0];
//         $restname=$namebase[0][0];

//         $spacepoint=mb_strpos($namebase[0][0],"　");
//         $repeat=0;

//         while(!empty($spacepoint)){
//           $partname[]=mb_substr($partname[count($partname)-1],$spacepoint+1);
//           array_splice($partname,count($partname)-2,1,mb_substr($restname,0,$spacepoint));
//           $restname=mb_substr($restname,$spacepoint+1);
//           $fullname=implode("",$partname);
//           $spacepoint=mb_strpos($restname,"　");
//         }


//           // $post=new Nowlists23;
//           // $post->team=$team;
//           // $post->num=$numbase[0][0];
//           // $post->full=$fullname;
//           // $post->part=implode(",",$partname);
//           // $post->right_full=0;
//           // $post->right_part=0;
//           // $post->right_withnum=0;
//           // $post->save();
    

//       }
//       $n++;
//     }
//   }