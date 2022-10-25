<?php

namespace PSobucki\Auction\Service;

use PSobucki\Auction\Exceptions\FailedToSendMailException;
use PSobucki\Auction\Model\Auction;

class MailSender
{

    /**
     * @throws FailedToSendMailException
     */
    public function notifiesAuctionEnd(Auction $auction): void
    {
        $success = mail(
            'user@test.com',
            'Auction Closed',
            "Auction referring to {$auction->description()} closed."
        );

        if (!$success) {
            throw new FailedToSendMailException();
        }
    }

}