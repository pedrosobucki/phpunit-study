<?php

namespace PSobucki\Auction\Service;

use PSobucki\Auction\DAO\AuctionDAO;

class Closer
{
    public function close()
    {
        $dao = new AuctionDao();
        $auctions = $dao->fetchNotClosed();

        foreach ($auctions as $auction) {
            if ($auction->wasCreatedMoreThanOneWeekAgo()) {
                $auction->close();
                $dao->update($auction);
            }
        }
    }
}