<body class="body_no_trans">
  <x-layout>
  <x-slot name="title">エラーのお知らせ</x-slot>
  <p style="text-align: center;margin-top:50px;margin-bottom:20px;">エラーが発生しました</p>
  <p style="text-align: center;margin-top:20px;margin-bottom:20px;">原因…<span>
    @empty($ptn)
        {{"不明"}}
    @else
      @switch($ptn)
        @case("team")
          {{"チーム名が不正です"}}    
        @break
        @case("type")
          {{"ゲームタイプが不正です"}}    
        @break
        @default
          {{"不明"}} 
      @endswitch
    @endempty  
  </span>
</p>
  <p style="text-align: center"><a href="{{route("indexroute")}}">戻る</a></p>
  </x-layout>
  