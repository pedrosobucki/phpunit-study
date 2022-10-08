<?php

namespace PSobucki\Auction\Exceptions;

class AuctionException extends \Exception
{

    public function __construct()
    {
        parent::__construct();
        $this->message = "Exception thrown while manipulating Auction type of data.";
    }

}