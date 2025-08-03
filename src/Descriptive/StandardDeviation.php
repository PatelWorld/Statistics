<?php

declare(strict_types=1);

namespace PatelWorld\Statistics\Descriptive;

use InvalidArgumentException;
use PatelWorld\Statistics\AbstractStatisticalOperation;
use PatelWorld\Statistics\Contracts\UnivariateOperation;

/**
 * Calculates the standard deviation of a dataset
 */
class StandardDeviation extends AbstractStatisticalOperation implements UnivariateOperation
{
    private bool $sample;
    
    /**
     * Constructor
     *
     * @param bool $sample Whether to calculate sample standard deviation (default: false)
     */
    public function __construct(bool $sample = false)
    {
        $this->sample = $sample;
    }
    
    /**
     * Calculate standard deviation
     *
     * @param array<int|float> $data Array of numeric values
     *
     * @return float The standard deviation value
     * @throws InvalidArgumentException If the array is empty or has only one element for sample standard deviation
     */
    public function calculate(array $data): float
    {
        $variance = (new Variance($this->sample))->calculate($data);
        return sqrt($variance);
    }
}
