<?php

declare(strict_types=1);

namespace PatelWorld\Statistics\Contracts;

use PatelWorld\Statistics\Exceptions\EmptyDataException;
use PatelWorld\Statistics\Exceptions\InvalidDataException;

/**
 * Interface for all statistical operations
 */
interface StatisticalOperation
{
    /**
     * Validates input data for statistical calculations
     *
     * @param array<int|float> $data Input data to validate
     *
     * @return bool True if data is valid
     * @throws EmptyDataException If data array is empty
     * @throws InvalidDataException If data contains non-numeric values
     */
    public function validate(array $data): bool;
}
