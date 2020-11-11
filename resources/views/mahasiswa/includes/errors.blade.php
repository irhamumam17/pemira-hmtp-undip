@if($errors->any())
<!-- Box error -->
<div class="box-error-msg">
    @foreach ($errors->all() as $error)
        <p class="text-error-msg"><strong>{{$error}}</strong></p>
    @endforeach
</div>
<!-- End Box error -->
@endif