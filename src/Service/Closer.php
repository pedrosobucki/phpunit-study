<?php

namespace PSobucki\Auction\Service;

use Exception;
use PSobucki\Auction\DAO\AuctionDAO;
use PSobucki\Auction\Exceptions\FailedToSendMailException;

class Closer
{

    public function __construct(
        private readonly AuctionDAO $dao = new AuctionDAO(),
        private readonly MailSender $mailSender = new MailSender()
    )
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

                try {
                    $this->mailSender->notifiesAuctionEnd($auction);
                } catch (FailedToSendMailException $e) {
                    // treats exception
                }
            }
        }
    }
}