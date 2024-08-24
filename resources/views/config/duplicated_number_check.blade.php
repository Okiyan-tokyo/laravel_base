<x-layout>
    <x-slot name="title">背番号重複チェック</x-slot>
    <h1>背番号重複チェック</h1>

    <div>
     <h2>チームと背番号が重複している例は以下の通り</h2>
     <p>1000番は2種</p>
     <ul>
        @foreach($lists as $list)
            <li>{{$list}}</li>
        @endforeach
     </ul>
    </div>
</x-layout>
