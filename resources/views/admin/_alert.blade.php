@if(session()->has('success'))
<div class="alert alert-success">
    {{session('success')}}
</div>
@endif
alert-error
@if(session()->has('alert-error'))
<div class="alert alert-danger">
    {{session('alert-error')}}
</div>
@endif