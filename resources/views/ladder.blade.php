@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="col-6">
        <h1>Ladder</h1>
    </div>
    <div class="table-responsive">
    <table class="table table-sm table-dark table-striped">
        <thead>
            <tr>
              <th class="col text-center">#</th>
              <th class="col">Name</th>
              <th class="col text-center">Rd</th>
              <th class="col text-center">Team</th>
              <th class="col text-center">Rd Pts</th>
              <th class="col text-center">Power Tip</th>
              <th class="col text-center">Total Pts</th>
            </tr>
          </thead>
          <tbody>
              @isset($ladder)
              @php
                  $rank = 1;
              @endphp
                @foreach ($ladder as $l)
                    <tr>
                        <td class="text-center">{{ $rank }}</td>
                        <td>{{ $l->displayName }}</td>
                        <td class="text-center">{{ $l->round }}</td>
                        <td class="text-center">{{ $l->teamTipped }}</td>
                        <td class="text-center">{{ $l->roundPoints }}</td>
                        <td class="text-center">
                            @isset($allPowerTips)
                                @foreach ($allPowerTips as $p)
                                    @if ($p->userId == $l->userId)
                                        @if ($p->round == $l->round)
                                            <img src="/images/stars-png-613.png" width="20px" height="20px" title="PowerTip this round">
                                        @else
                                            <img src="/images/tick.png" width="20px" height="15px" title="PowerTip round {{ $p->round }}">
                                        @endif
                                    @endif
                                @endforeach
                            @endisset                            
                        </td>
                        <td class="text-center">{{ $l->totalPoints }}</td>
                    </tr>
                    @php
                        $rank++;
                    @endphp
                @endforeach
                  
              @endisset
          </tbody>
    </table>
    </div>
</div>
@endsection