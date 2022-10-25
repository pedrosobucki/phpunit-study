<?php

namespace Psobucki\Auction\Tests\Service;

use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PSobucki\Auction\DAO\AuctionDAO;
use PSobucki\Auction\Exceptions\FailedToSendMailException;
use PSobucki\Auction\Model\Auction;
use PSobucki\Auction\Service\Closer;
use PSobucki\Auction\Service\MailSender;

class CloserTest extends TestCase
{
    private Closer $closer;
    private MockObject $mailSenderMock;
    private Auction $auctionFiat;
    private Auction $auctionBeetle;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->auctionFiat = new Auction('Fiat 147 0Km', new \DateTimeImmutable('8 days ago'));
        $this->auctionBeetle = new Auction('Blue Beetle', new \DateTimeImmutable('10 days ago'));

        $auctionDAO = $this->createMock(AuctionDAO::class);

        $auctionDAO->method('fetchClosed')
            ->willReturn([$this->auctionFiat, $this->auctionBeetle]);

        $auctionDAO->expects(self::once())
            ->method('fetchNotClosed')
            ->willReturn([$this->auctionFiat, $this->auctionBeetle]);

        $auctionDAO->expects($this->exactly(2))
            ->method('update')
            ->withConsecutive(
                [$this->auctionFiat],
                [$this->auctionBeetle]
            );

        $auctionDAO->fetchClosed();
        $auctionDAO->save($this->auctionBeetle);

        $this->mailSenderMock = $this->createMock(MailSender::class);
        $this->closer = new Closer($auctionDAO, $this->mailSenderMock);
    }

    /**
     * @throws Exception
     */
    public function testMustCloseAuctionsStartedMoreThanOneWeekAgo(): void
    {
        $this->closer->close();

        $closedAuctions = [$this->auctionFiat, $this->auctionBeetle];

        static::assertCount(2, $closedAuctions);

        static::assertTrue($closedAuctions[0]->isClosed());
        static::assertTrue($closedAuctions[1]->isClosed());
    }

    /**
     * @throws Exception
     */
    public function testAuctionsAreStillClosedWhenMailExceptionIsThrown(): void
    {
        $this->mailSenderMock
            ->expects($this->exactly(2))
            ->method('notifiesAuctionEnd')
            ->willThrowException(new FailedToSendMailException());

        $this->closer->close();
    }
}