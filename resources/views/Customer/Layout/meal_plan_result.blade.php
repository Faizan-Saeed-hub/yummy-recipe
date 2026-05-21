@extends('Customer.Layout.app')

@section('content')
<style>
.result-hero {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    padding: 50px 0 80px;
    position: relative; overflow: hidden;
}
.result-hero::before {
    content:''; position:absolute; width:500px; height:500px;
    background: radial-gradient(circle, rgba(211,51,51,0.15) 0%, transparent 70%);
    top:-150px; right:-100px; border-radius:50%;
}
.result-hero h1 { font-family:'Inter',sans-serif; font-weight:800; color:#fff; font-size:2.4rem; }
.result-hero h1 span { color:#e85d5d; }
.hero-badge {
    display:inline-flex; align-items:center; gap:6px;
    background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2);
    color:rgba(255,255,255,0.85); border-radius:50px; padding:5px 14px;
    font-size:0.78rem; font-weight:600; margin-bottom:16px;
}

/* Summary Cards */
.summary-row { margin-top:-50px; position:relative; z-index:10; }
.stat-card {
    background:#fff; border-radius:18px;
    box-shadow:0 10px 40px rgba(0,0,0,0.1);
    padding:24px 22px; text-align:center; height:100%;
    transition: transform 0.2s;
}
.stat-card:hover { transform:translateY(-4px); }
.stat-card .icon-wrap {
    width:50px; height:50px; border-radius:14px;
    display:flex; align-items:center; justify-content:center;
    font-size:1.3rem; margin:0 auto 12px;
}
.stat-card .stat-val { font-size:1.7rem; font-weight:800; color:#1a1a2e; line-height:1; }
.stat-card .stat-lbl { font-size:0.75rem; color:#888; font-weight:600; margin-top:4px; text-transform:uppercase; letter-spacing:0.8px; }
.stat-card .stat-sub { font-size:0.78rem; color:#aaa; margin-top:6px; }

/* Macro bar */
.macro-bar-wrap { background:#fff; border-radius:18px; box-shadow:0 10px 40px rgba(0,0,0,0.08); padding:28px 30px; margin-bottom:30px; }
.macro-title { font-size:0.7rem; letter-spacing:1.5px; text-transform:uppercase; font-weight:700; color:#e85d5d; margin-bottom:18px; }
.macro-row { display:flex; gap:16px; flex-wrap:wrap; }
.macro-pill {
    flex:1; min-width:120px;
    border-radius:14px; padding:16px 18px;
    text-align:center;
}
.macro-pill.protein { background:linear-gradient(135deg,#e3f2fd,#bbdefb); }
.macro-pill.carbs   { background:linear-gradient(135deg,#fff8e1,#ffecb3); }
.macro-pill.fat     { background:linear-gradient(135deg,#fce4ec,#f8bbd0); }
.macro-pill .macro-val { font-size:1.6rem; font-weight:800; color:#1a1a2e; }
.macro-pill .macro-unit { font-size:0.75rem; color:#666; font-weight:600; }
.macro-pill .macro-name { font-size:0.8rem; color:#555; margin-top:4px; }

/* Health badges */
.health-badge {
    display:inline-flex; align-items:center; gap:5px;
    background:#e8f5e9; border:1px solid #c8e6c9; color:#2e7d32;
    border-radius:50px; padding:4px 12px; font-size:0.76rem; font-weight:600;
    margin:4px 3px;
}
.diet-badge {
    display:inline-flex; align-items:center; gap:5px;
    background:#e3f2fd; border:1px solid #bbdefb; color:#1565c0;
    border-radius:50px; padding:4px 12px; font-size:0.76rem; font-weight:600;
    margin:4px 3px;
}

/* Day plan card */
.day-section { margin-bottom:24px; }
.day-header {
    display:flex; align-items:center; gap:14px;
    background:linear-gradient(135deg,#1a1a2e,#0f3460);
    color:#fff; border-radius:16px 16px 0 0;
    padding:14px 22px;
}
.day-num {
    background:#e85d5d; color:#fff; border-radius:10px;
    padding:4px 12px; font-size:0.8rem; font-weight:800;
}
.day-total { margin-left:auto; font-size:0.82rem; color:rgba(255,255,255,0.7); }
.day-total strong { color:#fff; font-size:0.95rem; }
.day-body { background:#fff; border-radius:0 0 16px 16px; box-shadow:0 8px 30px rgba(0,0,0,0.07); overflow:hidden; }

/* Meal rows */
.meal-row {
    display:grid; grid-template-columns:110px 1fr auto;
    align-items:start; gap:16px;
    padding:18px 22px; border-bottom:1px solid #f0f0f0;
    transition: background 0.15s;
}
.meal-row:last-child { border-bottom:none; }
.meal-row:hover { background:#fafafa; }
.meal-slot-tag {
    display:inline-flex; align-items:center; gap:6px;
    border-radius:8px; padding:5px 10px;
    font-size:0.73rem; font-weight:700; white-space:nowrap;
}
.slot-breakfast { background:#fff8e1; color:#f57f17; }
.slot-lunch     { background:#e8f5e9; color:#2e7d32; }
.slot-dinner    { background:#fce4ec; color:#c62828; }
.slot-snack     { background:#e8eaf6; color:#283593; }
.meal-name { font-weight:700; font-size:0.95rem; color:#1a1a2e; margin-bottom:4px; }
.meal-desc { font-size:0.78rem; color:#888; line-height:1.4; }
.meal-tags { margin-top:6px; display:flex; flex-wrap:wrap; gap:4px; }
.tag-sm {
    font-size:0.67rem; padding:2px 8px; border-radius:20px; font-weight:600;
    background:#f5f5f5; color:#666;
}
.cal-badge {
    background:linear-gradient(135deg,#e85d5d,#D33333);
    color:#fff; border-radius:10px; padding:6px 12px;
    text-align:center; white-space:nowrap;
    font-size:0.8rem; font-weight:700; min-width:70px;
}
.cal-badge small { display:block; font-size:0.65rem; font-weight:500; opacity:0.85; }

/* Timeline */
.timeline-card {
    background:linear-gradient(135deg,#fff3e0,#ffe0b2);
    border:1px solid #ffcc80; border-radius:16px; padding:20px 24px;
    margin-bottom:30px; display:flex; align-items:center; gap:16px;
}
.timeline-icon { font-size:2rem; }
.timeline-text { font-size:0.9rem; color:#e65100; font-weight:600; }
.timeline-text small { display:block; font-weight:400; color:#bf360c; margin-top:2px; }

/* Action buttons */
.action-row { display:flex; gap:14px; flex-wrap:wrap; margin-bottom:40px; }
.btn-replan {
    background:linear-gradient(135deg,#D33333,#e85d5d);
    color:#fff; border:none; border-radius:12px;
    padding:12px 26px; font-weight:700; font-size:0.9rem;
    cursor:pointer; transition: all 0.25s; text-decoration:none;
    display:inline-flex; align-items:center; gap:8px;
}
.btn-replan:hover { transform:translateY(-2px); box-shadow:0 8px 25px rgba(211,51,51,0.35); color:#fff; }
.btn-print {
    background:#fff; color:#1a1a2e; border:2px solid #e0e0e0;
    border-radius:12px; padding:12px 26px; font-weight:700; font-size:0.9rem;
    cursor:pointer; transition: all 0.25s; text-decoration:none;
    display:inline-flex; align-items:center; gap:8px;
}
.btn-print:hover { border-color:#1a1a2e; color:#1a1a2e; }

@media print {
    .result-hero, .action-row, header, footer, #scroll-top { display:none !important; }
    .summary-row { margin-top:0 !important; }
    .day-section { break-inside:avoid; }
}
@media(max-width:768px){
    .result-hero h1 { font-size:1.7rem; }
    .meal-row { grid-template-columns:1fr; gap:8px; }
    .macro-row { flex-direction:column; }
}
</style>

{{-- Hero --}}
<section class="result-hero">
    <div class="container text-center">
        <div class="hero-badge"><i class="bi bi-check-circle-fill"></i> Plan Generated Successfully</div>
        <h1>Your <span>{{ $plan['goal'] === 'loss' ? 'Weight Loss' : 'Weight Gain' }}</span> Meal Plan</h1>
        <p style="color:rgba(255,255,255,0.6); margin-top:8px; font-size:1rem;">
            {{ count($plan['plan']) }}-Day Personalised Pakistani Cuisine Plan
        </p>
    </div>
</section>

<section class="section pt-2 pb-5" style="background:#f4f6fa;">
<div class="container">

    {{-- Summary Stats --}}
    <div class="row g-3 summary-row mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="icon-wrap" style="background:#fff3e0;">🔥</div>
                <div class="stat-val">{{ $plan['calorie_target'] }}</div>
                <div class="stat-lbl">Daily Calories</div>
                <div class="stat-sub">Target kcal/day</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="icon-wrap" style="background:#e8f5e9;">⚡</div>
                <div class="stat-val">{{ $plan['tdee'] }}</div>
                <div class="stat-lbl">Your TDEE</div>
                <div class="stat-sub">kcal maintenance</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="icon-wrap" style="background:#e3f2fd;">🧬</div>
                <div class="stat-val">{{ $plan['bmr'] }}</div>
                <div class="stat-lbl">BMR</div>
                <div class="stat-sub">Base metabolic rate</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="icon-wrap" style="background:#fce4ec;">🍽️</div>
                <div class="stat-val">{{ $plan['recipes_matched'] }}</div>
                <div class="stat-lbl">Recipes Matched</div>
                <div class="stat-sub">Health-filtered pool</div>
            </div>
        </div>
    </div>

    {{-- Macro breakdown --}}
    <div class="macro-bar-wrap">
        <div class="macro-title">Daily Macro Targets</div>
        <div class="macro-row">
            <div class="macro-pill protein">
                <div class="macro-val">{{ $plan['macros']['protein_g'] }}<span class="macro-unit">g</span></div>
                <div class="macro-name">💪 Protein</div>
            </div>
            <div class="macro-pill carbs">
                <div class="macro-val">{{ $plan['macros']['carbs_g'] }}<span class="macro-unit">g</span></div>
                <div class="macro-name">🌾 Carbohydrates</div>
            </div>
            <div class="macro-pill fat">
                <div class="macro-val">{{ $plan['macros']['fat_g'] }}<span class="macro-unit">g</span></div>
                <div class="macro-name">🥑 Healthy Fats</div>
            </div>
            <div class="macro-pill" style="background:linear-gradient(135deg,#f3e5f5,#e1bee7);">
                <div class="macro-val">
                    @if($plan['goal'] === 'loss')
                        -{{ $plan['calorie_adjustment'] }}
                    @else
                        +{{ abs($plan['calorie_adjustment']) }}
                    @endif
                    <span class="macro-unit">kcal</span>
                </div>
                <div class="macro-name">{{ $plan['goal'] === 'loss' ? '📉 Daily Deficit' : '📈 Daily Surplus' }}</div>
            </div>
        </div>
    </div>

    {{-- Timeline estimate --}}
    <div class="timeline-card">
        <div class="timeline-icon">⏱️</div>
        <div>
            <div class="timeline-text">{{ $plan['timeline_note'] }}</div>
            <small>Based on a {{ $plan['goal'] === 'loss' ? '500 kcal daily deficit' : '400 kcal daily surplus' }} using the Mifflin-St Jeor formula</small>
        </div>
    </div>

    {{-- Health & Diet badges --}}
    @if(!empty($plan['health_conditions']) || !empty($plan['dietary_restrictions']))
    <div style="background:#fff; border-radius:16px; padding:20px 24px; margin-bottom:24px; box-shadow:0 8px 30px rgba(0,0,0,0.07);">
        <div class="macro-title">Plan Personalisation Applied</div>
        @foreach($plan['health_conditions'] as $cond)
            <span class="health-badge"><i class="bi bi-heart-pulse-fill"></i> {{ ucwords(str_replace('_', ' ', $cond)) }}</span>
        @endforeach
        @foreach($plan['dietary_restrictions'] as $diet)
            <span class="diet-badge"><i class="bi bi-leaf-fill"></i> {{ ucwords(str_replace('_', ' ', $diet)) }}</span>
        @endforeach
    </div>
    @endif

    {{-- Action Buttons --}}
    <div class="action-row">
        <a href="{{ url('/meal/planner') }}" class="btn-replan">
            <i class="bi bi-arrow-counterclockwise"></i> Generate New Plan
        </a>
        <button class="btn-print" onclick="window.print()">
            <i class="bi bi-printer"></i> Print Plan
        </button>
    </div>

    {{-- 7-Day Plan --}}
    @php
        $slotLabels = ['breakfast'=>'Breakfast','lunch'=>'Lunch','dinner'=>'Dinner','snack'=>'Snack'];
        $slotClasses= ['breakfast'=>'slot-breakfast','lunch'=>'slot-lunch','dinner'=>'slot-dinner','snack'=>'slot-snack'];
        $slotIcons  = ['breakfast'=>'☀️','lunch'=>'🌤️','dinner'=>'🌙','snack'=>'🍎'];
        $dayNames   = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday',
                       'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
    @endphp

    @foreach($plan['plan'] as $day)
    <div class="day-section">
        <div class="day-header">
            <span class="day-num">Day {{ $day['day'] }}</span>
            <span style="font-weight:700; font-size:1rem;">{{ $dayNames[$day['day']-1] ?? 'Day '.$day['day'] }}</span>
            <span class="day-total">
                Total: <strong>{{ $day['total_calories'] }} kcal</strong>
                &nbsp;·&nbsp;Protein: <strong>{{ $day['total_protein_g'] }}g</strong>
            </span>
        </div>
        <div class="day-body">
            @foreach($day['meals'] as $slot => $meal)
            <div class="meal-row">
                <div>
                    <span class="meal-slot-tag {{ $slotClasses[$slot] }}">
                        {{ $slotIcons[$slot] }} {{ $slotLabels[$slot] }}
                    </span>
                </div>
                <div>
                    <div class="meal-name">{{ $meal['name'] }}</div>
                    <div class="meal-desc">{{ $meal['description'] }}</div>
                    <div class="meal-tags">
                        @foreach(array_slice($meal['diet_tags'], 0, 3) as $tag)
                            <span class="tag-sm">{{ str_replace('_',' ', $tag) }}</span>
                        @endforeach
                        @if(!empty($meal['ingredients']))
                            <span class="tag-sm" style="background:#e8f5e9;color:#2e7d32;">
                                🥘 {{ implode(', ', array_slice($meal['ingredients'], 0, 3)) }}…
                            </span>
                        @endif
                    </div>
                </div>
                <div class="cal-badge">
                    {{ $meal['calories'] }}
                    <small>kcal</small>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    {{-- Bottom action --}}
    <div class="action-row mt-2">
        <a href="{{ url('/meal/planner') }}" class="btn-replan">
            <i class="bi bi-arrow-counterclockwise"></i> Generate New Plan
        </a>
        <button class="btn-print" onclick="window.print()">
            <i class="bi bi-printer"></i> Print Plan
        </button>
    </div>

</div>
</section>
@endsection
