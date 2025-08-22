@section('title', 'User')
@extends('layouts.app')
@section('no-sidebar', true)
@section('no-header', true)
@section('content')
<div class="container">
    <!-- Create User Form -->
    <div class="card mb-4">
        <div class="card-header">Login</div>
        <div class="card-body">
            <form id="loginForm" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password">
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>
</div>
<div id="notify" class="alert d-none position-fixed end-0 top-0 m-4" style="z-index:1050; min-width: 300px;">
</div>
@endsection
@push('scripts')
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script>
    $(document).ready(function() {
        // $('#loginForm').validate({
        //     rules:{
        //         email:{
        //             required:true,
        //             email:true,
        //         },
        //         password:{
        //             required:true,
                    
        //         }
        //     },
        //     messages:{
        //         email: {
        //             required: 'Please enter an email',
        //             email: 'Please enter a valid email'
        //         },
        //         password:{
        //             required:"password is required",
                    
        //         }
        //     },
        //     submitHandler: function(form, event) {
        //         alert(123);
        //         event.preventDefault(); // 🔥 Prevent default form submit
        //         $.ajax({
        //             url: $(form).attr('action'),
        //             method: $(form).attr('method'),
        //             data:$(form).serialize(),
        //             success: function(response){

        //             },
        //             error: function (xhr) {
        //                 if (xhr.status == 422) {
        //                     let errors = xhr.responseJSON.errors;
        //                     let messages = '';
        //                     $.each(errors, function(key, value){
        //                         messages += value[0] + '\n';
        //                     });
        //                     showNotification('danger', messages);
        //                 }
        //             }
        //         });
        //     },
        //     errorClass: "text-danger",
        //     errorElement: "div",
        //     highlight: function (element) {
        //         $(element).addClass('is-invalid');
        //     },
        //     unhighlight: function (element) {
        //         $(element).removeClass('is-invalid');
        //     }
        // });
    });
    
</script>
@endpush