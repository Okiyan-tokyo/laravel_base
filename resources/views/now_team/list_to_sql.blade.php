<?php
use App\Models\Nowlists22;

// まずは全件削除
Nowlists22::truncate();

// txtのファイルの取得

// phpのみならこれ
// $txtfiles=glob(__DIR__."/*.txt");

// laravelならこれ
$txtfiles=glob(resource_path()."/views/now_team/*.txt");

// 正規表現

// $ptn_name="/[0-9]+ ([ぁ-ん]|[ァ-ヴー]|[一-龠]|　)+]/u";
$ptn_num="/(?:[0-9])+/u";
$ptn_name="/(?:[ぁ-ん]|[ァ-ヴー]|[一-龠々]|　)+/u";

$teamnamelists=[];

// txtファイルを開いて１行ずつ取り出す
foreach($txtfiles as $txt){
  $lists=file($txt);
  $slashpoint=mb_strpos($txt,"now_team/");
  $teamandtxt=mb_substr($txt,$slashpoint+9);
  $team=mb_substr($teamandtxt,0,mb_strlen($teamandtxt)-4);

  $teamnamelists[$team]=[];

  $n=0; 
  foreach($lists as $list){

    // 初期化
    $fullname="";
    $restname="";
    $partname=[];

    if($n%3===0){
      preg_match_all($ptn_num,$list,$numbase);
      preg_match_all($ptn_name,$list,$namebase);

   
      // echo($numbase[0][0]);

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


      $post=new Nowlists22;
      $post->team=$team;
      $post->num=$numbase[0][0];
      $post->full=$fullname;
      $post->part=implode(",",$partname);
      $post->save();

    }
    $n++;
  }
  }

// $teamnamelistsをsqlへ