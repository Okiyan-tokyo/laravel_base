<!DOCTYPE html>

<html lang="ja">

<head>
  <meta charset="utf8">
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
  <title>{{$title}}</title>
  <link rel="stylesheet" href="{{ url("css/styles.css") }}">
</head>
<script src="https://code.jquery.com/jquery-3.6.3.min.js"
integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU="
crossorigin="anonymous"></script>
<script src="{{ url("js/game.js")}}"></script>

{{$slot}}

</body>


</html>