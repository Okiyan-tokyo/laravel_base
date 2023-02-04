<x-layout>




<x-slot name="title">{{$teamsets[0]->jpn_name}}</x-slot>

<body 
data-red="{{$teamsets[0]->red}}"
data-green="{{$teamsets[0]->green}}"
data-blue="{{$teamsets[0]->blue}}"
id="its_game">

<div class="fixed_top">

<h2 class="toph2">{{$teamsets[0]->jpn_name}}：選手当てクイズ！</h2>

<form action="{{route($formroute)}}" method="post" class="answerform" data-url="{{route($formroute)}}" data-team="{{$teamsets[0]->eng_name}}">   
  
  @csrf
  
  <div class="user_answer_set">
    <div class="inputsets">
    <input type="text" name="user_answer" id="user_answer">
    <button>決定！</button>
    </div>
    <div class="correct_answers">
      <p class="correct_count"><span class="countspan1">0</span>人正解</p>
      <p class="rest_count">(あと<span class="countspan2">{{
        count($lists);
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
  <th class="answerth">背番号</th>
    <th class="answerth">名前</th>
</tr>
</thead>
<tbody>
  @forelse ($lists as $list)
  <tr class="answertr">
  <td class="answertd">{{$list->num}}</td>
  <td class="tdquestion"  data-open="close" data-num="{{$list->num}}" data-name="{{$list->full}}">？？？</td>
  </tr>
  @empty
  {{"エラーです"}}
  <?php exit; ?>        
  @endforelse
</tbody>

</table>
</div>

{{-- おめでとう表示 --}}
<div class="congratulation">
  <p>全問正解！</p>
  <p>おめでとうございます！！</p>
  <p><a  style="color:white" href="{{route("indexroute")}}">戻る</a></p>
</div>

<script>
  $(()=>{



    $("button").on("click",function(e){
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
      ).then((response)=>{
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
          
          // 正解の背番号の名前を開ける
          // それ以外の欄を消す
          // 正解の数を１つ増やす
          if(elemsets.length>0){
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
          $(".not_fixed").css("transform","translateY(225px)");

          setTimeout(function(){
            $(".tdquestion").each(function(i,elem){
              $(elem).closest('tr').css("visibility","visible");
            })
            $("#whenright").removeClass("whenright");
            $("#whenright").addClass("whenrightbase");
            $(".not_fixed").css("transform","translateY(175px)");
          },3000)
  
          // 全問正解していたら、お祝いの表示
          if(Number($(".countspan2").text())===0){
          $(".congratulation").addClass("congraadd");
          $(".congratulation").css("display","block");
           }

          }else{
            // 既に回答済みの時の処理
            $("#existname").removeClass("existbase")
                           .addClass("whenexist");
            $(".not_fixed").css("transform","translateY(225px)");
            setTimeout(function(){
              $("#existname").removeClass("whenexist")
                             .addClass("existbase");
              $(".not_fixed").css("transform","translateY(175px)");
            },2000)
          }

      // 間違いの時の処理
        }else{
          $("#whenwrong").removeClass("whenwrongbase")
                         .addClass("whenwrong");
          $(".not_fixed").css("transform","translateY(225px)");
          setTimeout(function(){
            $("#whenwrong").removeClass("whenwrong")
                           .addClass("whenwrongbase");
            $(".not_fixed").css("transform","translateY(175px)");
          },2000)
        }
    })
   })
  })
  </script>


  <div class="backtopdiv"><p class="backtopp"><a class="backtopa" href="{{route("indexroute")}}">戻る</a></div>


</x-layout>