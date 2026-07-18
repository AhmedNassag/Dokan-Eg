<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $shop->name }}</title>
  <link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $shop->font_family) }}&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: '{{ $shop->font_family }}', sans-serif; color: #1a1a2e; background: #fafafa; }
    a { text-decoration: none; color: inherit; }

    .navbar { background: #fff; padding: 1rem 3rem; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 20px rgba(0,0,0,.06); position: sticky; top: 0; z-index: 100; }
    .navbar .logo img { height: 42px; border-radius: 50%; }
    .navbar .logo span { font-size: 1.4rem; font-weight: 800; margin-left: 12px; background: linear-gradient(135deg, {{ $shop->primary_color }}, {{ $shop->secondary_color }}); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .navbar nav a { margin-left: 2rem; font-weight: 600; font-size: .95rem; color: #555; transition: color .2s; }
    .navbar nav a:hover { color: {{ $shop->primary_color }}; }

    .hero { background: linear-gradient(160deg, {{ $shop->primary_color }} 0%, {{ $shop->secondary_color }} 100%); color: #fff; padding: 6rem 3rem; display: flex; align-items: center; justify-content: space-between; max-width: 1200px; margin: 2rem auto; border-radius: 24px; }
    .hero-text h1 { font-size: 3rem; font-weight: 800; line-height: 1.2; margin-bottom: 1rem; }
    .hero-text p { font-size: 1.1rem; opacity: .9; max-width: 500px; }
    .hero-visual { width: 280px; height: 280px; border-radius: 50%; overflow: hidden; border: 6px solid rgba(255,255,255,.3); flex-shrink: 0; }
    .hero-visual img { width: 100%; height: 100%; object-fit: cover; }

    .section-title { text-align: center; font-size: 2rem; font-weight: 800; margin: 4rem 0 2rem; }
    .section-title span { background: linear-gradient(135deg, {{ $shop->primary_color }}, {{ $shop->secondary_color }}); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

    .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2rem; max-width: 1200px; margin: 0 auto; padding: 0 2rem 4rem; }
    .product-card { background: #fff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,.05); transition: all .3s ease; position: relative; }
    .product-card:hover { transform: translateY(-8px); box-shadow: 0 12px 40px rgba(0,0,0,.12); }
    .product-card img { width: 100%; height: 240px; object-fit: cover; }
    .product-card .info { padding: 1.2rem 1.5rem; }
    .product-card .info h3 { font-size: 1.05rem; font-weight: 700; margin-bottom: .5rem; }
    .product-card .info .price { font-size: 1.2rem; font-weight: 800; color: {{ $shop->primary_color }}; }
    .product-card .info .btn { display: inline-block; margin-top: .8rem; padding: .6rem 1.5rem; background: linear-gradient(135deg, {{ $shop->primary_color }}, {{ $shop->secondary_color }}); color: #fff; border-radius: 30px; font-weight: 600; font-size: .85rem; transition: transform .2s; }
    .product-card .info .btn:hover { transform: scale(1.05); }
    .product-card .badge { position: absolute; top: 12px; right: 12px; background: linear-gradient(135deg, {{ $shop->primary_color }}, {{ $shop->secondary_color }}); color: #fff; padding: 5px 14px; border-radius: 20px; font-size: .75rem; font-weight: 700; }

    .footer { background: #1a1a2e; color: #ccc; text-align: center; padding: 2.5rem; font-size: .85rem; }
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
      <div class="hero-text">
        <h1>{{ $shop->name }}</h1>
        <p>{{ $shop->description ?? 'Discover our premium collection crafted for you.' }}</p>
      </div>
      @if($shop->cover)
        <div class="hero-visual">
          <img src="{{ $shop->cover }}" alt="{{ $shop->name }}">
        </div>
      @endif
    </section>

    <h2 class="section-title" id="products"><span>Our Collection</span></h2>

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
            <a href="#" class="btn">Add to Cart</a>
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
