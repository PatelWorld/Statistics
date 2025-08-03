<?php

declare(strict_types=1);

namespace PatelWorld\Statistics\Descriptive;

use PatelWorld\Statistics\AbstractStatisticalOperation;
use PatelWorld\Statistics\Exceptions\EmptyDataException;
use PatelWorld\Statistics\Exceptions\InvalidDataException;

/**
 * Calculates percentiles of a dataset
 */
class Percentile extends AbstractStatisticalOperation
{
    /**
     * Calculate a specific percentile
     *
     * @param array<int|float> $data       Array of numeric values
     * @param float            $percentile Percentile to calculate (0-100)
     *
     * @return float The percentile value
     * @throws InvalidDataException|EmptyDataException If the percentile is not between 0 and 100
     */
    public function calculatePercentile(array $data, float $percentile): float
    {
        $this->validate($data);
        
        if ($percentile < 0 || $percentile > 100) {
            throw new InvalidDataException('Percentile must be between 0 and 100');
        }
        
        $sorted = $data;
        sort($sorted, SORT_NUMERIC);
        
        $n = count($sorted);
        $pos = ($percentile / 100) * ($n - 1);
        $floor = (int)floor($pos);
        $ceil = (int)ceil($pos);
        
        if ($floor == $ceil) {
            return $sorted[$floor];
        }
        
        // Linear interpolation
        $lower = $sorted[$floor] * ($ceil - $pos);
        $upper = $sorted[$ceil] * ($pos - $floor);
        
        return $lower + $upper;
    }
}
