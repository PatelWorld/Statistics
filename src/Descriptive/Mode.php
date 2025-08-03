<?php

declare(strict_types=1);

namespace PatelWorld\Statistics\Descriptive;

use InvalidArgumentException;
use PatelWorld\Statistics\AbstractStatisticalOperation;
use PatelWorld\Statistics\Contracts\UnivariateOperation;
use PatelWorld\Statistics\Exceptions\EmptyDataException;
use PatelWorld\Statistics\Exceptions\InvalidDataException;

/**
 * Calculates the mode (most frequent value) of a dataset
 */
class Mode extends AbstractStatisticalOperation implements UnivariateOperation
{
    /**
     * Calculate the mode
     *
     * @param array<int|float> $data Array of numeric values
     *
     * @return array<int|float> The mode value(s) (can be multiple if there are ties)
     * @throws InvalidArgumentException If the array is empty
     * @throws InvalidDataException|EmptyDataException If the validation failed
     *
     */
    public function calculate(array $data): array
    {
        $this->validate($data);
        
        // Count frequency of each value
        $frequencies = array_count_values(array_map('strval', $data));
        
        // Find the maximum frequency
        $maxFrequency = max($frequencies);
        
        // Find all values with the maximum frequency
        $modes = [];
        foreach ($frequencies as $value => $frequency) {
            if ($frequency === $maxFrequency) {
                // Convert back to the original type (float or int)
                $modes[] = is_int($data[array_search($value, array_map('strval', $data))])
                    ? (int)$value
                    : (float)$value;
            }
        }
        
        return $modes;
    }
}
