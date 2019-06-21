<?php

namespace Tubber\Indexer\Exceptions;

class NoCoreFoundException extends \Exception
{

    public function __construct()
    {
        $message = "No search core was found in the provided configuration";

        parent::__construct($message);
    }

}