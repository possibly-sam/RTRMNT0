<?php
/*
 * Retirement Calculator for Couples - Main Form
 * 
 * Key Features Implemented:
 * 
 * Couple Information:
 * - Names and current ages for both partners
 * - Currency selection (USD, EUR, GBP, CAD, AUD)
 * 
 * Cost of Living:
 * - Monthly living expenses input
 * - Currency selection
 * 
 * Interest Rate Education:
 * - Clear explanation of real vs nominal interest rates
 * - Default real interest rate setting
 * - Inflation assumption input
 * 
 * Retirement Buckets System:
 * - Dynamic form to add multiple retirement accounts
 * - Each bucket includes:
 *   - Name and type (private/public)
 *   - Location/country
 *   - Owner (Person 1, Person 2, or Joint)
 *   - Current value and custom real interest rate
 *   - Access ages (early and full benefit)
 *   - Monthly contributions and contribution end age
 *   - Early access penalties
 *   - Notes field
 * 
 * User Experience:
 * - Clean, professional styling
 * - Responsive design
 * - JavaScript for adding/removing buckets dynamically
 * - Form validation
 * - Educational content about interest rates
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retirement Calculator for Couples</title>
    <link rel="stylesheet" href="RTRMNT0/RTRMNT.css">
</head>
<body>
    <div class="container">
        <h1>Retirement Calculator for Couples</h1>
        
        <form method="POST" action="RTRMNT0/calculate.php">
            
            <!-- Couple Information Section -->
            <div class="form-section">
                <h2>Couple Information</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label for="person1_name">Person 1 Name:</label>
                        <input type="text" id="person1_name" name="person1_name" required>
                    </div>
                    <div class="form-group">
                        <label for="person1_age">Person 1 Current Age:</label>
                        <input type="number" id="person1_age" name="person1_age" min="18" max="100" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="person2_name">Person 2 Name:</label>
                        <input type="text" id="person2_name" name="person2_name" required>
                    </div>
                    <div class="form-group">
                        <label for="person2_age">Person 2 Current Age:</label>
                        <input type="number" id="person2_age" name="person2_age" min="18" max="100" required>
                    </div>
                </div>
            </div>

            <!-- Cost of Living Section -->
            <div class="form-section">
                <h2>Monthly Cost of Living</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label for="monthly_expenses">Estimated Monthly Living Expenses ($):</label>
                        <input type="number" id="monthly_expenses" name="monthly_expenses" min="0" step="100" required>
                    </div>
                    <div class="form-group">
                        <label for="currency">Currency:</label>
                        <select id="currency" name="currency">
                            <option value="USD">USD - US Dollar</option>
                            <option value="EUR">EUR - Euro</option>
                            <option value="GBP">GBP - British Pound</option>
                            <option value="INR">INR - Indian Rupees</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Interest Rates Section -->
            <div class="form-section">
                <h2>Interest Rate Assumptions</h2>
                
                <div class="info-box">
                    <h3>Understanding Real vs Nominal Interest Rates</h3>
                    <p><strong>Nominal Interest Rate:</strong> The stated interest rate on an investment (e.g., 7% annual return on a mutual fund).</p>
                    <p><strong>Real Interest Rate:</strong> The nominal rate minus inflation. This represents your actual purchasing power growth.</p>
                    <p><strong>Example:</strong> If your investment returns 7% but inflation is 3%, your real return is approximately 4%. This matters because $100 today won't buy the same amount in 20 years due to inflation.</p>
                    <p><strong>Why it matters:</strong> For retirement planning, you need to know how much purchasing power you'll actually have, not just the dollar amount.</p>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="default_real_rate">Default Real Interest Rate (%):</label>
                        <input type="number" id="default_real_rate" name="default_real_rate" step="0.1" value="2.0" required>
                        <small>Used for investments where you don't specify a custom rate</small>
                    </div>
                    <div class="form-group">
                        <label for="inflation_assumption">Expected Annual Inflation (%):</label>
                        <input type="number" id="inflation_assumption" name="inflation_assumption" step="0.1" value="2.5" required>
                        <small>Used for reference and calculations</small>
                    </div>
                </div>
            </div>

            <!-- Retirement Buckets Section -->
            <div class="form-section">
                <h2>Retirement Accounts & Investments</h2>
                <p>Add all your retirement accounts, investments, and expected benefits. Each can have its own growth rate.</p>
                
                <div id="retirement-buckets">
                    <!-- Initial bucket template -->
                    <div class="bucket-item" data-bucket="1">
                        <button type="button" class="remove-bucket" onclick="removeBucket(1)">Remove</button>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="bucket_1_name">Account/Investment Name:</label>
                                <input type="text" id="bucket_1_name" name="buckets[1][name]" placeholder="e.g., 401k, IRA, Pension, Social Security">
                            </div>
                            <div class="form-group">
                                <label for="bucket_1_type">Type:</label>
                                <select id="bucket_1_type" name="buckets[1][type]">
                                    <option value="private">Private (401k, IRA, Mutual Fund, etc.)</option>
                                    <option value="public">Public (Social Security, Pension, etc.)</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="bucket_1_location">Location/Country:</label>
                                <input type="text" id="bucket_1_location" name="buckets[1][location]" placeholder="e.g., USA, France, UK">
                            </div>
                            <div class="form-group">
                                <label for="bucket_1_owner">Owner:</label>
                                <select id="bucket_1_owner" name="buckets[1][owner]">
                                    <option value="person1">Person 1</option>
                                    <option value="person2">Person 2</option>
                                    <option value="joint">Joint</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="bucket_1_current_value">Current Value ($):</label>
                                <input type="number" id="bucket_1_current_value" name="buckets[1][current_value]" min="0" step="100">
                            </div>
                            <div class="form-group">
                                <label for="bucket_1_real_rate">Real Interest Rate (%):</label>
                                <input type="number" id="bucket_1_real_rate" name="buckets[1][real_rate]" step="0.1" placeholder="Leave blank to use default">
                                <small>Override default rate for this investment</small>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="bucket_1_access_age">Earliest Access Age:</label>
                                <input type="number" id="bucket_1_access_age" name="buckets[1][access_age]" min="50" max="100" placeholder="e.g., 59.5 for 401k, 62 for Social Security">
                            </div>
                            <div class="form-group">
                                <label for="bucket_1_full_benefit_age">Full Benefit Age:</label>
                                <input type="number" id="bucket_1_full_benefit_age" name="buckets[1][full_benefit_age]" min="50" max="100" placeholder="e.g., 67 for Social Security">
                                <small>Age when penalties end or full benefits available</small>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="bucket_1_monthly_contribution">Monthly Contribution ($):</label>
                                <input type="number" id="bucket_1_monthly_contribution" name="buckets[1][monthly_contribution]" min="0" step="50" placeholder="0 if no ongoing contributions">
                            </div>
                            <div class="form-group">
                                <label for="bucket_1_contribution_end_age">Stop Contributing at Age:</label>
                                <input type="number" id="bucket_1_contribution_end_age" name="buckets[1][contribution_end_age]" min="50" max="100" placeholder="Age when contributions stop">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="bucket_1_early_penalty">Early Access Penalty (%):</label>
                                <input type="number" id="bucket_1_early_penalty" name="buckets[1][early_penalty]" step="0.1" placeholder="e.g., 10 for 401k early withdrawal">
                                <small>Penalty for accessing before full benefit age</small>
                            </div>
                            <div class="form-group">
                                <label for="bucket_1_notes">Notes:</label>
                                <input type="text" id="bucket_1_notes" name="buckets[1][notes]" placeholder="Any special conditions or notes">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="add-bucket-btn" onclick="addBucket()">Add Another Retirement Account</button>
            </div>

            <div class="button-group">
                <button type="submit" class="calculate-btn">Calculate Retirement Scenarios</button>
                <button type="button" class="save-btn" onclick="saveData()">Save</button>
                <button type="button" class="read-btn" onclick="readData()">Read</button>
            </div>
        </form>
        
        <!-- Hidden file input for reading JSON files -->
        <input type="file" id="fileInput" accept=".json" style="display: none;" onchange="loadFromFile(event)">
        
        <!-- Save/Read status messages -->
        <div id="statusMessage" class="status-message" style="display: none;"></div>
    </div>

    <script>
        let bucketCount = 1;

        function addBucket() {
            bucketCount++;
            const bucketsContainer = document.getElementById('retirement-buckets');
            const newBucket = document.createElement('div');
            newBucket.className = 'bucket-item';
            newBucket.setAttribute('data-bucket', bucketCount);
            
            newBucket.innerHTML = `
                <button type="button" class="remove-bucket" onclick="removeBucket(${bucketCount})">Remove</button>
                <div class="form-row">
                    <div class="form-group">
                        <label for="bucket_${bucketCount}_name">Account/Investment Name:</label>
                        <input type="text" id="bucket_${bucketCount}_name" name="buckets[${bucketCount}][name]" placeholder="e.g., 401k, IRA, Pension, Social Security">
                    </div>
                    <div class="form-group">
                        <label for="bucket_${bucketCount}_type">Type:</label>
                        <select id="bucket_${bucketCount}_type" name="buckets[${bucketCount}][type]">
                            <option value="private">Private (401k, IRA, Mutual Fund, etc.)</option>
                            <option value="public">Public (Social Security, Pension, etc.)</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="bucket_${bucketCount}_location">Location/Country:</label>
                        <input type="text" id="bucket_${bucketCount}_location" name="buckets[${bucketCount}][location]" placeholder="e.g., USA, France, UK">
                    </div>
                    <div class="form-group">
                        <label for="bucket_${bucketCount}_owner">Owner:</label>
                        <select id="bucket_${bucketCount}_owner" name="buckets[${bucketCount}][owner]">
                            <option value="person1">Person 1</option>
                            <option value="person2">Person 2</option>
                            <option value="joint">Joint</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="bucket_${bucketCount}_current_value">Current Value ($):</label>
                        <input type="number" id="bucket_${bucketCount}_current_value" name="buckets[${bucketCount}][current_value]" min="0" step="100">
                    </div>
                    <div class="form-group">
                        <label for="bucket_${bucketCount}_real_rate">Real Interest Rate (%):</label>
                        <input type="number" id="bucket_${bucketCount}_real_rate" name="buckets[${bucketCount}][real_rate]" step="0.1" placeholder="Leave blank to use default">
                        <small>Override default rate for this investment</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="bucket_${bucketCount}_access_age">Earliest Access Age:</label>
                        <input type="number" id="bucket_${bucketCount}_access_age" name="buckets[${bucketCount}][access_age]" min="50" max="100" placeholder="e.g., 59.5 for 401k, 62 for Social Security">
                    </div>
                    <div class="form-group">
                        <label for="bucket_${bucketCount}_full_benefit_age">Full Benefit Age:</label>
                        <input type="number" id="bucket_${bucketCount}_full_benefit_age" name="buckets[${bucketCount}][full_benefit_age]" min="50" max="100" placeholder="e.g., 67 for Social Security">
                        <small>Age when penalties end or full benefits available</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="bucket_${bucketCount}_monthly_contribution">Monthly Contribution ($):</label>
                        <input type="number" id="bucket_${bucketCount}_monthly_contribution" name="buckets[${bucketCount}][monthly_contribution]" min="0" step="50" placeholder="0 if no ongoing contributions">
                    </div>
                    <div class="form-group">
                        <label for="bucket_${bucketCount}_contribution_end_age">Stop Contributing at Age:</label>
                        <input type="number" id="bucket_${bucketCount}_contribution_end_age" name="buckets[${bucketCount}][contribution_end_age]" min="50" max="100" placeholder="Age when contributions stop">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="bucket_${bucketCount}_early_penalty">Early Access Penalty (%):</label>
                        <input type="number" id="bucket_${bucketCount}_early_penalty" name="buckets[${bucketCount}][early_penalty]" step="0.1" placeholder="e.g., 10 for 401k early withdrawal">
                        <small>Penalty for accessing before full benefit age</small>
                    </div>
                    <div class="form-group">
                        <label for="bucket_${bucketCount}_notes">Notes:</label>
                        <input type="text" id="bucket_${bucketCount}_notes" name="buckets[${bucketCount}][notes]" placeholder="Any special conditions or notes">
                    </div>
                </div>
            `;
            
            bucketsContainer.appendChild(newBucket);
        }

        function removeBucket(bucketId) {
            const bucket = document.querySelector(`[data-bucket="${bucketId}"]`);
            if (bucket && document.querySelectorAll('.bucket-item').length > 1) {
                bucket.remove();
            }
        }

        // Save form data as JSON file
        function saveData() {
            const formData = collectFormData();
            const jsonString = JSON.stringify(formData, null, 2);
            const blob = new Blob([jsonString], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            
            const a = document.createElement('a');
            a.href = url;
            a.download = 'retirement_data_' + new Date().toISOString().split('T')[0] + '.json';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            showStatus('Data saved successfully!', 'success');
        }

        // Trigger file input for reading JSON
        function readData() {
            document.getElementById('fileInput').click();
        }

        // Load data from selected JSON file
        function loadFromFile(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    const data = JSON.parse(e.target.result);
                    populateForm(data);
                    showStatus('Data loaded successfully!', 'success');
                } catch (error) {
                    showStatus('Error loading file: Invalid JSON format', 'error');
                }
            };
            reader.readAsText(file);
        }

        // Collect all form data into retirement_data structure
        function collectFormData() {
            const retirement_data = {
                couple: {
                    person1: {
                        name: document.getElementById('person1_name').value,
                        age: parseInt(document.getElementById('person1_age').value) || 0
                    },
                    person2: {
                        name: document.getElementById('person2_name').value,
                        age: parseInt(document.getElementById('person2_age').value) || 0
                    }
                },
                financial: {
                    monthly_expenses: parseFloat(document.getElementById('monthly_expenses').value) || 0,
                    currency: document.getElementById('currency').value,
                    default_real_rate: parseFloat(document.getElementById('default_real_rate').value) || 2.0,
                    inflation_assumption: parseFloat(document.getElementById('inflation_assumption').value) || 2.5
                },
                buckets: []
            };

            // Collect bucket data
            const bucketItems = document.querySelectorAll('.bucket-item');
            bucketItems.forEach((bucket, index) => {
                const bucketId = bucket.getAttribute('data-bucket');
                const name = document.getElementById(`bucket_${bucketId}_name`)?.value;
                
                if (name) { // Only include buckets with names
                    retirement_data.buckets.push({
                        id: bucketId,
                        name: name,
                        type: document.getElementById(`bucket_${bucketId}_type`)?.value || 'private',
                        location: document.getElementById(`bucket_${bucketId}_location`)?.value || '',
                        owner: document.getElementById(`bucket_${bucketId}_owner`)?.value || 'person1',
                        current_value: parseFloat(document.getElementById(`bucket_${bucketId}_current_value`)?.value) || 0,
                        real_rate: parseFloat(document.getElementById(`bucket_${bucketId}_real_rate`)?.value) || retirement_data.financial.default_real_rate,
                        access_age: parseFloat(document.getElementById(`bucket_${bucketId}_access_age`)?.value) || 65,
                        full_benefit_age: parseFloat(document.getElementById(`bucket_${bucketId}_full_benefit_age`)?.value) || 67,
                        monthly_contribution: parseFloat(document.getElementById(`bucket_${bucketId}_monthly_contribution`)?.value) || 0,
                        contribution_end_age: parseFloat(document.getElementById(`bucket_${bucketId}_contribution_end_age`)?.value) || 65,
                        early_penalty: parseFloat(document.getElementById(`bucket_${bucketId}_early_penalty`)?.value) || 0,
                        notes: document.getElementById(`bucket_${bucketId}_notes`)?.value || ''
                    });
                }
            });

            return retirement_data;
        }

        // Populate form with loaded data
        function populateForm(data) {
            // Clear existing buckets except the first one
            const bucketItems = document.querySelectorAll('.bucket-item');
            for (let i = 1; i < bucketItems.length; i++) {
                bucketItems[i].remove();
            }
            bucketCount = 1;

            // Populate couple information
            if (data.couple) {
                if (data.couple.person1) {
                    document.getElementById('person1_name').value = data.couple.person1.name || '';
                    document.getElementById('person1_age').value = data.couple.person1.age || '';
                }
                if (data.couple.person2) {
                    document.getElementById('person2_name').value = data.couple.person2.name || '';
                    document.getElementById('person2_age').value = data.couple.person2.age || '';
                }
            }

            // Populate financial information
            if (data.financial) {
                document.getElementById('monthly_expenses').value = data.financial.monthly_expenses || '';
                document.getElementById('currency').value = data.financial.currency || 'USD';
                document.getElementById('default_real_rate').value = data.financial.default_real_rate || 2.0;
                document.getElementById('inflation_assumption').value = data.financial.inflation_assumption || 2.5;
            }

            // Populate buckets
            if (data.buckets && data.buckets.length > 0) {
                data.buckets.forEach((bucket, index) => {
                    if (index === 0) {
                        // Use the first existing bucket
                        populateBucket(1, bucket);
                    } else {
                        // Add new buckets
                        addBucket();
                        populateBucket(bucketCount, bucket);
                    }
                });
            }
        }

        // Populate a specific bucket with data
        function populateBucket(bucketId, bucketData) {
            const setValue = (fieldName, value) => {
                const element = document.getElementById(`bucket_${bucketId}_${fieldName}`);
                if (element) element.value = value || '';
            };

            setValue('name', bucketData.name);
            setValue('type', bucketData.type);
            setValue('location', bucketData.location);
            setValue('owner', bucketData.owner);
            setValue('current_value', bucketData.current_value);
            setValue('real_rate', bucketData.real_rate);
            setValue('access_age', bucketData.access_age);
            setValue('full_benefit_age', bucketData.full_benefit_age);
            setValue('monthly_contribution', bucketData.monthly_contribution);
            setValue('contribution_end_age', bucketData.contribution_end_age);
            setValue('early_penalty', bucketData.early_penalty);
            setValue('notes', bucketData.notes);
        }

        // Show status messages
        function showStatus(message, type) {
            const statusDiv = document.getElementById('statusMessage');
            statusDiv.textContent = message;
            statusDiv.className = `status-message ${type}`;
            statusDiv.style.display = 'block';
            
            setTimeout(() => {
                statusDiv.style.display = 'none';
            }, 3000);
        }
    </script>
</body>
</html>



<?php



//### 1. Save Button

//• Collects all form data into the same $retirement_data structure used
//by calculate.php
//• Exports data as a JSON file with timestamp in filename
//• Downloads automatically to user's computer

//### 2. Read Button

//• Opens file picker to select JSON files
//• Loads and validates JSON data
//• Populates all form fields with loaded data
//• Handles multiple retirement buckets dynamically

//### 3. User Interface

//• Added button group with Calculate, Save, and Read buttons
//• Responsive design that stacks buttons on mobile
//• Status messages for success/error feedback
//• Professional styling matching existing design

//### 4. JavaScript Functions

//• saveData() - Collects form data and triggers download
//• readData() - Opens file picker
//• loadFromFile() - Processes selected JSON file
//• collectFormData() - Builds retirement_data structure
//• populateForm() - Fills form with loaded data
//• populateBucket() - Populates individual bucket data
//• showStatus() - Displays feedback messages

//The implementation follows the existing code style and integrates
//seamlessly with the current retirement calculator. Users can now save
//their retirement scenarios and reload them later for comparison or
//modification.



?>






