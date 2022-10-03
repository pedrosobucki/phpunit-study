<?php

namespace PSobucki\Auction\Service;

use PSobucki\Auction\Model\Auction;

class Evaluator
{
    private float $highestBid = -INF;
    private float $lowestBid = INF;

    public function evaluate(Auction $auction): void
    {
        foreach ($auction->getBids() as $bid) {
            if ($bid->getValue() > $this->highestBid) {
                $this->highestBid = $bid->getValue();
            }

            if ($bid->getValue() < $this->lowestBid) {
                $this->lowestBid = $bid->getValue();
            }
        }
    }

    public function getHighestBid(): float
    {
        return $this->highestBid;
    }

    public function getLowestBid(): float
    {
        return $this->lowestBid;
    }
}