<?php

declare(strict_types=1);

namespace PatelWorld\Statistics\Regression;

use PatelWorld\Statistics\AbstractStatisticalOperation;
use PatelWorld\Statistics\Correlation\Covariance;
use PatelWorld\Statistics\Descriptive\Mean;
use PatelWorld\Statistics\Descriptive\Variance;

/**
 * Performs simple linear regression analysis
 */
class SimpleLinearRegression extends AbstractStatisticalOperation
{
    /**
     * Calculate regression coefficients and related statistics
     *
     * @param array<int|float> $x Independent variable values
     * @param array<int|float> $y Dependent variable values
     *
     * @return array<string, float|array> Regression results including slope, intercept and predictions
     */
    public function calculate(array $x, array $y): array
    {
        $covariance = (new Covariance(true))->calculate($x, $y);
        $varianceX = (new Variance(true))->calculate($x);
        $meanX = (new Mean())->calculate($x);
        $meanY = (new Mean())->calculate($y);
        
        // Calculate slope and intercept
        $slope = $covariance / $varianceX;
        $intercept = $meanY - $slope * $meanX;
        
        // Calculate predicted values
        $predictions = [];
        foreach ($x as $xValue) {
            $predictions[] = $intercept + $slope * $xValue;
        }
        
        // Calculate residuals
        $residuals = [];
        for ($i = 0; $i < count($y); $i++) {
            $residuals[] = $y[$i] - $predictions[$i];
        }
        
        return [
            'slope'       => $slope,
            'intercept'   => $intercept,
            'predictions' => $predictions,
            'residuals'   => $residuals
        ];
    }
}
