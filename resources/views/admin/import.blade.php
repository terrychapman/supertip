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
                <h1>Import</h1>
                <form action="/games/import/save" enctype="multipart/form-data" method="POST">
                    @csrf <!-- this is built in protection for your form post --->
                    <div class="card">
                        <div class="card-header text-center"><strong>Import Games File</strong></div>
                        <div class="card-body">
                            <p class="card-text">This is used to import a csv file containing the games for the season. It is downloaded from
                                <a href="https://fixturedownload.com" target="_blank">https://fixturedownload.com</a>. Note the Match Number, Location and Result columns are ignored in the import.</p>
                            <p class="card-text">Ensure the csv file is in the format - <em>Match Number,Round Number,Date,Location,Home Team,Away Team,Result</em></p>
                            <p class="card-text">Ensure there are no empty lines at the end of the data.</p>
                            <p class="card-text"><input type="file" class="form-control-file" id="importGamesFile" name="importGamesFile"></p>
                            @error('importGamesFile')
                                <p class="text-danger"><strong>{{ $message }}</strong></p>
                            @enderror
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </div>
                </form>

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

