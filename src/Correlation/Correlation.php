<?php

declare(strict_types=1);

namespace PatelWorld\Statistics\Correlation;

use PatelWorld\Statistics\AbstractStatisticalOperation;
use PatelWorld\Statistics\Descriptive\StandardDeviation;

/**
 * Calculates the Pearson correlation coefficient between two datasets
 */
class Correlation extends AbstractStatisticalOperation
{
    /**
     * Calculate Pearson correlation coefficient
     *
     * @param array<int|float> $x First dataset
     * @param array<int|float> $y Second dataset
     *
     * @return float The correlation coefficient (-1 to 1)
     */
    public function calculate(array $x, array $y): float
    {
        $covariance = (new Covariance(true))->calculate($x, $y);
        $stdDevX = (new StandardDeviation(true))->calculate($x);
        $stdDevY = (new StandardDeviation(true))->calculate($y);
        
        // Handle division by zero
        if ($stdDevX == 0 || $stdDevY == 0) {
            if ($stdDevX == 0 && $stdDevY == 0) {
                return 1; // Perfect correlation if both are constant
            }
            return 0; // No correlation if one is constant
        }
        
        return $covariance / ($stdDevX * $stdDevY);
    }
}
