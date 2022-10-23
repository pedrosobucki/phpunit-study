<?php

namespace Psobucki\Auction\Tests\Service;

use Exception;
use PHPUnit\Framework\TestCase;
use PSobucki\Auction\DAO\AuctionDAO;
use PSobucki\Auction\Model\Auction;
use PSobucki\Auction\Service\Closer;

class AuctionDAOMock extends AuctionDAO
{
    /** @var Auction[] */
    private array $auctions = [];

    public function save(Auction $auction): bool
    {
        $this->auctions[] = $auction;
        return true;
    }

    /** @return Auction[] */
    public function fetchClosed(): array
    {
        return array_filter($this->auctions,
            static fn(Auction $auction) => $auction->isClosed()
        );
    }

    /** @return Auction[] */
    public function fetchNotClosed(): array
    {
        return array_filter($this->auctions,
            static fn(Auction $auction) => !$auction->isClosed()
        );
    }

    public function update(Auction $auction): bool
    {
        return true;
    }

}

class CloserTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testMustCloseAuctionsStartedMoreThanOneWeekAgo(): void
    {
        $auctionFiat = new Auction('Fiat 147 0Km', new \DateTimeImmutable('8 days ago'));
        $auctionBeetle = new Auction('Blue Beetle', new \DateTimeImmutable('10 days ago'));

        $auctionDAO = new AuctionDAOMock();
        $auctionDAO->save($auctionFiat);
        $auctionDAO->save($auctionBeetle);

        $closer = new Closer($auctionDAO);
        $closer->close();

        $closedAuctions = $auctionDAO->fetchClosed();
        static::assertCount(2, $closedAuctions);
        static::assertEquals('Fiat 147 0Km', $closedAuctions[0]->description());
        static::assertEquals('Blue Beetle', $closedAuctions[1]->description());
    }
}