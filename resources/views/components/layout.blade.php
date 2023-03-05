<!DOCTYPE html>

<html lang="ja">

<head>

<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
<meta property="og:title" content="Jリーグ選手当てクイズ！" />
<meta property="og:type" content="website" />
<meta property="og:url" content="https://prog-prac.hiho.jp/quizgame/public/"/>
<meta property="og:image" content="https://prog-prac.hiho.jp/quizgame/public/img/img2.jpg" />
<meta property="og:site_name" content="Jリーグ選手当てクイズ！" />
<meta property="og:description" content="Jリーグの選手名を知っているだけ列挙するサイトです" />
<meta name="twitter:image" content="https://prog-prac.hiho.jp/quizgame/public/img/img2.jpg"/>
<meta name="twitter:card" content="app"/>

  <meta charset="utf8">
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
  <title>{{$title}}</title>
  <link rel="stylesheet" href="{{ url("css/styles.css") }}">
  <link rel="icon" href="{{url("img/quiz.ico")}}">
</head>
<script src="https://code.jquery.com/jquery-3.6.3.min.js"
integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU="
crossorigin="anonymous"></script>
<script differ src="{{ url("js/game.js")}}"></script>

{{$slot}}

</body>


</html>