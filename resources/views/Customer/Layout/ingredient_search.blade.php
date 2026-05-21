@extends('Customer.Layout.app')

@section('content')
    <section class="section py-5" style="background: radial-gradient(circle at top left, rgba(255, 247, 236, 0.95), #f6e9d7 30%, #ffffff 100%);">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-5" data-aos="fade-right" data-aos-delay="100">
                    <div class="rounded-4 shadow-lg overflow-hidden position-relative" style="min-height: 480px; background: linear-gradient(180deg, rgba(4, 51, 72, 0.88), rgba(30, 85, 120, 0.88)), url('{{ asset('assets/img/s1.jpg') }}') center/cover no-repeat;">
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(0, 0, 0, 0.18), rgba(0, 0, 0, 0.35));"></div>
                        <div class="position-relative h-100 d-flex flex-column justify-content-between p-5 text-white">
                            <div>
                                <span class="badge rounded-pill bg-warning text-dark py-2 px-3 mb-3" data-aos="fade-down" data-aos-delay="200">Ingredient Search</span>
                                <h2 class="fw-bold display-6 mb-3" style="color: #ffffff; text-shadow: 2px 2px 4px rgba(0,0,0,0.7);" data-aos="fade-up" data-aos-delay="300">Search recipes with fresh ingredients</h2>
                                <p class="fs-5 text-white-75" data-aos="fade-up" data-aos-delay="400">Type or select ingredients, then find recipes matched to your pantry. The results are beautiful, fast, and easy to scan.</p>
                            </div>
                            <div class="mt-4" data-aos="fade-up" data-aos-delay="500">
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-light text-dark rounded-pill py-2 px-3">Tomato</span>
                                    <span class="badge bg-light text-dark rounded-pill py-2 px-3">Coriander</span>
                                    <span class="badge bg-light text-dark rounded-pill py-2 px-3">Chicken</span>
                                </div>
                                <div class="mt-4">
                                    <p class="mb-1 text-uppercase small letter-spacing-2 text-white-50">Tip</p>
                                    <p class="mb-0 text-white-75">Click a suggestion or press Enter to add an ingredient instantly.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7" data-aos="fade-left" data-aos-delay="200">
                    <div class="card rounded-4 border-0 shadow p-4">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between mb-4">
                                <div>
                                    <h3 class="fw-bold mb-1" data-aos="fade-up" data-aos-delay="300">Ingredient search</h3>
                                    <p class="text-muted mb-0" data-aos="fade-up" data-aos-delay="400">Select by ingredient and choose a search mode to get the best matches.</p>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('ingredient.search.submit') }}" novalidate>
                                @csrf
                                <div class="mb-4" data-aos="fade-up" data-aos-delay="500">
                                    <label for="ingredient_search" class="form-label fw-semibold">Search ingredients</label>
                                    <div class="position-relative">
                                        <input id="ingredient_search" type="text" class="form-control form-control-lg rounded-4" placeholder="Type ingredient name, e.g. chicken" autocomplete="off">
                                        <div id="ingredient_suggestions" class="list-group position-absolute w-100 mt-1 shadow-sm rounded-4" style="z-index: 20; max-height: 220px; overflow-y: auto;"></div>
                                    </div>
                                    <div id="selectedIngredients" class="mt-3 d-flex flex-wrap gap-2"></div>
                                    <div id="ingredientInputs"></div>
                                    @error('ingredients')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-2">Choose ingredients from the dropdown, or type and press Enter to add.</small>
                                </div>

                                <div class="row align-items-center mb-4 gy-3" data-aos="fade-up" data-aos-delay="600">
                                    <div class="col-md-7">
                                        <p class="mb-2 fw-semibold">Search mode</p>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="search_mode" id="general_mode" value="general" {{ (isset($searchMode) ? $searchMode : 'general') === 'general' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="general_mode">General recipe search</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="search_mode" id="profile_mode" value="profile" {{ (isset($searchMode) && $searchMode === 'profile') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="profile_mode">Health, diet & age suggestions</label>
                                        </div>
                                    </div>
                                    <div class="col-md-5 text-md-end">
                                        <button type="submit" class="btn rounded-pill px-4 py-2" style="background-color: #ff6b6b; color: white; border: none; font-weight: 600;" data-aos="zoom-in" data-aos-delay="700">Search recipes</button>
                                    </div>
                                </div>

                                @if(!Auth::check())
                                    <div class="alert alert-info py-3 rounded-4" data-aos="fade-in" data-aos-delay="800">Login to use profile-based recipe suggestions. General search still works.</div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($featuredRecipes) && count($featuredRecipes) > 0)
                <div class="mt-5" data-aos="fade-up" data-aos-delay="100">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="fw-bold mb-1">Featured Recipes</h4>
                            <p class="text-muted mb-0">Discover some popular recipes to get started.</p>
                        </div>
                    </div>
                    <div class="row g-4">
                        @foreach($featuredRecipes as $recipe)
                            <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ 200 + ($loop->index * 100) }}">
                                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                    <div class="position-relative" style="min-height: 180px; background: linear-gradient(180deg, rgba(255, 193, 7, 0.3), rgba(255, 235, 59, 0.3)), url('{{ asset('assets/img/s1.jpg') }}') center/cover no-repeat;">
                                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(255,255,255,0.4);"></div>
                                        <div class="position-absolute bottom-0 start-0 p-4 text-dark">
                                            <h5 class="mb-1">{{ $recipe['name'] }}</h5>
                                            <small class="text-muted">{{ $recipe['category'] }} · {{ ucfirst($recipe['cuisine']) }}</small>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted mb-3">{{ $recipe['description'] }}</p>
                                        <div class="d-flex flex-wrap gap-2 mb-3">
                                            @foreach(array_slice($recipe['ingredients'], 0, 3) as $ingredient)
                                                <span class="badge bg-light text-dark rounded-pill py-2 px-3">{{ $ingredient }}</span>
                                            @endforeach
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            @if(isset($recipe['health_tags']) && count($recipe['health_tags']) > 0)
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($recipe['health_tags'] as $tag)
                                                        @php
                                                            $emoji = '';
                                                            $label = '';
                                                            switch($tag) {
                                                                case 'heart_friendly':
                                                                    $emoji = '❤️';
                                                                    $label = 'Heart Friendly';
                                                                    break;
                                                                case 'diabetes_friendly':
                                                                    $emoji = '🩸';
                                                                    $label = 'Diabetes Friendly';
                                                                    break;
                                                                case 'low_salt':
                                                                    $emoji = '🧂';
                                                                    $label = 'Low Salt';
                                                                    break;
                                                                case 'anemia_friendly':
                                                                    $emoji = '🩸';
                                                                    $label = 'Anemia Friendly';
                                                                    break;
                                                                case 'low_fat':
                                                                    $emoji = '🥑';
                                                                    $label = 'Low Fat';
                                                                    break;
                                                                default:
                                                                    $emoji = '🏥';
                                                                    $label = ucfirst(str_replace('_', ' ', $tag));
                                                            }
                                                        @endphp
                                                        <span class="badge bg-success text-white rounded-pill py-2 px-3" style="font-size: 0.8rem;">{{ $emoji }} {{ $label }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted small">No specific health concerns</span>
                                            @endif
                                            <span class="text-muted small">Diet: {{ implode(', ', $recipe['diet_tags'] ?? []) ?: 'Standard' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(isset($message) && $message)
                <div class="alert alert-warning rounded-4 mt-4 shadow-sm" data-aos="fade-in"> {{ $message }}</div>
            @endif

            @if(isset($recipes) && count($recipes) > 0)
                <div class="mt-5" data-aos="fade-up" data-aos-delay="100">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="fw-bold mb-1">Suggested recipes</h4>
                            <p class="text-muted mb-0">Recipes that match your selected ingredients.</p>
                        </div>
                        <span class="text-muted">{{ count($recipes) }} result{{ count($recipes) === 1 ? '' : 's' }}</span>
                    </div>
                    <div class="row g-4">
                        @foreach($recipes as $recipe)
                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="{{ 200 + ($loop->index * 100) }}">
                                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                    <div class="position-relative" style="min-height: 180px; background: linear-gradient(180deg, rgba(255, 193, 7, 0.3), rgba(255, 235, 59, 0.3)), url('{{ asset('assets/img/s1.jpg') }}') center/cover no-repeat;">
                                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(255,255,255,0.4);"></div>
                                        <div class="position-absolute bottom-0 start-0 p-4 text-dark">
                                            <h5 class="mb-1">{{ $recipe['name'] }}</h5>
                                            <small class="text-muted">{{ $recipe['category'] }} · {{ ucfirst($recipe['cuisine']) }}</small>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted mb-3">{{ $recipe['description'] }}</p>
                                        <div class="d-flex flex-wrap gap-2 mb-3">
                                            @foreach($recipe['ingredients'] as $ingredient)
                                                <span class="badge bg-light text-dark rounded-pill py-2 px-3">{{ $ingredient }}</span>
                                            @endforeach
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            @if(isset($recipe['health_tags']) && count($recipe['health_tags']) > 0)
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($recipe['health_tags'] as $tag)
                                                        @php
                                                            $emoji = '';
                                                            $label = '';
                                                            switch($tag) {
                                                                case 'heart_friendly':
                                                                    $emoji = '❤️';
                                                                    $label = 'Heart Friendly';
                                                                    break;
                                                                case 'diabetes_friendly':
                                                                    $emoji = '🩸';
                                                                    $label = 'Diabetes Friendly';
                                                                    break;
                                                                case 'low_salt':
                                                                    $emoji = '🧂';
                                                                    $label = 'Low Salt';
                                                                    break;
                                                                case 'anemia_friendly':
                                                                    $emoji = '🩸';
                                                                    $label = 'Anemia Friendly';
                                                                    break;
                                                                case 'low_fat':
                                                                    $emoji = '🥑';
                                                                    $label = 'Low Fat';
                                                                    break;
                                                                default:
                                                                    $emoji = '🏥';
                                                                    $label = ucfirst(str_replace('_', ' ', $tag));
                                                            }
                                                        @endphp
                                                        <span class="badge bg-success text-white rounded-pill py-2 px-3" style="font-size: 0.8rem;">{{ $emoji }} {{ $label }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted small">No specific health concerns</span>
                                            @endif
                                            <span class="badge bg-light text-dark rounded-pill py-2 px-3">Matches: {{ count(array_intersect(array_map('strtolower', $recipe['ingredients']), array_map('strtolower', $selectedIngredients ?? []))) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @elseif(isset($recipes))
                <div class="alert alert-secondary rounded-4 mt-4" data-aos="fade-in">No recipe results were found. Try a different ingredient combination.</div>
            @endif
        </div>
    </section>

    @push('scripts')
        <script>
            const ingredients = @json($ingredients ?? []);
            const selectedIngredients = new Set(@json($selectedIngredients ?? []));

            const searchInput = document.getElementById('ingredient_search');
            const suggestionBox = document.getElementById('ingredient_suggestions');
            const selectedContainer = document.getElementById('selectedIngredients');
            const inputsContainer = document.getElementById('ingredientInputs');

            function createIngredientBadge(name) {
                const badge = document.createElement('span');
                badge.className = 'badge bg-primary text-white rounded-pill d-inline-flex align-items-center gap-2 py-2 px-3';
                badge.innerHTML = `<span>${name}</span><button type="button" class="btn-close btn-close-white btn-sm" aria-label="Remove"></button>`;
                badge.querySelector('button').addEventListener('click', () => {
                    selectedIngredients.delete(name);
                    renderSelected();
                });
                return badge;
            }

            function updateHiddenInputs() {
                inputsContainer.innerHTML = '';
                selectedIngredients.forEach((ingredient) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ingredients[]';
                    input.value = ingredient;
                    inputsContainer.appendChild(input);
                });
            }

            function renderSelected() {
                selectedContainer.innerHTML = '';
                updateHiddenInputs();
                selectedIngredients.forEach((ingredient) => {
                    selectedContainer.appendChild(createIngredientBadge(ingredient));
                });
            }

            function renderSuggestions(query) {
                suggestionBox.innerHTML = '';
                if (!query) {
                    return;
                }

                const lowerQuery = query.toLowerCase();
                const matches = ingredients.filter((ingredient) => ingredient.toLowerCase().includes(lowerQuery) && !selectedIngredients.has(ingredient));

                matches.slice(0, 8).forEach((ingredient) => {
                    const item = document.createElement('button');
                    item.type = 'button';
                    item.className = 'list-group-item list-group-item-action py-2 suggestion-item';
                    item.textContent = ingredient;
                    item.addEventListener('click', () => {
                        selectedIngredients.add(ingredient);
                        renderSelected();
                        searchInput.value = '';
                        suggestionBox.innerHTML = '';
                    });
                    suggestionBox.appendChild(item);
                });
            }

            searchInput.addEventListener('input', (event) => {
                renderSuggestions(event.target.value);
            });

            searchInput.addEventListener('keydown', (event) => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    const value = searchInput.value.trim();
                    if (!value) {
                        return;
                    }

                    const match = ingredients.find((ingredient) => ingredient.toLowerCase() === value.toLowerCase());
                    if (match && !selectedIngredients.has(match)) {
                        selectedIngredients.add(match);
                        renderSelected();
                    }
                    searchInput.value = '';
                    suggestionBox.innerHTML = '';
                }
            });

            document.addEventListener('click', (event) => {
                if (!event.target.closest('#ingredient_search') && !event.target.closest('#ingredient_suggestions')) {
                    suggestionBox.innerHTML = '';
                }
            });

            renderSelected();
        </script>
    @endpush
@endsection

