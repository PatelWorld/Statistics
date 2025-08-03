<?php

declare(strict_types=1);

namespace PatelWorld\Statistics\Descriptive;

use PatelWorld\Statistics\AbstractStatisticalOperation;
use PatelWorld\Statistics\Contracts\UnivariateOperation;
use PatelWorld\Statistics\Exceptions\EmptyDataException;
use PatelWorld\Statistics\Exceptions\InsufficientDataException;
use PatelWorld\Statistics\Exceptions\InvalidDataException;

/**
 * Calculates the skewness of a dataset
 */
class Skewness extends AbstractStatisticalOperation implements UnivariateOperation
{
    /**
     * Calculate skewness
     *
     * @param array<int|float> $data Array of numeric values
     *
     * @return float The skewness value
     * @throws InvalidDataException|EmptyDataException If the validation failed
     *
     */
    public function calculate(array $data): float
    {
        $this->validate($data);
        
        $n = count($data);
        $mean = (new Mean())->calculate($data);
        $stdDev = (new StandardDeviation())->calculate($data);
        
        if ($stdDev == 0) {
            return 0; // No skewness when all values are the same
        }
        
        $sum = 0;
        foreach ($data as $value) {
            $sum += pow(($value - $mean) / $stdDev, 3);
        }
        
        // Fisher-Pearson coefficient of skewness
        return ($n / (($n - 1) * ($n - 2))) * $sum;
    }
    
    /**
     * {@inheritdoc}
     */
    public function validate(array $data): bool
    {
        parent::validate($data);
        
        if (count($data) < 3) {
            throw new InsufficientDataException('Skewness calculation requires at least 3 data points');
        }
        
        return true;
    }
}
