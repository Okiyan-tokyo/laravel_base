<x-layout>
<x-slot name="title">MyBBS</x-slot>

<h1>ここでおきやんが編集したよ！</h1>

<div class="createpoint">
    <p><a href="{{route("createroute")}}">create</a></p>
</div>

    <ul>
    @forelse ($posts as $post)
    <li>
        <a href="{{route("showroute",$post["id"])}}">{{$post["title"]}}</a>
    <form method="post" action="{{route('deleteroute',$post["id"])}}" onsubmit="return kakunin({{$post['id']}})">
        @method("delete")
        @csrf

        <button>x</button>
    </form>
    </li>   
    @empty
        {{ないよa！}}
    @endforelse
    </ul>

    <script>
        function kakunin(id){
            alert("id"+id+"を消してよいですか？")
        }
    </script>

</x-layout>
