<?php

namespace PSobucki\Auction\Exceptions;

use PSobucki\Auction\Model\Auction;
use PSobucki\Auction\Model\User;

class MaximumBidsPerUserExceededException extends AuctionException
{
    private int $maxBids = Auction::MAX_BIDS_PER_USER;
    private User $user;

    public function __construct(User $user)
    {
        parent::__construct();

        $this->user = $user;
        $this->message = "Maximum Bids count of {$this->maxBids} was exceeded by User.";
    }

    public function user(): User
    {
        return $this->user;
    }

    public function maxBids(): int
    {
        return $this->maxBids;
    }

}