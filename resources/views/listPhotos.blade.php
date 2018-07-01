<h2>Photos</h2>

@foreach ($photoArr as $key=>$value)
	@if($loop->index==5)
		</br>
	@endif
<img src="{{$value}}" height="200" width="200" style="padding:5px">

@endforeach
</br></br>
<a href="{{ url('/gallery', [$albumId, $nextPage])}}">NEXT PAGE</a></br>