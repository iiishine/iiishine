<?php

class BaseController extends Controller {

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if ( ! is_null($this->layout))
        {
            $this->layout = View::make($this->layout);
        }
    }

    /**
     * @param string $type 'join' 或者  'share'
     *
     * @return object
     */
    protected function createGuestPrize($customer, $type = 'join')
    {
        $prize = array(
            'name' => event_text("guest_{$type}_prize_name"),
            'image' => event_text("guest_{$type}_prize_image"),
//            'merchant_url' => $customer->isMember() ? event_text('guest_prize_member_link') : url('commonreg/reg'),
            'merchant_intro' => event_text("guest_{$type}_prize_desc"),
            'created_at' => $customer->CREATED_AT->format('Y-m-d H:i:s'),
        );

        return (object)$prize;
    }

}
