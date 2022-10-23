<?php

namespace Psobucki\Auction\Tests\Service;

use PHPUnit\Framework\TestCase;
use PSobucki\Auction\DAO\AuctionDAO;
use PSobucki\Auction\Model\Auction;
use PSobucki\Auction\Service\Closer;

class CloserTest extends TestCase
{
    public function testMustCloseAuctionsStartedMoreThanOneWeekAgo(): void
    {
        $auctionFiat = new Auction('Fiat 147 0Km', new \DateTimeImmutable('8 days ago'));
        $auctionBeetle = new Auction('Blue Beetle', new \DateTimeImmutable('10 days ago'));

        $auctionDAO = new AuctionDAO();
        $auctionDAO->save($auctionFiat);
        $auctionDAO->save($auctionBeetle);

        $closer = new Closer();
        $closer->close();

        $closedAuctions = $auctionDAO->fetchClosed();
        static::assertCount(2, $closedAuctions);
        static::assertEquals('Fiat 147 0Km', $closedAuctions[0]->description());
        static::assertEquals('Blue Beetle', $closedAuctions[1]->description());
    }
}