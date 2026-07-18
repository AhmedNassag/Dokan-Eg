<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $shop->name }}</title>
  <link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $shop->font_family) }}&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: '{{ $shop->font_family }}', sans-serif; color: #333; background: #f5f5f5; }
    a { text-decoration: none; color: inherit; }

    .navbar { background: {{ $shop->primary_color }}; padding: 1rem 2rem; display: flex; align-items: center; justify-content: space-between; }
    .navbar .logo img { height: 45px; border-radius: 8px; }
    .navbar .logo span { color: {{ $shop->secondary_color }}; font-size: 1.3rem; font-weight: 700; margin-left: 12px; }
    .navbar nav a { color: {{ $shop->secondary_color }}; margin-left: 1.5rem; font-weight: 500; transition: opacity .2s; }
    .navbar nav a:hover { opacity: .8; }

    .hero { background: linear-gradient(135deg, {{ $shop->primary_color }}, {{ $shop->secondary_color }}); color: #fff; text-align: center; padding: 5rem 2rem; }
    .hero h1 { font-size: 2.8rem; margin-bottom: 1rem; }
    .hero p { font-size: 1.15rem; opacity: .9; max-width: 600px; margin: 0 auto; }

    .section-title { text-align: center; font-size: 1.8rem; font-weight: 700; margin: 3rem 0 2rem; color: {{ $shop->primary_color }}; }

    .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1.5rem; max-width: 1200px; margin: 0 auto; padding: 0 2rem 4rem; }
    .product-card { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.08); transition: transform .2s, box-shadow .2s; }
    .product-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
    .product-card img { width: 100%; height: 220px; object-fit: cover; }
    .product-card .info { padding: 1rem; }
    .product-card .info h3 { font-size: 1rem; margin-bottom: .5rem; }
    .product-card .info .price { color: {{ $shop->primary_color }}; font-weight: 700; font-size: 1.1rem; }
    .product-card .badge { position: absolute; top: 10px; left: 10px; background: {{ $shop->primary_color }}; color: {{ $shop->secondary_color }}; padding: 4px 12px; border-radius: 20px; font-size: .75rem; font-weight: 600; }
    .product-card { position: relative; }

    .footer { background: {{ $shop->primary_color }}; color: {{ $shop->secondary_color }}; text-align: center; padding: 2rem; font-size: .9rem; }
  </style>
</head>
<body>
  <header class="navbar">
    <div class="logo" style="display:flex;align-items:center;">
      @if($shop->logo)
        <img src="{{ $shop->logo }}" alt="{{ $shop->name }}">
      @endif
      <span>{{ $shop->name }}</span>
    </div>
    <nav>
      <a href="#">Home</a>
      <a href="#products">Products</a>
      <a href="#">Contact</a>
    </nav>
  </header>

  @if($sections->count())
    @include('shop._sections')
  @else
    <section class="hero">
      <h1>{{ $shop->name }}</h1>
      <p>{{ $shop->description ?? 'Welcome to our store. Discover our curated collection of quality products.' }}</p>
    </section>

    <h2 class="section-title" id="products">Our Products</h2>

    <div class="products-grid">
      @foreach($staticProducts as $product)
        <div class="product-card">
          @if($product['badge'])
            <span class="badge">{{ $product['badge'] }}</span>
          @endif
          <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}">
          <div class="info">
            <h3>{{ $product['name'] }}</h3>
            <span class="price">${{ number_format($product['price'], 2) }}</span>
          </div>
        </div>
      @endforeach
    </div>
  @endif

  <footer class="footer">
    <p>&copy; {{ date('Y') }} {{ $shop->name }}. All rights reserved.</p>
  </footer>
</body>
</html>
