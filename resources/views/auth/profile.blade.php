@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if($saved)
                <div class="alert alert-success" role="alert">
                    <p class="fs-5">Your profile has been saved</p>
                </div>
            @endif
            
            <div class="card">
                <div class="card-header">{{ __('Profile') }}</div>

                <div class="card-body">
                    <form method="POST" action="/profile/save">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                @if($saved)
                                    <input id="name" type="text" class="form-control" name="name" value="{{ $user[0]->name }}" readonly>
                                @else
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user[0]->name) }}" required autocomplete="name" autofocus>
                                @endif

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                @if($saved)
                                    <input id="email" type="email" class="form-control" name="email" value="{{ $user[0]->email }}" readonly>
                                @else
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user[0]->email) }}" required autocomplete="email">
                                @endif

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="displayName" class="col-md-4 col-form-label text-md-end">{{ __('Display Name') }}</label>

                            <div class="col-md-6">
                                @if($saved)
                                    <input id="displayName" type="text" class="form-control" name="displayName" value="{{ $user[0]->displayName }}" readonly>
                                @else
                                    <input id="displayName" type="text" class="form-control @error('displayName') is-invalid @enderror"
                                        name="displayName" value="{{ old('displayName', $user[0]->displayName) }}" required autocomplete="displayName"
                                        placeholder="this is displayed on the ladder...">
                                @endif

                                @error('displayName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                @if($saved)
                                    <a class="btn btn-secondary" href="{{ route('profile') }}">Edit</a>
                                @else
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Save') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
