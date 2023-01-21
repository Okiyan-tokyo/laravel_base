<x-layout>
<x-slot name="title">{{$post["title"]}}</x-slot>
  <h2>{{$post["title"]}}</h2>
  <div class="createpoint">
    <p><a href="{{route("editroute",$post["id"])}}">edit</a></p>
</div>
  <p class="bodyclass">
    {!! nl2br(e($post["body"])) !!}
  </p>
  <p class="backclass"><a href="{{route('indexroute')}}">&laquo;戻る</a></p>

  <div class="commentpoint">
    <h3>コメント一覧</h3>
    <ul>
    @forelse ($comments as $comment)
        <li class="commentsli">
        <span>{{$comment->body}}</span>
        <span class="cdspan" data-value="{{$comment->body}}">[x]</span>
        </li>

      <form style="display:none" action="{{route("cmdeleteroute",$comment->id)}}" method="post" class="cdform">
          @method("DELETE")
          @csrf
      <input type="hidden" name="deleteid" value="{{$comment->id}}">
      </form>
    @empty
      <li>コメントはありません</li>   
    @endforelse
    </ul>
  </div>
  
  
<form class="createform" method="post" action="{{route("cmcreateroute")}}">
  @csrf  
  <h3>コメント投稿</h3>
  <div class="c2">
  <input type="hidden" name="post_id" value="{{$post["id"]}}">
<textarea name="body" value="{{old("body")}}"></textarea>
  </div>

@error('body')
    {{$message}}
@enderror

<div class="btndiv">
  <button>投稿</button>
</div>
</form>
  

<script>
  const cdform=document.getElementsByClassName("cdform");
  const cdspan=document.getElementsByClassName("cdspan");


for(let n=0;n<cdspan.length;n++){
  cdspan[n].addEventListener("click",()=>{
    console.log(cdspan[n])
    const which=confirm("コメント"+cdspan[n].dataset.value+"を消去して良いでか？");
    if(which){
      cdform[n].submit();
    }
  })
}

</script>
  
</x-layout>