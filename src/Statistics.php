<?php

declare(strict_types=1);

namespace PatelWorld\Statistics;

use PatelWorld\Statistics\Exceptions\EmptyDataException;
use PatelWorld\Statistics\Exceptions\InsufficientDataException;
use PatelWorld\Statistics\Exceptions\InvalidDataException;

/**
 * Statistics - Main entry point for the statistics library (Facade pattern)
 *
 * This class provides a unified interface to access all statistical operations
 * while following SOLID principles internally.
 */
class Statistics
{
    //------------------------------------------------------------------------
    // Descriptive Statistics
    //------------------------------------------------------------------------
    
    /**
     * Calculate the median of a dataset
     *
     * @param array<int|float> $data Array of numeric values
     *
     * @return float The median value
     * @throws EmptyDataException If the array is empty
     * @throws InvalidDataException If the array contains non-numeric values
     */
    public static function median(array $data): float
    {
        return (new Descriptive\Median())->calculate($data);
    }
    
    /**
     * Calculate the mode (most frequent value) of a dataset
     *
     * @param array<int|float> $data Array of numeric values
     *
     * @return array<int|float> The mode value(s)
     * @throws EmptyDataException If the array is empty
     * @throws InvalidDataException If the array contains non-numeric values
     */
    public static function mode(array $data): array
    {
        return (new Descriptive\Mode())->calculate($data);
    }
    
    /**
     * Calculate the variance of a dataset
     *
     * @param array<int|float> $data   Array of numeric values
     * @param bool             $sample Whether to calculate sample variance (default: false)
     *
     * @return float The variance value
     * @throws EmptyDataException If the array is empty
     * @throws InvalidDataException If the array contains non-numeric values
     * @throws InsufficientDataException If sample is true and there's only one data point
     */
    public static function variance(array $data, bool $sample = false): float
    {
        return (new Descriptive\Variance($sample))->calculate($data);
    }
    
    /**
     * Calculate the range of a dataset
     *
     * @param array<int|float> $data Array of numeric values
     *
     * @return float The range value
     * @throws EmptyDataException If the array is empty
     * @throws InvalidDataException If the array contains non-numeric values
     */
    public static function range(array $data): float
    {
        return (new Descriptive\Range())->calculate($data);
    }
    
    /**
     * Calculate quartiles of a dataset
     *
     * @param array<int|float> $data Array of numeric values
     *
     * @return array<string, float> Associative array with Q1, Q2 (median), Q3, and IQR
     * @throws EmptyDataException If the array is empty
     * @throws InvalidDataException If the array contains non-numeric values
     */
    public static function quartiles(array $data): array
    {
        return (new Descriptive\Quartiles())->calculate($data);
    }
    
    /**
     * Calculate a specific percentile of a dataset
     *
     * @param array<int|float> $data       Array of numeric values
     * @param float            $percentile Percentile to calculate (0-100)
     *
     * @return float The percentile value
     * @throws EmptyDataException If the array is empty
     * @throws InvalidDataException If the array contains non-numeric values or percentile is invalid
     */
    public static function percentile(array $data, float $percentile): float
    {
        return (new Descriptive\Percentile())->calculatePercentile($data, $percentile);
    }
    
    /**
     * Calculate skewness of a dataset
     *
     * @param array<int|float> $data Array of numeric values
     *
     * @return float The skewness value
     * @throws EmptyDataException If the array is empty
     * @throws InvalidDataException If the array contains non-numeric values
     * @throws InsufficientDataException If there are fewer than 3 data points
     */
    public static function skewness(array $data): float
    {
        return (new Descriptive\Skewness())->calculate($data);
    }
    
    /**
     * Calculate kurtosis of a dataset
     *
     * @param array<int|float> $data Array of numeric values
     *
     * @return float The excess kurtosis value
     * @throws EmptyDataException If the array is empty
     * @throws InvalidDataException If the array contains non-numeric values
     * @throws InsufficientDataException If there are fewer than 4 data points
     */
    public static function kurtosis(array $data): float
    {
        return (new Descriptive\Kurtosis())->calculate($data);
    }
    
    /**
     * Calculate covariance between two datasets
     *
     * @param array<int|float> $x      First dataset
     * @param array<int|float> $y      Second dataset
     * @param bool             $sample Whether to calculate sample covariance (default: false)
     *
     * @return float The covariance value
     * @throws EmptyDataException If either array is empty
     * @throws InvalidDataException If either array contains non-numeric values or they have different lengths
     * @throws InsufficientDataException If sample is true and there's only one data point
     */
    public static function covariance(array $x, array $y, bool $sample = false): float
    {
        return (new Correlation\Covariance($sample))->calculate($x, $y);
    }
    
    /**
     * Calculate Pearson correlation coefficient between two datasets
     *
     * @param array<int|float> $x First dataset
     * @param array<int|float> $y Second dataset
     *
     * @return float The correlation coefficient (-1 to 1)
     * @throws EmptyDataException If either array is empty
     * @throws InvalidDataException If either array contains non-numeric values or they have different lengths
     * @throws InsufficientDataException If there's only one data point
     */
    public static function correlation(array $x, array $y): float
    {
        return (new Correlation\Correlation())->calculate($x, $y);
    }
    
    //------------------------------------------------------------------------
    // Correlation and Regression
    //------------------------------------------------------------------------
    
    /**
     * Perform simple linear regression analysis
     *
     * @param array<int|float> $x Independent variable values
     * @param array<int|float> $y Dependent variable values
     *
     * @return array<string, float|array> Regression results including slope, intercept and predictions
     * @throws EmptyDataException If either array is empty
     * @throws InvalidDataException If either array contains non-numeric values or they have different lengths
     * @throws InsufficientDataException If there are too few data points
     */
    public static function linearRegression(array $x, array $y): array
    {
        return (new Regression\SimpleLinearRegression())->calculate($x, $y);
    }
    
    /**
     * Calculate normal distribution probability density function (PDF)
     *
     * @param float $x      Value to calculate PDF at
     * @param float $mean   Mean of the distribution
     * @param float $stdDev Standard deviation of the distribution
     *
     * @return float PDF value
     * @throws InvalidDataException If standard deviation is not positive
     */
    public static function normalPdf(float $x, float $mean = 0.0, float $stdDev = 1.0): float
    {
        return (new Distributions\NormalDistribution())->pdf($x, $mean, $stdDev);
    }
    
    /**
     * Calculate normal distribution cumulative distribution function (CDF)
     *
     * @param float $x      Value to calculate CDF at
     * @param float $mean   Mean of the distribution
     * @param float $stdDev Standard deviation of the distribution
     *
     * @return float CDF value (probability)
     * @throws InvalidDataException If standard deviation is not positive
     */
    public static function normalCdf(float $x, float $mean = 0.0, float $stdDev = 1.0): float
    {
        return (new Distributions\NormalDistribution())->cdf($x, $mean, $stdDev);
    }
    
    //------------------------------------------------------------------------
    // Probability Distributions
    //------------------------------------------------------------------------
    
    /**
     * Calculate normal distribution quantile (inverse CDF)
     *
     * @param float $p      Probability (0 < p < 1)
     * @param float $mean   Mean of the distribution
     * @param float $stdDev Standard deviation of the distribution
     *
     * @return float Quantile value
     * @throws InvalidDataException If probability is not between 0 and 1 or standard deviation is not positive
     */
    public static function normalQuantile(float $p, float $mean = 0.0, float $stdDev = 1.0): float
    {
        return (new Distributions\NormalDistribution())->quantile($p, $mean, $stdDev);
    }
    
    /**
     * Perform one-sample t-test
     *
     * @param array<int|float> $data Sample data
     * @param float            $mu   Population mean to test against
     *
     * @return array<string, float> Test results including t-statistic, p-value, and degrees of freedom
     * @throws EmptyDataException If the array is empty
     * @throws InvalidDataException If the array contains non-numeric values
     * @throws InsufficientDataException If there are fewer than 2 data points
     */
    public static function tTestOneSample(array $data, float $mu = 0.0): array
    {
        return (new Hypothesis\TTest())->oneSample($data, $mu);
    }
    
    /**
     * Perform two-sample independent t-test
     *
     * @param array<int|float> $data1         First sample data
     * @param array<int|float> $data2         Second sample data
     * @param bool             $equalVariance Whether to assume equal variances (default: false)
     *
     * @return array<string, float> Test results including t-statistic, p-value, and degrees of freedom
     * @throws EmptyDataException If either array is empty
     * @throws InvalidDataException If either array contains non-numeric values
     * @throws InsufficientDataException If there are fewer than 2 data points in either sample
     */
    public static function tTestTwoSampleIndependent(array $data1, array $data2, bool $equalVariance = false): array
    {
        return (new Hypothesis\TTest())->twoSampleIndependent($data1, $data2, $equalVariance);
    }
    
    //------------------------------------------------------------------------
    // Hypothesis Testing
    //------------------------------------------------------------------------
    
    /**
     * Perform paired t-test
     *
     * @param array<int|float> $data1 First sample data
     * @param array<int|float> $data2 Second sample data
     *
     * @return array<string, float> Test results including t-statistic, p-value, and degrees of freedom
     * @throws EmptyDataException If either array is empty
     * @throws InvalidDataException If either array contains non-numeric values or they have different lengths
     * @throws InsufficientDataException If there are fewer than 2 data points
     */
    public static function tTestPaired(array $data1, array $data2): array
    {
        return (new Hypothesis\TTest())->pairedTest($data1, $data2);
    }
    
    /**
     * Calculate z-scores (standardize) a dataset
     *
     * @param array<int|float> $data   Input data
     * @param bool             $sample Whether to use sample standard deviation (default: true)
     *
     * @return array<float> Z-scores for each value
     * @throws EmptyDataException If the array is empty
     * @throws InvalidDataException If the array contains non-numeric values
     */
    public static function zScores(array $data, bool $sample = true): array
    {
        $mean = self::mean($data);
        $stdDev = self::standardDeviation($data, $sample);
        
        if ($stdDev == 0) {
            throw new InvalidDataException("Cannot compute z-scores: standard deviation is zero");
        }
        
        $zScores = [];
        foreach ($data as $value) {
            $zScores[] = ($value - $mean) / $stdDev;
        }
        
        return $zScores;
    }
    
    /**
     * Calculate the mean (average) of a dataset
     *
     * @param array<int|float> $data Array of numeric values
     *
     * @return float The mean value
     * @throws EmptyDataException If the array is empty
     * @throws InvalidDataException If the array contains non-numeric values
     */
    public static function mean(array $data): float
    {
        return (new Descriptive\Mean())->calculate($data);
    }
    
    //------------------------------------------------------------------------
    // Data Transformation Utilities
    //------------------------------------------------------------------------
    
    /**
     * Calculate the standard deviation of a dataset
     *
     * @param array<int|float> $data   Array of numeric values
     * @param bool             $sample Whether to calculate sample standard deviation (default: false)
     *
     * @return float The standard deviation value
     * @throws EmptyDataException If the array is empty
     * @throws InvalidDataException If the array contains non-numeric values
     * @throws InsufficientDataException If sample is true and there's only one data point
     */
    public static function standardDeviation(array $data, bool $sample = false): float
    {
        return (new Descriptive\StandardDeviation($sample))->calculate($data);
    }
    
    /**
     * Min-max normalization of a dataset to [0, 1] or custom range
     *
     * @param array<int|float> $data Input data
     * @param float            $min  Minimum of target range (default: 0)
     * @param float            $max  Maximum of target range (default: 1)
     *
     * @return array<float> Normalized values
     * @throws EmptyDataException If the array is empty
     * @throws InvalidDataException If the array contains non-numeric values or all values are the same
     */
    public static function minMaxNormalize(array $data, float $min = 0.0, float $max = 1.0): array
    {
        if (empty($data)) {
            throw new EmptyDataException();
        }
        
        $dataMin = min($data);
        $dataMax = max($data);
        
        if ($dataMax == $dataMin) {
            throw new InvalidDataException("Cannot normalize: all values in the dataset are the same");
        }
        
        $normalized = [];
        foreach ($data as $value) {
            $normalized[] = (($value - $dataMin) / ($dataMax - $dataMin)) * ($max - $min) + $min;
        }
        
        return $normalized;
    }
    
    /**
     * Rank the values in a dataset
     *
     * @param array<int|float> $data      Input data
     * @param bool             $ascending Whether to rank in ascending order (default: true)
     *
     * @return array<float> Ranks for each value
     * @throws EmptyDataException If the array is empty
     * @throws InvalidDataException If the array contains non-numeric values
     */
    public static function rank(array $data, bool $ascending = true): array
    {
        if (empty($data)) {
            throw new EmptyDataException();
        }
        
        // Create value-index pairs
        $pairs = [];
        foreach ($data as $index => $value) {
            if (!is_numeric($value)) {
                throw new InvalidDataException();
            }
            $pairs[] = ['value' => $value, 'index' => $index];
        }
        
        // Sort by value
        usort($pairs, function ($a, $b) use ($ascending) {
            if ($a['value'] == $b['value']) {
                return 0;
            }
            $comparison = $a['value'] < $b['value'] ? -1 : 1;
            return $ascending ? $comparison : -$comparison;
        });
        
        // Assign ranks (handle ties with average rank)
        $ranks = array_fill(0, count($data), 0);
        $i = 0;
        while ($i < count($pairs)) {
            $j = $i;
            // Find all equal values
            while ($j < count($pairs) - 1 && $pairs[$j]['value'] == $pairs[$j + 1]['value']) {
                $j++;
            }
            
            // Calculate average rank for ties
            $rank = $i + 1;
            if ($j > $i) {
                $rank = ($i + $j + 2) / 2; // Average rank for ties
            }
            
            // Assign the rank
            for ($k = $i; $k <= $j; $k++) {
                $ranks[$pairs[$k]['index']] = $rank;
            }
            
            $i = $j + 1;
        }
        
        return $ranks;
    }
}
