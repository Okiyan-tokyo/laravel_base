<x-layout>
  <x-slot name="title">設定完了</x-slot>

  {{-- bodyのopacity変更 --}}
<body id="year_change_body">
<p id="config_completed_sign">{{$message}}</p>
</body>

<p id="config_to_index"><a href="{{route("indexroute")}}">トップページを確認<a></p>

</x-layout>