 @if(Session::has('flash_notification'))
	 <script type="text/javascript">
	    $('document').ready(function(){
	    toastr.options = $.parseJSON('{!!json_encode(config('flash-toastr.options'), JSON_UNESCAPED_SLASHES)!!}');
	    @foreach (session('flash_notification', collect())->toArray() as $message)
			toastr["{!! $message['level'] !!}"]("{!! $message['message'] !!}", "{!! $message['title'] !!}");
		@endforeach
	    });
	</script>
{{ Session::forget('flash_notification') }}
@endif
