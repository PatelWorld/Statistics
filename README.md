# PatelWorld Statistics

A comprehensive PHP statistics library implementing SOLID principles with strict typing.

## Features

- Written in PHP 7.4+ with strict typing
- Follows SOLID design principles
- Single point of execution through the `Statistics` facade class
- Comprehensive error handling with custom exceptions
- Easily extensible architecture

## Installation

```bash
composer require patelworld/statistics
```

## Usage

You can use this library in two ways:

1. Through the **Statistics facade** (recommended for most uses)
2. By creating instances of individual operation classes directly (for more control)

### Using the Statistics Facade

The Statistics class provides a single entry point for all statistical operations:

```php
use PatelWorld\Statistics\Statistics;
use PatelWorld\Statistics\Exceptions\StatisticsException;

try {
    $data = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
    
    // Basic descriptive statistics
    $mean = Statistics::mean($data);  // 5.5
    $median = Statistics::median($data);  // 5.5
    
    // More complex operations
    $correlationResult = Statistics::correlation($data, [2, 4, 6, 8, 10, 12, 14, 16, 18, 20]);
    $regressionResult = Statistics::linearRegression($data, [5, 7, 9, 11, 13, 15, 17, 19, 21, 23]);
} catch (StatisticsException $e) {
    echo "Statistics Error: " . $e->getMessage();
}
```

### Using Individual Operation Classes

For more control or when extending functionality:

```php
use PatelWorld\Statistics\Descriptive\Mean;
use PatelWorld\Statistics\Descriptive\StandardDeviation;
use PatelWorld\Statistics\Correlation\Correlation;
use PatelWorld\Statistics\Exceptions\StatisticsException;

try {
    $data = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
    
    // Create operation instances
    $meanCalculator = new Mean();
    $stdDevCalculator = new StandardDeviation(true); // Sample standard deviation
    $correlationCalculator = new Correlation();
    
    // Perform calculations
    $meanValue = $meanCalculator->calculate($data);
    $stdDevValue = $stdDevCalculator->calculate($data);
    $correlationValue = $correlationCalculator->calculate($data, [2, 4, 6, 8, 10, 12, 14, 16, 18, 20]);
} catch (StatisticsException $e) {
    echo "Statistics Error: " . $e->getMessage();
}
```

## Complete Reference

### Descriptive Statistics

#### Basic Measures

```php
// Mean (average)
$mean = Statistics::mean([1, 2, 3, 4, 5]);  // 3

// Median (middle value)
$median = Statistics::median([1, 2, 3, 4, 5]);  // 3
$median = Statistics::median([1, 2, 3, 4]);  // 2.5 (average of middle values)

// Mode (most frequent value)
$mode = Statistics::mode([1, 2, 2, 3, 4, 5]);  // [2]
$mode = Statistics::mode([1, 1, 2, 2, 3]);  // [1, 2] (multiple modes)

// Range (max - min)
$range = Statistics::range([1, 2, 3, 4, 5]);  // 4

// Variance
$popVariance = Statistics::variance([1, 2, 3, 4, 5]);  // Population variance
$sampleVariance = Statistics::variance([1, 2, 3, 4, 5], true);  // Sample variance

// Standard deviation
$popStdDev = Statistics::standardDeviation([1, 2, 3, 4, 5]);  // Population std dev
$sampleStdDev = Statistics::standardDeviation([1, 2, 3, 4, 5], true);  // Sample std dev
```

#### Quantiles and Distribution Shape

```php
// Quartiles (25%, 50%, 75%)
$quartiles = Statistics::quartiles([1, 2, 3, 4, 5, 6, 7, 8]);
// Returns: ['Q1' => 2.5, 'Q2' => 4.5, 'Q3' => 6.5, 'IQR' => 4]

// Arbitrary percentile
$p90 = Statistics::percentile([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], 90);  // 9.1

// Skewness (asymmetry measure)
$skewness = Statistics::skewness([1, 2, 3, 4, 10]);  // Positive (right-skewed)

// Kurtosis (tailedness measure)
$kurtosis = Statistics::kurtosis([1, 2, 3, 4, 5, 6, 7]);  // Excess kurtosis
```

### Correlation and Regression

```php
$x = [1, 2, 3, 4, 5];
$y = [2, 4, 5, 4, 5];

// Covariance
$popCovariance = Statistics::covariance($x, $y);  // Population covariance
$sampleCovariance = Statistics::covariance($x, $y, true);  // Sample covariance

// Pearson correlation coefficient
$correlation = Statistics::correlation($x, $y);  // Value between -1 and 1

// Simple linear regression (y = a + bx)
$regression = Statistics::linearRegression($x, $y);
// Returns: [
//   'slope' => 0.6,         // b in y = a + bx
//   'intercept' => 2.2,     // a in y = a + bx
//   'predictions' => [...], // Predicted y values
//   'residuals' => [...]    // Residual (error) values
// ]

// Get predicted value for new x
$newX = 6;
$predictedY = $regression['intercept'] + $regression['slope'] * $newX;
```

### Probability Distributions

```php
// Normal distribution probability density function (PDF)
$pdf = Statistics::normalPdf(0);  // PDF at x=0 for standard normal
$pdf = Statistics::normalPdf(1.5, 2, 0.5);  // PDF at x=1.5 for N(2, 0.5²)

// Normal distribution cumulative distribution function (CDF)
$cdf = Statistics::normalCdf(1.96);  // P(X ≤ 1.96) ≈ 0.975 for standard normal
$cdf = Statistics::normalCdf(85, 72, 15.2);  // P(X ≤ 85) for N(72, 15.2²)

// Normal distribution quantile function (inverse CDF)
$z = Statistics::normalQuantile(0.975);  // ≈ 1.96 (z-score for 97.5th percentile)
$score = Statistics::normalQuantile(0.9, 500, 100);  // 90th percentile for N(500, 100²)
```

### Hypothesis Testing

```php
// One-sample t-test
$result = Statistics::tTestOneSample([5, 7, 9, 11, 13], 8);
// Returns: [
//   't_statistic' => 1.118,          // t-statistic
//   'p_value' => 0.326,              // p-value (two-tailed)
//   'degrees_of_freedom' => 4        // df
// ]

// Two-sample independent t-test
$group1 = [5, 7, 9, 11, 13];
$group2 = [6, 8, 10, 12, 14];
$equalVariances = false;  // Welch's t-test (doesn't assume equal variances)
$result = Statistics::tTestTwoSampleIndependent($group1, $group2, $equalVariances);

// Paired t-test
$before = [210, 205, 193, 182, 259, 231, 200];
$after =  [190, 180, 185, 170, 240, 210, 195];
$result = Statistics::tTestPaired($before, $after);
```

### Data Transformation

```php
$data = [10, 20, 30, 40, 50];

// Z-score standardization (mean=0, sd=1)
$zScores = Statistics::zScores($data);  // [-1.26, -0.63, 0, 0.63, 1.26]

// Min-max normalization
$normalized = Statistics::minMaxNormalize($data);  // [0, 0.25, 0.5, 0.75, 1]
$normalized = Statistics::minMaxNormalize($data, -1, 1);  // [-1, -0.5, 0, 0.5, 1]

// Ranking
$ranks = Statistics::rank($data);  // [1, 2, 3, 4, 5]
$ranks = Statistics::rank($data, false);  // [5, 4, 3, 2, 1] (descending)
```

## Direct Class Usage Examples

For advanced usage, you can create instances of the operation classes directly:

### Descriptive Statistics

```php
use PatelWorld\Statistics\Descriptive\Mean;
use PatelWorld\Statistics\Descriptive\Median;
use PatelWorld\Statistics\Descriptive\Quartiles;

$data = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

$meanCalculator = new Mean();
$mean = $meanCalculator->calculate($data);  // 5.5

$medianCalculator = new Median();
$median = $medianCalculator->calculate($data);  // 5.5

$quartilesCalculator = new Quartiles();
$quartiles = $quartilesCalculator->calculate($data);  // Q1, Q2, Q3, IQR
```

### Hypothesis Testing

```php
use PatelWorld\Statistics\Hypothesis\TTest;

$tTest = new TTest();

// One-sample t-test
$result = $tTest->oneSample([5, 7, 9, 11, 13], 8);

// Two-sample t-test
$result = $tTest->twoSampleIndependent([5, 7, 9, 11, 13], [6, 8, 10, 12, 14], false);

// Paired t-test
$result = $tTest->pairedTest([210, 205, 193, 182, 259], [190, 180, 185, 170, 240]);
```

### Probability Distributions

```php
use PatelWorld\Statistics\Distributions\NormalDistribution;

$normal = new NormalDistribution();

// PDF at x=1.5 for N(2, 0.5²)
$pdf = $normal->pdf(1.5, 2, 0.5);

// CDF at x=85 for N(72, 15.2²)
$cdf = $normal->cdf(85, 72, 15.2);

// 90th percentile for N(500, 100²)
$quantile = $normal->quantile(0.9, 500, 100);
```

## Error Handling

This library uses a hierarchy of custom exceptions for precise error handling:

```php
use PatelWorld\Statistics\Statistics;
use PatelWorld\Statistics\Exceptions\EmptyDataException;
use PatelWorld\Statistics\Exceptions\InvalidDataException;
use PatelWorld\Statistics\Exceptions\InsufficientDataException;
use PatelWorld\Statistics\Exceptions\StatisticsException;

try {
    // Your statistical calculations
    $result = Statistics::mean([]);
} catch (EmptyDataException $e) {
    echo "Empty data error: " . $e->getMessage();
} catch (InvalidDataException $e) {
    echo "Invalid data error: " . $e->getMessage();
} catch (InsufficientDataException $e) {
    echo "Insufficient data error: " . $e->getMessage();
} catch (StatisticsException $e) {
    echo "General statistics error: " . $e->getMessage();
} catch (\Exception $e) {
    echo "Unexpected error: " . $e->getMessage();
}
```

## Exception Types

- `StatisticsException`: Base class for all library exceptions
- `EmptyDataException`: Thrown when an empty dataset is provided
- `InvalidDataException`: Thrown when data contains non-numeric values or parameters are invalid
- `InsufficientDataException`: Thrown when there aren't enough data points for a calculation

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This package is open-sourced software licensed under the MIT license.
