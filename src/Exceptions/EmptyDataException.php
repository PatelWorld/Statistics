<?php

declare(strict_types=1);

namespace PatelWorld\Statistics\Exceptions;

/**
 * Exception thrown when an empty dataset is provided
 */
class EmptyDataException extends StatisticsException
{
    public function __construct(string $message = "Data array cannot be empty", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}