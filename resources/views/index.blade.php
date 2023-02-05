<x-layout>

  @if($errors->any())
    <body class="notransbody">
  @else
    <body>
  @endif


<x-slot name="title">23年Jリーグ選手名クイズ！</x-slot>

<h1 class="toph1">23年Jリーグ：選手名クイズ！</h1>

<h3 class="toph3">チームとタイプを選択してください！</h3>


<form action="{{ route("selectteamroute");}}" method="post" class="firstselectform">
  @csrf

<div class="selectsets">

<div class="teamselectdiv">
<p class="teamlabel">チーム名</p>
<select id="team" name="teamselect" class="teamselect">
  <option hidden value="">選択してください</option>
      <optgroup label="J1"></optgroup>
      @foreach ($J1lists as $J1list)
      <option name="teamselect" value={{$J1list->eng_name}}>{{$J1list->jpn_name}}</option>
      @endforeach
      <optgroup label="J2"></optgroup>
      @foreach ($J2lists as $J2list)
      <option name="teamselect" value={{$J2list->eng_name}}>{{$J2list->jpn_name}}</option>
      @endforeach
      <optgroup label="J3"></optgroup>
      @foreach ($J3lists as $J3list)
        <option name="teamselect" value={{$J3list->eng_name}}>{{$J3list->jpn_name}}</option>
      @endforeach
<select>
</div>
    @error('teamselect')
    <p class="errormessage">{{"選択してください"}}</p>
    @enderror
    
<div class="typeselectdiv">
  <p class="typelabel">タイプ</p>
  <select id="type" name="typeselect" class="typeselect">
    <option hidden value="">選択してください</option>
    <option value="full">登録名（フル）</option>
    <option value="part">登録名の一部</option>
    <option value="withnum">背番号と名前</option>
  <select>
</div>
@error('typeselect')
  <p class="errormessage">{{"選択してください"}}</p>
@enderror

</div>

<div class="postbutton">
  <button>決定！</button>
</div>

</form>


</x-layout>