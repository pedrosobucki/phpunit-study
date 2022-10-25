<?php

namespace Psobucki\Auction\Tests\Service;

use Exception;
use PHPUnit\Framework\TestCase;
use PSobucki\Auction\DAO\AuctionDAO;
use PSobucki\Auction\Model\Auction;
use PSobucki\Auction\Service\Closer;

class CloserTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testMustCloseAuctionsStartedMoreThanOneWeekAgo(): void
    {
        $auctionFiat = new Auction('Fiat 147 0Km', new \DateTimeImmutable('8 days ago'));
        $auctionBeetle = new Auction('Blue Beetle', new \DateTimeImmutable('10 days ago'));

        $auctionDAO = $this->createMock(AuctionDAO::class);

        $auctionDAO->method('fetchClosed')
            ->willReturn([$auctionFiat, $auctionBeetle]);

        $auctionDAO->expects(self::once())
            ->method('fetchNotClosed')
            ->willReturn([$auctionFiat, $auctionBeetle]);

        $auctionDAO->expects($this->exactly(2))
            ->method('update')
            ->withConsecutive(
                [$auctionFiat],
                [$auctionBeetle]
            );

        $auctionDAO->fetchClosed();
        $auctionDAO->save($auctionBeetle);

        $closer = new Closer($auctionDAO);
        $closer->close();

        $closedAuctions = $auctionDAO->fetchClosed();
        static::assertCount(2, $closedAuctions);
        static::assertEquals('Fiat 147 0Km', $closedAuctions[0]->description());
        static::assertEquals('Blue Beetle', $closedAuctions[1]->description());

        static::assertTrue($closedAuctions[0]->isClosed());
        static::assertTrue($closedAuctions[1]->isClosed());
    }
}