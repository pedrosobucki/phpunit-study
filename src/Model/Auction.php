<?php

namespace PSobucki\Auction\Model;

class Auction
{
    public const MAX_BIDS_PER_USER = 5;

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

        $totalUserBids = $this->bidCountByUser($bid->getUser());
        if ($totalUserBids >= self::MAX_BIDS_PER_USER) {
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

    private function isRepeatedUserBid(Bid $bid): bool
    {
        $lastBid = $this->bids[array_key_last($this->bids)];
        return $lastBid->getUser() === $bid->getUser();
    }

    private function bidCountByUser(User $user): int
    {
        return array_reduce(
            $this->bids,
            static function (int $accTotal, Bid $currentBid) use ($user) {
                if ($currentBid->getUser() === $user) {
                    return ++$accTotal;
                }
                return $accTotal;
            },
            0
        );
    }
}
