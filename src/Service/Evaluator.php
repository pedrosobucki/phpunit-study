<?php

namespace PSobucki\Auction\Service;

use PSobucki\Auction\Model\Auction;

class Evaluator
{
    private float $highestBid = -INF;

    public function evaluate(Auction $auction): void
    {
        foreach ($auction->getBids() as $bid) {
            if ($bid->getValue() > $this->highestBid) {
                $this->highestBid = $bid->getValue();
            }
        }
    }

    public function getHighestBid(): float
    {
        return $this->highestBid;
    }
}