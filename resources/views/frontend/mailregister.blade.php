
<p>

    @if (is_array($body))
        @foreach ($body as $Key=>$item )
            {{ $Key }}:{{ $item }}
            <br>
        @endforeach
    @else
       {!!$body !!}
    @endif

   </p>
   <br>

