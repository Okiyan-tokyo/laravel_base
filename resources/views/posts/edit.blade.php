<x-layout>
<x-slot name="title">{{$post["title"]}}</x-slot>

<h2>編集！</h2>

<p class="backclass"><a href="{{route('indexroute')}}">&laquo;戻る</a></p>

<form action="{{route("updateroute",$post["id"])}}" method="post" class="createform">
  @method("PATCH")
  @csrf

  <div class="c1">
    タイトル<br>
    <input type="text" name="title" value="{{ old('title',$post["title"]) }}">
    </div>
    
    @error("title")
    <p class="errmessage">{{$message}}</p>
    @enderror
  
 <div class="c2">
本文<br>
<textarea name="body">{{old("body",$post["body"])}}</textarea>

</div>
@error("body")
<p class="errmessage">{{$message}}</p>
@enderror

<div class="btndiv">
<button>投稿</button>
</div>

</form>


</x-layout>