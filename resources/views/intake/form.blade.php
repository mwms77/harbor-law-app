@extends('layouts.app')

@section('title', 'Estate Planning Intake Form')

@section('content')

@push('styles')
<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            height: auto;
            min-height: 100%;
            overflow-x: hidden;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 10px;
        }

        .container {
            max-width: 100%;
            width: 100%;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: visible;
            min-height: auto;
        }
        
        @media (min-width: 768px) {
            .container {
                max-width: 900px;
            }
            
            body {
                padding: 20px;
            }
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 15px;
            text-align: center;
        }

        .header h1 {
            font-size: 20px;
            margin-bottom: 8px;
        }
        
        @media (min-width: 768px) {
            .header {
                padding: 30px 20px;
            }
            
            .header h1 {
                font-size: 28px;
                margin-bottom: 10px;
            }
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .progress-bar {
            background: rgba(255, 255, 255, 0.2);
            height: 8px;
            border-radius: 4px;
            margin-top: 20px;
            overflow: hidden;
        }

        .progress-fill {
            background: white;
            height: 100%;
            width: 0%;
            transition: width 0.3s ease;
        }

        .form-content {
            padding: 20px 15px;
        }
        
        @media (min-width: 768px) {
            .form-content {
                padding: 30px 25px;
            }
        }

        .section {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .section.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .section-title {
            font-size: 20px;
            color: #333;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 3px solid #667eea;
        }
        
        @media (min-width: 768px) {
            .section-title {
                font-size: 24px;
                margin-bottom: 10px;
                padding-bottom: 10px;
            }
        }

        .section-subtitle {
            font-size: 16px;
            color: #555;
            margin-top: 20px;
            margin-bottom: 12px;
            font-weight: 600;
        }
        
        @media (min-width: 768px) {
            .section-subtitle {
                font-size: 18px;
                margin-top: 25px;
                margin-bottom: 15px;
            }
        }

        .form-group {
            margin-bottom: 15px;
        }
        
        @media (min-width: 768px) {
            .form-group {
                margin-bottom: 20px;
            }
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
            font-size: 14px;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="tel"],
        .form-group input[type="date"],
        .form-group input[type="number"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        
        @media (min-width: 768px) {
            .form-group input[type="text"],
            .form-group input[type="email"],
            .form-group input[type="tel"],
            .form-group input[type="date"],
            .form-group input[type="number"],
            .form-group select,
            .form-group textarea {
                padding: 12px;
            }
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .checkbox-group input[type="checkbox"],
        .checkbox-group input[type="radio"] {
            margin-right: 10px;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .checkbox-group label {
            margin-bottom: 0;
            cursor: pointer;
        }

        .repeater {
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
            background: #f9f9f9;
        }
        
        @media (min-width: 768px) {
            .repeater {
                padding: 20px;
                margin-bottom: 20px;
            }
        }

        .repeater-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .repeater-title {
            font-weight: 600;
            color: #333;
        }

        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        @media (min-width: 768px) {
            .btn {
                padding: 12px 24px;
                font-size: 14px;
            }
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
            padding: 8px 16px;
            font-size: 12px;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-add {
            background: #17a2b8;
            color: white;
            margin-top: 10px;
        }

        .btn-add:hover {
            background: #138496;
        }

        .navigation {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
        }
        
        @media (min-width: 768px) {
            .navigation {
                margin-top: 30px;
                padding-top: 30px;
            }
        }

        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 13px;
        }
        
        @media (min-width: 768px) {
            .alert {
                padding: 15px;
                margin-bottom: 20px;
                font-size: 14px;
            }
        }

        .alert-info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }

        .alert-warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }

        .help-text {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
            font-style: italic;
        }

        .required {
            color: #dc3545;
        }

        .section-nav {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-bottom: 20px;
        }
        
        @media (min-width: 768px) {
            .section-nav {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 10px;
                margin-bottom: 30px;
            }
        }

        .section-nav-item {
            padding: 8px 5px;
            background: #f0f0f0;
            border-radius: 6px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 11px;
        }
        
        @media (min-width: 768px) {
            .section-nav-item {
                padding: 10px;
                font-size: 13px;
            }
        }

        .section-nav-item:hover {
            background: #e0e0e0;
        }

        .section-nav-item.completed {
            background: #d4edda;
            color: #155724;
        }

        .section-nav-item.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .summary-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .summary-section h3 {
            color: #333;
            margin-bottom: 15px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-label {
            font-weight: 600;
            color: #555;
        }

        .summary-value {
            color: #333;
        }

        #exportButtons {
            display: flex;
            gap: 8px;
            flex-direction: column;
        }
        
        @media (min-width: 768px) {
            #exportButtons {
                flex-direction: row;
                flex-wrap: wrap;
                gap: 10px;
            }
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s ease;
            overflow-y: auto;
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 12px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .modal-header {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
        }

        .modal-body {
            margin-bottom: 20px;
            color: #555;
        }

        .modal-footer {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        /* Additional styles for auto-save indicator */
        .auto-save-indicator {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            font-size: 14px;
            display: none;
            z-index: 9999;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .auto-save-indicator.saving {
            background: #ffc107;
            display: block;
        }

        .auto-save-indicator.saved {
            background: #28a745;
            display: block;
        }
    </style>
@endpush

<div class="container">
    <div class="header">
        <h1>Harbor Law Estate Planning Intake Form</h1>
        <p>Complete all sections to securely submit your information. This will allow us to start drafting your comprehensive estate plan, uniquely tailored to you.</p>
        <div class="progress-bar">
            <div class="progress-fill" id="progressBar"></div>
        </div>
    </div>

    <div class="form-content">
        <form id="intakeForm">
            @csrf
            
                <!-- Section 1: Client Information -->
                <div class="section active" data-section="1">
                    <h2 class="section-title">Section 1: Client Information</h2>
                    
                    <div class="alert alert-info">
                        Please provide accurate information as it will appear in legal documents.
                    </div>

                    <h3 class="section-subtitle">Personal Details</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>First Name <span class="required">*</span></label>
                            <input type="text" name="firstName" required>
                        </div>
                        <div class="form-group">
                            <label>Middle Name</label>
                            <input type="text" name="middleName">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Last Name <span class="required">*</span></label>
                            <input type="text" name="lastName" required>
                        </div>
                        <div class="form-group">
                            <label>Preferred Name</label>
                            <input type="text" name="preferredName">
                            <span class="help-text">If different from legal name</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Date of Birth <span class="required">*</span></label>
                            <input type="date" name="dateOfBirth" required>
                        </div>
                        <div class="form-group">
                            <label>Social Security Number</label>
                            <input type="text" name="ssn" placeholder="XXX-XX-XXXX">
                            <span class="help-text">Optional but helpful for tax purposes</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Marital Status <span class="required">*</span></label>
                        <select name="maritalStatus" required>
                            <option value="">Select...</option>
                            <option value="single">Single</option>
                            <option value="married">Married</option>
                            <option value="divorced">Divorced</option>
                            <option value="widowed">Widowed</option>
                            <option value="domestic_partnership">Domestic Partnership</option>
                        </select>
                    </div>

                    <h3 class="section-subtitle">Contact Information</h3>

                    <div class="form-group">
                        <label>Street Address <span class="required">*</span></label>
                        <input type="text" name="streetAddress" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>City <span class="required">*</span></label>
                            <input type="text" name="city" required>
                        </div>
                        <div class="form-group">
                            <label>County <span class="required">*</span></label>
                            <input type="text" name="county" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>State <span class="required">*</span></label>
                            <input type="text" name="state" value="Michigan" required>
                        </div>
                        <div class="form-group">
                            <label>ZIP Code <span class="required">*</span></label>
                            <input type="text" name="zipCode" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Mailing Address</label>
                        <input type="text" name="mailingAddress">
                        <span class="help-text">If different from physical address</span>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Primary Phone <span class="required">*</span></label>
                            <input type="tel" name="primaryPhone" required>
                        </div>
                        <div class="form-group">
                            <label>Secondary Phone</label>
                            <input type="tel" name="secondaryPhone">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Email <span class="required">*</span></label>
                            <input type="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label>Occupation</label>
                            <input type="text" name="occupation">
                        </div>
                    </div>

                    <div id="spouseSection" style="display: none;">
                        <h3 class="section-subtitle">Spouse Information</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Spouse's Full Name</label>
                                <input type="text" name="spouseName">
                            </div>
                            <div class="form-group">
                                <label>Spouse's Date of Birth</label>
                                <input type="date" name="spouseDOB">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Spouse's SSN</label>
                                <input type="text" name="spouseSSN">
                            </div>
                            <div class="form-group">
                                <label>Date of Marriage</label>
                                <input type="date" name="marriageDate">
                            </div>
                        </div>

                        <div class="checkbox-group">
                            <input type="checkbox" name="prenuptialAgreement" id="prenup">
                            <label for="prenup">Prenuptial or Postnuptial Agreement exists</label>
                        </div>
                    </div>

                    <div class="navigation">
                        <button type="button" class="btn btn-secondary" disabled>Previous</button>
                        <button type="button" class="btn btn-primary" onclick="nextSection()">Next</button>
                    </div>
                </div>

                <!-- Section 2: Children & Descendants -->
                <div class="section" data-section="2">
                    <h2 class="section-title">Section 2: Children & Descendants</h2>

                    <div class="alert alert-info">
                        Include all biological, adopted, and stepchildren. You can add additional children using the "Add Child" button.
                    </div>

                    <div id="childrenContainer"></div>

                    <button type="button" class="btn btn-add" onclick="addChild()">+ Add Child</button>

                    <h3 class="section-subtitle">Other Potential Beneficiaries</h3>

                    <div class="form-group">
                        <label>Other Family Members to Include</label>
                        <textarea name="otherFamilyBeneficiaries" placeholder="Names and relationships"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Friends to Include</label>
                        <textarea name="friendBeneficiaries" placeholder="Names and contact information"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Charitable Organizations</label>
                        <textarea name="charityBeneficiaries" placeholder="Organization names"></textarea>
                    </div>

                    <div class="navigation">
                        <button type="button" class="btn btn-secondary" onclick="prevSection()">Previous</button>
                        <button type="button" class="btn btn-primary" onclick="nextSection()">Next</button>
                    </div>
                </div>

                <!-- Section 3: Fiduciary Appointments -->
                <div class="section" data-section="3">
                    <h2 class="section-title">Section 3: Fiduciary Appointments</h2>

                    <div class="alert alert-warning">
                        Choose trusted individuals who are at least 18 years old. List alternates in order of preference.
                    </div>

                    <h3 class="section-subtitle">Trustee Selection</h3>
                    <p style="color: #555; margin-bottom: 15px;">The trustee manages trust assets during your lifetime and distributes them after your death.</p>

                    <div id="trusteesContainer"></div>
                    <button type="button" class="btn btn-add" onclick="addTrustee()">+ Add Alternate Trustee</button>

                    <h3 class="section-subtitle">Personal Representative (Executor)</h3>
                    <p style="color: #555; margin-bottom: 15px;">The personal representative administers your estate through probate.</p>

                    <div id="executorsContainer"></div>
                    <button type="button" class="btn btn-add" onclick="addExecutor()">+ Add Alternate Executor</button>

                    <h3 class="section-subtitle">Agent Under Financial Power of Attorney</h3>
                    <p style="color: #555; margin-bottom: 15px;">This person manages your financial affairs if you become incapacitated.</p>

                    <div id="financialAgentsContainer"></div>
                    <button type="button" class="btn btn-add" onclick="addFinancialAgent()">+ Add Alternate Agent</button>

                    <h3 class="section-subtitle">Patient Advocate (Healthcare Power of Attorney)</h3>
                    <p style="color: #555; margin-bottom: 15px;">This person makes healthcare decisions for you if you cannot.</p>

                    <div id="healthcareAgentsContainer"></div>
                    <button type="button" class="btn btn-add" onclick="addHealthcareAgent()">+ Add Alternate Advocate</button>

                    <h3 class="section-subtitle">Guardian for Minor Children</h3>
                    <p style="color: #555; margin-bottom: 15px;">If applicable, who should care for your minor children?</p>

                    <div id="guardiansContainer"></div>
                    <button type="button" class="btn btn-add" onclick="addGuardian()">+ Add Alternate Guardian</button>

                    <div class="navigation">
                        <button type="button" class="btn btn-secondary" onclick="prevSection()">Previous</button>
                        <button type="button" class="btn btn-primary" onclick="nextSection()">Next</button>
                    </div>
                </div>

                <!-- Section 4: Asset Inventory -->
                <div class="section" data-section="4">
                    <h2 class="section-title">Section 4: Asset Inventory</h2>

                    <div class="alert alert-info">
                        List all your assets. This helps ensure everything is properly included in your estate plan.
                    </div>

                    <h3 class="section-subtitle">Real Property</h3>
                    <div id="realPropertyContainer"></div>
                    <button type="button" class="btn btn-add" onclick="addRealProperty()">+ Add Property</button>

                    <h3 class="section-subtitle">Bank & Financial Accounts</h3>
                    <div id="bankAccountsContainer"></div>
                    <button type="button" class="btn btn-add" onclick="addBankAccount()">+ Add Account</button>

                    <h3 class="section-subtitle">Investment Accounts</h3>
                    <div id="investmentAccountsContainer"></div>
                    <button type="button" class="btn btn-add" onclick="addInvestmentAccount()">+ Add Account</button>

                    <h3 class="section-subtitle">Retirement Accounts</h3>
                    <div id="retirementAccountsContainer"></div>
                    <button type="button" class="btn btn-add" onclick="addRetirementAccount()">+ Add Account</button>

                    <h3 class="section-subtitle">Life Insurance Policies</h3>
                    <div id="lifeInsuranceContainer"></div>
                    <button type="button" class="btn btn-add" onclick="addLifeInsurance()">+ Add Policy</button>

                    <h3 class="section-subtitle">Business Interests</h3>
                    <div id="businessInterestsContainer"></div>
                    <button type="button" class="btn btn-add" onclick="addBusinessInterest()">+ Add Business</button>

                    <h3 class="section-subtitle">Vehicles</h3>
                    <div id="vehiclesContainer"></div>
                    <button type="button" class="btn btn-add" onclick="addVehicle()">+ Add Vehicle</button>

                    <h3 class="section-subtitle">Valuable Personal Property</h3>
                    <div class="form-group">
                        <label>Firearms</label>
                        <textarea name="firearms" placeholder="Make, model, estimated value"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Jewelry</label>
                        <textarea name="jewelry" placeholder="Description and estimated value"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Artwork & Collections</label>
                        <textarea name="artwork" placeholder="Description and estimated value"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Other Valuable Items</label>
                        <textarea name="otherValuables" placeholder="Antiques, heirlooms, etc."></textarea>
                    </div>

                    <h3 class="section-subtitle">Digital Assets</h3>
                    <div class="form-group">
                        <label>Digital Asset Inventory</label>
                        <textarea name="digitalAssets" placeholder="Email accounts, social media, cryptocurrency, online businesses, cloud storage, etc."></textarea>
                    </div>

                    <div class="navigation">
                        <button type="button" class="btn btn-secondary" onclick="prevSection()">Previous</button>
                        <button type="button" class="btn btn-primary" onclick="nextSection()">Next</button>
                    </div>
                </div>

                <!-- Section 5: Liabilities -->
                <div class="section" data-section="5">
                    <h2 class="section-title">Section 5: Liabilities</h2>

                    <div class="alert alert-info">
                        List all debts and financial obligations.
                    </div>

                    <div id="liabilitiesContainer"></div>
                    <button type="button" class="btn btn-add" onclick="addLiability()">+ Add Debt/Liability</button>

                    <div class="navigation">
                        <button type="button" class="btn btn-secondary" onclick="prevSection()">Previous</button>
                        <button type="button" class="btn btn-primary" onclick="nextSection()">Next</button>
                    </div>
                </div>

                <!-- Section 6: Distribution Plan -->
                <div class="section" data-section="6">
                    <h2 class="section-title">Section 6: Distribution Plan</h2>

                    <div class="alert alert-info">
                        Specify how you want your assets distributed and any special conditions.
                    </div>

                    <h3 class="section-subtitle">Specific Gifts</h3>
                    <div id="specificGiftsContainer"></div>
                    <button type="button" class="btn btn-add" onclick="addSpecificGift()">+ Add Specific Gift</button>

                    <h3 class="section-subtitle">Firearms Distribution</h3>
                    <div class="form-group">
                        <label>Recipient of Firearms</label>
                        <input type="text" name="firearmsRecipient">
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" name="firearmsFiveYearRule" id="fiveYear">
                        <label for="fiveYear">If sold within 5 years, proceeds divided equally among all beneficiaries</label>
                    </div>

                    <h3 class="section-subtitle">Tangible Personal Property</h3>
                    <div class="form-group">
                        <label>How should household items be distributed?</label>
                        <select name="tangiblePropertyMethod">
                            <option value="">Select...</option>
                            <option value="equal_rotation">Equal shares with rotation selection</option>
                            <option value="equal_value">Equal value distribution</option>
                            <option value="as_agreed">As beneficiaries mutually agree</option>
                            <option value="memorandum">Per separate memorandum</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Specific Items for Specific People</label>
                        <textarea name="specificTangibleProperty" placeholder="e.g., 'Grandmother's ring to daughter Sarah'"></textarea>
                    </div>

                    <h3 class="section-subtitle">Primary Distribution</h3>
                    <div class="form-group">
                        <label>Distribution Method</label>
                        <select name="distributionMethod">
                            <option value="">Select...</option>
                            <option value="equal_children">Equally among all children</option>
                            <option value="per_stirpes">Per stirpes (by bloodline)</option>
                            <option value="custom">Custom percentages</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Custom Distribution Details</label>
                        <textarea name="customDistribution" placeholder="Specify percentages or special arrangements"></textarea>
                    </div>

                    <h3 class="section-subtitle">Age-Based Distribution Schedule</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Age for 1/3 Withdrawal</label>
                            <input type="number" name="ageOneThird" value="18">
                        </div>
                        <div class="form-group">
                            <label>Age for 1/2 Withdrawal</label>
                            <input type="number" name="ageOneHalf" value="21">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Age for Full Distribution</label>
                        <input type="number" name="ageFull" value="25">
                    </div>

                    <h3 class="section-subtitle">Special Circumstances</h3>
                    <div class="form-group">
                        <label>Beneficiaries with Special Needs</label>
                        <textarea name="specialNeedsBeneficiaries" placeholder="Name and needs description"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Other Special Circumstances</label>
                        <textarea name="specialCircumstances" placeholder="Substance abuse, financial difficulties, estrangement, etc."></textarea>
                    </div>

                    <div class="navigation">
                        <button type="button" class="btn btn-secondary" onclick="prevSection()">Previous</button>
                        <button type="button" class="btn btn-primary" onclick="nextSection()">Next</button>
                    </div>
                </div>

                <!-- Section 7: Healthcare Preferences -->
                <div class="section" data-section="7">
                    <h2 class="section-title">Section 7: Healthcare Preferences</h2>

                    <div class="alert alert-warning">
                        These important decisions guide your healthcare if you cannot communicate your wishes.
                    </div>

                    <h3 class="section-subtitle">End-of-Life Care</h3>

                    <div class="checkbox-group">
                        <input type="checkbox" name="noLifeProlonging" id="noLife">
                        <label for="noLife">Do not prolong life if terminally ill or in persistent vegetative state</label>
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" name="dnr" id="dnr">
                        <label for="dnr">Do Not Resuscitate (DNR) preference</label>
                    </div>

                    <div class="form-group">
                        <label>Additional End-of-Life Instructions</label>
                        <textarea name="endOfLifeInstructions" placeholder="Any specific preferences or values to guide decisions"></textarea>
                    </div>

                    <h3 class="section-subtitle">Pain Management</h3>

                    <div class="checkbox-group">
                        <input type="checkbox" name="painReliefPriority" id="painRelief">
                        <label for="painRelief">Prioritize pain relief even if it may hasten death</label>
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" name="alternativeTherapies" id="altTherapy">
                        <label for="altTherapy">Open to alternative/unconventional pain therapies</label>
                    </div>

                    <h3 class="section-subtitle">Organ Donation</h3>

                    <div class="checkbox-group">
                        <input type="checkbox" name="organDonor" id="organDonor">
                        <label for="organDonor">I wish to be an organ donor</label>
                    </div>

                    <div class="form-group">
                        <label>Specific Organs or All</label>
                        <input type="text" name="organSpecifics" placeholder="e.g., 'All organs' or 'Kidneys, liver only'">
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" name="bodyDonation" id="bodyDonation">
                        <label for="bodyDonation">Donate body to medical science</label>
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" name="registeredDonor" id="registered">
                        <label for="registered">Already registered with donor registry</label>
                    </div>

                    <h3 class="section-subtitle">Mental Health Treatment</h3>

                    <div class="form-group">
                        <label>Mental Health Treatment Preferences</label>
                        <textarea name="mentalHealthPreferences" placeholder="Preferences for psychiatric treatment, hospitalization, etc."></textarea>
                    </div>

                    <div class="navigation">
                        <button type="button" class="btn btn-secondary" onclick="prevSection()">Previous</button>
                        <button type="button" class="btn btn-primary" onclick="nextSection()">Next</button>
                    </div>
                </div>

                <!-- Section 8: Additional Information -->
                <div class="section" data-section="8">
                    <h2 class="section-title">Section 8: Additional Information</h2>

                    <h3 class="section-subtitle">Existing Estate Planning Documents</h3>

                    <div class="checkbox-group">
                        <input type="checkbox" name="existingWill" id="existWill">
                        <label for="existWill">I have an existing Will</label>
                    </div>
                    <div class="form-group">
                        <label>Will Date and Attorney</label>
                        <input type="text" name="existingWillDetails">
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" name="existingTrust" id="existTrust">
                        <label for="existTrust">I have an existing Trust</label>
                    </div>
                    <div class="form-group">
                        <label>Trust Date and Attorney</label>
                        <input type="text" name="existingTrustDetails">
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" name="existingPOA" id="existPOA">
                        <label for="existPOA">I have an existing Power of Attorney</label>
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" name="revokeAllPrior" id="revokeAll">
                        <label for="revokeAll">I want to revoke all prior estate planning documents</label>
                    </div>

                    <h3 class="section-subtitle">Professional Advisors</h3>

                    <div class="form-group">
                        <label>Attorney</label>
                        <input type="text" name="attorney" placeholder="Name, firm, contact">
                    </div>

                    <div class="form-group">
                        <label>Accountant/CPA</label>
                        <input type="text" name="accountant" placeholder="Name, firm, contact">
                    </div>

                    <div class="form-group">
                        <label>Financial Advisor</label>
                        <input type="text" name="financialAdvisor" placeholder="Name, firm, contact">
                    </div>

                    <div class="form-group">
                        <label>Insurance Agent</label>
                        <input type="text" name="insuranceAgent" placeholder="Name, firm, contact">
                    </div>

                    <h3 class="section-subtitle">Funeral/Burial Preferences</h3>

                    <div class="form-group">
                        <label>Preference</label>
                        <select name="burialPreference">
                            <option value="">Select...</option>
                            <option value="burial">Burial</option>
                            <option value="cremation">Cremation</option>
                            <option value="no_preference">No Preference</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Preferred Location or Cemetery</label>
                        <input type="text" name="burialLocation">
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" name="prePaidFuneral" id="prePaid">
                        <label for="prePaid">Pre-paid funeral arrangements exist</label>
                    </div>

                    <div class="form-group">
                        <label>Special Funeral Wishes</label>
                        <textarea name="funeralWishes"></textarea>
                    </div>

                    <h3 class="section-subtitle">Personal Statement</h3>

                    <div class="form-group">
                        <label>Message to Your Loved Ones</label>
                        <textarea name="personalStatement" placeholder="Values, reasons for decisions, or anything you'd like to communicate"></textarea>
                    </div>

                    <h3 class="section-subtitle">Questions or Concerns</h3>

                    <div class="form-group">
                        <label>Anything Else We Should Know?</label>
                        <textarea name="additionalNotes" placeholder="Special situations, family dynamics, or questions"></textarea>
                    </div>

                    <div class="navigation">
                        <button type="button" class="btn btn-secondary" onclick="prevSection()">Previous</button>
                        <button type="button" class="btn btn-primary" onclick="nextSection()">Next</button>
                    </div>
                </div>

                <!-- Section 9: Review & Export -->
                <div class="section" data-section="9">
                    <h2 class="section-title">Section 9: Review & Export</h2>

                    <div class="alert alert-info">
                        Review your information and export as JSON to use with your estate planning software.
                    </div>

                    <div id="reviewSummary"></div>

                    <div id="exportButtons">
                        <button type="button" class="btn btn-success" onclick="submitToHarborLaw()">📧 Submit to Harbor Law</button>
                        <button type="button" class="btn btn-primary" onclick="exportJSON()">📥 Download JSON Backup</button>
                    </div>

                    <div class="alert alert-info" style="margin-top: 20px;">
                        <strong>Next Steps:</strong><br>
                        • Click <strong>"Submit to Harbor Law"</strong> to securely send your completed intake form to our office<br>
                        • Optionally <strong>download a backup copy</strong> for your records<br>
                        • You will receive a confirmation after submission
                    </div>

                    <div class="navigation">
                        <button type="button" class="btn btn-secondary" onclick="prevSection()">Previous</button>
                        <button type="button" class="btn btn-primary" onclick="location.reload()">Start New Form</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">✅ Success!</div>
            <div class="modal-body" id="modalMessage"></div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="closeModal()">OK</button>
            </div>
        </div>
    </div>

    <script>
        // Global state
        let currentSection = 1;
        const totalSections = 9;
        let formData = {};

        // Section titles for navigation
        const sectionTitles = [
            'Client Info',
            'Children',
            'Fiduciaries',
            'Assets',
            'Liabilities',
            'Distribution',
            'Healthcare',
            'Additional',
            'Review'
        ];

        // Initialize form
        document.addEventListener('DOMContentLoaded', function() {
            // Check if user just submitted
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('submitted') === 'true') {
                showModal('✅ Thank you! Your estate planning intake form has been successfully submitted to Harbor Law. We will review your information and contact you soon.');
                // Clean URL
                window.history.replaceState({}, document.title, window.location.pathname);
            }

            createSectionNav();
            updateProgress();
            addChild(); // Add one child by default
            addTrustee(); // Add primary trustee
            addExecutor(); // Add primary executor
            addFinancialAgent(); // Add primary agent
            addHealthcareAgent(); // Add primary advocate
            addGuardian(); // Add primary guardian
            
            // Show/hide spouse section based on marital status
            document.querySelector('select[name="maritalStatus"]').addEventListener('change', function() {
                const spouseSection = document.getElementById('spouseSection');
                spouseSection.style.display = this.value === 'married' ? 'block' : 'none';
            });

            // Auto-save to localStorage
            setInterval(saveToLocalStorage, 30000); // Every 30 seconds
        });

        // Create section navigation
        function createSectionNav() {
            const nav = document.getElementById('sectionNav');
            sectionTitles.forEach((title, index) => {
                const navItem = document.createElement('div');
                navItem.className = 'section-nav-item';
                if (index === 0) navItem.classList.add('active');
                navItem.textContent = `${index + 1}. ${title}`;
                navItem.onclick = () => goToSection(index + 1);
                nav.appendChild(navItem);
            });
        }

        // Navigation functions
        // Modal dialog function
        function showModal(message) {
            alert(message);
        }

        function nextSection() {
            if (currentSection < totalSections) {
                // Validate current section
                const currentForm = document.querySelector(`.section[data-section="${currentSection}"]`);
                const requiredFields = currentForm.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.style.borderColor = '#dc3545';
                    } else {
                        field.style.borderColor = '#e0e0e0';
                    }
                });

                if (!isValid) {
                    showModal('Please fill in all required fields marked with *');
                    return;
                }

                currentSection++;
                showSection(currentSection);
            }
        }

        function prevSection() {
            if (currentSection > 1) {
                currentSection--;
                showSection(currentSection);
            }
        }

        function goToSection(section) {
            currentSection = section;
            showSection(currentSection);
        }

        function showSection(section) {
            // Hide all sections
            document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
            
            // Show target section
            const targetSection = document.querySelector(`.section[data-section="${section}"]`);
            if (targetSection) {

        </form>

        <!-- Success Modal -->
        <div class="modal" id="successModal">
            <div class="modal-content">
                <div class="modal-header">Success!</div>
                <div class="modal-body" id="modalMessage">
                    Your form has been submitted successfully.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" onclick="closeModal()">Close</button>
                </div>
            </div>
        </div>

        <!-- Auto-save indicator -->
        <div class="auto-save-indicator" id="autoSaveIndicator">
            Saving...
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Form state - load from database
    let currentSection = {{ $submission->current_section ?? 0 }};
    const totalSections = 12;

                targetSection.classList.add('active');
            }

            // Update navigation
            document.querySelectorAll('.section-nav-item').forEach((item, index) => {
                item.classList.remove('active');
                if (index + 1 === section) {
                    item.classList.add('active');
                }
                if (index + 1 < section) {
                    item.classList.add('completed');
                }
            });

            // Update progress bar
            updateProgress();

            // Scroll to top
            window.scrollTo(0, 0);

            // Generate review if on last section
            if (section === totalSections) {
                generateReview();
            }
        }

        function updateProgress() {
            const progress = (currentSection / totalSections) * 100;
            document.getElementById('progressBar').style.width = progress + '%';
        }

        // Dynamic field functions
        function addChild() {
            const container = document.getElementById('childrenContainer');
            const childNum = container.children.length + 1;
            
            const childDiv = document.createElement('div');
            childDiv.className = 'repeater';
            childDiv.innerHTML = `
                <div class="repeater-header">
                    <span class="repeater-title">Child ${childNum}</span>
                    <button type="button" class="btn btn-danger" onclick="this.parentElement.parentElement.remove()">Remove</button>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="child_${childNum}_name">
                    </div>
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" name="child_${childNum}_dob">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Relationship</label>
                        <select name="child_${childNum}_relationship">
                            <option value="biological">Biological</option>
                            <option value="adopted">Adopted</option>
                            <option value="step">Step-child</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="tel" name="child_${childNum}_phone">
                    </div>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="child_${childNum}_address">
                </div>
                <div class="form-group">
                    <label>Special Needs or Considerations</label>
                    <textarea name="child_${childNum}_notes"></textarea>
                </div>
            `;
            container.appendChild(childDiv);
        }

        function addTrustee() {
            const container = document.getElementById('trusteesContainer');
            const num = container.children.length + 1;
            
            const div = document.createElement('div');
            div.className = 'repeater';
            div.innerHTML = `
                <div class="repeater-header">
                    <span class="repeater-title">${num === 1 ? 'Primary' : 'Alternate'} Trustee ${num > 1 ? num - 1 : ''}</span>
                    ${num > 1 ? '<button type="button" class="btn btn-danger" onclick="this.parentElement.parentElement.remove()">Remove</button>' : ''}
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="trustee_${num}_name">
                    </div>
                    <div class="form-group">
                        <label>Relationship</label>
                        <input type="text" name="trustee_${num}_relationship">
                    </div>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="trustee_${num}_address">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="tel" name="trustee_${num}_phone">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="trustee_${num}_email">
                    </div>
                </div>
            `;
            container.appendChild(div);
        }

        function addExecutor() {
            const container = document.getElementById('executorsContainer');
            const num = container.children.length + 1;
            
            const div = document.createElement('div');
            div.className = 'repeater';
            div.innerHTML = `
                <div class="repeater-header">
                    <span class="repeater-title">${num === 1 ? 'Primary' : 'Alternate'} Personal Representative ${num > 1 ? num - 1 : ''}</span>
                    ${num > 1 ? '<button type="button" class="btn btn-danger" onclick="this.parentElement.parentElement.remove()">Remove</button>' : ''}
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="executor_${num}_name">
                    </div>
                    <div class="form-group">
                        <label>Relationship</label>
                        <input type="text" name="executor_${num}_relationship">
                    </div>
                </div>
                <div class="form-group">
                    <label>Contact Information</label>
                    <input type="text" name="executor_${num}_contact">
                </div>
            `;
            container.appendChild(div);
        }

        function addFinancialAgent() {
            const container = document.getElementById('financialAgentsContainer');
            const num = container.children.length + 1;
            
            const div = document.createElement('div');
            div.className = 'repeater';
            div.innerHTML = `
                <div class="repeater-header">
                    <span class="repeater-title">${num === 1 ? 'Primary' : 'Alternate'} Financial Agent ${num > 1 ? num - 1 : ''}</span>
                    ${num > 1 ? '<button type="button" class="btn btn-danger" onclick="this.parentElement.parentElement.remove()">Remove</button>' : ''}
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="financialAgent_${num}_name">
                    </div>
                    <div class="form-group">
                        <label>Relationship</label>
                        <input type="text" name="financialAgent_${num}_relationship">
                    </div>
                </div>
                <div class="form-group">
                    <label>Contact Information</label>
                    <input type="text" name="financialAgent_${num}_contact">
                </div>
            `;
            container.appendChild(div);
        }

        function addHealthcareAgent() {
            const container = document.getElementById('healthcareAgentsContainer');
            const num = container.children.length + 1;
            
            const div = document.createElement('div');
            div.className = 'repeater';
            div.innerHTML = `
                <div class="repeater-header">
                    <span class="repeater-title">${num === 1 ? 'Primary' : 'Alternate'} Patient Advocate ${num > 1 ? num - 1 : ''}</span>
                    ${num > 1 ? '<button type="button" class="btn btn-danger" onclick="this.parentElement.parentElement.remove()">Remove</button>' : ''}
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="healthcareAgent_${num}_name">
                    </div>
                    <div class="form-group">
                        <label>Relationship</label>
                        <input type="text" name="healthcareAgent_${num}_relationship">
                    </div>
                </div>
                <div class="form-group">
                    <label>Contact Information</label>
                    <input type="text" name="healthcareAgent_${num}_contact">
                </div>
            `;
            container.appendChild(div);
        }

        function addGuardian() {
            const container = document.getElementById('guardiansContainer');
            const num = container.children.length + 1;
            
            const div = document.createElement('div');
            div.className = 'repeater';
            div.innerHTML = `
                <div class="repeater-header">
                    <span class="repeater-title">${num === 1 ? 'Primary' : 'Alternate'} Guardian ${num > 1 ? num - 1 : ''}</span>
                    ${num > 1 ? '<button type="button" class="btn btn-danger" onclick="this.parentElement.parentElement.remove()">Remove</button>' : ''}
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="guardian_${num}_name">
                    </div>
                    <div class="form-group">
                        <label>Relationship</label>
                        <input type="text" name="guardian_${num}_relationship">
                    </div>
                </div>
                <div class="form-group">
                    <label>Contact Information</label>
                    <input type="text" name="guardian_${num}_contact">
                </div>
            `;
            container.appendChild(div);
        }

        function addRealProperty() {
            const container = document.getElementById('realPropertyContainer');
            const num = container.children.length + 1;
            
            const div = document.createElement('div');
            div.className = 'repeater';
            div.innerHTML = `
                <div class="repeater-header">
                    <span class="repeater-title">Property ${num}</span>
                    <button type="button" class="btn btn-danger" onclick="this.parentElement.parentElement.remove()">Remove</button>
                </div>
                <div class="form-group">
                    <label>Property Address</label>
                    <input type="text" name="property_${num}_address">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>County</label>
                        <input type="text" name="property_${num}_county">
                    </div>
                    <div class="form-group">
                        <label>Tax ID/Parcel Number</label>
                        <input type="text" name="property_${num}_taxid">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Estimated Value</label>
                        <input type="number" name="property_${num}_value">
                    </div>
                    <div class="form-group">
                        <label>Mortgage Balance</label>
                        <input type="number" name="property_${num}_mortgage">
                    </div>
                </div>
                <div class="form-group">
                    <label>How Titled</label>
                    <select name="property_${num}_title">
                        <option value="individual">Individual</option>
                        <option value="joint">Joint Tenancy</option>
                        <option value="tenancy_common">Tenancy in Common</option>
                    </select>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="property_${num}_primary" id="prop_${num}_primary">
                    <label for="prop_${num}_primary">Primary Residence</label>
                </div>
            `;
            container.appendChild(div);
        }

        function addBankAccount() {
            const container = document.getElementById('bankAccountsContainer');
            const num = container.children.length + 1;
            
            const div = document.createElement('div');
            div.className = 'repeater';
            div.innerHTML = `
                <div class="repeater-header">
                    <span class="repeater-title">Bank Account ${num}</span>
                    <button type="button" class="btn btn-danger" onclick="this.parentElement.parentElement.remove()">Remove</button>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Institution Name</label>
                        <input type="text" name="bank_${num}_institution">
                    </div>
                    <div class="form-group">
                        <label>Account Type</label>
                        <select name="bank_${num}_type">
                            <option value="checking">Checking</option>
                            <option value="savings">Savings</option>
                            <option value="money_market">Money Market</option>
                            <option value="cd">Certificate of Deposit</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Last 4 Digits of Account</label>
                        <input type="text" name="bank_${num}_account" maxlength="4">
                    </div>
                    <div class="form-group">
                        <label>Approximate Balance</label>
                        <input type="number" name="bank_${num}_balance">
                    </div>
                </div>
                <div class="form-group">
                    <label>Current Beneficiary</label>
                    <input type="text" name="bank_${num}_beneficiary">
                </div>
            `;
            container.appendChild(div);
        }

        function addInvestmentAccount() {
            const container = document.getElementById('investmentAccountsContainer');
            const num = container.children.length + 1;
            
            const div = document.createElement('div');
            div.className = 'repeater';
            div.innerHTML = `
                <div class="repeater-header">
                    <span class="repeater-title">Investment Account ${num}</span>
                    <button type="button" class="btn btn-danger" onclick="this.parentElement.parentElement.remove()">Remove</button>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Institution Name</label>
                        <input type="text" name="investment_${num}_institution">
                    </div>
                    <div class="form-group">
                        <label>Account Type</label>
                        <input type="text" name="investment_${num}_type" placeholder="Brokerage, Mutual Fund, etc.">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Last 4 Digits</label>
                        <input type="text" name="investment_${num}_account" maxlength="4">
                    </div>
                    <div class="form-group">
                        <label>Approximate Value</label>
                        <input type="number" name="investment_${num}_value">
                    </div>
                </div>
                <div class="form-group">
                    <label>Current Beneficiary</label>
                    <input type="text" name="investment_${num}_beneficiary">
                </div>
            `;
            container.appendChild(div);
        }

        function addRetirementAccount() {
            const container = document.getElementById('retirementAccountsContainer');
            const num = container.children.length + 1;
            
            const div = document.createElement('div');
            div.className = 'repeater';
            div.innerHTML = `
                <div class="repeater-header">
                    <span class="repeater-title">Retirement Account ${num}</span>
                    <button type="button" class="btn btn-danger" onclick="this.parentElement.parentElement.remove()">Remove</button>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Account Type</label>
                        <select name="retirement_${num}_type">
                            <option value="ira">Traditional IRA</option>
                            <option value="roth">Roth IRA</option>
                            <option value="401k">401(k)</option>
                            <option value="403b">403(b)</option>
                            <option value="pension">Pension</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Institution</label>
                        <input type="text" name="retirement_${num}_institution">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Approximate Value</label>
                        <input type="number" name="retirement_${num}_value">
                    </div>
                    <div class="form-group">
                        <label>Primary Beneficiary</label>
                        <input type="text" name="retirement_${num}_primary_beneficiary">
                    </div>
                </div>
                <div class="form-group">
                    <label>Contingent Beneficiary</label>
                    <input type="text" name="retirement_${num}_contingent_beneficiary">
                </div>
            `;
            container.appendChild(div);
        }

        function addLifeInsurance() {
            const container = document.getElementById('lifeInsuranceContainer');
            const num = container.children.length + 1;
            
            const div = document.createElement('div');
            div.className = 'repeater';
            div.innerHTML = `
                <div class="repeater-header">
                    <span class="repeater-title">Life Insurance Policy ${num}</span>
                    <button type="button" class="btn btn-danger" onclick="this.parentElement.parentElement.remove()">Remove</button>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Insurance Company</label>
                        <input type="text" name="insurance_${num}_company">
                    </div>
                    <div class="form-group">
                        <label>Policy Number</label>
                        <input type="text" name="insurance_${num}_policy">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Policy Type</label>
                        <select name="insurance_${num}_type">
                            <option value="term">Term</option>
                            <option value="whole">Whole Life</option>
                            <option value="universal">Universal</option>
                            <option value="variable">Variable</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Death Benefit</label>
                        <input type="number" name="insurance_${num}_benefit">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Primary Beneficiary</label>
                        <input type="text" name="insurance_${num}_primary">
                    </div>
                    <div class="form-group">
                        <label>Contingent Beneficiary</label>
                        <input type="text" name="insurance_${num}_contingent">
                    </div>
                </div>
            `;
            container.appendChild(div);
        }

        function addBusinessInterest() {
            const container = document.getElementById('businessInterestsContainer');
            const num = container.children.length + 1;
            
            const div = document.createElement('div');
            div.className = 'repeater';
            div.innerHTML = `
                <div class="repeater-header">
                    <span class="repeater-title">Business Interest ${num}</span>
                    <button type="button" class="btn btn-danger" onclick="this.parentElement.parentElement.remove()">Remove</button>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Business Name</label>
                        <input type="text" name="business_${num}_name">
                    </div>
                    <div class="form-group">
                        <label>Structure</label>
                        <select name="business_${num}_structure">
                            <option value="sole_proprietor">Sole Proprietor</option>
                            <option value="partnership">Partnership</option>
                            <option value="llc">LLC</option>
                            <option value="corporation">Corporation</option>
                            <option value="s_corp">S-Corporation</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Ownership Percentage</label>
                        <input type="number" name="business_${num}_ownership" min="0" max="100">
                    </div>
                    <div class="form-group">
                        <label>Estimated Value</label>
                        <input type="number" name="business_${num}_value">
                    </div>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="business_${num}_agreement" id="bus_${num}_agreement">
                    <label for="bus_${num}_agreement">Buy-Sell Agreement Exists</label>
                </div>
            `;
            container.appendChild(div);
        }

        function addVehicle() {
            const container = document.getElementById('vehiclesContainer');
            const num = container.children.length + 1;
            
            const div = document.createElement('div');
            div.className = 'repeater';
            div.innerHTML = `
                <div class="repeater-header">
                    <span class="repeater-title">Vehicle ${num}</span>
                    <button type="button" class="btn btn-danger" onclick="this.parentElement.parentElement.remove()">Remove</button>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Year</label>
                        <input type="number" name="vehicle_${num}_year">
                    </div>
                    <div class="form-group">
                        <label>Make</label>
                        <input type="text" name="vehicle_${num}_make">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Model</label>
                        <input type="text" name="vehicle_${num}_model">
                    </div>
                    <div class="form-group">
                        <label>Estimated Value</label>
                        <input type="number" name="vehicle_${num}_value">
                    </div>
                </div>
                <div class="form-group">
                    <label>Outstanding Loan Balance</label>
                    <input type="number" name="vehicle_${num}_loan">
                </div>
            `;
            container.appendChild(div);
        }

        function addLiability() {
            const container = document.getElementById('liabilitiesContainer');
            const num = container.children.length + 1;
            
            const div = document.createElement('div');
            div.className = 'repeater';
            div.innerHTML = `
                <div class="repeater-header">
                    <span class="repeater-title">Liability ${num}</span>
                    <button type="button" class="btn btn-danger" onclick="this.parentElement.parentElement.remove()">Remove</button>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Type</label>
                        <select name="liability_${num}_type">
                            <option value="mortgage">Mortgage</option>
                            <option value="auto_loan">Auto Loan</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="student_loan">Student Loan</option>
                            <option value="personal_loan">Personal Loan</option>
                            <option value="business_loan">Business Loan</option>
                            <option value="medical">Medical Debt</option>
                            <option value="tax">Tax Obligation</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Lender/Creditor</label>
                        <input type="text" name="liability_${num}_lender">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Balance Owed</label>
                        <input type="number" name="liability_${num}_balance">
                    </div>
                    <div class="form-group">
                        <label>Monthly Payment</label>
                        <input type="number" name="liability_${num}_payment">
                    </div>
                </div>
            `;
            container.appendChild(div);
        }

        function addSpecificGift() {
            const container = document.getElementById('specificGiftsContainer');
            const num = container.children.length + 1;
            
            const div = document.createElement('div');
            div.className = 'repeater';
            div.innerHTML = `
                <div class="repeater-header">
                    <span class="repeater-title">Specific Gift ${num}</span>
                    <button type="button" class="btn btn-danger" onclick="this.parentElement.parentElement.remove()">Remove</button>
                </div>
                <div class="form-group">
                    <label>Description of Item/Property</label>
                    <textarea name="gift_${num}_description"></textarea>
                </div>
                <div class="form-group">
                    <label>Recipient Name and Relationship</label>
                    <input type="text" name="gift_${num}_recipient">
                </div>
                <div class="form-group">
                    <label>Conditions (if any)</label>
                    <input type="text" name="gift_${num}_conditions">
                </div>
                <div class="form-group">
                    <label>If recipient predeceases you</label>
                    <select name="gift_${num}_contingency">
                        <option value="lapse">Gift lapses (goes to residue)</option>
                        <option value="descendants">To recipient's descendants</option>
                        <option value="alternate">To alternate (specify below)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Alternate Recipient (if selected above)</label>
                    <input type="text" name="gift_${num}_alternate">
                </div>
            `;
            container.appendChild(div);
        }

        // Generate review summary
        function generateReview() {
            const form = document.getElementById('intakeForm');
            const formDataObj = new FormData(form);
            const data = {};
            
            for (let [key, value] of formDataObj.entries()) {
                data[key] = value;
            }

            // Also get checkbox values
            const checkboxes = form.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(cb => {
                data[cb.name] = cb.checked;
            });

            formData = data;



    // LARAVEL-SPECIFIC FUNCTIONS - Override original functions

    // Load saved data from database on page load
    window.addEventListener('DOMContentLoaded', function() {
        loadSavedData();
        updateProgressBar();
        initializeSectionNavigation();
        
        if (currentSection > 0) {
            goToSection(currentSection);
        }

        // Setup auto-save on form changes
        const form = document.getElementById('intakeForm');
        if (form) {
            let autoSaveTimeout;
            form.addEventListener('input', function() {
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(saveProgress, 2000);
            });
            form.addEventListener('change', function() {
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(saveProgress, 2000);
            });
        }
    });

    // Load saved data from Laravel backend
    function loadSavedData() {
        const savedData = @json($submission->form_data ?? []);
        const userData = @json([
            'firstName' => $user->first_name ?? '',
            'lastName' => $user->last_name ?? '',
            'email' => $user->email ?? '',
        ]);
        
        // Pre-fill user data if form is empty
        if (Object.keys(savedData).length === 0) {
            Object.keys(userData).forEach(key => {
                const field = document.querySelector(`[name="${key}"]`);
                if (field && userData[key] && !field.value) {
                    field.value = userData[key];
                }
            });
            console.log('✓ Pre-filled user data from account');
        }
        
        // Load saved form data (overrides prefilled data)
        if (Object.keys(savedData).length > 0) {
            Object.keys(savedData).forEach(key => {
                const field = document.querySelector(`[name="${key}"]`);
                if (field) {
                    if (field.type === 'checkbox' || field.type === 'radio') {
                        field.checked = savedData[key];
                    } else {
                        field.value = savedData[key];
                    }
                }
            });
            
            console.log('✓ Form data loaded from database');
        }
    }

    // Save progress to Laravel backend
    function saveProgress() {
        const indicator = document.getElementById('autoSaveIndicator');
        indicator.className = 'auto-save-indicator saving';
        indicator.textContent = 'Saving...';
        
        const formData = new FormData(document.getElementById('intakeForm'));
        const data = {};
        
        for (let [key, value] of formData.entries()) {
            if (key !== '_token') {
                data[key] = value;
            }
        }
        
        // Get checkbox values
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(cb => {
            data[cb.name] = cb.checked;
        });

        const progress = Math.round(((currentSection + 1) / totalSections) * 100);

        fetch('{{ route("intake.save") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                form_data: data,
                current_section: currentSection,
                progress_percentage: progress
            })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                indicator.className = 'auto-save-indicator saved';
                indicator.textContent = '✓ Saved';
                setTimeout(() => {
                    indicator.style.display = 'none';
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Auto-save error:', error);
            indicator.style.display = 'none';
        });
    }

    // OVERRIDE: Replace the original submitToHarborLaw with Laravel submit
    function submitToHarborLaw() {
        submitForm();
    }

    // Submit form to Laravel
    function submitForm() {
        if (!confirm('Are you sure you want to submit your intake form? You can still edit it later if needed.')) {
            return;
        }

        const formData = new FormData(document.getElementById('intakeForm'));
        const data = {};
        
        for (let [key, value] of formData.entries()) {
            if (key !== '_token') {
                data[key] = value;
            }
        }
        
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(cb => {
            data[cb.name] = cb.checked;
        });

        fetch('{{ route("intake.submit") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ form_data: data })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                showModal('Your intake form has been submitted successfully!');
                setTimeout(() => {
                    window.location.href = '{{ route("dashboard") }}';
                }, 2000);
            } else {
                alert('Error submitting form. Please try again.');
            }
        })
        .catch(error => {
            console.error('Submit error:', error);
            alert('Error submitting form. Please try again.');
        });
    }

    // OVERRIDE: Remove localStorage functions (use database instead)
    function saveToLocalStorage() {
        // Disabled - we use database auto-save instead
        console.log('Using database auto-save instead of localStorage');
    }

    function loadFromLocalStorage() {
        // Disabled - we load from database instead
        console.log('Loading from database instead of localStorage');
    }

</script>
@endpush

@endsection
