<x-layout>
  <x-slot name="title">選手ランキング</x-slot>

<body class="body_not_trans" style="background: url({{url("img/img2.jpg")}});background-size:contain;">

<h1 class="recordh1">回答された選手ランキング</h1>

<div class="recordtablepoint" >

{{-- 30位以下へのバリデーションエラー --}}
{{-- 個々のランクの下に置くと全てにエラー表示が出るため、ここで定義 --}}
@if(!$isOver30 && ($errors->has("rank_kind") || $errors->has("season")))
  @foreach(["rank_kind","season"] as $field)
    @foreach($errors->get($field) as $message)
    <div class="select_when_error_row1">
      <p class="errormessage">{{$message}}</p>
    </div>
    @endforeach
  @endforeach
@endif


@foreach($lists_array as $lists)
<?php 
// 順位、通しナンバー、他何人のフラグ
  $rank=1;
  $serialnumber=1;
  $serialequal=0;
?>

@switch($lists[1])
  @case("part")
  <h3 class="recordh3">名前の一部で回答</h3>
  @break;
  @case("full")
  <h3 class="recordh3">フルネームで回答</h3>
  @break;
  @case("withnum")
  <h3 class="recordh3">背番号セットで回答</h3>
  @break;
@endswitch

<table class="rank_table">
  <thead>
    <tr class="rank_trhead">
      <th class="rank_th">順位</th>
      <th class="rank_th">名前</th>
      <th class="rank_th">チーム</th>
      <th class="rank_th">正解数</th>
    </tr>
  </thead>
<tbody>
  @forelse ($lists[0] as $p) 

  {{-- ほか◯◯人のフラグ --}}
  <?php $rankforother=$rank;  ?>

  {{-- 順位を足す --}}  
  @switch($lists[1])
  @case("part")
    @if(isset($prev_rank) && $prev_rank>$p->right_part)
      <?php $rank=$serialnumber; ?>
    @endif
    @break;
    @case("full")
    @if(isset($prev_rank) && $prev_rank>$p->right_full)
      <?php $rank=$serialnumber; ?>
    @endif
    @break;
    @case("withnum")
    @if(isset($prev_rank) && $prev_rank>$p->right_withnum)
      <?php $rank=$serialnumber; ?>
    @endif
  @break;
  @endswitch
    
  {{-- over30ではない場合 --}}
  {{-- 順位が指定以上の場合 --}}
  @if($isOver30 || !$isOver30 && $serialnumber<=30)
      <tr class="rank_tr" style="background-color:rgb({{$p->r}},{{$p->g}},{{$p->b}})" data-r="{{$p->r}}" data-b="{{$p->b}}" data-g="{{$p->g}}">
          <td class="rank_td">{{ $rank }}</td>
          <td class="rank_td">{{ $p->full }}</td>
          <td class="rank_td">{{ $p->team }}</td>
          @switch($lists[1])
          @case("part")
          <td class="rank_td">{{ $p->right_part }}</td>
          @break;
          @case("full")
          <td class="rank_td">{{ $p->right_full }}</td>
          @break;
          @case("withnum")
          <td class="rank_td">{{ $p->right_withnum }}</td>
          @break;
          @endswitch
        </tr>
  {{-- ほか◯◯名 --}}
     @else
            @if($rank===$serialnumber && $serialequal!==0 && $rankforother<31)
                 <tr class="rank_tr_none" ><td class="rank_td_none"  colspan="4">{{$rankforother}}位…ほか{{$serialequal}}選手</td></tr>
            @elseif(count($lists[0])===$serialnumber && $rankforother<31)
                <tr class="rank_tr_none" ><td class="rank_td_none"  colspan="4">{{$rankforother}}位…ほか{{$serialequal+1}}選手</td></tr>
            @else
             <?php $serialequal++ ?>
            @endif
   @endif
   {{-- シリアルNo.を足す --}}
     @switch($lists[1])
     @case("part")
     <?php  $prev_rank=$p->right_part; $serialnumber++; ?>  
     @break;
     @case("full")
     <?php  $prev_rank=$p->right_full; $serialnumber++; ?>  
     @break;
     @case("withnum")
     <?php  $prev_rank=$p->right_withnum; $serialnumber++; ?>  
     @break;
     @endswitch
  @empty    
  <tr class="rank_tr_none" ><td class="rank_td_none"  colspan="4">まだ解かれていません</td></tr>
  @endforelse
</tbody>
</table>

{{-- 30位以下へのリンク --}}
@if(!$isOver30)
  <div class="over_rank30_div">
    <p class="over_rank30_p">30位以下は<a class="over_rank30_a" href="{{route("over_30_route",
    [
      "season"=>$season,"rank_kind"=>$lists[1]
    ])
    }}">こちら</a></p>
  </div>
@endif


@endforeach

</div>

<div class="goarchivediv">
  @if($season!=="all")
  <p class="goarchivep"><a class="goarchivea" href="{{route("recordroute")}}">現在の記録</a></p>
  @else
  <p class="goarchivep"><a class="goarchivea" href="{{route("archiveroute")}}">過去年の記録</a></p>
  @endif
</div>

<div class="backtopdiv2">
  <p class="backtopp2"><a class="backtopa" href="{{route("indexroute")}}">戻る</a></p>
</div>

</x-layout> 