<?php

namespace PSobucki\Auction\Service;

use Exception;
use PSobucki\Auction\DAO\AuctionDAO;

class Closer
{

    public function __construct(private readonly AuctionDAO $dao = new AuctionDAO())
    {}

    /**
     * @throws Exception
     */
    public function close(): void
    {
        $auctions = $this->dao->fetchNotClosed();

        foreach ($auctions as $auction) {
            if ($auction->wasCreatedMoreThanOneWeekAgo()) {
                $auction->close();
                $this->dao->update($auction);
            }
        }
    }
}