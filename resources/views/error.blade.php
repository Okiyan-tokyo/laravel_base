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
        @case("exist")
          {{"既に過去データが存在しています"}}
        @break
        @case("teamdata_update")
          {{"チーム登録時のエラーです"}}
        @break
        @case("store_quiz_archive")
          {{"クイズデータを過去登録時のエラーです"}}
        @break
        @case("store_team_archive")
          {{"チームを過去登録時のエラーです"}}
        @break
        @case("team_archive_data_change")
          {{"過去チームデータ変更時のエラーです"}}
        @break
        @case("archivePlayer_relatedTeam_data_change")
          {{"過去選手記録のチーム名変更時のエラーです"}}
        @break
        @case("player_update")
          {{"プレイヤー登録時のエラーです"}}
        @break
        @case("playerNameException")
          {{"過去の選手例外取得時のエラーです"}}
        @break
        @case("withKakkoException")
          {{"（がついた選手の\n存在確認時のエラーです"}}
        @break
        @case("noCommaException")
          {{"カンマがない選手の\n存在確認時のエラーです"}}
        @break
        @default
        {{$ptn}}
           {{"不明"}}
      @endswitch
    @endempty
  </span>
</p>
  <p style="text-align: center"><a href="{{route("indexroute")}}">トップに戻る</a></p>
  </x-layout>
