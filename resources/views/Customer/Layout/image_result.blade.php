@extends('Customer.Layout.app')
@section('content')
<section id="book-a-table" class="book-a-table section">
    <div class="container section-title" data-aos="fade-up">
        <h2 style="color: black">Recognition Result</h2>
        <p><span>Your</span> <span class="description-title">AI Predicted Recipe<br></span></p>
    </div>

    <div class="container" style="background-color: #fff9f9; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <div class="row" data-aos="fade-up" data-aos-delay="100">
            {{-- Left: Uploaded image + dish name --}}
            <div class="col-lg-5 text-center mb-4 mb-lg-0">
                <img src="{{ $imageSrc }}" alt="Uploaded Food" class="img-fluid rounded"
                     style="max-height: 380px; width:100%; object-fit: cover; box-shadow: 0 5px 15px rgba(0,0,0,0.12);">

                <div class="mt-4">
                    <h3 style="color: #ce1212; font-weight: bold;">
                        {{ $metadata['dish_name'] ?? str_replace('_', ' ', ucwords(str_replace('_', ' ', $prediction))) }}
                    </h3>
                    <p class="text-muted mb-1">
                        <i class="bi bi-globe"></i> {{ $metadata['cuisine'] ?? 'Unknown Cuisine' }}
                    </p>

                    {{-- Confidence Badge --}}
                    <span class="badge px-3 py-2 mt-1"
                          style="background-color: {{ $confidence >= 60 ? '#198754' : ($confidence >= 35 ? '#fd7e14' : '#dc3545') }}; font-size:14px;">
                        <i class="bi bi-cpu"></i> AI Confidence: {{ $confidence }}%
                    </span>
                </div>

                {{-- Top-3 Alternatives --}}
                <div class="mt-4 text-start px-2">
                    <h6 class="fw-bold mb-2" style="color:#333;"><i class="bi bi-list-ol"></i> Top Predictions</h6>
                    @foreach($top3 as $i => $item)
                        <div class="mb-2 p-2 rounded {{ $i === 0 ? 'border border-danger' : 'bg-white border' }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <span style="font-size:13px; font-weight: {{ $i === 0 ? '700' : '500' }}; color: {{ $i === 0 ? '#ce1212' : '#555' }};">
                                    #{{ $i+1 }} {{ str_replace('_', ' ', ucwords(str_replace('_', ' ', $item['prediction']))) }}
                                </span>
                                <span class="badge bg-secondary">{{ $item['confidence'] }}%</span>
                            </div>
                            <div class="progress mt-1" style="height:5px;">
                                <div class="progress-bar" role="progressbar"
                                     style="width: {{ $item['confidence'] }}%; background-color: {{ $i === 0 ? '#ce1212' : '#6c757d' }};">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Right: Metadata --}}
            <div class="col-lg-7 px-4">
                <h4 class="mb-3 border-bottom pb-2">Description</h4>
                <p>{{ $metadata['description'] ?? 'No description available for this dish.' }}</p>

                <div class="row mt-4">
                    <div class="col-md-6 mb-3">
                        <div class="p-3 bg-white rounded shadow-sm h-100">
                            <h5 style="color: #ce1212;"><i class="bi bi-geo-alt"></i> Origin & Category</h5>
                            <ul class="list-unstyled mb-0">
                                <li><strong>Cuisine:</strong> {{ $metadata['cuisine'] ?? 'Pakistani/Indian' }}</li>
                                <li><strong>Category:</strong> {{ $metadata['category'] ?? 'Main Course' }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="p-3 bg-white rounded shadow-sm h-100">
                            <h5 style="color: #ce1212;"><i class="bi bi-star"></i> Recipe Rating</h5>
                            <div class="mb-2">
                                <span class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= ($metadata['rating'] ?? 4))
                                            <i class="bi bi-star-fill"></i>
                                        @else
                                            <i class="bi bi-star"></i>
                                        @endif
                                    @endfor
                                </span>
                                <span class="ms-2">{{ $metadata['rating'] ?? '4.2' }}/5.0</span>
                            </div>
                            <p class="mb-0 text-muted">{{ $metadata['review_count'] ?? 'Based on 150+ reviews' }}</p>
                        </div>
                    </div>
                </div>

                <h4 class="mt-3 mb-3 border-bottom pb-2">Ingredients</h4>
                <div class="row">
                    @if(!empty($metadata['ingredients']))
                        @foreach($metadata['ingredients'] as $ingredient)
                            <div class="col-md-6 mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i> {{ ucfirst($ingredient) }}
                            </div>
                        @endforeach
                    @else
                        <div class="col-12"><p class="text-muted">Ingredients not available.</p></div>
                    @endif
                </div>

                <div class="mt-4 text-center text-lg-start">
                    <a href="/image/recognition" class="btn text-white px-4 py-2 rounded-pill shadow"
                       style="background-color: #ce1212;">
                        <i class="bi bi-arrow-left"></i> Try Another Image
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
