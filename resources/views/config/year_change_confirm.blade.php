{{-- シーズン変更のSQLチェンジの確認のページ --}}

<x-layout>
  <x-slot name="title">シーズン変更の確認</x-slot>
  {{-- jsのセッティング --}}
  <x-slot name="js">config</x-slot>

  {{-- bodyのopacity変更 --}}
<body id="year_change_body">
<h3 id="seasonChangeH3">シーズン変更確認</h3>

<form action="{{ route("year_change_route")}}" method="post" id="year_change_form">
    {{-- 実際はjs側で設定しているので必要ない --}}
    @method("PATCH")
    @csrf

    <div class="seazonChange_confirm_div" id="#seazonChange0">
      <p>新シーズンのリストはstorage/app/file/team_nameに置きましたか？</p>
      <input id="year_change_listsOk" type="checkbox" name="player_ok"><label class="season_change_lavel" for="year_change_playerOk">はい</label>
    </div>

    <div class="seazonChange_confirm_div" id="#seazonChange1">
      <p>新シーズンのチームのカテの更新、チームデータの更新、降格したチームのArchive登録の事前準備はしましたか？</p>
      <input id="year_change_cateOk" type="checkbox" name="team_ok"><label class="season_change_lavel" for="year_change_cateOk">はい</label>
    </div>

  {{-- 登録の年次 --}}
  <div class="seazonChange_confirm_div" id="#seazonChange2">
      <p>過去データで登録するのは？</p>
      <select name="old_year_name" id="old_year_name_select">
        <option hidden value="no_choise">選択してください</option>
        <option class="old_data_option" value="{{$pastYear}}">去年</option>
        <option class="old_data_option" value="{{$thisYear}}">今年</option>
        <option class="old_data_option" value="{{$pastYear."_".$thisYear}}">去年〜今年またぎ</option>
        <option class="old_data_option" value="no_store">登録しない</option>
      </select>
      @error("old_year_name")
      <p class="errormessage">{{$message}}</p>
      @enderror
   </div>


   {{-- 万が一のことを考えてパス登録 --}}
   <div class="seazonChange_confirm_div" id="#seazonChange3">
    <p>パスワードを入力してください</p>
    <input name="pass" type="text" id="passInput">
    @error("pass")
    <div class=".select_when_error5">
     <p class="errormessage">{{$message}}</p>
    </div>
    @enderror
   </div>

{{-- 例外の名前チェックを行ったか？ --}}
   <div class="seazonChange_confirm_div" id="#seazonChange5">
    <p>config/view_irregularページで<br>例外の名前チェックを行いましたか？</p>
    <input id="year_change_irregularOk" type="checkbox" name="irregular_ok"><label class="season_change_lavel" for="year_change_irregulaerOK">はい</label>
  </div>

   {{-- 新データのテーブルは、いずれにしても「Nowlists23s」 --}}


    <div id="year_change_btn_div">
      <button id="year_change_btn">決定！</button>
    </div>

  </form>

</x-layout>
