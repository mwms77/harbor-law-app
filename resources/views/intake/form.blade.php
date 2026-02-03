@extends('layouts.app')

@section('title', 'Estate Planning Intake Form')

@section('content')
<div x-data="intakeForm()" x-init="init()" class="intake-wrapper">
    <!-- Header -->
    <div class="intake-header">
        <h1>Estate Planning Intake Form</h1>
        <p>Complete all sections to help us prepare your estate plan</p>
        
        <!-- Progress Bar -->
        <div class="progress-bar">
            <div class="progress-fill" :style="`width: ${progress}%`"></div>
        </div>
        <p class="progress-text">Step <span x-text="step"></span> of <span x-text="totalSteps"></span></p>
    </div>

    <!-- Form -->
    <div class="intake-content">
        <!-- Step 1: Personal Info -->
        <div x-show="step === 1" class="step-content">
            <h2>Personal Information</h2>
            
            <div class="form-row">
                <div class="form-group">
                    <label>First Name *</label>
                    <input type="text" x-model="formData.personal.first_name" required>
                </div>
                <div class="form-group">
                    <label>Middle Name</label>
                    <input type="text" x-model="formData.personal.middle_name">
                </div>
                <div class="form-group">
                    <label>Last Name *</label>
                    <input type="text" x-model="formData.personal.last_name" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Date of Birth *</label>
                    <input type="date" x-model="formData.personal.date_of_birth" required>
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" x-model="formData.personal.email" required>
                </div>
                <div class="form-group">
                    <label>Phone *</label>
                    <input type="tel" x-model="formData.personal.primary_phone" required>
                </div>
            </div>

            <div class="form-group">
                <label>Street Address *</label>
                <input type="text" x-model="formData.personal.street_address" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>City *</label>
                    <input type="text" x-model="formData.personal.city" required>
                </div>
                <div class="form-group">
                    <label>State *</label>
                    <input type="text" x-model="formData.personal.state" required>
                </div>
                <div class="form-group">
                    <label>ZIP Code *</label>
                    <input type="text" x-model="formData.personal.zip_code" required>
                </div>
            </div>

            <div class="form-group">
                <label>Marital Status *</label>
                <select x-model="formData.personal.marital_status" required>
                    <option value="">Select...</option>
                    <option value="single">Single</option>
                    <option value="married">Married</option>
                    <option value="divorced">Divorced</option>
                    <option value="widowed">Widowed</option>
                </select>
            </div>
        </div>

        <!-- Step 2: Spouse Info -->
        <div x-show="step === 2" class="step-content">
            <h2>Spouse Information</h2>
            
            <template x-if="formData.personal.marital_status === 'married'">
                <div>
                    <div class="form-group">
                        <label>Spouse's Full Name</label>
                        <input type="text" x-model="formData.spouse.spouse_name">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Spouse's Date of Birth</label>
                            <input type="date" x-model="formData.spouse.spouse_dob">
                        </div>
                        <div class="form-group">
                            <label>Spouse's Occupation</label>
                            <input type="text" x-model="formData.spouse.spouse_occupation">
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="formData.personal.marital_status !== 'married'">
                <div class="alert-info">
                    This section only applies if you are married. Click Next to continue.
                </div>
            </template>
        </div>

        <!-- Step 3: Children -->
        <div x-show="step === 3" class="step-content">
            <h2>Children & Descendants</h2>
            
            <template x-for="(child, index) in formData.children" :key="index">
                <div class="repeater-item">
                    <div class="repeater-header">
                        <span>Child <span x-text="index + 1"></span></span>
                        <button type="button" @click="removeChild(index)" class="btn-remove">Remove</button>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name *</label>
                            <input type="text" x-model="child.full_name" required>
                        </div>
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" x-model="child.date_of_birth">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Relationship</label>
                            <select x-model="child.relationship">
                                <option value="biological">Biological</option>
                                <option value="adopted">Adopted</option>
                                <option value="step">Step-child</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" x-model="child.minor"> Minor (under 18)
                            </label>
                        </div>
                    </div>
                </div>
            </template>

            <button type="button" @click="addChild()" class="btn-add">+ Add Child</button>
            
            <div x-show="formData.children.length === 0" class="alert-info">
                If you have no children, click Next to continue.
            </div>
        </div>

        <!-- Step 4: Assets -->
        <div x-show="step === 4" class="step-content">
            <h2>Assets</h2>
            
            <template x-for="(asset, index) in formData.assets" :key="index">
                <div class="repeater-item">
                    <div class="repeater-header">
                        <span>Asset <span x-text="index + 1"></span></span>
                        <button type="button" @click="removeAsset(index)" class="btn-remove">Remove</button>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Asset Type *</label>
                            <select x-model="asset.asset_type" required>
                                <option value="real_estate">Real Estate</option>
                                <option value="bank_account">Bank Account</option>
                                <option value="investment">Investment</option>
                                <option value="retirement">Retirement Account</option>
                                <option value="business">Business</option>
                                <option value="vehicle">Vehicle</option>
                                <option value="life_insurance">Life Insurance</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Description *</label>
                            <input type="text" x-model="asset.description" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Estimated Value</label>
                            <input type="number" x-model="asset.estimated_value" step="0.01">
                        </div>
                        <div class="form-group">
                            <label>Ownership</label>
                            <select x-model="asset.ownership">
                                <option value="individual">Individual</option>
                                <option value="joint">Joint</option>
                                <option value="trust">Trust</option>
                            </select>
                        </div>
                    </div>
                </div>
            </template>

            <button type="button" @click="addAsset()" class="btn-add">+ Add Asset</button>
            
            <div x-show="formData.assets.length === 0" class="alert-info">
                If you have no assets to declare, click Next to continue.
            </div>
        </div>

        <!-- Step 5: Liabilities -->
        <div x-show="step === 5" class="step-content">
            <h2>Liabilities & Debts</h2>
            
            <template x-for="(liability, index) in formData.liabilities" :key="index">
                <div class="repeater-item">
                    <div class="repeater-header">
                        <span>Liability <span x-text="index + 1"></span></span>
                        <button type="button" @click="removeLiability(index)" class="btn-remove">Remove</button>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Type *</label>
                            <select x-model="liability.liability_type" required>
                                <option value="mortgage">Mortgage</option>
                                <option value="auto_loan">Auto Loan</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="student_loan">Student Loan</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Lender *</label>
                            <input type="text" x-model="liability.lender" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Balance Owed</label>
                            <input type="number" x-model="liability.balance_owed" step="0.01">
                        </div>
                        <div class="form-group">
                            <label>Monthly Payment</label>
                            <input type="number" x-model="liability.monthly_payment" step="0.01">
                        </div>
                    </div>
                </div>
            </template>

            <button type="button" @click="addLiability()" class="btn-add">+ Add Liability</button>
            
            <div x-show="formData.liabilities.length === 0" class="alert-info">
                If you have no liabilities, click Next to continue.
            </div>
        </div>

        <!-- Step 6: Review -->
        <div x-show="step === 6" class="step-content">
            <h2>Review & Submit</h2>
            
            <div class="review-box">
                <h3>Personal Information</h3>
                <p><strong>Name:</strong> <span x-text="`${formData.personal.first_name} ${formData.personal.last_name}`"></span></p>
                <p><strong>Email:</strong> <span x-text="formData.personal.email"></span></p>
                <p><strong>Phone:</strong> <span x-text="formData.personal.primary_phone"></span></p>
            </div>

            <div x-show="formData.children.length > 0" class="review-box">
                <h3>Children</h3>
                <p><span x-text="formData.children.length"></span> child(ren) listed</p>
            </div>

            <div x-show="formData.assets.length > 0" class="review-box">
                <h3>Assets</h3>
                <p><span x-text="formData.assets.length"></span> asset(s) listed</p>
            </div>

            <div class="alert-success">
                ✓ Ready to submit! Click Submit below to complete your intake form.
            </div>
        </div>

        <!-- Navigation -->
        <div class="navigation-buttons">
            <button type="button" @click="prevStep()" x-show="step > 1" class="btn btn-secondary">
                ← Previous
            </button>

            <button type="button" @click="saveAndExit()" class="btn btn-outline">
                💾 Save & Exit
            </button>

            <button type="button" @click="nextStep()" x-show="step < totalSteps" class="btn btn-primary">
                Next →
            </button>

            <button type="button" @click="submit()" x-show="step === totalSteps" class="btn btn-success">
                ✓ Submit Form
            </button>
        </div>

        <!-- Saving Indicator -->
        <div x-show="saving" class="saving-indicator">
            Saving...
        </div>
    </div>
</div>

<style>
    .intake-wrapper {
        max-width: 900px;
        margin: 0 auto;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .intake-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px;
        text-align: center;
    }

    .intake-header h1 {
        font-size: 32px;
        margin-bottom: 10px;
    }

    .intake-header p {
        opacity: 0.9;
        margin-bottom: 20px;
    }

    .progress-bar {
        background: rgba(255,255,255,0.2);
        height: 8px;
        border-radius: 4px;
        overflow: hidden;
        margin: 20px 0 10px;
    }

    .progress-fill {
        background: white;
        height: 100%;
        transition: width 0.3s;
    }

    .progress-text {
        font-size: 14px;
        opacity: 0.9;
    }

    .intake-content {
        padding: 40px;
    }

    .step-content {
        animation: fadeIn 0.3s;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    h2 {
        color: #333;
        margin-bottom: 30px;
        font-size: 24px;
        border-bottom: 2px solid #667eea;
        padding-bottom: 10px;
    }

    h3 {
        color: #667eea;
        font-size: 18px;
        margin: 15px 0 10px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #555;
        font-weight: 500;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        font-size: 16px;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #667eea;
    }

    .repeater-item {
        background: #f8f9fa;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .repeater-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        font-weight: 600;
        color: #667eea;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102,126,234,0.4);
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-success {
        background: #28a745;
        color: white;
    }

    .btn-outline {
        background: white;
        color: #667eea;
        border: 2px solid #667eea;
    }

    .btn-outline:hover {
        background: #667eea;
        color: white;
    }

    .btn-add {
        background: #17a2b8;
        color: white;
        width: 100%;
        padding: 12px;
        margin-top: 10px;
    }

    .btn-remove {
        background: #dc3545;
        color: white;
        padding: 6px 12px;
        font-size: 14px;
    }

    .navigation-buttons {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-top: 40px;
        padding-top: 20px;
        border-top: 2px solid #e0e0e0;
    }

    .alert-info {
        background: #d1ecf1;
        color: #0c5460;
        padding: 15px;
        border-radius: 6px;
        margin: 20px 0;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: 6px;
        margin: 20px 0;
    }

    .review-box {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .saving-indicator {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #667eea;
        color: white;
        padding: 12px 20px;
        border-radius: 6px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    @media (max-width: 768px) {
        .intake-header {
            padding: 20px;
        }
        .intake-content {
            padding: 20px;
        }
        .form-row {
            grid-template-columns: 1fr;
        }
        .navigation-buttons {
            flex-direction: column;
        }
    }
</style>

<script>
    function intakeForm() {
        return {
            step: 1,
            totalSteps: 6,
            saving: false,
            
            formData: {
                personal: {
                    first_name: '{{ $user->first_name ?? '' }}',
                    middle_name: '',
                    last_name: '{{ $user->last_name ?? '' }}',
                    date_of_birth: '',
                    email: '{{ $user->email ?? '' }}',
                    primary_phone: '',
                    street_address: '',
                    city: '',
                    state: 'Michigan',
                    zip_code: '',
                    marital_status: ''
                },
                spouse: {
                    spouse_name: '',
                    spouse_dob: '',
                    spouse_occupation: '',
                    marriage_date: '',
                    marriage_location: ''
                },
                children: [],
                assets: [],
                liabilities: []
            },

            get progress() {
                return Math.round((this.step / this.totalSteps) * 100);
            },

            init() {
                // Load existing data from backend
                @if($personalInfo)
                    this.formData.personal = @json($personalInfo);
                @endif
                
                @if($spouseInfo)
                    this.formData.spouse = @json($spouseInfo);
                @endif
                
                @if($children && count($children) > 0)
                    this.formData.children = @json($children);
                @endif
                
                @if($assets && count($assets) > 0)
                    this.formData.assets = @json($assets);
                @endif
                
                @if($liabilities && count($liabilities) > 0)
                    this.formData.liabilities = @json($liabilities);
                @endif
            },

            nextStep() {
                if (this.step < this.totalSteps) {
                    this.step++;
                    this.save();
                    window.scrollTo(0, 0);
                }
            },

            prevStep() {
                if (this.step > 1) {
                    this.step--;
                    window.scrollTo(0, 0);
                }
            },

            addChild() {
                this.formData.children.push({
                    full_name: '',
                    date_of_birth: '',
                    relationship: 'biological',
                    minor: false
                });
            },

            removeChild(index) {
                this.formData.children.splice(index, 1);
            },

            addAsset() {
                this.formData.assets.push({
                    asset_type: 'bank_account',
                    description: '',
                    estimated_value: null,
                    ownership: 'individual'
                });
            },

            removeAsset(index) {
                this.formData.assets.splice(index, 1);
            },

            addLiability() {
                this.formData.liabilities.push({
                    liability_type: 'credit_card',
                    lender: '',
                    balance_owed: null,
                    monthly_payment: null
                });
            },

            removeLiability(index) {
                this.formData.liabilities.splice(index, 1);
            },

            save() {
                this.saving = true;

                fetch('{{ route('intake.save-all') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ data: this.formData })
                })
                .then(response => response.json())
                .then(result => {
                    console.log('Saved:', result);
                    setTimeout(() => this.saving = false, 500);
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.saving = false;
                });
            },

            saveAndExit() {
                this.save();
                setTimeout(() => {
                    window.location.href = '{{ route('dashboard') }}';
                }, 1000);
            },

            submit() {
                this.saving = true;

                fetch('{{ route('intake.submit-all') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ data: this.formData })
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert('✓ Your intake form has been submitted successfully!');
                        window.location.href = '{{ route('dashboard') }}';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error submitting form. Please try again.');
                    this.saving = false;
                });
            }
        }
    }
</script>

<!-- Alpine.js from CDN -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

@endsection
