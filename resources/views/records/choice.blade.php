<x-layout>
  <x-slot name="title">結果表示年度の選択</x-slot>

  <body class="topbody notransbody" style="background: url({{url("img/img3.jpg")}}); background-size:300px 300px; background-repeat:repeat;">

  <h2 class="recordh2">過去の回答された選手ランキング</h2>

  
  {{-- 過去ログない時 --}}
  @if(empty($seasons))
  <p>まだアーカイブはありません</p>  
  @else
  {{-- 過去ログある時 --}}
  <form method="post" action="{{route("archive_decide_route")}}" id="record_year_choice_form">
    
    @csrf

    <h4 class="toph4">年度の選択をしてください</h4>

    <select name="record_year_select" id="record_year_select">
      <option class="record_year_option" value="no_choice" hidden>選択してください</option>
    @foreach($seasons as $season)
    <option class="record_year_option" value="{{$season}}">{{$season}}</option>
    @endforeach
    </select>

    {{-- 年度選択のバリデーションにかかった時 --}}
    @error("record_year_select")
     <div class="select_when_error_row1">
       <p class="errormessage">{{$message}}</p>
     </div>
    @enderror


    <div id="record_year_btn_div">
      <button id="record_year_btn">決定</button>
    </div>

    @endif

  </form>
  </body>
</x-layout>