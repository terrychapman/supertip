@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @guest
        <!-- if not logged in but manage to get to the page -->
        <div class="alert alert-danger" role="alert">
            FORBIDDEN!
        </div>
        @else
            <!-- must be admin -->
            @if (Auth::user()->email == 'terry@mychapman.com' || Auth::user()->email == 'peterdanielsmith@hotmail.com')
            @isset($users)
                <div class="row">
                
                    <div class="col">
                        <h1>Users</h1>
                    </div>
                    <div class="col">
                        <h5 class="text-center">No of users: {{ count($users) }}</h5>
                    </div>
                </div>
                <!-- if error -->
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                    @foreach($errors->all() as $error)
                    <li>
                        {{$error}}
                    </li>
                    @endforeach
                    </ul>
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                            <th class="col text-center">Id</th>
                            <th class="col">Name</th>
                            <th class="col">Email</th>
                            <th class="col">Display Name</th>
                            <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <form id="{{ $user->id }}_form" name="{{ $user->id }}_form" action="/user/{{ $user->id }}" method="POST">
                                @csrf <!-- this is built in protection for your form post ---> 
                                <tr>
                                    <td>
                                        {{ $user->id }}
                                        <input type="hidden" id="id" name="id" value="{{ $user->id }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control w-80" id="name" name="name" value="{{ $user->name }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control w-80" id="email" name="email" value="{{ $user->email }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control w-80" id="displayName" name="displayName" value="{{ $user->displayName }}">
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-primary btn-sm" name="action" value="save">Save</button>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteConfirmation{{ $user->id }}">Delete</button>
                                    </td>

                                    <!--  delete confirmation modal -->
                                    <div class="modal fade" id="deleteConfirmation{{ $user->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteConfirmationLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h5 class="modal-title" id="deleteConfirmationLabel">Delete User</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete user - {{ $user->name }} with Display Name of {{ $user->displayName }}?
                                            </div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <a href="{{ url('/user/delete/'.$user->id) }}"><button type="button" class="btn btn-danger">Yes</button></a>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </tr>
                            </form>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endisset

            <!-- else forbidden access -->
            @else
                <div class="alert alert-danger" role="alert">
                    FORBIDDEN!
                </div>
            @endif
        @endguest
    </div>
</div>
    
@endsection