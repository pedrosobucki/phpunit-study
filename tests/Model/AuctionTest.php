<?php

namespace PSobucki\Auction\Tests\Model;

use PHPUnit\Framework\TestCase;
use PSobucki\Auction\Model\Auction;
use PSobucki\Auction\Model\Bid;
use PSobucki\Auction\Model\User;

class AuctionTest extends TestCase
{

    /**
     * @dataProvider auctionSets
     */
    public function testAuctionMustReceiveBids(
        int $bidQuantity,
        Auction $auction,
        array $values
    ): void {
        self::assertCount($bidQuantity, $auction->getBids());

        foreach ($values as $i => $expectedValue) {
            self::assertEquals($expectedValue, $auction->getBids()[$i]->getValue());
        }
    }

    public function testAuctionMustNotReceiveRepeatedBids(): void
    {
        $auction = new Auction('Variant');
        $anne = new User('Anne');

        $auction->receiveBid(new Bid($anne, 1000));
        $auction->receiveBid(new Bid($anne, 1500));

        $bids = $auction->getBids();
        self::assertCount(1, $bids);
        self::assertEquals(1000, $bids[array_key_last($bids)]->getValue());
    }

    public function testAuctionShouldNotReceiveMoreThan5BidsPerUser(): void
    {
        $auction = new Auction('White Focus');
        $john = new User('John');
        $anne = new User('Anne');

        $auction->receiveBid(new Bid($john, 1000));
        $auction->receiveBid(new Bid($anne, 1500));
        $auction->receiveBid(new Bid($john, 2000));
        $auction->receiveBid(new Bid($anne, 2500));
        $auction->receiveBid(new Bid($john, 3000));
        $auction->receiveBid(new Bid($anne, 3500));
        $auction->receiveBid(new Bid($john, 4000));
        $auction->receiveBid(new Bid($anne, 4500));
        $auction->receiveBid(new Bid($john, 5000));
        $auction->receiveBid(new Bid($anne, 5500));

        $auction->receiveBid(new Bid($john, 6000));

        $bids = $auction->getBids();
        static::assertCount(10, $bids);
        static::assertEquals(5500, $bids[array_key_last($bids)]->getValue());
    }
    
    public function auctionSets(): array
    {
        $john = new User('John');
        $anne = new User('Anne');

        $auctionWith2Bids = new Auction('Fiat 147 0KM');
        $auctionWith2Bids->receiveBid(new Bid($john, 1000));
        $auctionWith2Bids->receiveBid(new Bid($anne, 2000));

        $auctionWith1Bid = new Auction('Fusca 1972 0KM');
        $auctionWith1Bid->receiveBid(new Bid($anne, 5000));

        return [
            '2-bids' => [2, $auctionWith2Bids, [1000, 2000]],
            '1-bid' => [1, $auctionWith1Bid, [5000]]
        ];
    }
}