@extends('layouts.app')

@section('title', 'Screening')

@push('styles')
<style>
    /* ========== WIZARD STYLES ========== */
    .scr-wrap {
        max-width: 780px;
        margin: 0 auto;
    }

    .scr-title {
        color: #fff;
        text-align: center;
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 18px;
    }

    /* Step indicator */
    .scr-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        position: relative;
    }

    .scr-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        position: relative;
        z-index: 2;
    }

    .scr-step-dot {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.12);
        border: 2px solid transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        color: #fff;
        transition: all 0.3s ease;
    }

    .scr-step-label {
        color: #94a3b8;
        font-size: 11px;
        margin-top: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .scr-step.active .scr-step-dot {
        background: linear-gradient(180deg, #ff4d8d, #c2185b);
        box-shadow: 0 0 15px rgba(255, 77, 141, 0.5);
        transform: scale(1.08);
    }

    .scr-step.active .scr-step-label {
        color: #fff;
        font-weight: 600;
    }

    .scr-step.completed .scr-step-dot {
        background: #22c55e;
    }

    /* Progress bar */
    .scr-progress {
        height: 6px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 22px;
    }

    .scr-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #ff4d8d, #c2185b);
        width: 25%;
        transition: width 0.4s ease;
        border-radius: 20px;
    }

    /* Card */
    .scr-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 18px;
        padding: 26px;
        color: #e2e8f0;
    }

    .scr-card h5 {
        color: #fff;
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 18px;
        padding-left: 12px;
        border-left: 3px solid #ff4d8d;
    }

    /* Step panels */
    .scr-panel { display: none; }
    .scr-panel.active { display: block; animation: scrFade 0.3s ease; }

    @keyframes scrFade {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Form fields */
    .scr-field { margin-bottom: 14px; }

    .scr-label {
        display: block;
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 5px;
    }

    .scr-select {
        width: 100%;
        background: #fff !important;
        color: #000 !important;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        padding: 10px 36px 10px 14px;
        font-size: 14px;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 12px center;
        transition: all 0.2s ease;
    }

    .scr-select:focus {
        outline: none;
        border-color: #ff4d8d;
        box-shadow: 0 0 0 3px rgba(255, 77, 141, 0.18);
    }

    .scr-select.invalid {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
    }

    /* Nav buttons */
    .scr-nav {
        display: flex;
        justify-content: space-between;
        margin-top: 22px;
        gap: 12px;
    }

    .scr-btn {
        flex: 1;
        padding: 11px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .scr-btn-primary {
        background: linear-gradient(180deg, #ff4d8d, #c2185b);
        color: #fff;
    }

    .scr-btn-primary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(190, 24, 93, 0.4);
        color: #fff;
    }

    .scr-btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .scr-btn-outline {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #fff;
    }

    .scr-btn-outline:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #fff;
    }

    /* Success message */
    .scr-success {
        background: rgba(34, 197, 94, 0.15);
        border: 1px solid rgba(34, 197, 94, 0.4);
        color: #bbf7d0;
        padding: 14px 16px;
        border-radius: 12px;
        margin-bottom: 16px;
        font-size: 14px;
        display: none;
        align-items: center;
        gap: 10px;
    }

    .scr-success.show { display: flex; }

    /* Modal content (already styled by app layout) */
    .scr-modal-org-name {
        color: #fff;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .scr-modal-org-desc {
        color: #cbd5f5;
        font-size: 13px;
        margin-bottom: 16px;
    }

    .scr-contact-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 14px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        margin-bottom: 8px;
        color: #fff;
        text-decoration: none;
        font-size: 13px;
        transition: background 0.2s;
    }

    .scr-contact-row:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    .scr-contact-row i { font-size: 16px; color: #ff4d8d; }

    @media (max-width: 576px) {
        .scr-card { padding: 20px 16px; }
        .scr-step-dot { width: 30px; height: 30px; font-size: 12px; }
        .scr-step-label { font-size: 10px; }
    }
</style>
@endpush

@section('content')
<div class="scr-wrap">
    <h3 class="scr-title">🧠 Smart Screening Questions</h3>

    {{-- Success banner --}}
    <div id="scrSuccess" class="scr-success">
        <i class="bi bi-check-circle-fill"></i>
        <span>Screening completed successfully!</span>
    </div>

    {{-- Step indicator --}}
    <div class="scr-steps">
        <div class="scr-step active" data-step="1">
            <div class="scr-step-dot">1</div>
            <div class="scr-step-label">Basics</div>
        </div>
        <div class="scr-step" data-step="2">
            <div class="scr-step-dot">2</div>
            <div class="scr-step-label">Mental Health</div>
        </div>
        <div class="scr-step" data-step="3">
            <div class="scr-step-dot">3</div>
            <div class="scr-step-label">Lifestyle</div>
        </div>
        <div class="scr-step" data-step="4">
            <div class="scr-step-dot">4</div>
            <div class="scr-step-label">Safety</div>
        </div>
    </div>

    {{-- Progress bar --}}
    <div class="scr-progress">
        <div class="scr-progress-fill" id="scrProgress"></div>
    </div>

    {{-- Wizard card --}}
    <form id="screeningForm" class="scr-card" autocomplete="off">
        @csrf

        {{-- STEP 1: Basics --}}
        <div class="scr-panel active" data-panel="1">
            <h5>Step 1: Basic Information</h5>

            <div class="scr-field">
                <label class="scr-label">Age Group</label>
                <select name="age" class="scr-select" required>
                    <option value="">Select age group</option>
                    <option>Under 18</option>
                    <option>18-24</option>
                    <option>25-34</option>
                    <option>35+</option>
                </select>
            </div>

            <div class="scr-field">
                <label class="scr-label">Gender Identity</label>
                <select name="gender" class="scr-select" required>
                    <option value="">Select gender</option>
                    <option>Male</option>
                    <option>Female</option>
                    <option>Gender Non-Conforming</option>
                    <option>Other</option>
                </select>
            </div>

            <div class="scr-field">
                <label class="scr-label">Pronouns</label>
                <select name="pronouns" class="scr-select" required>
                    <option value="">Select pronouns</option>
                    <option>He/Him</option>
                    <option>She/Her</option>
                    <option>They/Them</option>
                    <option>MX</option>
                </select>
            </div>

            <div class="scr-field">
                <label class="scr-label">Sexual Orientation</label>
                <select name="orientation" class="scr-select" required>
                    <option value="">Select orientation</option>
                    <option>Heterosexual</option>
                    <option>LGBTIQ</option>
                    <option>Rather Not Say</option>
                </select>
            </div>

            <div class="scr-field">
                <label class="scr-label">Prefer a Similar Counselor?</label>
                <select name="counselor" class="scr-select" required>
                    <option value="">Select option</option>
                    <option>Yes</option>
                    <option>No</option>
                </select>
            </div>
        </div>

        {{-- STEP 2: Mental Health --}}
        <div class="scr-panel" data-panel="2">
            <h5>Step 2: Mental Health & Concerns</h5>

            <div class="scr-field">
                <label class="scr-label">Have you seen a therapist before?</label>
                <select name="therapist" class="scr-select" required>
                    <option value="">Select option</option>
                    <option>Yes</option>
                    <option>No</option>
                </select>
            </div>

            <div class="scr-field">
                <label class="scr-label">Main Concern</label>
                <select name="concern" class="scr-select" required>
                    <option value="">Select concern</option>
                    <option>Stress</option>
                    <option>Anxiety</option>
                    <option>Depression</option>
                    <option>Identity Issues</option>
                    <option>Family Conflict</option>
                    <option>Trauma</option>
                </select>
            </div>

            <div class="scr-field">
                <label class="scr-label">Mental Health Rating</label>
                <select name="rating" class="scr-select" required>
                    <option value="">Select rating</option>
                    <option>Excellent</option>
                    <option>Good</option>
                    <option>Okay</option>
                    <option>Poor</option>
                </select>
            </div>

            <div class="scr-field">
                <label class="scr-label">Are you on medication?</label>
                <select name="medication" class="scr-select" required>
                    <option value="">Select option</option>
                    <option>Yes</option>
                    <option>No</option>
                </select>
            </div>
        </div>

        {{-- STEP 3: Lifestyle --}}
        <div class="scr-panel" data-panel="3">
            <h5>Step 3: Lifestyle & Support</h5>

            <div class="scr-field">
                <label class="scr-label">Do you have a trusted person?</label>
                <select name="support" class="scr-select" required>
                    <option value="">Select option</option>
                    <option>Yes</option>
                    <option>No</option>
                </select>
            </div>

            <div class="scr-field">
                <label class="scr-label">Substance Use</label>
                <select name="substance" class="scr-select" required>
                    <option value="">Select frequency</option>
                    <option>No</option>
                    <option>Sometimes</option>
                    <option>Often</option>
                </select>
            </div>

            <div class="scr-field">
                <label class="scr-label">Average Sleep Duration</label>
                <select name="sleep" class="scr-select" required>
                    <option value="">Select hours</option>
                    <option>Less than 5 hours</option>
                    <option>5-7 hours</option>
                    <option>7-9 hours</option>
                </select>
            </div>
        </div>

        {{-- STEP 4: Safety --}}
        <div class="scr-panel" data-panel="4">
            <h5>Step 4: Safety & Urgency</h5>

            <div class="scr-field">
                <label class="scr-label">Are you having self-harm thoughts?</label>
                <select name="selfHarm" class="scr-select" required>
                    <option value="">Select option</option>
                    <option>Yes</option>
                    <option>Sometimes</option>
                    <option>No</option>
                </select>
            </div>

            <div class="scr-field">
                <label class="scr-label">Are you in immediate danger?</label>
                <select name="danger" class="scr-select" required>
                    <option value="">Select option</option>
                    <option>Yes</option>
                    <option>No</option>
                </select>
            </div>

            <div class="scr-field">
                <label class="scr-label">What type of support do you need?</label>
                <select name="supportNeed" class="scr-select" required>
                    <option value="">Select support type</option>
                    <option>Urgent Help</option>
                    <option>Ongoing Support</option>
                    <option>Just Exploring Options</option>
                </select>
            </div>

            <div class="scr-field">
                <label class="scr-label">Select Your Preferred Referral</label>
                <select name="referral" class="scr-select" required>
                    <option value="">Select referral</option>
                    <option value="none">No Preference</option>
                    @foreach($organizations as $org)
                        <option value="{{ $org->id }}">{{ $org->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Navigation --}}
        <div class="scr-nav">
            <button type="button" id="scrPrev" class="scr-btn scr-btn-outline" style="display: none;">
                <i class="bi bi-arrow-left"></i> Previous
            </button>
            <button type="button" id="scrNext" class="scr-btn scr-btn-primary">
                Next <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </form>
</div>

{{-- ORGANIZATION MODAL --}}
<div class="modal fade" id="scrOrgModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">
            <div class="text-center mb-3">
                <div style="width: 60px; height: 60px; border-radius: 50%;
                            background: linear-gradient(180deg, #ff4d8d, #c2185b);
                            display: flex; align-items: center; justify-content: center;
                            margin: 0 auto 12px; font-size: 26px; color: #fff;">
                    <i class="bi bi-building"></i>
                </div>
                <h5 id="scrOrgName" class="scr-modal-org-name"></h5>
                <p id="scrOrgDesc" class="scr-modal-org-desc"></p>
            </div>

            <hr style="border-color: rgba(255,255,255,0.1);">

            <a id="scrOrgCall" class="scr-contact-row" style="display:none;">
                <i class="bi bi-telephone-fill"></i>
                <span>Call</span>
            </a>
            <a id="scrOrgWa" class="scr-contact-row" target="_blank" style="display:none;">
                <i class="bi bi-whatsapp" style="color:#22c55e;"></i>
                <span>WhatsApp</span>
            </a>
            <a id="scrOrgEmail" class="scr-contact-row" style="display:none;">
                <i class="bi bi-envelope-fill"></i>
                <span>Email</span>
            </a>
            <a id="scrOrgWeb" class="scr-contact-row" target="_blank" style="display:none;">
                <i class="bi bi-globe"></i>
                <span>Website</span>
            </a>

            <button type="button" class="scr-btn scr-btn-primary w-100 mt-3" data-bs-dismiss="modal">
                Close
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    const form      = document.getElementById('screeningForm');
    const panels    = form.querySelectorAll('.scr-panel');
    const steps     = document.querySelectorAll('.scr-step');
    const progress  = document.getElementById('scrProgress');
    const prevBtn   = document.getElementById('scrPrev');
    const nextBtn   = document.getElementById('scrNext');
    const successEl = document.getElementById('scrSuccess');
    const totalSteps = panels.length;

    let currentStep = 1;
    let isSubmitting = false;

    /* ================================
       SHOW A SPECIFIC STEP
    ================================= */
    function showStep(step) {
        currentStep = step;

        // Toggle panels
        panels.forEach(p => {
            p.classList.toggle('active', parseInt(p.dataset.panel) === step);
        });

        // Toggle step indicators
        steps.forEach(s => {
            const n = parseInt(s.dataset.step);
            s.classList.remove('active', 'completed');
            if (n === step) s.classList.add('active');
            else if (n < step) s.classList.add('completed');
        });

        // Progress bar
        progress.style.width = ((step / totalSteps) * 100) + '%';

        // Prev button visibility
        prevBtn.style.display = (step === 1) ? 'none' : 'flex';

        // Next button label
        if (step === totalSteps) {
            nextBtn.innerHTML = 'Submit <i class="bi bi-check-circle-fill"></i>';
        } else {
            nextBtn.innerHTML = 'Next <i class="bi bi-arrow-right"></i>';
        }

        // Scroll top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    /* ================================
       VALIDATE CURRENT STEP
    ================================= */
    function validateStep(step) {
        const panel = form.querySelector(`.scr-panel[data-panel="${step}"]`);
        if (!panel) return true;

        const selects = panel.querySelectorAll('select');
        let valid = true;

        selects.forEach(sel => {
            if (!sel.value) {
                sel.classList.add('invalid');
                valid = false;
            } else {
                sel.classList.remove('invalid');
            }
        });

        return valid;
    }

    /* Remove red border on change */
    form.addEventListener('change', e => {
        if (e.target.tagName === 'SELECT' && e.target.value) {
            e.target.classList.remove('invalid');
        }
    });

    /* ================================
       NEXT / PREV BUTTON HANDLERS
    ================================= */
    nextBtn.addEventListener('click', () => {
        if (!validateStep(currentStep)) {
            // Shake the button briefly to indicate error
            nextBtn.style.transform = 'translateX(-4px)';
            setTimeout(() => nextBtn.style.transform = '', 100);
            return;
        }

        if (currentStep < totalSteps) {
            showStep(currentStep + 1);
        } else {
            submitScreening();
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentStep > 1) showStep(currentStep - 1);
    });

    /* ================================
       SUBMIT SCREENING
    ================================= */
    async function submitScreening() {
        if (isSubmitting) return;
        isSubmitting = true;

        nextBtn.disabled = true;
        nextBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Submitting...';
        successEl.classList.remove('show');

        const formData = new FormData(form);

        try {
            const res = await fetch('{{ route("screening.store") }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData,
            });

            const data = await res.json();

            if (!res.ok) {
                const errors = data.errors ? Object.values(data.errors).flat() : [];
                throw new Error(errors.join('\n') || 'Submission failed');
            }

            if (data.status !== 'success') {
                throw new Error(data.message || 'Unexpected error');
            }

            // Success feedback
            successEl.classList.add('show');
            window.scrollTo({ top: 0, behavior: 'smooth' });

            // High risk → redirect to panic
            if (data.risk === 'high') {
                setTimeout(() => {
                    const panicBtn = document.querySelector('.panic-float-btn');
                    if (panicBtn) panicBtn.click();
                    else alert('⚠️ High risk detected. Please seek immediate help.');
                }, 800);
            }

            // Show referral organization
            if (data.organization) {
                showOrgModal(data.organization);
            }

            // Reset wizard after 2.5 s
            setTimeout(() => {
                form.reset();
                panels.forEach(p => p.querySelectorAll('select').forEach(s => s.classList.remove('invalid')));
                successEl.classList.remove('show');
                showStep(1);
            }, 2500);

        } catch (err) {
            console.error(err);
            alert('Error: ' + err.message);
        } finally {
            isSubmitting = false;
            nextBtn.disabled = false;
            nextBtn.innerHTML = (currentStep === totalSteps)
                ? 'Submit <i class="bi bi-check-circle-fill"></i>'
                : 'Next <i class="bi bi-arrow-right"></i>';
        }
    }

    /* ================================
       SHOW ORG MODAL
    ================================= */
    function showOrgModal(org) {
        document.getElementById('scrOrgName').textContent = org.name || '';
        document.getElementById('scrOrgDesc').textContent = org.description || '';

        const call  = document.getElementById('scrOrgCall');
        const wa    = document.getElementById('scrOrgWa');
        const email = document.getElementById('scrOrgEmail');
        const web   = document.getElementById('scrOrgWeb');

        // Call
        if (org.phone) {
            call.href = 'tel:' + org.phone.replace(/\s+/g, '');
            call.style.display = 'flex';
        } else call.style.display = 'none';

        // WhatsApp
        if (org.whatsapp) {
            const clean = org.whatsapp.replace(/\D/g, '');
            const msg = encodeURIComponent('Hello, I need support from ' + org.name);
            wa.href = `https://wa.me/${clean}?text=${msg}`;
            wa.style.display = 'flex';
        } else wa.style.display = 'none';

        // Email
        if (org.email) {
            email.href = 'mailto:' + org.email;
            email.style.display = 'flex';
        } else email.style.display = 'none';

        // Website
        if (org.website) {
            let w = org.website.trim();
            if (!/^https?:\/\//.test(w)) w = 'https://' + w;
            web.href = w;
            web.style.display = 'flex';
        } else web.style.display = 'none';

        new bootstrap.Modal(document.getElementById('scrOrgModal')).show();
    }

    /* ================================
       INIT
    ================================= */
    showStep(1);
})();
</script>
@endpush