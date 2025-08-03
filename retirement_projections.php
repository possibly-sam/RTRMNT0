<?php
/*
 * Retirement Projections - Interactive Form for Customized Disbursement Analysis
 * 
 * Key Features:
 * - User-customizable disbursement age (default: current age)
 * - Adjustable withdrawal period (default: 20 years)
 * - Modifiable interest rate assumptions
 * - Interactive form-based interface
 * - Real-time projection calculations
 * - Integration with existing retirement data
 * 
 * Refactored from calculate.php for better modularity and user control
 * ## Retirement Projections Refactoring Summary:

### 1. Created retirement_projections.php - Interactive Form

• Separate form-based interface for customizable retirement
projections
• User-customizable parameters:
 • Disbursement Age: User can choose when to start receiving payments  (default: current age)
 • Withdrawal Period: Variable years instead of fixed 20 years (1-50  years)
 • Interest Rate: Adjustable interest rate assumptions (0-15%)
• Session-based data transfer from calculate.php
• Real-time calculations based on user inputs

### 2. Enhanced User Control Features

• Dynamic defaults: Uses older person's age as default disbursement
age
• Flexible parameters: All key variables are user-adjustable
• Penalty calculations: Automatically applies early access penalties when applicable
• Coverage analysis: Shows expense coverage percentage with color
coding
• Shortfall/surplus calculations: Clear financial gap analysis

### 3. Updated calculate.php

• Removed static projections table (lines 218-263)
• Added interactive link to new projections form
• Data passing mechanism via hidden f
 
* ### 4. Custom Projection Logic

• calculate_custom_projections() function for user-specified
parameters
• Handles edge cases: Zero interest rates, immediate disbursement
• Contribution calculations: Includes ongoing contributions until end age
• Penalty application: Early access penalties based on user's chosen age
• Flexible withdrawal periods: Any period from 1-50 years

### 5. Comprehensive Testing

• Created TDD/test_projections.php test suite
• Multiple scenarios tested:
 • Early retirement (age 62, 25 years)
 • Standard retirement (age 67, 20 years)
 • Conservative approach (lower rate, longer period)
 • Aggressive approach (higher rate, shorter period)
• Edge case testing: Zero rates, immediate disbursement
• All tests pass with proper calculations

### 6. User Experience Improvements

• Form-based interface instead of static table
• Real-time parameter adjustment with immediate recalculation
• Clear navigation between full analysis and projections
• Visual feedback: Color-coded coverage percentages
• Detailed explanations of what each paramete
 */

// Include core calculation functions
include 'calculations.php';

// Get retirement data from session or POST
session_start();
$retirement_data = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['retirement_data'])) {
    // Data passed from calculate.php
    $retirement_data = json_decode($_POST['retirement_data'], true);
    $_SESSION['retirement_data'] = $retirement_data;
} elseif (isset($_SESSION['retirement_data'])) {
    // Data from session
    $retirement_data = $_SESSION['retirement_data'];
} else {
    // Redirect back if no data available
    header('Location: index.php');
    exit;
}

// Process projection form if submitted
$projection_results = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calculate_projections'])) {
    $disbursement_age = (float)($_POST['disbursement_age'] ?? 65);
    $withdrawal_period = (int)($_POST['withdrawal_period'] ?? 20);
    $custom_interest_rate = (float)($_POST['custom_interest_rate'] ?? $retirement_data['financial']['default_real_rate']);
    
    // Calculate custom projections
    $projection_results = calculate_custom_projections($retirement_data, $disbursement_age, $withdrawal_period, $custom_interest_rate);
}

/**
 * Calculate custom retirement projections based on user parameters
 * 
 * @param array $retirement_data The complete retirement data structure
 * @param float $disbursement_age Age to start disbursements
 * @param int $withdrawal_period Number of years for withdrawal period
 * @param float $custom_interest_rate Custom interest rate to use
 * @return array Custom projection results
 */
function calculate_custom_projections($retirement_data, $disbursement_age, $withdrawal_period, $custom_interest_rate) {
    $couple = $retirement_data['couple'];
    $buckets = $retirement_data['buckets'];
    
    $results = [];
    
    foreach ($buckets as $bucket) {
        // Get owner's current age
        $owner_age = 0;
        if ($bucket['owner'] === 'person1') {
            $owner_age = $couple['person1']['age'];
        } elseif ($bucket['owner'] === 'person2') {
            $owner_age = $couple['person2']['age'];
        } else {
            $owner_age = min($couple['person1']['age'], $couple['person2']['age']);
        }
        
        // Skip if disbursement age is in the past
        if ($disbursement_age <= $owner_age) {
            $disbursement_age = $owner_age; // Use current age if specified age is in past
        }
        
        $years_to_disbursement = $disbursement_age - $owner_age;
        $current_value = $bucket['current_value'];
        $real_rate = $custom_interest_rate / 100; // Use custom rate
        
        // Calculate account value at disbursement age
        $value_at_disbursement = $current_value * ((1 + $real_rate) ** $years_to_disbursement);
        
        // Add contribution growth if applicable
        if ($bucket['monthly_contribution'] > 0 && $owner_age < $bucket['contribution_end_age']) {
            $contribution_years = min($years_to_disbursement, $bucket['contribution_end_age'] - $owner_age);
            $annual_contribution = $bucket['monthly_contribution'] * 12;
            
            if ($real_rate > 0) {
                $contribution_fv = $annual_contribution * (((1 + $real_rate) ** $contribution_years - 1) / $real_rate);
                $value_at_disbursement += $contribution_fv;
            } else {
                $value_at_disbursement += $annual_contribution * $contribution_years;
            }
        }
        
        // Calculate monthly payment using custom withdrawal period
        if ($real_rate == 0) {
            $monthly_payment = $value_at_disbursement / ($withdrawal_period * 12);
        } else {
            $monthly_payment = amortization($value_at_disbursement, $real_rate / 12, $withdrawal_period * 12);
        }
        
        // Apply early access penalty if applicable
        $penalty_applied = false;
        if ($disbursement_age < $bucket['full_benefit_age'] && $bucket['early_penalty'] > 0) {
            $penalty_factor = (100 - $bucket['early_penalty']) / 100;
            $monthly_payment *= $penalty_factor;
            $penalty_applied = true;
        }
        
        $results[] = [
            'bucket_info' => $bucket,
            'projection' => [
                'disbursement_age' => $disbursement_age,
                'years_to_disbursement' => $years_to_disbursement,
                'account_value' => $value_at_disbursement,
                'monthly_payment' => $monthly_payment,
                'annual_payment' => $monthly_payment * 12,
                'withdrawal_period' => $withdrawal_period,
                'interest_rate_used' => $custom_interest_rate,
                'penalty_applied' => $penalty_applied,
                'penalty_rate' => $penalty_applied ? $bucket['early_penalty'] : 0
            ]
        ];
    }
    
    return $results;
}

// Get current ages for default disbursement age
$current_ages = [
    $retirement_data['couple']['person1']['age'],
    $retirement_data['couple']['person2']['age']
];
$default_disbursement_age = max($current_ages); // Use older person's age as default
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retirement Projections - Customizable Analysis</title>
    <link rel="stylesheet" href="RTRMNT.css">
</head>
<body>
    <div class="container">
        <h1>Retirement Projections</h1>
        <p>Customize your retirement disbursement analysis with your preferred parameters.</p>
        
        <!-- Projection Parameters Form -->
        <form method="POST" action="">
            <div class="form-section">
                <h2>Projection Parameters</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="disbursement_age">First Disbursement Age:</label>
                        <input type="number" id="disbursement_age" name="disbursement_age" 
                               min="<?php echo min($current_ages); ?>" max="100" step="0.5" 
                               value="<?php echo $_POST['disbursement_age'] ?? $default_disbursement_age; ?>" required>
                        <small>Age when you want to start receiving payments</small>
                    </div>
                    <div class="form-group">
                        <label for="withdrawal_period">Withdrawal Period (Years):</label>
                        <input type="number" id="withdrawal_period" name="withdrawal_period" 
                               min="1" max="50" 
                               value="<?php echo $_POST['withdrawal_period'] ?? 20; ?>" required>
                        <small>Number of years to spread withdrawals over</small>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="custom_interest_rate">Interest Rate Assumption (%):</label>
                        <input type="number" id="custom_interest_rate" name="custom_interest_rate" 
                               step="0.1" min="0" max="15" 
                               value="<?php echo $_POST['custom_interest_rate'] ?? $retirement_data['financial']['default_real_rate']; ?>" required>
                        <small>Real interest rate for projections</small>
                    </div>
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" name="calculate_projections" class="calculate-btn">Calculate Projections</button>
                    </div>
                </div>
            </div>
        </form>
        
        <?php if ($projection_results): ?>
        <!-- Projection Results -->
        <h2>Custom Projection Results</h2>
        
        <?php 
        $total_monthly_income = 0;
        foreach ($projection_results as $result): 
            $bucket = $result['bucket_info'];
            $projection = $result['projection'];
            $total_monthly_income += $projection['monthly_payment'];
        ?>
        <div class="form-section">
            <h3><?php echo htmlspecialchars($bucket['name']); ?> - Custom Projection</h3>
            <div class="form-row">
                <div class="form-group">
                    <p><strong>Owner:</strong> <?php echo ucfirst(str_replace('person', 'Person ', $bucket['owner'])); ?></p>
                    <p><strong>Current Value:</strong> $<?php echo number_format($bucket['current_value'], 0); ?></p>
                    <p><strong>Interest Rate Used:</strong> <?php echo $projection['interest_rate_used']; ?>%</p>
                </div>
                <div class="form-group">
                    <p><strong>Disbursement Age:</strong> <?php echo $projection['disbursement_age']; ?></p>
                    <p><strong>Years to Disbursement:</strong> <?php echo $projection['years_to_disbursement']; ?></p>
                    <p><strong>Withdrawal Period:</strong> <?php echo $projection['withdrawal_period']; ?> years</p>
                </div>
            </div>
            
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                <thead>
                    <tr style="background-color: #f0f0f0;">
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Account Value at Disbursement</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: right;">Monthly Payment</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: right;">Annual Payment</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">Penalty Applied</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="<?php echo $projection['penalty_applied'] ? 'background-color: #ffe6e6;' : ''; ?>">
                        <td style="border: 1px solid #ddd; padding: 8px;">$<?php echo number_format($projection['account_value'], 0); ?></td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">$<?php echo number_format($projection['monthly_payment'], 0); ?></td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">$<?php echo number_format($projection['annual_payment'], 0); ?></td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                            <?php echo $projection['penalty_applied'] ? $projection['penalty_rate'] . "%" : "None"; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <?php if ($projection['penalty_applied']): ?>
            <p style="margin-top: 10px; font-size: 0.9em; color: #666;">
                <em>Note: Payment reduced due to early access penalty of <?php echo $projection['penalty_rate']; ?>%.</em>
            </p>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
        
        <!-- Summary -->
        <div class="form-section">
            <h3>Projection Summary</h3>
            <p><strong>Total Monthly Income:</strong> $<?php echo number_format($total_monthly_income, 0); ?></p>
            <p><strong>Monthly Expenses Target:</strong> $<?php echo number_format($retirement_data['financial']['monthly_expenses'], 0); ?></p>
            <?php 
            $coverage_percentage = ($total_monthly_income / $retirement_data['financial']['monthly_expenses']) * 100;
            $coverage_color = $coverage_percentage >= 100 ? 'green' : ($coverage_percentage >= 75 ? 'orange' : 'red');
            ?>
            <p><strong>Expense Coverage:</strong> 
                <span style="color: <?php echo $coverage_color; ?>; font-weight: bold;">
                    <?php echo number_format($coverage_percentage, 1); ?>%
                </span>
            </p>
            
            <?php if ($coverage_percentage < 100): ?>
            <p style="color: #d9534f;"><strong>Shortfall:</strong> $<?php echo number_format($retirement_data['financial']['monthly_expenses'] - $total_monthly_income, 0); ?> per month</p>
            <?php else: ?>
            <p style="color: #5cb85c;"><strong>Surplus:</strong> $<?php echo number_format($total_monthly_income - $retirement_data['financial']['monthly_expenses'], 0); ?> per month</p>
            <?php endif; ?>
        </div>
        
        <?php endif; ?>
        
        <!-- Navigation -->
        <div style="margin-top: 30px; text-align: center;">
            <a href="calculate.php" class="calculate-btn" style="display: inline-block; text-decoration: none; margin-right: 15px;">Back to Full Analysis</a>
            <a href="index.php" class="calculate-btn" style="display: inline-block; text-decoration: none;">New Calculation</a>
        </div>
    </div>
</body>
</html>
