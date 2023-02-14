<x-layout>
  <x-slot name="title">選手ランキング</x-slot>

<body class="body_not_trans" style="background: url({{url("img/img2.jpg")}});background-size:contain;">

<h1 class="recordh1">回答された選手ランキング</h1>
<h3 class="recordh3">フルネームで回答</h3>
<table class="rank_table">
  <thead>
    <tr class="rank_trhead">
      <th class="rank_th">順位</th>
      <th class="rank_th">名前</th>
      <th class="rank_th">チーム</th>
      <th class="rank_th">正解された回数</th>
    </tr>
  </thead>
<tbody>
  @forelse ($full as $f) 
    @if(isset($prev_full) && $prev_full>$f->right_full)
      <?php $rank++; ?>
    @endif
    <tr class="rank_tr" style="background-color:rgb({{$f->r}},{{$f->b}},{{$f->g}})" data-r="{{$f->r}}" data-b="{{$f->b}}" data-g="{{$f->g}}">
      <td class="rank_td">{{ $rank }}</td>
      <td class="rank_td">{{ $f->full }}</td>
      <td class="rank_td">{{ $f->team }}</td>
      <td class="rank_td">{{ $f->right_full }}</td>
    </tr>
    <?php  $prev_full=$f->right_full; ?>
  @empty    
    <tr>{{まだ解かれていません}}</tr>
  @endforelse
</tbody>
</table>


<h3 class="recordh3">名前の一部で回答</h3>
<table class="rank_table">
  <thead>
    <tr class="rank_trhead">
      <th class="rank_th">順位</th>
      <th class="rank_th">名前</th>
      <th class="rank_th">チーム</th>
      <th class="rank_th">正解された回数</th>
    </tr>
  </thead>
<tbody>
  @forelse ($part as $p) 
    @if(isset($prev_part) && $prev_part>$p->right_part)
      <?php $rank++; ?>
    @endif
<tr class="rank_tr" style="background-color:rgb({{$p->r}},{{$p->b}},{{$p->g}})" data-r="{{$p->r}}" data-b="{{$p->b}}" data-g="{{$p->g}}">
      <td class="rank_td">{{ $rank }}</td>
      <td class="rank_td">{{ $p->full }}</td>
      <td class="rank_td">{{ $p->team }}</td>
      <td class="rank_td">{{ $p->right_part }}</td>
    </tr>
    <?php  $prev_part=$p->right_part; ?>
  @empty    
    <tr>{{まだ解かれていません}}</tr>
  @endforelse
</tbody>
</table>

<h3 class="recordh3">背番号セットで回答</h3>
<table class="rank_table">
  <thead>
    <tr class="rank_trhead">
      <th class="rank_th">順位</th>
      <th class="rank_th">名前</th>
      <th class="rank_th">チーム</th>
      <th class="rank_th">正解された回数</th>
    </tr>
  </thead>
<tbody>
  @forelse ($withnum as $w) 
    @if(isset($prev_withnum) && $prev_withnum>$w->right_withnum)
      <?php $rank++; ?>
    @endif
    <tr class="rank_tr" style="background-color:rgb({{$w->r}},{{$w->b}},{{$w->g}})" data-r="{{$w->r}}" data-b="{{$w->b}}" data-g="{{$w->g}}">
      <td class="rank_td">{{ $rank }}</td>
      <td class="rank_td">{{ $w->full }}</td>
      <td class="rank_td">{{ $w->team }}</td>
      <td class="rank_td">{{ $w->right_withnum }}</td>
    </tr>
    <?php  $prev_withnum=$w->right_withnum; ?>
  @empty    
    <tr>{{まだ解かれていません}}</tr>
  @endforelse
</tbody>
</table>


<div class="backtopdiv">
  <p class="backtopp"><a class="backtopa" href="{{route("indexroute")}}">戻る</a>
  </p>
</div>

</x-layout> 