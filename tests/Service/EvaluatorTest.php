<?php

namespace PSobucki\Auction\Tests\Service;

use PHPUnit\Framework\TestCase;
use PSobucki\Auction\Model\Auction;
use PSobucki\Auction\Model\Bid;
use PSobucki\Auction\Model\User;
use PSobucki\Auction\Service\Evaluator;

class EvaluatorTest extends TestCase
{
    private Evaluator $auctioneer;

    protected function setUp(): void
    {
        $this->auctioneer = new Evaluator();
    }

    /**
     * @dataProvider auctionInAscendingOrder
     * @dataProvider auctionInDescendingOrder
     * @dataProvider auctionInRandomOrder
     */
    public function testAuctioneerShouldFindHighestBid(Auction $auction): void
    {
        // Act - When
        $this->auctioneer->evaluate($auction);
        $expectedValue = $this->auctioneer->getHighestBid();

        // Assert - Then
        self::assertEquals(2500, $expectedValue);
    }

    /**
     * @dataProvider auctionInAscendingOrder
     * @dataProvider auctionInDescendingOrder
     * @dataProvider auctionInRandomOrder
     */
    public function testAuctioneerShouldFindLowestBid(Auction $auction): void
    {
        // Act - When
        $this->auctioneer->evaluate($auction);
        $expectedValue = $this->auctioneer->getLowestBid();

        // Assert - Then
        self::assertEquals(1700, $expectedValue);
    }

    /**
     * @dataProvider auctionInAscendingOrder
     * @dataProvider auctionInDescendingOrder
     * @dataProvider auctionInRandomOrder
     */
    public function testAuctioneerMustRetrieve3HighestBiddingValues(Auction $auction): void
    {
        // Act - When
        $this->auctioneer->evaluate($auction);
        $highestBids = $this->auctioneer->getHighestBids();

        // Assert - Then
        static::assertCount(3, $highestBids);
        static::assertEquals(2500, $highestBids[0]->getValue());
        static::assertEquals(2000, $highestBids[1]->getValue());
        static::assertEquals(1700, $highestBids[2]->getValue());
    }


    public function auctionInAscendingOrder(): array
    {
        $auction = new Auction('Fiat 147 0KM');

        $mary = new User('Mary');
        $john = new User('John');
        $anne = new User('Anne');

        $auction->receiveBid(new Bid($anne, 1700));
        $auction->receiveBid(new Bid($john, 2000));
        $auction->receiveBid(new Bid($mary, 2500));

        return [
            'ascending-order' => [$auction]
        ];
    }

    public function auctionInDescendingOrder(): array
    {
        $auction = new Auction('Fiat 147 0KM');

        $mary = new User('Mary');
        $john = new User('John');
        $anne = new User('Anne');

        $auction->receiveBid(new Bid($mary, 2500));
        $auction->receiveBid(new Bid($john, 2000));
        $auction->receiveBid(new Bid($anne, 1700));

        return [
            'descending-order' => [$auction]
        ];
    }

    public function auctionInRandomOrder(): array
    {
        $auction = new Auction('Fiat 147 0KM');

        $mary = new User('Mary');
        $john = new User('John');
        $anne = new User('Anne');

        $auction->receiveBid(new Bid($john, 2000));
        $auction->receiveBid(new Bid($mary, 2500));
        $auction->receiveBid(new Bid($anne, 1700));

        return [
            'random-order' => [$auction]
        ];
    }

}