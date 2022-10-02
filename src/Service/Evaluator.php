<?php

namespace PSobucki\Auction\Service;

use PSobucki\Auction\Model\Auction;

class Evaluator
{
    private float $highestBid;

    public function evaluate(Auction $auction): void
    {
        $bids = $auction->getBids();
        $lastBid = $bids[count($bids) - 1];

        $this->highestBid = $lastBid->getValue();
    }

    public function getHighestBid(): float
    {
        return $this->highestBid;
    }
}