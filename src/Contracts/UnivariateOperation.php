<?php

declare(strict_types=1);

namespace PatelWorld\Statistics\Contracts;

use PatelWorld\Statistics\Exceptions\EmptyDataException;
use PatelWorld\Statistics\Exceptions\InvalidDataException;

/**
 * Interface for univariate statistical operations (operating on a single dataset)
 */
interface UnivariateOperation extends StatisticalOperation
{
    /**
     * Calculate the statistical measure
     *
     * @param array<int|float> $data Input data for calculation
     *
     * @return float|array The calculated statistic
     * @throws EmptyDataException If data array is empty
     * @throws InvalidDataException If data contains non-numeric values
     */
    public function calculate(array $data);
}
