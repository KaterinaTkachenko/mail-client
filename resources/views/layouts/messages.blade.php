@if(session('success'))
<div class="alert alert-secondary">
    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
    {{session('success')}}
</div>
@endif
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif