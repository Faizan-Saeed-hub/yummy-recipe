@extends('Customer.Layout.app')
@section('content')

<section id="nutritional-analysis" class="book-a-table section">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h2 style="color: black"><b>Nutritional Analysis</b></h2>
        <p>
            <span>Analyze Your</span>
            <span class="description-title">Meal Nutrition Instantly<br></span>
        </p>
    </div>

    <!-- Main Container -->
    <div class="container" style="background-color: #f7ece1">
        <div class="row g-0" data-aos="fade-up" data-aos-delay="100">

            <!-- Image Section -->
           <div class="col-lg-4 reservation-img" style="background-image: url('{{ asset('/assets/img/s2.jpg') }}'); min-height: 500px; background-size: cover; background-position: center;">
            </div>

            <!-- Form Section -->
            <div class="col-lg-8 p-4 d-flex flex-column justify-content-start" data-aos="fade-up" data-aos-delay="200">

                <div class="w-100 p-2">
                    <!-- Step 1: Recipe Selection -->
                    <div class="form-group mb-4">
                        <label class="fw-bold mb-2">Select a Pakistani Recipe</label>
                        <select id="recipeSelect" class="form-select form-control" style="border: 1px solid #ced4da;">
                            <option value="">-- Loading Recipes... --</option>
                        </select>
                    </div>

                    <!-- Step 2 & 3: Ingredients List -->
                    <div id="ingredientsContainer" class="d-none mb-4 p-3" style="background: white; border-radius: 8px; border: 1px solid #dee2e6;">
                        <h5 class="fw-bold mb-3">Ingredients</h5>
                        <div id="ingredientsList">
                            <!-- Dynamic checkboxes will appear here -->
                        </div>
                    </div>

                    <!-- Button -->
                    <div class="text-center mt-3">
                        <button id="calculateBtn" type="button" class="btn" style="background-color:#D33333; color:white; padding:10px 30px; display: none;">
                            <span id="btnText">Analyze Nutrition</span>
                            <span id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>

                    <!-- Error Message -->
                    <div id="errorMessage" class="alert alert-danger mt-3 d-none"></div>

                    <!-- Step 4: Results Container -->
                    <div id="resultsContainer" class="mt-4 d-none p-4" style="background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <h4 class="text-center fw-bold mb-3">Nutrition Summary</h4>
                        
                        <!-- Health Labels -->
                        <div id="healthLabels" class="d-flex flex-wrap gap-2 justify-content-center mb-4"></div>

                        <!-- Main Macros -->
                        <div class="row text-center mb-4">
                            <div class="col-md-3 col-6 mb-3">
                                <div class="p-3 border rounded h-100" style="background-color: #fff3cd;">
                                    <h3 class="fw-bold m-0" id="resCalories">0</h3>
                                    <small class="text-muted">Calories</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="p-3 border rounded h-100" style="background-color: #d1e7dd;">
                                    <h3 class="fw-bold m-0 text-success" id="resProtein">0g</h3>
                                    <small class="text-muted">Protein</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="p-3 border rounded h-100" style="background-color: #cff4fc;">
                                    <h3 class="fw-bold m-0 text-info" id="resCarbs">0g</h3>
                                    <small class="text-muted">Carbs</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="p-3 border rounded h-100" style="background-color: #f8d7da;">
                                    <h3 class="fw-bold m-0 text-danger" id="resFats">0g</h3>
                                    <small class="text-muted">Fats</small>
                                </div>
                            </div>
                        </div>

                        <!-- Detailed Table -->
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mt-2">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ingredient</th>
                                        <th>Grams</th>
                                        <th>Cals</th>
                                        <th>Protein</th>
                                        <th>Carbs</th>
                                        <th>Fat</th>
                                    </tr>
                                </thead>
                                <tbody id="breakdownTableBody">
                                </tbody>
                                <tfoot class="table-secondary fw-bold">
                                    <tr>
                                        <td colspan="2" class="text-end">Totals:</td>
                                        <td id="totCals">0</td>
                                        <td id="totPro">0g</td>
                                        <td id="totCarbs">0g</td>
                                        <td id="totFats">0g</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</section>

<!-- Required JS -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const recipeSelect = document.getElementById('recipeSelect');
    const ingredientsContainer = document.getElementById('ingredientsContainer');
    const ingredientsList = document.getElementById('ingredientsList');
    const calculateBtn = document.getElementById('calculateBtn');
    const resultsContainer = document.getElementById('resultsContainer');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');
    const errorMessage = document.getElementById('errorMessage');

    let currentRecipeDietTags = [];
    let currentIngredients = [];

    // 1. Fetch Recipes on Load
    fetch('/api/recipes')
        .then(response => response.json())
        .then(data => {
            recipeSelect.innerHTML = '<option value="">-- Choose a Recipe --</option>';
            data.forEach(recipe => {
                const opt = document.createElement('option');
                opt.value = recipe.name;
                opt.textContent = recipe.name;
                recipeSelect.appendChild(opt);
            });
        })
        .catch(err => console.error("Error loading recipes:", err));

    // 2. Fetch Ingredients when Recipe changes
    recipeSelect.addEventListener('change', function() {
        resultsContainer.classList.add('d-none');
        errorMessage.classList.add('d-none');
        
        const selected = this.value;
        if (!selected) {
            ingredientsContainer.classList.add('d-none');
            calculateBtn.style.display = 'none';
            return;
        }

        fetch(`/api/recipe-ingredients?recipe=${encodeURIComponent(selected)}`)
            .then(res => res.json())
            .then(data => {
                currentRecipeDietTags = data.diet_tags || [];
                currentIngredients = data.ingredients || [];
                
                ingredientsList.innerHTML = '';
                
                currentIngredients.forEach((ing, index) => {
                    const row = document.createElement('div');
                    row.className = 'd-flex align-items-center mb-2';
                    row.innerHTML = `
                        <div class="form-check flex-grow-1">
                            <input class="form-check-input ing-checkbox" type="checkbox" value="${index}" id="ing_${index}" checked>
                            <label class="form-check-label" for="ing_${index}">
                                ${ing.display_name}
                            </label>
                        </div>
                        <div class="input-group input-group-sm" style="width: 120px;">
                            <input type="number" class="form-control ing-grams" data-index="${index}" value="${ing.default_grams}" min="1">
                            <span class="input-group-text">g</span>
                        </div>
                    `;
                    ingredientsList.appendChild(row);
                });

                ingredientsContainer.classList.remove('d-none');
                calculateBtn.style.display = 'inline-block';
            })
            .catch(err => {
                errorMessage.textContent = "Failed to load ingredients.";
                errorMessage.classList.remove('d-none');
            });
    });

    // 3. Handle Calculation Submit
    calculateBtn.addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.ing-checkbox:checked');
        if (checkboxes.length === 0) {
            alert("Please select at least one ingredient.");
            return;
        }

        const payloadIngredients = [];
        checkboxes.forEach(cb => {
            const index = cb.value;
            const ingData = currentIngredients[index];
            const gramsInput = document.querySelector(`.ing-grams[data-index="${index}"]`);
            
            payloadIngredients.push({
                display_name: ingData.display_name,
                usda_query: ingData.usda_query,
                grams: parseFloat(gramsInput.value) || 0
            });
        });

        // UI Loading
        btnText.textContent = "Calculating...";
        btnSpinner.classList.remove('d-none');
        calculateBtn.disabled = true;
        resultsContainer.classList.add('d-none');
        errorMessage.classList.add('d-none');

        // AJAX Request
        fetch('/api/nutrition/calculate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                ingredients: payloadIngredients,
                diet_tags: currentRecipeDietTags
            })
        })
        .then(res => {
            if (!res.ok) throw new Error("API request failed");
            return res.json();
        })
        .then(data => {
            renderResults(data);
        })
        .catch(err => {
            errorMessage.textContent = "Error calculating nutrition. Please check your API key and connection.";
            errorMessage.classList.remove('d-none');
            console.error(err);
        })
        .finally(() => {
            btnText.textContent = "Analyze Nutrition";
            btnSpinner.classList.add('d-none');
            calculateBtn.disabled = false;
        });
    });

    // 4. Render Results
    function renderResults(data) {
        // Render Labels
        const labelsContainer = document.getElementById('healthLabels');
        labelsContainer.innerHTML = '';
        if (data.labels && data.labels.length > 0) {
            data.labels.forEach(lbl => {
                const badge = document.createElement('span');
                badge.className = `badge ${lbl.class} px-3 py-2`;
                badge.style.fontSize = "0.9rem";
                badge.textContent = lbl.name;
                labelsContainer.appendChild(badge);
            });
        }

        // Render Totals
        document.getElementById('resCalories').textContent = data.totals.calories;
        document.getElementById('resProtein').textContent = data.totals.protein + 'g';
        document.getElementById('resCarbs').textContent = data.totals.carbs + 'g';
        document.getElementById('resFats').textContent = data.totals.fats + 'g';

        // Render Table Breakdown
        const tbody = document.getElementById('breakdownTableBody');
        tbody.innerHTML = '';
        data.breakdown.forEach(item => {
            tbody.innerHTML += `
                <tr>
                    <td>${item.name}</td>
                    <td>${item.grams}g</td>
                    <td>${item.calories}</td>
                    <td>${item.protein}g</td>
                    <td>${item.carbs}g</td>
                    <td>${item.fats}g</td>
                </tr>
            `;
        });

        // Table Footers
        document.getElementById('totCals').textContent = data.totals.calories;
        document.getElementById('totPro').textContent = data.totals.protein + 'g';
        document.getElementById('totCarbs').textContent = data.totals.carbs + 'g';
        document.getElementById('totFats').textContent = data.totals.fats + 'g';

        resultsContainer.classList.remove('d-none');
        
        // Scroll to results
        resultsContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
});
</script>

@endsection
