<?php

declare(strict_types=1);

namespace PatelWorld\Statistics\Descriptive;

use InvalidArgumentException;
use PatelWorld\Statistics\AbstractStatisticalOperation;
use PatelWorld\Statistics\Contracts\UnivariateOperation;
use PatelWorld\Statistics\Exceptions\EmptyDataException;
use PatelWorld\Statistics\Exceptions\InvalidDataException;

/**
 * Calculates the median (middle value) of a dataset
 */
class Median extends AbstractStatisticalOperation implements UnivariateOperation
{
    /**
     * Calculate the median
     *
     * @param array<int|float> $data Array of numeric values
     *
     * @return float The median value
     * @throws InvalidArgumentException If the array is empty
     * @throws InvalidDataException|EmptyDataException If the validation failed
     *
     */
    public function calculate(array $data): float
    {
        $this->validate($data);
        
        // Sort the data
        $sortedData = $data;
        sort($sortedData, SORT_NUMERIC);
        
        $count = count($sortedData);
        $middle = floor(($count - 1) / 2);
        
        if ($count % 2 === 0) {
            // Even number of elements, average the two middle values
            return ($sortedData[$middle] + $sortedData[$middle + 1]) / 2;
        } else {
            // Odd number of elements, return the middle value
            return $sortedData[$middle];
        }
    }
}
