@extends('layouts.app')
@section('content')	
	@include('layouts.modal-sendMail')
	<div class="wrapper">
		@include('layouts.sidebar')
		<div id="content" class="main show">
			@include('layouts.header')				
				@yield('main')
			@include('layouts.footer')
		</div>
	</div>
@endsection