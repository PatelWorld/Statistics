<?php

declare(strict_types=1);

namespace PatelWorld\Statistics;

use PatelWorld\Statistics\Contracts\StatisticalOperation;
use PatelWorld\Statistics\Exceptions\EmptyDataException;
use PatelWorld\Statistics\Exceptions\InvalidDataException;

/**
 * Base abstract class for all statistical operations
 */
abstract class AbstractStatisticalOperation implements StatisticalOperation
{
    /**
     * Validates that the data array is not empty and contains only numeric values
     *
     * @param array<int|float> $data Input data to validate
     *
     * @return bool True if data is valid
     * @throws EmptyDataException If data array is empty
     * @throws InvalidDataException If data contains non-numeric values
     */
    public function validate(array $data): bool
    {
        if (empty($data)) {
            throw new EmptyDataException();
        }
        
        foreach ($data as $value) {
            if (!is_numeric($value)) {
                throw new InvalidDataException();
            }
        }
        
        return true;
    }
}
