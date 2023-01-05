@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <h1>Sorry, there has been an error.</h1>
        @csrf <!-- this is built in protection for your form post --->
        <div class="card">
            <div class="card-body">
                <p class="card-text">The site has experienced an error.</p>
                <p class="card-text">Please email <a href = "mailto: terry@mychapman.com">terry@mychapman.com</a> with details of
                what it was you were doing and what time did this happen. They will investigate.</p>
                <p class="card-text">Go back to <a href="{{ route('home') }}">Home Page</a></p>
            </div>
        </div>
    </div>
</div>
    
@endsection

