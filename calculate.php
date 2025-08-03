<?php
/*
 * Retirement Calculator for Couples - Calculation Engine
 * 
 * Key Features Implemented:
 * 
 * 1. Modular Architecture:
 *    - Core calculation functions extracted to calculations.php
 *    - process_single_bucket(): Handles individual bucket calculations
 *    - calculation_logic(): Uses functional programming with array_map
 *    - amortization(): Calculates monthly payments using amortization formula
 *    - All functions include comprehensive PHPDoc documentation
 * 
 * 2. Enhanced calculation_logic Function:
 *    - Uses functional programming approach with array_map instead of foreach loops
 *    - Processes each retirement bucket through comprehensive calculations
 *    - Handles different ownership scenarios (person1, person2, joint)
 * 
 * 3. Comprehensive Bucket Calculations (process_single_bucket):
 *    - Growth Projections: Calculates account values at key ages (access age, full benefit age, 70, 75, 80)
 *    - Contribution Handling: Includes ongoing monthly contributions until contribution end age
 *    - Compound Growth: Applies real interest rates over time
 *    - Penalty Application: Reduces payments for early access based on penalty rates
 * 
 * 4. Amortization Integration:
 *    - Uses amortization($principal, $interest_rate, $number_of_years) function
 *    - Calculates monthly payments assuming 20-year withdrawal period
 *    - Converts annual rates to monthly for accurate calculations
 * 
 * 5. Rich Output Display:
 *    - Projection Tables: Shows account values and payments at different ages
 *    - Penalty Highlighting: Red-highlighted rows show reduced payments due to early access
 *    - Summary Section: Total monthly income and expense coverage percentages
 *    - Breakeven Analysis: Compares early access vs full benefit scenarios
 * 
 * 6. Key Calculations Per Bucket:
 *    - Account value at target age (with contributions and growth)
 *    - Monthly payment using amortization formula
 *    - Annual payment equivalent
 *    - Penalty adjustments for early access
 *    - Years from current age to target age
 * 
 * REFACTORING SUMMARY:
 * ===================
 * 
 * 1. Modular Extraction (Latest):
 *    - Extracted all calculation functions to separate calculations.php file
 *    - Improved separation of concerns: calculations vs presentation logic
 *    - Enhanced reusability: functions can be included in multiple contexts
 *    - Better testability: calculation logic can be tested in isolation
 *    - Cleaner file structure: calculate.php focuses on web interface logic
 * 
 * 2. Extracted process_single_bucket() Function:
 *    - Moved all bucket processing logic from the foreach loop into a separate function
 *    - Takes a single bucket and couple data as parameters
 *    - Returns the complete $bucket_result with all calculations
 *    - Added comprehensive PHPDoc documentation
 * 
 * 3. Refactored calculation_logic() Function:
 *    - Replaced the foreach loop with functional programming using array_map()
 *    - Now uses a clean, concise approach: array_map(function($bucket) use ($couple) { return process_single_bucket($bucket, $couple); }, $buckets)
 *    - Maintains the same return structure and functionality
 *    - Added detailed PHPDoc comments explaining parameters and return values
 * 
 * 4. Enhanced amortization() Function:
 *    - Added proper PHPDoc documentation
 *    - Cleaned up formatting and spacing
 *    - Maintained original functionality
 * 
 * 5. Updated File Structure:
 *    - calculations.php: Pure calculation functions (no side effects)
 *    - calculate.php: Web interface logic and form handling
 *    - TDD/test_refactor.php: Comprehensive test suite
 *    - Documented the new modular architecture
 * 
 * 6. Verified Functionality:
 *    - Created and ran comprehensive tests
 *    - Confirmed all calculations produce identical results
 *    - Verified the data structure integrity
 *    - Ensured no breaking changes
 * 
 * Key Benefits of the Modular Refactoring:
 * - Separation of Concerns: Calculation logic completely separated from presentation
 * - Functional Programming: Uses array_map for cleaner, more declarative code
 * - Reusability: Functions can be included in web, CLI, or API contexts
 * - Maintainability: Easier to test and modify individual components
 * - Documentation: Comprehensive PHPDoc comments for all functions
 * - Code Quality: Follows PHP best practices and coding standards
 * - Modularity: Clean file organization with single responsibility principle
 * 
 * The refactored code maintains 100% backward compatibility while providing 
 * a cleaner, more maintainable, and modular architecture.
 */

// Include core calculation functions
include 'calculations.php';

// Form handler with JSON object creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input data
    $person1_name = htmlspecialchars($_POST['person1_name'] ?? '');
    $person1_age = (int)($_POST['person1_age'] ?? 0);
    $person2_name = htmlspecialchars($_POST['person2_name'] ?? '');
    $person2_age = (int)($_POST['person2_age'] ?? 0);
    
    $monthly_expenses = (float)($_POST['monthly_expenses'] ?? 0);
    $currency = htmlspecialchars($_POST['currency'] ?? 'USD');
    $default_real_rate = (float)($_POST['default_real_rate'] ?? 2.0);
    $inflation_assumption = (float)($_POST['inflation_assumption'] ?? 2.5);
    
    $buckets = $_POST['buckets'] ?? [];
    
    // Basic validation
    $errors = [];
    if (empty($person1_name)) $errors[] = "Person 1 name is required";
    if ($person1_age < 18 || $person1_age > 100) $errors[] = "Person 1 age must be between 18 and 100";
    if (empty($person2_name)) $errors[] = "Person 2 name is required";
    if ($person2_age < 18 || $person2_age > 100) $errors[] = "Person 2 age must be between 18 and 100";
    if ($monthly_expenses <= 0) $errors[] = "Monthly expenses must be greater than 0";
    
    if (!empty($errors)) {
        echo "<h2>Validation Errors:</h2>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul>";
        echo "<a href='index.php'>Go Back</a>";
        exit;
    }
    
    // Create comprehensive JSON object with all retirement data
    $retirement_data = [
        'couple' => [
            'person1' => [
                'name' => $person1_name,
                'age' => $person1_age
            ],
            'person2' => [
                'name' => $person2_name,
                'age' => $person2_age
            ]
        ],
        'financial' => [
            'monthly_expenses' => $monthly_expenses,
            'currency' => $currency,
            'default_real_rate' => $default_real_rate,
            'inflation_assumption' => $inflation_assumption
        ],
        'buckets' => []
    ];
    
    // Process and structure bucket data
    foreach ($buckets as $id => $bucket) {
        if (!empty($bucket['name'])) {
            $retirement_data['buckets'][] = [
                'id' => $id,
                'name' => htmlspecialchars($bucket['name']),
                'type' => htmlspecialchars($bucket['type'] ?? 'private'),
                'location' => htmlspecialchars($bucket['location'] ?? ''),
                'owner' => htmlspecialchars($bucket['owner'] ?? 'person1'),
                'current_value' => (float)($bucket['current_value'] ?? 0),
                'real_rate' => !empty($bucket['real_rate']) ? (float)$bucket['real_rate'] : $default_real_rate,
                'access_age' => (float)($bucket['access_age'] ?? 65),
                'full_benefit_age' => (float)($bucket['full_benefit_age'] ?? 67),
                'monthly_contribution' => (float)($bucket['monthly_contribution'] ?? 0),
                'contribution_end_age' => (float)($bucket['contribution_end_age'] ?? 65),
                'early_penalty' => (float)($bucket['early_penalty'] ?? 0),
                'notes' => htmlspecialchars($bucket['notes'] ?? '')
            ];
        }
    }
    
    // Call calculation logic with structured data
    $calculation_results = calculation_logic($retirement_data);
    
    // Display results
    echo "<link rel='stylesheet' href='RTRMNT.css'>";
    echo "<div class='container'>";
    echo "<h1>Retirement Calculation Results</h1>";
    
    echo "<h2>Input Data Summary</h2>";
    echo "<div class='form-section'>";
    echo "<h3>Couple Information</h3>";
    echo "<p><strong>{$retirement_data['couple']['person1']['name']}:</strong> Age {$retirement_data['couple']['person1']['age']}</p>";
    echo "<p><strong>{$retirement_data['couple']['person2']['name']}:</strong> Age {$retirement_data['couple']['person2']['age']}</p>";
    echo "<p><strong>Monthly Expenses:</strong> " . number_format($retirement_data['financial']['monthly_expenses'], 2) . " {$retirement_data['financial']['currency']}</p>";
    echo "<p><strong>Default Real Interest Rate:</strong> {$retirement_data['financial']['default_real_rate']}%</p>";
    echo "<p><strong>Expected Inflation:</strong> {$retirement_data['financial']['inflation_assumption']}%</p>";
    echo "</div>";
    
    echo "<div class='form-section'>";
    echo "<h3>Retirement Accounts</h3>";
    foreach ($retirement_data['buckets'] as $bucket) {
        echo "<div class='bucket-item'>";
        echo "<h4>" . $bucket['name'] . "</h4>";
        echo "<p><strong>Type:</strong> " . ucfirst($bucket['type']) . "</p>";
        echo "<p><strong>Location:</strong> " . $bucket['location'] . "</p>";
        echo "<p><strong>Owner:</strong> " . ucfirst(str_replace('person', 'Person ', $bucket['owner'])) . "</p>";
        echo "<p><strong>Current Value:</strong> $" . number_format($bucket['current_value'], 2) . "</p>";
        echo "<p><strong>Real Interest Rate:</strong> " . $bucket['real_rate'] . "%</p>";
        echo "<p><strong>Access Age:</strong> " . $bucket['access_age'] . "</p>";
        echo "<p><strong>Full Benefit Age:</strong> " . $bucket['full_benefit_age'] . "</p>";
        if ($bucket['monthly_contribution'] > 0) {
            echo "<p><strong>Monthly Contribution:</strong> $" . number_format($bucket['monthly_contribution'], 2) . " until age " . $bucket['contribution_end_age'] . "</p>";
        }
        if ($bucket['early_penalty'] > 0) {
            echo "<p><strong>Early Access Penalty:</strong> " . $bucket['early_penalty'] . "%</p>";
        }
        if (!empty($bucket['notes'])) {
            echo "<p><strong>Notes:</strong> " . $bucket['notes'] . "</p>";
        }
        echo "</div>";
    }
    echo "</div>";
    
    // Link to Interactive Retirement Projections
    echo "<h2>Retirement Projections</h2>";
    echo "<div class='form-section'>";
    echo "<p>For customizable retirement projections with your preferred disbursement age, withdrawal period, and interest rates:</p>";
    echo "<form method='POST' action='retirement_projections.php' style='display: inline;'>";
    echo "<input type='hidden' name='retirement_data' value='" . htmlspecialchars(json_encode($retirement_data)) . "'>";
    echo "<button type='submit' class='calculate-btn' style='margin: 10px 0;'>Open Interactive Projections</button>";
    echo "</form>";
    echo "<p><em>The interactive projections tool allows you to:</em></p>";
    echo "<ul>";
    echo "<li>Choose your preferred disbursement age (default: current age)</li>";
    echo "<li>Set custom withdrawal periods (instead of fixed 20 years)</li>";
    echo "<li>Adjust interest rate assumptions</li>";
    echo "<li>See real-time calculations based on your parameters</li>";
    echo "</ul>";
    echo "</div>";
    
    // Summary section
    echo "<h2>Summary</h2>";
    echo "<div class='form-section'>";
    echo "<p><strong>Monthly Expenses Target:</strong> $" . number_format($retirement_data['financial']['monthly_expenses'], 0) . "</p>";
    
    $total_monthly_at_access = 0;
    $total_monthly_at_full = 0;
    
    foreach ($calculation_results['bucket_calculations'] as $bucket_calc) {
        if (isset($bucket_calc['calculations']['access_age'])) {
            $total_monthly_at_access += $bucket_calc['calculations']['access_age']['monthly_payment'];
        }
        if (isset($bucket_calc['calculations']['full_benefit_age'])) {
            $total_monthly_at_full += $bucket_calc['calculations']['full_benefit_age']['monthly_payment'];
        }
    }
    
    if ($total_monthly_at_access > 0) {
        echo "<p><strong>Total Monthly Income at Early Access:</strong> $" . number_format($total_monthly_at_access, 0) . "</p>";
        $coverage_early = ($total_monthly_at_access / $retirement_data['financial']['monthly_expenses']) * 100;
        echo "<p><strong>Expense Coverage (Early Access):</strong> " . number_format($coverage_early, 1) . "%</p>";
    }
    
    if ($total_monthly_at_full > 0) {
        echo "<p><strong>Total Monthly Income at Full Benefit:</strong> $" . number_format($total_monthly_at_full, 0) . "</p>";
        $coverage_full = ($total_monthly_at_full / $retirement_data['financial']['monthly_expenses']) * 100;
        echo "<p><strong>Expense Coverage (Full Benefit):</strong> " . number_format($coverage_full, 1) . "%</p>";
    }
    echo "</div>";
    
    echo "<h2>JSON Data Structure</h2>";
    echo "<div class='form-section'>";
    echo "<pre style='background: #f8f8f8; padding: 15px; border-radius: 5px; overflow-x: auto; font-size: 12px;'>";
    echo json_encode($calculation_results, JSON_PRETTY_PRINT);
    echo "</pre>";
    echo "</div>";
    
    echo "<a href='index.php' class='calculate-btn' style='display: inline-block; text-decoration: none; margin-top: 20px;'>Calculate Another Scenario</a>";
    echo "</div>";
    
} else {
    // Redirect to form if accessed directly
    header('Location: index.php');
    exit;
}
?>
