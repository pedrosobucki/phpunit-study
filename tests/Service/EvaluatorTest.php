<?php

namespace PSobucki\Auction\Tests\Service;

use PHPUnit\Framework\TestCase;
use PSobucki\Auction\Model\Auction;
use PSobucki\Auction\Model\Bid;
use PSobucki\Auction\Model\User;
use PSobucki\Auction\Service\Evaluator;

class EvaluatorTest extends TestCase
{

    public function testAuctioneerShouldFindHighestBidInAscendingOrder(): void
    {
        // Arrange - Given;
        $auction = new Auction("Fiat 147 0km");

        $john = new User("John");
        $anne = new User("Anne");

        $auction->receiveBid(new Bid($john, 2000));
        $auction->receiveBid(new Bid($anne, 2500));

        $auctioneer = new Evaluator();


        // Act - When
        $auctioneer->evaluate($auction);
        $expectedValue = $auctioneer->getHighestBid();


        // Assert - Then
        self::assertEquals(2500, $expectedValue);
    }

    public function testAuctioneerShouldFindHighestBidInDescendingOrder(): void
    {
        // Arrange - Given;
        $auction = new Auction("Fiat 147 0km");

        $john = new User("John");
        $anne = new User("Anne");

        $auction->receiveBid(new Bid($anne, 2500));
        $auction->receiveBid(new Bid($john, 2000));

        $auctioneer = new Evaluator();


        // Act - When
        $auctioneer->evaluate($auction);
        $expectedValue = $auctioneer->getHighestBid();


        // Assert - Then
        self::assertEquals(2500, $expectedValue);
    }

    public function testAuctioneerShouldFindLowestBidInAscendingOrder(): void
    {
        // Arrange - Given;
        $auction = new Auction("Fiat 147 0km");

        $john = new User("John");
        $anne = new User("Anne");

        $auction->receiveBid(new Bid($john, 2000));
        $auction->receiveBid(new Bid($anne, 2500));

        $auctioneer = new Evaluator();


        // Act - When
        $auctioneer->evaluate($auction);
        $expectedValue = $auctioneer->getLowestBid();


        // Assert - Then
        self::assertEquals(2000.0, $expectedValue);
    }

    public function testAuctioneerShouldFindLowestBidInDescendingOrder(): void
    {
        // Arrange - Given;
        $auction = new Auction("Fiat 147 0km");

        $john = new User("John");
        $anne = new User("Anne");

        $auction->receiveBid(new Bid($anne, 2500));
        $auction->receiveBid(new Bid($john, 2000));

        $auctioneer = new Evaluator();


        // Act - When
        $auctioneer->evaluate($auction);
        $expectedValue = $auctioneer->getLowestBid();


        // Assert - Then
        self::assertEquals(2000.0, $expectedValue);
    }

    public function testAuctioneerMustRetrieve3HighestBiddingValues(): void
    {
        $auction = new Auction('Fiat 147 0KM');
        $john = new User('Josh');
        $anne = new User('Anne');
        $mary = new User('Mary');
        $jorge = new User('Jorge');

        $auction->receiveBid(new Bid($anne, 1500));
        $auction->receiveBid(new Bid($john, 1000));
        $auction->receiveBid(new Bid($mary, 2000));
        $auction->receiveBid(new Bid($jorge, 1700));

        $auctioneer = new Evaluator();
        $auctioneer->evaluate($auction);

        $highestBids = $auctioneer->getHighestBids();
        static::assertCount(3, $highestBids);
        static::assertEquals(2000, $highestBids[0]->getValue());
        static::assertEquals(1700, $highestBids[1]->getValue());
        static::assertEquals(1500, $highestBids[2]->getValue());
    }

}