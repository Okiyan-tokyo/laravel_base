<x-layout>




<x-slot name="title">{{$teamsets[0]->jpn_name}}</x-slot>

<body 
data-red="{{$teamsets[0]->red}}"
data-green="{{$teamsets[0]->green}}"
data-blue="{{$teamsets[0]->blue}}"
id="its_game">

<div class="fixed_top">


<h2 class="toph2"> {{$teamsets[0]->jpn_name}}：選手当てクイズ！</h2>

<form action="{{route($formroute)}}" method="post" class="answerform" data-url="{{route($formroute)}}" data-team="{{$teamsets[0]->eng_name}}">   
  
  @csrf
  
  <div class="user_answer_set">
    @if($formroute!=="withnumroute")
    <div class="inputsets">
    <input type="text" name="user_answer" id="user_answer">
    <button id="totalbtn">決定！</button>
    </div>
    @endif

    @if($formroute!=="withnumroute")
    <div class="correct_answers">
    @else
    <div class="correct_answers correct_answers2">
    @endif

      @if($formroute!=="withnumroute")
      <p class="correct_count">
      @else
      <p class="correct_count correct_count2">
      @endif
      <span class="countspan1">0</span>人正解</p>

      @if($formroute!=="withnumroute")
      <p class="rest_count">
      @else
      <p class="rest_count rest_count2">
      @endif
      (あと<span class="countspan2">{{count($lists);
      }}</span>人)</p>
      
    </div>
  </div>
</form>
  
  <p id="whenwrong" class="whenwrongbase">×</p>
  <div id="existname" class="existbase">
    <div class="existtext">回答済</div>
  </div>
  <div id="whenright" class="whenrightbase">
    <div class="answermark">正解！</div>
  </div>

</div>

<div class="not_fixed">
<table class="answertable">
<thead>
<tr class="answertr">
  <th class="answerth"
    @if($formroute==="withnumroute")
    style="width:30%;"
    @endif
    >背番号</th>
    <th class="answerth"
    @if($formroute==="withnumroute")
    style="width:70%;"
    @endif
  >名前</th>
</tr>
</thead>
<tbody>
  @forelse ($lists as $list)
  <tr class="answertr">
  <td class="answertd"
  @if($formroute==="withnumroute")
  style="width:30%;"
  @endif
  >{{$list->num}}</td>
  @if($formroute==="withnumroute")
  <td class="tdquestion" style="width:70%;" data-open="close" data-num="{{$list->num}}" data-name="{{$list->full}}">
    <input type="text" name="player" class="input_with_num">
    <button class="numsetbtn">決定！</button>
  </td>
  @else
  <td class="tdquestion" data-open="close" data-num="{{$list->num}}" data-name="{{$list->full}}">？？？</td>  
  @endif
  </tr>
  @empty
  {{"エラーです"}}
  <?php exit; ?>        
  @endforelse
</tbody>

</table>
  <div class="for_bottom_space"></div>
</div>

{{-- おめでとう表示 --}}
<div class="congratulation">
  <p>全問正解！</p>
  <p>おめでとうございます！！</p>
  <p><a  style="color:white" href="{{route("indexroute")}}">戻る</a></p>
</div>

<script>
  $(()=>{

    if($("#totalbtn").length){
      $("#totalbtn").on("click",submit_answer1)
    }
    
    if($('.numsetbtn').length){
      $(".numsetbtn").each(function(i,elem){
        submit_answer2(i,elem);
      })
    }

    function submit_answer1(e){
      e.preventDefault();
      fetch(
          $("form").data("url"),
          {
            method:"post",
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}' 
            },
            body:new URLSearchParams({
                answer:$("#user_answer").val(),
                team:$("form").data("team"),
            })
          }    
      )    
      .then((response)=>{
        return response.json();
      }).then((json)=>{
        json=JSON.parse(json);
    
        if(json.isok==="ok"){
          // 正解の場合
          // 処理する要素の番号を格納
          let elemsets=[];

          // 正解した問題の背番号の取得
           json.numset.forEach(json_num => {
              // 不正解ならnumsetは空
              $(".tdquestion").each(function(i,elem){
                if(Number($(elem).data("num"))===Number(json_num)){
                $(elem).text($(elem).data("name"));
                // 既に正解していたら、正解リストに付け加えない
                if($(elem).data("open")==="close"){
                  elemsets.push(i);
                  $(elem).data("open", "open");
                }
              }
            })
          });
          
          if(elemsets.length>0){
            right_display(elemsets);
          }else{
            // 既に回答済みの時の処理
            exist_display();    
            // 間違いの時の処理
          }
        }else{
          wrong_display();
        }
    })
  }

  function submit_answer2(i,elem){
    $(elem).on("click",function(e){
      e.preventDefault();
      if($(".input_with_num").eq(i).val()===$(".tdquestion").eq(i).data("name")){
        $(".tdquestion").eq(i).text($(".tdquestion").eq(i).data("name"));
        elemsets=[i]
        right_display(elemsets);
      }else{
        wrong_display();
      }        
      })
    }
  
  
      // 正解
      function right_display(elemsets){
          // 正解の背番号の名前を開ける
          // それ以外の欄を消す
          // 正解の数を１つ増やす
            $(".tdquestion").each(function(i,elem){
              if(!elemsets.includes(i)){
                $(elem).closest('tr').css("visibility","collapse");
              }else{
                $(elem).closest('tr').css("background-color","gold");
                $(elem).closest('tr').css("color","black");
                $(".countspan1").text(Number($(".countspan1").text())+1);
                $(".countspan2").text(Number($(".countspan2").text())-1);
              }
            })

          // 正解マークの表示
          $("#whenright").removeClass("whenrightbase");
          $("#whenright").addClass("whenright");
          $(".not_fixed").css("transform","translateY(215px)");
          $("input").val("").focus();
          $("button").css({pointerEvents:"none",opacity:0.3});
          setTimeout(function(){
            $(".tdquestion").each(function(i,elem){
              $(elem).closest('tr').css("visibility","visible");
            })
            $("#whenright").removeClass("whenright");
            $("#whenright").addClass("whenrightbase");
            $("button").css({pointerEvents:"auto",opacity:1});
            $(".not_fixed").css("transform","translateY(170px)");
          },3000)
  
          // 全問正解していたら、お祝いの表示
          if(Number($(".countspan2").text())===0){
            $(".congratulation").addClass("congraadd");
            $(".congratulation").css("display","block");
           }
    }

    // 既に存在
    function exist_display(){
           $("#existname").removeClass("existbase")
                           .addClass("whenexist");
            $(".not_fixed").css("transform","translateY(215px)");
            $("input").val("").focus();
            $("button").css({pointerEvents:"none",opacity:0.3});
            setTimeout(function(){
              $("#existname").removeClass("whenexist")
                             .addClass("existbase");
              $(".not_fixed").css("transform","translateY(170px)");
              $("button").css({pointerEvents:"auto",opacity:1});
            },2000);
    }

    // 間違い
    function wrong_display(){
          $("#whenwrong").removeClass("whenwrongbase")
                         .addClass("whenwrong");
          $(".not_fixed").css("transform","translateY(215px)");
          $("input").val("").focus();
          $("button").css({pointerEvents:"none",opacity:0.3});
          setTimeout(function(){
            $("#whenwrong").removeClass("whenwrong")
            .addClass("whenwrongbase");
            $(".not_fixed").css("transform","translateY(170px)");
            $("button").css({pointerEvents:"auto",opacity:1});
          },2000);
    }



// jqueryマークの終点
})

  </script>


  <div class="backtopdiv">
    <p class="backtopp"><a class="backtopa" href="{{route("indexroute")}}">戻る</a>
    <span class="dataday">＊23年2月5日現在</span>
    </p>
  </div>


</x-layout>