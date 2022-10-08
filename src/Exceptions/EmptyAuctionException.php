<?php

namespace PSobucki\Auction\Exceptions;

class EmptyAuctionException extends EvaluatorException
{
    public function __construct()
    {
        parent::__construct();
        $this->message ="An empty Auction cannot be evaluated";
    }
}