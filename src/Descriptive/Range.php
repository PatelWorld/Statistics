<?php

declare(strict_types=1);

namespace PatelWorld\Statistics\Descriptive;

use PatelWorld\Statistics\AbstractStatisticalOperation;
use PatelWorld\Statistics\Contracts\UnivariateOperation;
use PatelWorld\Statistics\Exceptions\EmptyDataException;
use PatelWorld\Statistics\Exceptions\InvalidDataException;

/**
 * Calculates the range (difference between max and min) of a dataset
 */
class Range extends AbstractStatisticalOperation implements UnivariateOperation
{
    /**
     * Calculate the range
     *
     * @param array<int|float> $data Array of numeric values
     *
     * @return float The range value
     * @throws InvalidDataException|EmptyDataException If the validation failed
     *
     */
    public function calculate(array $data): float
    {
        $this->validate($data);
        
        return max($data) - min($data);
    }
}
