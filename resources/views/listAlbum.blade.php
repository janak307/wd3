<h2>Albums</h2>

@foreach ($albInfo as $key=>$value)

<a href="{{ url('/gallery', $value)}}">{{$key !='' ? $key : 'No Title'}}</a></br>

@endforeach