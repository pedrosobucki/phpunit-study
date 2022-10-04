<?php

namespace PSobucki\Auction\Model;

class Auction
{
    /** @var Bid[] */
    private array $bids;
    private string $description;

    public function __construct(string $description)
    {
        $this->description = $description;
        $this->bids = [];
    }

    public function receiveBid(Bid $bid): void
    {
        if (!empty($this->bids) && $this->isRepeatedUserBid($bid)) {
            return;
        }

        $this->bids[] = $bid;
    }

    /**
     * @return Bid[]
     */
    public function getBids(): array
    {
        return $this->bids;
    }

    /**
     * @param Bid $bid
     * @return bool
     */
    public function isRepeatedUserBid(Bid $bid): bool
    {
        $lastBid = $this->bids[array_key_last($this->bids)];
        return $lastBid->getUser() === $bid->getUser();
    }
}
