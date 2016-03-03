@extends('page')

@section('content')
<div class="again">
  <div class="agimg"><img src="{{ asset('images/againimg.png') }}"/></div>
  <div class="btn"><a href="{{ url('commonreg/reg') }}">{{ event_text('btn_receive') }}</a></div>
</div>
@stop
