<x-layout>
<x-slot name="title">作成</x-slot>
<h1 style="font-size:1.2em;">作成!</h1>

<p class="backclass"><a href="{{route('indexroute')}}">&laquo;戻る</a></p>


<form action="{{route("storeroute")}}" method="post" class="createform">
  @csrf

<div class="c1">
タイトル<br>
<input type="text" name="title" value="{{ old('title') }}">
</div>

@error("title")
<p class="errmessage">{{$message}}</p>
@enderror


<div class="c2">
本文<br>
<textarea name="body">{{old("body")}}</textarea>

</div>
@error("body")
<p class="errmessage">{{$message}}</p>
@enderror

<div class="btndiv">
<button>投稿</button>
</div>

</form>

</x-layout>