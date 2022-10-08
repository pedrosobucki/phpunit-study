<?php

namespace PSobucki\Auction\Service;

use PSobucki\Auction\Exceptions\EmptyAuctionException;
use PSobucki\Auction\Model\Auction;
use PSobucki\Auction\Model\Bid;

class Evaluator
{
    private float $highestBid = -INF;
    private float $lowestBid = INF;
    private array $highestBids = [];

    /**
     * @throws EmptyAuctionException
     */
    public function evaluate(Auction $auction): void
    {
        if (empty($auction->getBids())) {
            throw new EmptyAuctionException();
        }

        foreach ($auction->getBids() as $bid) {
            if ($bid->getValue() > $this->highestBid) {
                $this->highestBid = $bid->getValue();
            }

            if ($bid->getValue() < $this->lowestBid) {
                $this->lowestBid = $bid->getValue();
            }
        }

        $this->saveHighestBids($auction);
    }

    private function saveHighestBids(Auction $auction): void
    {
        $bids = $auction->getBids();

        usort($bids, static fn (Bid $firstBid, Bid $secondBid) =>
            $secondBid->getValue() - $firstBid->getValue()
        );

        $this->highestBids = array_slice($bids, 0, 3);
    }

    public function getHighestBid(): float
    {
        return $this->highestBid;
    }

    public function getLowestBid(): float
    {
        return $this->lowestBid;
    }

    /**
     * @return Bid[]
     */
    public function getHighestBids(): array
    {
        return $this->highestBids;
    }
}