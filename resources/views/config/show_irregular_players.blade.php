<x-layout>
  <x-slot name="title">txtファイルの登録名の確認</x-slot>

  <h1>登録選手名の例外</h1>

    <div>
    <h2>カンマがない選手</h2>
    @foreach($no_conma_players as $no_conma)
      {!! nl2br(e($no_conma["team"]."...".$no_conma["full"])."\n") !!}
    @endforeach
  </div>

  <div>
    <h2>カッコがある選手</h2>
    @foreach($with_kakko_players as $with_kakko)
      {!! nl2br(e($with_kakko["team"]."...".$with_kakko["full"])."\n") !!}
    @endforeach
  </div>

  <div>
    <h2>背番号がない選手</h2>
    <p>＊このままでは2種と見做して1000で登録</p>
    @foreach($no_number_players as $no_number)
      {!! nl2br(e($no_number["team"]."...".$no_number["full"])."\n") !!}
    @endforeach
  </div>

  <div>
    <h2>過去に例外の名前の選手</h2>
    <p>＊必要なら手動で訂正を</p>
    @foreach($player_name_exceptions as $exception_player_name)
      {!! nl2br(e($exception_player_name["team"]."...".$exception_player_name["full"])."\n") !!}
    @endforeach
  </div>


</x-layout>
