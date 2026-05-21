@extends('Customer.Layout.app')

@section('content')
<style>
    /* ═══════════════════════════════════════════════════════
       MEAL PLANNER — Premium Form Design
    ═══════════════════════════════════════════════════════ */
    .mp-hero {
        background: #f7ece1;
        padding: 70px 0 50px;
        position: relative;
        overflow: hidden;
    }
    .mp-hero::before {
        content: '';
        position: absolute;
        width: 600px; height: 600px;
        background: radial-gradient(circle, rgba(211,51,51,0.15) 0%, transparent 70%);
        top: -200px; right: -150px;
        border-radius: 50%;
    }
    .mp-hero::after {
        content: '';
        position: absolute;
        width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(100,120,255,0.12) 0%, transparent 70%);
        bottom: -100px; left: -100px;
        border-radius: 50%;
    }
    .mp-hero-title {
        font-family: 'Inter', sans-serif;
        font-size: 2.8rem;
        font-weight: 800;
        color: #1a1a2e;
        line-height: 1.2;
        z-index: 2; position: relative;
    }
    .mp-hero-title span { color: #e85d5d; }
    .mp-hero-subtitle {
        color: #555;
        font-size: 1.1rem;
        z-index: 2; position: relative;
    }
    .mp-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.08);
        color: #1a1a2e;
        border-radius: 50px;
        padding: 5px 14px;
        font-size: 0.78rem;
        font-weight: 600;
        margin-bottom: 18px;
    }

    /* Form card */
    .mp-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 30px 80px rgba(0,0,0,0.12);
        overflow: hidden;
        margin-top: -30px;
        position: relative;
        z-index: 10;
    }
    .mp-sidebar {
        background: linear-gradient(170deg, #1a1a2e, #0f3460);
        padding: 40px 32px;
        color: #fff;
        min-height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .mp-sidebar h4 {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 20px;
        color: #e85d5d;
    }
    .mp-sidebar-stat {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid rgba(255,255,255,0.08);
    }
    .mp-sidebar-stat:last-of-type { border-bottom: none; }
    .mp-stat-icon {
        width: 38px; height: 38px;
        background: rgba(232,93,93,0.2);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
        color: #e85d5d;
        flex-shrink: 0;
    }
    .mp-stat-label { font-size: 0.75rem; color: rgba(255,255,255,0.5); }
    .mp-stat-value { font-size: 0.95rem; font-weight: 600; color: #fff; }
    .mp-feature-item {
        display: flex; align-items: center; gap: 10px;
        font-size: 0.85rem;
        color: rgba(255,255,255,0.75);
        margin-bottom: 10px;
    }
    .mp-feature-item i { color: #4CAF50; font-size: 0.9rem; }

    /* Form body */
    .mp-form-body { padding: 40px 36px; }
    .mp-section-title {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #e85d5d;
        margin-bottom: 16px;
        margin-top: 28px;
    }
    .mp-section-title:first-of-type { margin-top: 0; }

    /* Goal selector */
    .goal-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 10px; }
    .goal-card {
        border: 2px solid #e9ecef;
        border-radius: 16px;
        padding: 20px 16px;
        cursor: pointer;
        transition: all 0.25s ease;
        text-align: center;
        position: relative;
    }
    .goal-card:hover { border-color: #e85d5d; background: #fff5f5; }
    .goal-card.active { border-color: #e85d5d; background: linear-gradient(135deg,#fff5f5,#ffe8e8); }
    .goal-card .goal-icon { font-size: 2rem; margin-bottom: 8px; }
    .goal-card .goal-title { font-weight: 700; font-size: 0.95rem; color: #1a1a2e; }
    .goal-card .goal-desc { font-size: 0.75rem; color: #888; margin-top: 3px; }
    .goal-card input[type="radio"] { display: none; }
    .goal-card .goal-check {
        position: absolute; top: 10px; right: 12px;
        width: 20px; height: 20px;
        background: #e85d5d;
        border-radius: 50%;
        display: none; align-items: center; justify-content: center;
        color: #fff; font-size: 0.7rem;
    }
    .goal-card.active .goal-check { display: flex; }

    /* Form fields */
    .mp-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .mp-field { margin-bottom: 0; }
    .mp-label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #444;
        margin-bottom: 6px;
    }
    .mp-input, .mp-select {
        width: 100%;
        padding: 11px 14px;
        border: 1.5px solid #e0e0e0;
        border-radius: 10px;
        font-size: 0.9rem;
        color: #1a1a2e;
        background: #fafafa;
        transition: all 0.2s;
        outline: none;
        font-family: 'Inter', sans-serif;
    }
    .mp-input:focus, .mp-select:focus {
        border-color: #e85d5d;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(232,93,93,0.1);
    }
    .mp-input::placeholder { color: #bbb; }


    /* Submit button */
    .mp-submit-btn {
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, #D33333, #e85d5d);
        color: #fff;
        border: none;
        border-radius: 14px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 28px;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        font-family: 'Inter', sans-serif;
        letter-spacing: 0.3px;
    }
    .mp-submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(211,51,51,0.35);
    }
    .mp-submit-btn:active { transform: translateY(0); }

    /* Profile auto-fill banner */
    .profile-banner {
        background: linear-gradient(135deg, #e8f5e9, #f3ffe4);
        border: 1px solid #c8e6c9;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 0.82rem;
        color: #388e3c;
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 20px;
    }

    /* Spinner overlay */
    .mp-overlay {
        display: none;
        position: fixed; inset: 0;
        background: rgba(15,20,50,0.7);
        z-index: 9999;
        align-items: center; justify-content: center;
        flex-direction: column; gap: 20px;
        backdrop-filter: blur(4px);
    }
    .mp-overlay.show { display: flex; }
    .mp-spinner {
        width: 60px; height: 60px;
        border: 5px solid rgba(255,255,255,0.2);
        border-top-color: #e85d5d;
        border-radius: 50%;
        animation: spin 0.9s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .mp-overlay-text { color: #fff; font-size: 1rem; font-weight: 600; font-family: 'Inter', sans-serif; }

    /* Error */
    .mp-error {
        background: #ffeaea;
        border: 1px solid #f5c6c6;
        border-radius: 12px;
        padding: 12px 16px;
        color: #c0392b;
        font-size: 0.85rem;
        margin-bottom: 20px;
        display: flex; align-items: center; gap: 8px;
    }

    @media (max-width: 768px) {
        .mp-hero-title { font-size: 1.9rem; }
        .mp-form-body { padding: 28px 20px; }
        .mp-row { grid-template-columns: 1fr; }
        .goal-grid { grid-template-columns: 1fr 1fr; }
    }
</style>

{{-- Loading Overlay --}}
<div class="mp-overlay" id="mpOverlay">
    <div class="mp-spinner"></div>
    <div class="mp-overlay-text">🧠 Generating Your Personalised Meal Plan…</div>
    <div style="color:rgba(255,255,255,0.5); font-size:0.8rem;">Analysing health conditions & selecting recipes</div>
</div>

{{-- Hero --}}
<section class="mp-hero">
    <div class="container text-center">
        <div class="mp-badge"><i class="bi bi-stars"></i> AI-Powered Recommendation Engine</div>
        <h1 class="mp-hero-title">Your <span>Smart Meal Plan</span><br>Crafted for Your Goal</h1>
        <p class="mp-hero-subtitle mt-2">Personalised Pakistani recipes · Health-aware · Science-backed calories</p>
    </div>
</section>

{{-- Main Card --}}
<section class="section pt-0 pb-5" style="background:#f4f6fa;">
    <div class="container">
        <div class="mp-card row g-0">

            {{-- Sidebar --}}
            <div class="col-lg-4 mp-sidebar">
                <div>
                    <h4><i class="bi bi-cpu me-2"></i>How It Works</h4>
                    <div class="mp-sidebar-stat">
                        <div class="mp-stat-icon"><i class="bi bi-calculator"></i></div>
                        <div>
                            <div class="mp-stat-label">Step 1</div>
                            <div class="mp-stat-value">BMR & TDEE Calculation</div>
                        </div>
                    </div>
                    <div class="mp-sidebar-stat">
                        <div class="mp-stat-icon"><i class="bi bi-pie-chart"></i></div>
                        <div>
                            <div class="mp-stat-label">Step 2</div>
                            <div class="mp-stat-value">Macro Distribution</div>
                        </div>
                    </div>
                    <div class="mp-sidebar-stat">
                        <div class="mp-stat-icon"><i class="bi bi-funnel"></i></div>
                        <div>
                            <div class="mp-stat-label">Step 3</div>
                            <div class="mp-stat-value">Health-Aware Filtering</div>
                        </div>
                    </div>
                    <div class="mp-sidebar-stat">
                        <div class="mp-stat-icon"><i class="bi bi-calendar-week"></i></div>
                        <div>
                            <div class="mp-stat-label">Step 4</div>
                            <div class="mp-stat-value">7-Day Meal Assignment</div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="mp-feature-item"><i class="bi bi-check-circle-fill"></i>100% Pakistani cuisine</div>
                    <div class="mp-feature-item"><i class="bi bi-check-circle-fill"></i>Diabetes, PCOS & heart-condition aware</div>
                    <div class="mp-feature-item"><i class="bi bi-check-circle-fill"></i>Vegetarian / Non-veg preference respected</div>
                    <div class="mp-feature-item"><i class="bi bi-check-circle-fill"></i>Mifflin-St Jeor BMR formula used</div>
                    <div class="mp-feature-item"><i class="bi bi-check-circle-fill"></i>No repeated meals within 3 days</div>
                </div>
            </div>

            {{-- Form --}}
            <div class="col-lg-8">
                <div class="mp-form-body">

                    {{-- Profile auto-fill notice --}}
                    @if(session('customer') && session('customer')->use_profile_suggestion)
                    <div class="profile-banner">
                        <i class="bi bi-person-check-fill" style="font-size:1.1rem;"></i>
                        <span>Your health profile (conditions &amp; dietary preferences) will be auto-applied to personalise your plan.</span>
                    </div>
                    @endif

                    {{-- Errors --}}
                    @if($errors->any())
                    <div class="mp-error">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <div>
                            @foreach($errors->all() as $err)
                                <div>{{ $err }}</div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <form action="{{ route('meal.planner.generate') }}" method="POST" id="mpForm">
                        @csrf

                        {{-- Goal --}}
                        <div class="mp-section-title">Your Goal</div>
                        <div class="goal-grid">
                            <label class="goal-card {{ old('goal', '') === 'loss' ? 'active' : '' }}" id="goalLoss" onclick="setGoal('loss')">
                                <input type="radio" name="goal" value="loss" {{ old('goal') === 'loss' ? 'checked' : '' }}>
                                <div class="goal-icon">🔥</div>
                                <div class="goal-title">Weight Loss</div>
                                <div class="goal-desc">Calorie deficit plan</div>
                                <div class="goal-check"><i class="bi bi-check"></i></div>
                            </label>
                            <label class="goal-card {{ old('goal', '') === 'gain' ? 'active' : '' }}" id="goalGain" onclick="setGoal('gain')">
                                <input type="radio" name="goal" value="gain" {{ old('goal') === 'gain' ? 'checked' : '' }}>
                                <div class="goal-icon">💪</div>
                                <div class="goal-title">Weight Gain</div>
                                <div class="goal-desc">Calorie surplus plan</div>
                                <div class="goal-check"><i class="bi bi-check"></i></div>
                            </label>
                        </div>

                        <div class="mp-row" style="margin-bottom:16px;">
                            <div class="mp-field">
                                <label class="mp-label">Current Weight (kg)</label>
                                <input class="mp-input" type="number" name="current_weight" step="0.1" min="20" max="300"
                                       placeholder="e.g. 75" value="{{ old('current_weight') }}" required>
                            </div>
                            <div class="mp-field">
                                <label class="mp-label">Target Weight (kg)</label>
                                <input class="mp-input" type="number" name="target_weight" step="0.1" min="20" max="300"
                                       placeholder="e.g. 65" value="{{ old('target_weight') }}" required>
                            </div>
                        </div>

                        {{-- Duration --}}
                        <div class="mp-section-title">Plan Duration</div>
                        <div class="mp-row">
                            <div class="mp-field">
                                <label class="mp-label">Duration</label>
                                <select class="mp-select" name="duration" required>
                                    <option value="7"  {{ old('duration', '7') === '7'  ? 'selected' : '' }}>1 Week (7 days)</option>
                                    <option value="14" {{ old('duration') === '14' ? 'selected' : '' }}>2 Weeks (14 days)</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="mp-submit-btn" id="mpSubmit" onclick="showOverlay()">
                            <i class="bi bi-lightning-charge-fill"></i> Generate My Meal Plan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function setGoal(val) {
        document.querySelectorAll('.goal-card').forEach(c => c.classList.remove('active'));
        document.getElementById('goal' + (val === 'loss' ? 'Loss' : 'Gain')).classList.add('active');
        document.querySelector('input[name="goal"][value="' + val + '"]').checked = true;
    }
    function showOverlay() {
        const form = document.getElementById('mpForm');
        // Only show if form is valid
        if (form.checkValidity() &&
            document.querySelector('input[name="goal"]:checked')) {
            document.getElementById('mpOverlay').classList.add('show');
        }
    }
    // Restore active state on old() values
    document.addEventListener('DOMContentLoaded', () => {
        const goal = document.querySelector('input[name="goal"]:checked');
        if (goal) setGoal(goal.value);
    });
</script>
@endsection
