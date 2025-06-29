<?php

namespace SiteMap\Exceptions;
class InvalidArrayException extends \InvalidArgumentException
{
    public function __construct($code = 0, \Throwable $previous = null)
    {
        $error = "Неверные входные данные";
        parent::__construct($error, $code, $previous);
    }
}