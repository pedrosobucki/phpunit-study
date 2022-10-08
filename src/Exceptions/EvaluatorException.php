<?php

namespace PSobucki\Auction\Exceptions;

class EvaluatorException extends \Exception
{
    public function __construct()
    {
        parent::__construct();
        $this->message = "Exception thrown while manipulating Evaluator type of data.";
    }
}