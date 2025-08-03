<?php

declare(strict_types=1);

namespace PatelWorld\Statistics\Descriptive;

use PatelWorld\Statistics\AbstractStatisticalOperation;
use PatelWorld\Statistics\Contracts\UnivariateOperation;
use PatelWorld\Statistics\Exceptions\EmptyDataException;
use PatelWorld\Statistics\Exceptions\InsufficientDataException;
use PatelWorld\Statistics\Exceptions\InvalidDataException;

/**
 * Calculates the kurtosis of a dataset
 */
class Kurtosis extends AbstractStatisticalOperation implements UnivariateOperation
{
    /**
     * Calculate excess kurtosis
     *
     * @param array<int|float> $data Array of numeric values
     *
     * @return float The excess kurtosis value
     * @throws InvalidDataException|EmptyDataException|InsufficientDataException If the validation failed
     *
     */
    public function calculate(array $data): float
    {
        $this->validate($data);
        
        $n = count($data);
        $mean = (new Mean())->calculate($data);
        $stdDev = (new StandardDeviation())->calculate($data);
        
        if ($stdDev == 0) {
            return -3; // Excess kurtosis for a point distribution
        }
        
        $sum = 0;
        foreach ($data as $value) {
            $sum += pow(($value - $mean) / $stdDev, 4);
        }
        
        // Fisher's excess kurtosis (normal distribution has kurtosis = 0)
        $prefactor = (($n * ($n + 1)) / (($n - 1) * ($n - 2) * ($n - 3)));
        $term1 = $sum * $prefactor;
        $term2 = (3 * pow($n - 1, 2)) / (($n - 2) * ($n - 3));
        
        return $term1 - $term2;
    }
    
    /**
     * {@inheritdoc}
     * @throws InsufficientDataException
     */
    public function validate(array $data): bool
    {
        parent::validate($data);
        
        if (count($data) < 4) {
            throw new InsufficientDataException('Kurtosis calculation requires at least 4 data points');
        }
        
        return true;
    }
}
