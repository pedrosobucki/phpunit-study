<?php

namespace PSobucki\Auction\Exceptions;

class UserCannotBidTwoTimesInARowException extends AuctionException
{

    public function __construct()
    {
        parent::__construct();
        $this->message = "When adding Bids to an Auction, the same User cannot bid two consecutive times in the same Auction.";
    }

}