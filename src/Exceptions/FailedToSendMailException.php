<?php

namespace PSobucki\Auction\Exceptions;

use Exception;

class FailedToSendMailException extends Exception
{

    public function __construct()
    {
        parent::__construct();
        $this->message = "Failed to send e-mail!";
    }

}