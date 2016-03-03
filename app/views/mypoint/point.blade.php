@extends('page')

@section('content')
  <div class="main">

    @if(empty($prizes))
    <style>
      .main { padding-top: 560px; }
    </style>
    <div class="winning">
      <div class="tips2">
        @if($customer->NEW_MEMBER)
          {{ event_text('newmbr_point_page_tip') }}
        @elseif($customer->MEMBER_JOIN_PRIZE || $customer->MEMBER_SHARE_PRIZE)
          {{ event_text('point_text1') }}
        @else
          {{ event_text('point_text2') }}
        @endif
      </div>
    </div>
    @endif

    <div class="fund_list">
      @if(!empty($prizes))
        <div class="block">
          <ul>
            @foreach($prizes as $value)
              <li class="items">
                <p class="text">
                  {{ event_text('prize_list_item', null, array(
                        '{date}' =>  Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value->created_at)->format('Y.m.d'),
                        '{prize}' => $value->name)) }}
                </p>
              </li>
            @endforeach
          </ul>
          @if($customer->NEW_MEMBER)
            <p class="point-tips">{{ event_text('newmbr_point_tip') }}</p>
          @endif
        </div>
      @endif

    </div>
  </div>
@stop
