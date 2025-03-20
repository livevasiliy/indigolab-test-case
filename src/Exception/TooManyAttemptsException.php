<?php

declare(strict_types=1);


namespace App\Exception;

class TooManyAttemptsException extends \Exception
{
    protected $message = 'Too many attempts. Try again later.';
}
