<?php

declare(strict_types=1);

namespace PatelWorld\Statistics\Descriptive;

use PatelWorld\Statistics\AbstractStatisticalOperation;
use PatelWorld\Statistics\Contracts\UnivariateOperation;
use PatelWorld\Statistics\Exceptions\EmptyDataException;
use PatelWorld\Statistics\Exceptions\InvalidDataException;

/**
 * Calculates quartiles of a dataset
 */
class Quartiles extends AbstractStatisticalOperation implements UnivariateOperation
{
    /**
     * Calculate quartiles
     *
     * @param array<int|float> $data Array of numeric values
     *
     * @return array<string, float> Associative array with Q1, Q2 (median), and Q3
     * @throws InvalidDataException|EmptyDataException If the validation failed
     */
    public function calculate(array $data): array
    {
        $this->validate($data);
        
        $sorted = $data;
        sort($sorted, SORT_NUMERIC);
        $count = count($sorted);
        
        // Q2 is the median
        $median = (new Median())->calculate($sorted);
        
        // Find indices for Q1 and Q3
        $lowerHalf = array_slice($sorted, 0, (int)floor($count / 2));
        $upperHalf = array_slice($sorted, (int)ceil($count / 2));
        
        $q1 = (new Median())->calculate($lowerHalf);
        $q3 = (new Median())->calculate($upperHalf);
        
        return [
            'Q1'  => $q1,
            'Q2'  => $median,
            'Q3'  => $q3,
            'IQR' => $q3 - $q1
        ];
    }
}
