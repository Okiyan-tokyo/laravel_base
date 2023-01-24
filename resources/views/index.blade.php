<x-layout>
  <x-slot name="title">Jリーグ選手名クイズ！</x-slot>

<h1 class="toph1">Jリーグ選手名クイズ！</h1>

<h3 class="toph3">チームとタイプを選択してください！</h3>

<form action="{{ route("selectteamroute");}}" method="post" class="firstselectform">
  @csrf

<div class="selectsets">

<div class="teamselectdiv">
<p class="teamlabel">チーム名</p>
<select id="team" name="teamselect" class="teamselect">
  <option hidden>選択してください</option>
@forelse ($lists as $list)
  <option name="teamselect">{{$list->team}}</option>
@empty
    {{エラーです}}
@endforelse
<select>
</div>

<div class="typeselectdiv">
<p class="typelabel">クイズのタイプ</p>
<select id="type" name="typeselect" class="typeselect">
  <option hidden>選択してください</option>
  <option name="typeselect">登録名（フル）</option>
  <option name="typeselect">登録名の一部</option>
  <option name="typeselect">背番号と名前</option>
<select>
</div>

</div>

<div class="postbutton">
  <button>決定！</button>
</div>

</form>

</x-layout>