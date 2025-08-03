<?php

declare(strict_types=1);

namespace PatelWorld\Statistics\Exceptions;

/**
 * Exception thrown when there isn't enough data for a calculation
 */
class InsufficientDataException extends StatisticsException
{
    public function __construct(string $message = "Insufficient data points for this operation", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}