<?php

declare(strict_types=1);

namespace PatelWorld\Statistics\Descriptive;

use PatelWorld\Statistics\AbstractStatisticalOperation;
use PatelWorld\Statistics\Contracts\UnivariateOperation;
use PatelWorld\Statistics\Exceptions\EmptyDataException;
use PatelWorld\Statistics\Exceptions\InsufficientDataException;
use PatelWorld\Statistics\Exceptions\InvalidDataException;

/**
 * Calculates the variance of a dataset
 */
class Variance extends AbstractStatisticalOperation implements UnivariateOperation
{
    private bool $sample;
    
    /**
     * Constructor
     *
     * @param bool $sample Whether to calculate sample variance (default: false)
     */
    public function __construct(bool $sample = false)
    {
        $this->sample = $sample;
    }
    
    /**
     * Calculate variance
     *
     * @param array<int|float> $data Array of numeric values
     *
     * @return float The variance value
     * @throws InvalidDataException|EmptyDataException|InsufficientDataException If the validation failed
     *
     */
    public function calculate(array $data): float
    {
        $this->validate($data);
        
        $mean = (new Mean())->calculate($data);
        $count = count($data);
        
        $sumSquaredDeviations = 0;
        foreach ($data as $value) {
            $sumSquaredDeviations += pow($value - $mean, 2);
        }
        
        // For sample variance, divide by n-1; for population variance, divide by n
        $divisor = $this->sample ? ($count - 1) : $count;
        
        return $sumSquaredDeviations / $divisor;
    }
    
    /**
     * {@inheritdoc}
     * @throws InsufficientDataException If there are too few data points for sample variance
     */
    public function validate(array $data): bool
    {
        parent::validate($data);
        
        // Additional validation for sample variance
        if ($this->sample && count($data) <= 1) {
            throw new InsufficientDataException('Sample variance requires at least two data points');
        }
        
        return true;
    }
}
