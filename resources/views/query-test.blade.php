<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <!-- Include Bootstrap 5 CSS via CDN for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">
        <h1 class="text-center mb-4 fw-bold">Product List</h1>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach ($products as $product)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <!-- Render product image from DB or fallback if empty -->
                        <img src="{{ $product->image ?? 'https://picsum.photos/400/300' }}" 
                             class="card-img-top" 
                             alt="{{ $product->name }}"
                             style="height: 250px; object-fit: cover;">
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold">{{ $product->name }}</h5>
                            <p class="card-text text-muted flex-grow-1">{{ $product->description }}</p>
                            <p class="card-text text-primary fw-bold fs-5">${{ number_format($product->price, 2) }}</p>
                            <button class="btn btn-primary w-100 mt-2">Add to Cart</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
