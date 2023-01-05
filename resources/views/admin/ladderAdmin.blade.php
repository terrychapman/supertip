@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @guest
        <!-- if not logged in but manage to get to the page -->
        <div class="alert alert-danger" role="alert">
            FORBIDDEN!
        </div>
    @else
        <div class="col-6">
            <h1>Ladder Admin</h1>
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
                <th class="col text-center">#</th>
                <th class="col">Name</th>
                <th class="col text-center">Rd Pts</th>
                <th class="col text-center">Power Tip</th>
                <th class="col text-center">Total Pts</th>
                <th class="col text-center">Paid</th>
                <th></th>
                </tr>
            </thead>
            <tbody>
                @isset($ladder)
                @php
                    $rank = 1;
                @endphp
                    @foreach ($ladder as $l)
                    <form id="{{ $l->id }}_form" name="{{ $l->id }}_form" action="/ladder/admin/{{ $l->id }}" method="POST">
                        @csrf <!-- this is built in protection for your form post ---> 
                        <tr>
                            <td class="text-center">
                                {{ $rank }}
                                <input type="hidden" id="id" name="id" value="{{ $l->id }}">
                            </td>
                            
                            <td>{{ $l->displayName }}</td>
                            <td class="text-center">
                                <input type="text" class="form-control w-80" id="roundPoints" name="roundPoints" value="{{ $l->roundPoints }}">
                            </td>
                            <td class="text-center">
                                <input type="text" class="form-control w-80" id="powerTip" name="powerTip" value="{{ $l->powerTip }}">
                            </td>
                            <td class="text-center">
                                <input type="text" class="form-control w-80" id="totalPoints" name="totalPoints" value="{{ $l->totalPoints }}">
                            </td>
                            <td class="text-center">
                                <select class={{ ($l->paid == 1) ? "bg-success" : "bg-danger" }} id="paid" name="paid">
                                    <option value="1" {{ ( $l->paid == 1) ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ ( $l->paid == 0) ? 'selected' : '' }}>No</option>
                                </select>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-primary btn-sm" name="action" value="save">Save</button>
                            </td>
                        </tr>
                        @php
                            $rank++;
                        @endphp
                    </form>
                    @endforeach
                    
                @endisset
            </tbody>
            </table>
        </div>
    @endguest
</div>
@endsection