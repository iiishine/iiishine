@extends('page')

@section('content')
  <div class="zdred">
    <div class="himg @if(isset($reg))zccgimg@endif"></div>
    <div class="shp">
      <div class="limg"><img src="{{ $prize->image }}" alt=""></div>
      <div class="rcon">
        <div class="nub">{{ $prize->name }}</div>
        <div class="outer">
          {{ $prize->merchant_intro }}
        </div>
      </div>
    </div>
    @if(isset($reg))
      <div class="btn btn-share"><a href="javascript:void(0);">{{ event_text('btn_lottery') }}</a></div>
    @else
      <div class="btn"><a href="{{ url('merchants') }}">{{ event_text('btn_lottery') }}</a></div>
    @endif
  </div>
@stop
