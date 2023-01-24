<x-layout>
<x-slot name="title">{{$team[0]->jpn_name}}</x-slot>

<h1 class="toph1">{{$team[0]->jpn_name}}選手当てクイズ！</h1>

<table>
<thead>
<tr>
  <th class="">背番号</th>
  <th class="">名前</th>
</tr>
</thead>

<tbody>
  @forelse ($lists as $list)
  <tr>
    <td class="">{{$list->num}}</td>
    <td class="">？？？</td>
  </tr>
  <span class="answer" value="{{$list->full}}"></span>
  @empty
  {{"エラーです"}}
  <?php exit; ?>        
  @endforelse
</tbody>

</table>

{{-- <form>
  @csrf
</form> --}}
  

  <input type="text" name="player">
  <button>決定！</button>

</x-layout>