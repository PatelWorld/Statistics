<?php

declare(strict_types=1);

namespace PatelWorld\Statistics\Correlation;

use PatelWorld\Statistics\AbstractStatisticalOperation;
use PatelWorld\Statistics\Descriptive\Mean;
use PatelWorld\Statistics\Exceptions\EmptyDataException;
use PatelWorld\Statistics\Exceptions\InsufficientDataException;
use PatelWorld\Statistics\Exceptions\InvalidDataException;

/**
 * Calculates the covariance between two datasets
 */
class Covariance extends AbstractStatisticalOperation
{
    private bool $sample;
    
    /**
     * Constructor
     *
     * @param bool $sample Whether to calculate sample covariance (default: false)
     */
    public function __construct(bool $sample = false)
    {
        $this->sample = $sample;
    }
    
    /**
     * Calculate covariance between two datasets
     *
     * @param array<int|float> $x First dataset
     * @param array<int|float> $y Second dataset
     *
     * @return float The covariance value
     */
    public function calculate(array $x, array $y): float
    {
        $this->validatePaired($x, $y);
        
        $n = count($x);
        $meanX = (new Mean())->calculate($x);
        $meanY = (new Mean())->calculate($y);
        
        $sum = 0;
        for ($i = 0; $i < $n; $i++) {
            $sum += ($x[$i] - $meanX) * ($y[$i] - $meanY);
        }
        
        $divisor = $this->sample ? ($n - 1) : $n;
        
        return $sum / $divisor;
    }
    
    /**
     * Validate that both datasets have the same length and contain only numeric values
     *
     * @param array<int|float> $x First dataset
     * @param array<int|float> $y Second dataset
     *
     * @return bool True if data is valid
     * @throws InvalidDataException If datasets have different lengths
     * @throws InsufficientDataException If sample is true and there's only one data point
     * @throws EmptyDataException
     */
    public function validatePaired(array $x, array $y): bool
    {
        parent::validate($x);
        parent::validate($y);
        
        if (count($x) !== count($y)) {
            throw new InvalidDataException('Datasets must have the same length for covariance calculation');
        }
        
        if ($this->sample && count($x) <= 1) {
            throw new InsufficientDataException('Sample covariance requires at least two data points');
        }
        
        return true;
    }
}
