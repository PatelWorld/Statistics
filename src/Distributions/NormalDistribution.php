<?php

declare(strict_types=1);

namespace PatelWorld\Statistics\Distributions;

use PatelWorld\Statistics\AbstractStatisticalOperation;
use PatelWorld\Statistics\Exceptions\InvalidDataException;

/**
 * Normal (Gaussian) distribution functions
 */
class NormalDistribution extends AbstractStatisticalOperation
{
    private const ERROR_MSG_REQUIRED_POSITIVE_STDDEV = 'Standard deviation must be positive';
    /**
     * Calculate the probability density function (PDF) of the normal distribution
     *
     * @param float $x      Value to calculate PDF at
     * @param float $mean   Mean of the distribution
     * @param float $stdDev Standard deviation of the distribution
     *
     * @return float PDF value
     * @throws InvalidDataException If standard deviation is not positive
     */
    public function pdf(float $x, float $mean = 0.0, float $stdDev = 1.0): float
    {
        if ($stdDev <= 0) {
            throw new InvalidDataException(self::ERROR_MSG_REQUIRED_POSITIVE_STDDEV);
        }
        
        $variance = $stdDev * $stdDev;
        $exponent = -pow($x - $mean, 2) / (2 * $variance);
        
        return (1 / ($stdDev * sqrt(2 * M_PI))) * exp($exponent);
    }
    
    /**
     * Calculate the cumulative distribution function (CDF) of the normal distribution
     * using the error function approximation
     *
     * @param float $x      Value to calculate CDF at
     * @param float $mean   Mean of the distribution
     * @param float $stdDev Standard deviation of the distribution
     *
     * @return float CDF value (probability)
     * @throws InvalidDataException If standard deviation is not positive
     */
    public function cdf(float $x, float $mean = 0.0, float $stdDev = 1.0): float
    {
        if ($stdDev <= 0) {
            throw new InvalidDataException(self::ERROR_MSG_REQUIRED_POSITIVE_STDDEV);
        }
        
        $z = ($x - $mean) / ($stdDev * sqrt(2));
        
        // Error function approximation
        $t = 1.0 / (1.0 + 0.5 * abs($z));
        
        // Coefficients for approximation
        $coefficients = [
            0.254829592, -0.284496736, 1.421413741, -1.453152027, 1.061405429
        ];
        
        $polynomial = 0.0;
        foreach ($coefficients as $i => $coefficient) {
            $polynomial += $coefficient * pow($t, $i + 1);
        }
        
        $erf = 1.0 - $polynomial * exp(-$z * $z);
        
        // Handle negative values
        if ($z < 0) {
            $erf = -$erf;
        }
        
        // Convert from error function to CDF
        return 0.5 * (1.0 + $erf);
    }
    
    /**
     * Calculate the inverse cumulative distribution function (quantile function)
     * using the Beasley-Springer-Moro algorithm
     *
     * @param float $p      Probability (0 < p < 1)
     * @param float $mean   Mean of the distribution
     * @param float $stdDev Standard deviation of the distribution
     *
     * @return float Quantile value
     * @throws InvalidDataException If probability is not between 0 and 1 or standard deviation is not positive
     */
    public function quantile(float $p, float $mean = 0.0, float $stdDev = 1.0): float
    {
        if ($p <= 0 || $p >= 1) {
            throw new InvalidDataException('Probability must be between 0 and 1 exclusive');
        }
        
        if ($stdDev <= 0) {
            throw new InvalidDataException(self::ERROR_MSG_REQUIRED_POSITIVE_STDDEV);
        }
        
        // Handle special cases
        if ($p == 0.5) {
            return $mean;
        }
        
        // Coefficients for the Beasley-Springer-Moro algorithm
        $a = [
            -3.969683028665376e+01, 2.209460984245205e+02,
            -2.759285104469687e+02, 1.383577518672690e+02,
            -3.066479806614716e+01, 2.506628277459239e+00
        ];
        
        $b = [
            -5.447609879822406e+01, 1.615858368580409e+02,
            -1.556989798598866e+02, 6.680131188771972e+01,
            -1.328068155288572e+01
        ];
        
        $c = [
            -7.784894002430293e-03, -3.223964580411365e-01,
            -2.400758277161838e+00, -2.549732539343734e+00,
            4.374664141464968e+00, 2.938163982698783e+00
        ];
        
        $d = [
            7.784695709041462e-03, 3.224671290700398e-01,
            2.445134137142996e+00, 3.754408661907416e+00
        ];
        
        // Convert to standard normal
        $q = $p - 0.5;
        
        if (abs($q) <= 0.425) {
            // Central region
            $r = 0.180625 - $q * $q;
            
            $z = $q * $this->polynomialEval($r, $a) / $this->polynomialEval($r, $b);
        } else {
            // Tail regions
            $r = $q > 0 ? 1 - $p : $p;
            
            $r = sqrt(-log($r));
            
            $z = $this->polynomialEval($r, $c) / $this->polynomialEval($r, $d);
            
            if ($q < 0) {
                $z = -$z;
            }
        }
        
        // Convert from standard normal to desired normal
        return $mean + $stdDev * $z;
    }
    
    /**
     * Evaluate a polynomial with the given coefficients at x
     *
     * @param float            $x            Value to evaluate at
     * @param array<int|float> $coefficients Polynomial coefficients
     *
     * @return float Result
     */
    private function polynomialEval(float $x, array $coefficients): float
    {
        $result = 0.0;
        foreach ($coefficients as $i => $coefficient) {
            $result += $coefficient * pow($x, $i);
        }
        return $result;
    }
}
