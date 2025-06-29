<?php

class InvalidFileTypeException extends \InvalidArgumentException
{
    public function __construct($code = 0, \Throwable $previous = null)
    {
        $error = "Unsupported file type. Allowed: xml, json, csv";
        parent::__construct($error, $code, $previous);
    }
}