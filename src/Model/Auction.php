<?php

namespace PSobucki\Auction\Model;

use DateTime;
use DateTimeImmutable;
use PSobucki\Auction\Exceptions\MaximumBidsPerUserExceededException;
use PSobucki\Auction\Exceptions\UserCannotBidTwoTimesInARowException;

class Auction
{
    public const MAX_BIDS_PER_USER = 5;

    /** @var Bid[] */
    private array $bids = [];
    private bool $closed = false;

    public function __construct(
        private readonly string $description,
        private readonly DateTimeImmutable $startDate = new DateTimeImmutable(),
        private readonly ?int $id = null
    )
    {}

    public function description(): string
    {
        return $this->description;
    }


    public function id(): ?int
    {
        return $this->id;
    }

    public function startDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function wasCreatedMoreThanOneWeekAgo(): bool
    {
        $today = new DateTime();
        $difference = $this->startDate->diff($today);

        return $difference->days > 7;
    }

    public function isClosed(): bool
    {
        return $this->closed;
    }

    public function close(): void
    {
        $this->closed = true;
    }

    /**
     * @throws UserCannotBidTwoTimesInARowException
     * @throws MaximumBidsPerUserExceededException
     */
    public function receiveBid(Bid $bid): void
    {
        if (!empty($this->bids) && $this->isRepeatedUserBid($bid)) {
            throw new UserCannotBidTwoTimesInARowException();
        }

        $totalUserBids = $this->bidCountByUser($bid->getUser());
        if ($totalUserBids >= self::MAX_BIDS_PER_USER) {
            throw new MaximumBidsPerUserExceededException($bid->getUser());
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
