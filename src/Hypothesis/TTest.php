<?php

declare(strict_types=1);

namespace PatelWorld\Statistics\Hypothesis;

use PatelWorld\Statistics\AbstractStatisticalOperation;
use PatelWorld\Statistics\Descriptive\Mean;
use PatelWorld\Statistics\Descriptive\StandardDeviation;
use PatelWorld\Statistics\Distributions\NormalDistribution;
use PatelWorld\Statistics\Exceptions\EmptyDataException;
use PatelWorld\Statistics\Exceptions\InsufficientDataException;
use PatelWorld\Statistics\Exceptions\InvalidDataException;

/**
 * Performs Student's t-test for hypothesis testing
 */
class TTest extends AbstractStatisticalOperation
{
    // T-test types
    public const ONE_SAMPLE = 1;
    public const TWO_SAMPLE_INDEPENDENT = 2;
    public const TWO_SAMPLE_PAIRED = 3;
    
    /**
     * Perform a two-sample independent t-test
     *
     * @param array<int|float> $data1         First sample data
     * @param array<int|float> $data2         Second sample data
     * @param bool             $equalVariance Whether to assume equal variances (default: false)
     *
     * @return array<string, float> Test results including t-statistic, p-value, and degrees of freedom
     * @throws InsufficientDataException If there's not enough data
     * @throws InvalidDataException|EmptyDataException
     */
    public function twoSampleIndependent(array $data1, array $data2, bool $equalVariance = false): array
    {
        $this->validateTTest(self::TWO_SAMPLE_INDEPENDENT, $data1, $data2);
        
        $n1 = count($data1);
        $n2 = count($data2);
        
        if ($n1 < 2 || $n2 < 2) {
            throw new InsufficientDataException('Two-sample t-test requires at least 2 data points per sample');
        }
        
        $mean1 = (new Mean())->calculate($data1);
        $mean2 = (new Mean())->calculate($data2);
        $var1 = (new StandardDeviation(true))->calculate($data1) ** 2;
        $var2 = (new StandardDeviation(true))->calculate($data2) ** 2;
        
        if ($equalVariance) {
            // Pooled variance
            $sp2 = ((($n1 - 1) * $var1) + (($n2 - 1) * $var2)) / ($n1 + $n2 - 2);
            $se = sqrt($sp2 * (1 / $n1 + 1 / $n2));
            $df = $n1 + $n2 - 2;
        } else {
            // Welch-Satterthwaite equation
            $se = sqrt(($var1 / $n1) + ($var2 / $n2));
            $df = (($var1 / $n1 + $var2 / $n2) ** 2) /
                ((($var1 / $n1) ** 2) / ($n1 - 1) + (($var2 / $n2) ** 2) / ($n2 - 1));
        }
        
        // Calculate t-statistic
        $t = ($mean1 - $mean2) / $se;
        
        // Calculate two-sided p-value using normal approximation
        $normalDist = new NormalDistribution();
        $p = 2 * (1 - $normalDist->cdf(abs($t)));
        
        return [
            't_statistic'        => $t,
            'p_value'            => $p,
            'degrees_of_freedom' => $df
        ];
    }
    
    /**
     * Validate specific requirements for different t-test types
     *
     * @param int                   $type  Test type (ONE_SAMPLE, TWO_SAMPLE_INDEPENDENT, or TWO_SAMPLE_PAIRED)
     * @param array<int|float>      $data1 First dataset
     * @param array<int|float>|null $data2 Second dataset (for two-sample tests)
     *
     * @return bool True if data is valid
     * @throws InvalidDataException If test type is invalid or datasets don't meet requirements
     * @throws EmptyDataException If there's not enough data
     */
    private function validateTTest(int $type, array $data1, ?array $data2 = null): bool
    {
        parent::validate($data1);
        
        if ($type !== self::ONE_SAMPLE && $type !== self::TWO_SAMPLE_INDEPENDENT && $type !== self::TWO_SAMPLE_PAIRED) {
            throw new InvalidDataException('Invalid t-test type');
        }
        
        if ($type !== self::ONE_SAMPLE) {
            if ($data2 === null) {
                throw new InvalidDataException('Second dataset required for two-sample t-test');
            }
            
            parent::validate($data2);
            
            if ($type === self::TWO_SAMPLE_PAIRED && count($data1) !== count($data2)) {
                throw new InvalidDataException('Paired t-test requires equal-sized datasets');
            }
        }
        
        return true;
    }
    
    /**
     * Perform a paired t-test
     *
     * @param array<int|float> $data1 First sample data
     * @param array<int|float> $data2 Second sample data
     *
     * @return array<string, float> Test results including t-statistic, p-value, and degrees of freedom
     * @throws InvalidDataException If datasets have different lengths
     * @throws InsufficientDataException|EmptyDataException
     */
    public function pairedTest(array $data1, array $data2): array
    {
        $this->validateTTest(self::TWO_SAMPLE_PAIRED, $data1, $data2);
        
        // Calculate differences
        $differences = [];
        for ($i = 0; $i < count($data1); $i++) {
            $differences[] = $data1[$i] - $data2[$i];
        }
        
        // Perform one-sample t-test on the differences
        return $this->oneSample($differences);
    }
    
    /**
     * Perform a one-sample t-test
     *
     * @param array<int|float> $data Sample data
     * @param float            $mu   Population mean to test against
     *
     * @return array<string, float> Test results including t-statistic, p-value, and degrees of freedom
     * @throws InsufficientDataException If there's not enough data
     * @throws InvalidDataException
     * @throws EmptyDataException
     */
    public function oneSample(array $data, float $mu = 0.0): array
    {
        $this->validateTTest(self::ONE_SAMPLE, $data);
        
        $n = count($data);
        if ($n < 2) {
            throw new InsufficientDataException('One-sample t-test requires at least 2 data points');
        }
        
        $mean = (new Mean())->calculate($data);
        $stdDev = (new StandardDeviation(true))->calculate($data);
        
        // Calculate t-statistic
        $se = $stdDev / sqrt($n);
        $t = ($mean - $mu) / $se;
        
        // Degrees of freedom
        $df = $n - 1;
        
        // Calculate two-sided p-value using normal approximation for large df
        $normalDist = new NormalDistribution();
        $p = 2 * (1 - $normalDist->cdf(abs($t)));
        
        return [
            't_statistic'        => $t,
            'p_value'            => $p,
            'degrees_of_freedom' => $df
        ];
    }
}
