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

}