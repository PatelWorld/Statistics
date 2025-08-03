<?php

declare(strict_types=1);

namespace PatelWorld\Statistics\Descriptive;

use InvalidArgumentException;
use PatelWorld\Statistics\AbstractStatisticalOperation;
use PatelWorld\Statistics\Contracts\UnivariateOperation;
use PatelWorld\Statistics\Exceptions\EmptyDataException;
use PatelWorld\Statistics\Exceptions\InvalidDataException;

/**
 * Calculates the mean (average) of a dataset
 */
class Mean extends AbstractStatisticalOperation implements UnivariateOperation
{
    /**
     * Calculate the arithmetic mean
     *
     * @param array<int|float> $data Array of numeric values
     *
     * @return float The mean value
     * @throws EmptyDataException|InvalidArgumentException If the array is empty
     * @throws InvalidDataException If the validation failed
     *
     */
    public function calculate(array $data): float
    {
        $this->validate($data);
        
        return array_sum($data) / count($data);
    }
}
