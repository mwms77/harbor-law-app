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
                    <label>County</label>
                    <input type="text" x-model="formData.personal.county">
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

                    <div class="form-row">
                        <div class="form-group">
                            <label>Marriage Date</label>
                            <input type="date" x-model="formData.spouse.marriage_date">
                        </div>
                        <div class="form-group">
                            <label>Marriage Location</label>
                            <input type="text" x-model="formData.spouse.marriage_location">
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

        <!-- Step 6: Fiduciaries -->
        <div x-show="step === 6" class="step-content">
            <h2>Fiduciaries & Decision Makers</h2>
            
            <p class="help-text">Who will manage your affairs and make decisions on your behalf?</p>

            <!-- Trustees -->
            <h3>Trustees</h3>
            <p class="help-text">Who will manage your trust if you become incapacitated or pass away?</p>
            
            <template x-for="(trustee, index) in formData.fiduciaries.trustees" :key="'trustee-' + index">
                <div class="repeater-item">
                    <div class="repeater-header">
                        <span><span x-text="index === 0 ? 'Primary' : 'Successor'"></span> Trustee</span>
                        <button type="button" @click="removeFiduciary('trustees', index)" class="btn-remove" x-show="index > 0">Remove</button>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name *</label>
                            <input type="text" x-model="trustee.full_name" required>
                        </div>
                        <div class="form-group">
                            <label>Relationship</label>
                            <input type="text" x-model="trustee.relationship" placeholder="e.g., Spouse, Child, Friend">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="tel" x-model="trustee.phone">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" x-model="trustee.email">
                        </div>
                    </div>
                </div>
            </template>

            <button type="button" @click="addFiduciary('trustees')" class="btn-add">+ Add Successor Trustee</button>

            <!-- Personal Representative (Executor) -->
            <h3 style="margin-top: 30px;">Personal Representative (Executor)</h3>
            <p class="help-text">Who will handle your estate and ensure your will is carried out?</p>
            
            <template x-for="(executor, index) in formData.fiduciaries.executors" :key="'executor-' + index">
                <div class="repeater-item">
                    <div class="repeater-header">
                        <span><span x-text="index === 0 ? 'Primary' : 'Successor'"></span> Personal Representative</span>
                        <button type="button" @click="removeFiduciary('executors', index)" class="btn-remove" x-show="index > 0">Remove</button>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name *</label>
                            <input type="text" x-model="executor.full_name" required>
                        </div>
                        <div class="form-group">
                            <label>Relationship</label>
                            <input type="text" x-model="executor.relationship">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="tel" x-model="executor.phone">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" x-model="executor.email">
                        </div>
                    </div>
                </div>
            </template>

            <button type="button" @click="addFiduciary('executors')" class="btn-add">+ Add Successor Personal Representative</button>

            <!-- Guardians (if minor children) -->
            <template x-if="hasMinorChildren">
                <div>
                    <h3 style="margin-top: 30px;">Guardian for Minor Children</h3>
                    <p class="help-text">Who will care for your minor children if you pass away?</p>
                    
                    <template x-for="(guardian, index) in formData.fiduciaries.guardians" :key="'guardian-' + index">
                        <div class="repeater-item">
                            <div class="repeater-header">
                                <span><span x-text="index === 0 ? 'Primary' : 'Successor'"></span> Guardian</span>
                                <button type="button" @click="removeFiduciary('guardians', index)" class="btn-remove" x-show="index > 0">Remove</button>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Full Name *</label>
                                    <input type="text" x-model="guardian.full_name" required>
                                </div>
                                <div class="form-group">
                                    <label>Relationship</label>
                                    <input type="text" x-model="guardian.relationship">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="tel" x-model="guardian.phone">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" x-model="guardian.email">
                                </div>
                            </div>
                        </div>
                    </template>

                    <button type="button" @click="addFiduciary('guardians')" class="btn-add">+ Add Successor Guardian</button>
                </div>
            </template>

            <!-- Patient Advocate (Healthcare POA) -->
            <h3 style="margin-top: 30px;">Patient Advocate (Healthcare Power of Attorney)</h3>
            <p class="help-text">Who will make medical decisions for you if you cannot?</p>
            
            <template x-for="(advocate, index) in formData.fiduciaries.patient_advocates" :key="'advocate-' + index">
                <div class="repeater-item">
                    <div class="repeater-header">
                        <span><span x-text="index === 0 ? 'Primary' : 'Successor'"></span> Patient Advocate</span>
                        <button type="button" @click="removeFiduciary('patient_advocates', index)" class="btn-remove" x-show="index > 0">Remove</button>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name *</label>
                            <input type="text" x-model="advocate.full_name" required>
                        </div>
                        <div class="form-group">
                            <label>Relationship</label>
                            <input type="text" x-model="advocate.relationship">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="tel" x-model="advocate.phone">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" x-model="advocate.email">
                        </div>
                    </div>
                </div>
            </template>

            <button type="button" @click="addFiduciary('patient_advocates')" class="btn-add">+ Add Successor Patient Advocate</button>

            <!-- Financial Power of Attorney -->
            <h3 style="margin-top: 30px;">Financial Power of Attorney</h3>
            <p class="help-text">Who will manage your finances if you become incapacitated?</p>
            
            <template x-for="(agent, index) in formData.fiduciaries.financial_agents" :key="'agent-' + index">
                <div class="repeater-item">
                    <div class="repeater-header">
                        <span><span x-text="index === 0 ? 'Primary' : 'Successor'"></span> Financial Agent</span>
                        <button type="button" @click="removeFiduciary('financial_agents', index)" class="btn-remove" x-show="index > 0">Remove</button>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name *</label>
                            <input type="text" x-model="agent.full_name" required>
                        </div>
                        <div class="form-group">
                            <label>Relationship</label>
                            <input type="text" x-model="agent.relationship">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="tel" x-model="agent.phone">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" x-model="agent.email">
                        </div>
                    </div>
                </div>
            </template>

            <button type="button" @click="addFiduciary('financial_agents')" class="btn-add">+ Add Successor Financial Agent</button>
        </div>

        <!-- Step 7: Pet Trust -->
        <div x-show="step === 7" class="step-content">
            <h2>Pet Trust</h2>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" x-model="formData.pets.has_pets"> I have pets that I want to provide for
                </label>
            </div>

            <template x-if="formData.pets.has_pets">
                <div>
                    <template x-for="(pet, index) in formData.pets.pets" :key="'pet-' + index">
                        <div class="repeater-item">
                            <div class="repeater-header">
                                <span>Pet <span x-text="index + 1"></span></span>
                                <button type="button" @click="removePet(index)" class="btn-remove">Remove</button>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Pet's Name</label>
                                    <input type="text" x-model="pet.name">
                                </div>
                                <div class="form-group">
                                    <label>Type/Breed</label>
                                    <input type="text" x-model="pet.type" placeholder="e.g., Golden Retriever, Tabby Cat">
                                </div>
                                <div class="form-group">
                                    <label>Age</label>
                                    <input type="number" x-model="pet.age">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Special Care Instructions</label>
                                <textarea x-model="pet.care_instructions" rows="3" placeholder="Dietary needs, medications, veterinary info, etc."></textarea>
                            </div>
                        </div>
                    </template>

                    <button type="button" @click="addPet()" class="btn-add">+ Add Pet</button>

                    <!-- Pet Caretaker -->
                    <h3 style="margin-top: 30px;">Pet Caretaker</h3>
                    <p class="help-text">Who will care for your pets?</p>
                    
                    <template x-for="(caretaker, index) in formData.pets.caretakers" :key="'caretaker-' + index">
                        <div class="repeater-item">
                            <div class="repeater-header">
                                <span><span x-text="index === 0 ? 'Primary' : 'Backup'"></span> Pet Caretaker</span>
                                <button type="button" @click="removePetCaretaker(index)" class="btn-remove" x-show="index > 0">Remove</button>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" x-model="caretaker.name">
                                </div>
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="tel" x-model="caretaker.phone">
                                </div>
                            </div>
                        </div>
                    </template>

                    <button type="button" @click="addPetCaretaker()" class="btn-add">+ Add Backup Caretaker</button>

                    <!-- Pet Trust Funding -->
                    <h3 style="margin-top: 30px;">Pet Trust Funding</h3>
                    <div class="form-group">
                        <label>Estimated Funding Amount</label>
                        <input type="number" x-model="formData.pets.funding_amount" step="100" placeholder="Amount to set aside for pet care">
                        <small class="help-text">Consider veterinary care, food, boarding, and estimated lifespan</small>
                    </div>

                    <div class="form-group">
                        <label>What should happen to remaining funds after pet(s) pass?</label>
                        <select x-model="formData.pets.remaining_funds">
                            <option value="caretaker">Give to caretaker</option>
                            <option value="charity">Donate to animal charity</option>
                            <option value="estate">Return to estate</option>
                        </select>
                    </div>
                </div>
            </template>

            <div x-show="!formData.pets.has_pets" class="alert-info">
                If you don't have pets, click Next to continue.
            </div>
        </div>

        <!-- Step 8: Review -->
        <div x-show="step === 8" class="step-content">
            <h2>Review & Submit</h2>
            
            <!-- Personal Information -->
            <div class="review-box">
                <h3>✓ Personal Information</h3>
                <p><strong>Name:</strong> <span x-text="`${formData.personal.first_name} ${formData.personal.last_name}`"></span></p>
                <p><strong>Email:</strong> <span x-text="formData.personal.email"></span></p>
                <p><strong>Phone:</strong> <span x-text="formData.personal.primary_phone"></span></p>
                <p><strong>Address:</strong> <span x-text="`${formData.personal.city}, ${formData.personal.state} ${formData.personal.zip_code}`"></span></p>
                <p><strong>Marital Status:</strong> <span x-text="formData.personal.marital_status"></span></p>
            </div>

            <!-- Spouse -->
            <div x-show="formData.personal.marital_status === 'married' && formData.spouse.spouse_name" class="review-box">
                <h3>✓ Spouse Information</h3>
                <p><strong>Spouse:</strong> <span x-text="formData.spouse.spouse_name"></span></p>
            </div>

            <!-- Children -->
            <div x-show="formData.children.length > 0" class="review-box">
                <h3>✓ Children</h3>
                <p><span x-text="formData.children.length"></span> child(ren) listed</p>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <template x-for="child in formData.children" :key="child.full_name">
                        <li x-text="child.full_name + (child.minor ? ' (Minor)' : '')"></li>
                    </template>
                </ul>
            </div>

            <!-- Assets -->
            <div x-show="formData.assets.length > 0" class="review-box">
                <h3>✓ Assets</h3>
                <p><span x-text="formData.assets.length"></span> asset(s) listed</p>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <template x-for="asset in formData.assets" :key="asset.description">
                        <li><span x-text="asset.asset_type"></span>: <span x-text="asset.description"></span></li>
                    </template>
                </ul>
            </div>

            <!-- Liabilities -->
            <div x-show="formData.liabilities.length > 0" class="review-box">
                <h3>✓ Liabilities</h3>
                <p><span x-text="formData.liabilities.length"></span> liability(ies) listed</p>
            </div>

            <!-- Fiduciaries -->
            <div class="review-box">
                <h3>✓ Fiduciaries & Decision Makers</h3>
                
                <div x-show="formData.fiduciaries.trustees.length > 0">
                    <p><strong>Trustee:</strong> <span x-text="formData.fiduciaries.trustees[0]?.full_name"></span></p>
                    <p x-show="formData.fiduciaries.trustees.length > 1"><strong>Successor Trustees:</strong> <span x-text="formData.fiduciaries.trustees.length - 1"></span> named</p>
                </div>

                <div x-show="formData.fiduciaries.executors.length > 0" style="margin-top: 10px;">
                    <p><strong>Personal Representative:</strong> <span x-text="formData.fiduciaries.executors[0]?.full_name"></span></p>
                </div>

                <div x-show="formData.fiduciaries.guardians.length > 0" style="margin-top: 10px;">
                    <p><strong>Guardian for Children:</strong> <span x-text="formData.fiduciaries.guardians[0]?.full_name"></span></p>
                </div>

                <div x-show="formData.fiduciaries.patient_advocates.length > 0" style="margin-top: 10px;">
                    <p><strong>Patient Advocate:</strong> <span x-text="formData.fiduciaries.patient_advocates[0]?.full_name"></span></p>
                </div>

                <div x-show="formData.fiduciaries.financial_agents.length > 0" style="margin-top: 10px;">
                    <p><strong>Financial Agent:</strong> <span x-text="formData.fiduciaries.financial_agents[0]?.full_name"></span></p>
                </div>
            </div>

            <!-- Pets -->
            <div x-show="formData.pets.has_pets && formData.pets.pets.length > 0" class="review-box">
                <h3>✓ Pet Trust</h3>
                <p><span x-text="formData.pets.pets.length"></span> pet(s) listed</p>
                <p x-show="formData.pets.caretakers.length > 0"><strong>Caretaker:</strong> <span x-text="formData.pets.caretakers[0]?.name"></span></p>
                <p x-show="formData.pets.funding_amount"><strong>Funding:</strong> $<span x-text="formData.pets.funding_amount"></span></p>
            </div>

            <div class="alert-success">
                <strong>✓ Ready to submit!</strong> Click the Submit button below to complete your intake form.
            </div>
        </div>

        <!-- Navigation Buttons -->
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

    .help-text {
        color: #666;
        font-size: 14px;
        margin: -5px 0 15px 0;
    }

    .form-group small.help-text {
        display: block;
        margin-top: 5px;
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
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        font-size: 16px;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
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

    .review-box ul {
        list-style: disc;
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
            totalSteps: 8,
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
                    county: '',
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
                liabilities: [],
                fiduciaries: {
                    trustees: [{ full_name: '', relationship: '', phone: '', email: '', role_type: 'trustee' }],
                    executors: [{ full_name: '', relationship: '', phone: '', email: '', role_type: 'personal_representative' }],
                    guardians: [{ full_name: '', relationship: '', phone: '', email: '', role_type: 'guardian' }],
                    patient_advocates: [{ full_name: '', relationship: '', phone: '', email: '', role_type: 'healthcare_poa' }],
                    financial_agents: [{ full_name: '', relationship: '', phone: '', email: '', role_type: 'financial_poa' }]
                },
                pets: {
                    has_pets: false,
                    pets: [],
                    caretakers: [{ name: '', phone: '' }],
                    funding_amount: null,
                    remaining_funds: 'caretaker'
                }
            },

            get progress() {
                return Math.round((this.step / this.totalSteps) * 100);
            },

            get hasMinorChildren() {
                return this.formData.children.some(child => child.minor);
            },

            init() {
                // Restore saved step from database
                const savedStep = {{ $submission->current_section ?? 1 }};
                if (savedStep > 1) {
                    this.step = savedStep;
                }

                console.log('=== LOADING DATA FROM DATABASE ===');

                // Load existing data from backend
                @if($personalInfo)
                    const loadedPersonal = @json($personalInfo);
                    console.log('Personal Info from DB:', loadedPersonal);
                    this.formData.personal = {
                        first_name: loadedPersonal.first_name || '',
                        middle_name: loadedPersonal.middle_name || '',
                        last_name: loadedPersonal.last_name || '',
                        date_of_birth: loadedPersonal.date_of_birth || '',
                        email: loadedPersonal.email || '',
                        primary_phone: loadedPersonal.primary_phone || '',
                        street_address: loadedPersonal.street_address || '',
                        city: loadedPersonal.city || '',
                        county: loadedPersonal.county || '',
                        state: loadedPersonal.state || 'Michigan',
                        zip_code: loadedPersonal.zip_code || '',
                        marital_status: loadedPersonal.marital_status || ''
                    };
                    console.log('Personal Info after mapping:', this.formData.personal);
                @endif
                
                @if($spouseInfo)
                    const loadedSpouse = @json($spouseInfo);
                    console.log('Spouse Info from DB:', loadedSpouse);
                    this.formData.spouse = {
                        spouse_name: loadedSpouse.spouse_name || '',
                        spouse_dob: loadedSpouse.spouse_dob || '',
                        spouse_occupation: loadedSpouse.spouse_occupation || '',
                        marriage_date: loadedSpouse.marriage_date || '',
                        marriage_location: loadedSpouse.marriage_location || ''
                    };
                    console.log('Spouse Info after mapping:', this.formData.spouse);
                @endif
                
                @if($children && count($children) > 0)
                    const loadedChildren = @json($children);
                    console.log('Children from DB:', loadedChildren);
                    this.formData.children = loadedChildren.map(child => ({
                        full_name: child.full_name || '',
                        date_of_birth: child.date_of_birth || '',
                        relationship: child.relationship || 'biological',
                        minor: child.minor || false
                    }));
                    console.log('Children after mapping:', this.formData.children);
                @endif
                
                @if($assets && count($assets) > 0)
                    const loadedAssets = @json($assets);
                    console.log('Assets from DB:', loadedAssets);
                    this.formData.assets = loadedAssets.map(asset => ({
                        asset_type: asset.asset_type || 'bank_account',
                        description: asset.description || '',
                        estimated_value: asset.estimated_value || null,
                        ownership: asset.ownership || 'individual'
                    }));
                    console.log('Assets after mapping:', this.formData.assets);
                @endif
                
                @if($liabilities && count($liabilities) > 0)
                    const loadedLiabilities = @json($liabilities);
                    console.log('Liabilities from DB:', loadedLiabilities);
                    this.formData.liabilities = loadedLiabilities.map(liability => ({
                        liability_type: liability.liability_type || 'credit_card',
                        lender: liability.lender || '',
                        balance_owed: liability.balance_owed || null,
                        monthly_payment: liability.monthly_payment || null
                    }));
                    console.log('Liabilities after mapping:', this.formData.liabilities);
                @endif

                @if($fiduciaries && count($fiduciaries) > 0)
                    // Load fiduciaries and group by role
                    const fiduciaries = @json($fiduciaries);
                    console.log('Fiduciaries from DB:', fiduciaries);
                    
                    this.formData.fiduciaries.trustees = fiduciaries.filter(f => f.role_type === 'trustee' || f.role_type === 'successor_trustee');
                    this.formData.fiduciaries.executors = fiduciaries.filter(f => f.role_type === 'personal_representative' || f.role_type === 'successor_personal_representative');
                    this.formData.fiduciaries.guardians = fiduciaries.filter(f => f.role_type === 'guardian' || f.role_type === 'successor_guardian');
                    this.formData.fiduciaries.patient_advocates = fiduciaries.filter(f => f.role_type === 'healthcare_poa');
                    this.formData.fiduciaries.financial_agents = fiduciaries.filter(f => f.role_type === 'financial_poa');
                    
                    console.log('Fiduciaries after grouping:', this.formData.fiduciaries);
                    
                    // Ensure at least one empty entry for each category
                    if (this.formData.fiduciaries.trustees.length === 0) this.formData.fiduciaries.trustees = [{ full_name: '', relationship: '', phone: '', email: '', role_type: 'trustee' }];
                    if (this.formData.fiduciaries.executors.length === 0) this.formData.fiduciaries.executors = [{ full_name: '', relationship: '', phone: '', email: '', role_type: 'personal_representative' }];
                    if (this.formData.fiduciaries.guardians.length === 0) this.formData.fiduciaries.guardians = [{ full_name: '', relationship: '', phone: '', email: '', role_type: 'guardian' }];
                    if (this.formData.fiduciaries.patient_advocates.length === 0) this.formData.fiduciaries.patient_advocates = [{ full_name: '', relationship: '', phone: '', email: '', role_type: 'healthcare_poa' }];
                    if (this.formData.fiduciaries.financial_agents.length === 0) this.formData.fiduciaries.financial_agents = [{ full_name: '', relationship: '', phone: '', email: '', role_type: 'financial_poa' }];
                @endif

                // Load pet data from notes field
                @if($submission && $submission->notes)
                    try {
                        const petData = @json(json_decode($submission->notes, true));
                        console.log('Pet data from DB:', petData);
                        if (petData) {
                            this.formData.pets = petData;
                        }
                        console.log('Pet data after loading:', this.formData.pets);
                    } catch(e) {
                        console.log('No pet data to load or error:', e);
                    }
                @endif

                console.log('=== FINAL FORM DATA ===', this.formData);
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

            addFiduciary(type) {
                const roleMap = {
                    'trustees': 'successor_trustee',
                    'executors': 'successor_personal_representative',
                    'guardians': 'successor_guardian',
                    'patient_advocates': 'healthcare_poa',
                    'financial_agents': 'financial_poa'
                };
                
                this.formData.fiduciaries[type].push({
                    full_name: '',
                    relationship: '',
                    phone: '',
                    email: '',
                    role_type: roleMap[type]
                });
            },

            removeFiduciary(type, index) {
                this.formData.fiduciaries[type].splice(index, 1);
            },

            addPet() {
                this.formData.pets.pets.push({
                    name: '',
                    type: '',
                    age: null,
                    care_instructions: ''
                });
            },

            removePet(index) {
                this.formData.pets.pets.splice(index, 1);
            },

            addPetCaretaker() {
                this.formData.pets.caretakers.push({
                    name: '',
                    phone: ''
                });
            },

            removePetCaretaker(index) {
                this.formData.pets.caretakers.splice(index, 1);
            },

            save() {
                this.saving = true;

                fetch('{{ route('intake.save-all') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ 
                        data: this.formData,
                        current_step: this.step
                    })
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
