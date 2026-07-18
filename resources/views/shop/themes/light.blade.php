<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $shop->name }}</title>
  <link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $shop->font_family) }}&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: '{{ $shop->font_family }}', sans-serif; color: #444; background: #f9fafb; line-height: 1.7; }
    a { text-decoration: none; color: inherit; }

    .navbar { background: #fff; padding: 1rem 3rem; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 1px 3px rgba(0,0,0,.04); }
    .navbar .logo img { height: 40px; border-radius: 10px; }
    .navbar .logo span { font-size: 1.3rem; font-weight: 700; margin-left: 12px; color: #333; }
    .navbar nav a { margin-left: 2rem; color: #666; font-weight: 500; transition: color .2s; }
    .navbar nav a:hover { color: {{ $shop->primary_color }}; }

    .hero { background: #fff; margin: 2rem auto; max-width: 1200px; border-radius: 20px; padding: 5rem 3rem; display: flex; align-items: center; gap: 3rem; box-shadow: 0 2px 20px rgba(0,0,0,.04); }
    .hero-text { flex: 1; }
    .hero-text h1 { font-size: 2.8rem; font-weight: 800; color: #222; margin-bottom: 1rem; line-height: 1.2; }
    .hero-text p { font-size: 1.1rem; color: #888; margin-bottom: 1.5rem; }
    .hero-text .cta { display: inline-block; padding: .8rem 2rem; background: {{ $shop->primary_color }}; color: {{ $shop->secondary_color }}; border-radius: 12px; font-weight: 600; transition: transform .2s, box-shadow .2s; }
    .hero-text .cta:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,.15); }
    .hero-image { flex-shrink: 0; width: 300px; height: 300px; border-radius: 20px; overflow: hidden; box-shadow: 0 8px 30px rgba(0,0,0,.08); }
    .hero-image img { width: 100%; height: 100%; object-fit: cover; }

    .section-title { text-align: center; font-size: 1.8rem; font-weight: 700; margin: 4rem 0 2rem; color: #222; }

    .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(270px, 1fr)); gap: 1.5rem; max-width: 1200px; margin: 0 auto; padding: 0 2rem 4rem; }
    .product-card { background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.04); transition: all .3s; position: relative; }
    .product-card:hover { transform: translateY(-6px); box-shadow: 0 12px 35px rgba(0,0,0,.1); }
    .product-card img { width: 100%; height: 230px; object-fit: cover; }
    .product-card .info { padding: 1.2rem 1.5rem; }
    .product-card .info h3 { font-size: 1rem; font-weight: 600; margin-bottom: .4rem; color: #333; }
    .product-card .info .price { font-size: 1.15rem; font-weight: 700; color: {{ $shop->primary_color }}; }
    .product-card .badge { position: absolute; top: 12px; left: 12px; background: {{ $shop->primary_color }}; color: {{ $shop->secondary_color }}; padding: 4px 14px; border-radius: 10px; font-size: .75rem; font-weight: 600; }

    .footer { background: #fff; border-top: 1px solid #eee; text-align: center; padding: 2rem; color: #aaa; font-size: .85rem; }
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
        <p>{{ $shop->description ?? 'A light, airy shopping experience. Clean, bright, and beautiful.' }}</p>
        <a href="#products" class="cta">Explore Products</a>
      </div>
      @if($shop->cover)
        <div class="hero-image">
          <img src="{{ $shop->cover }}" alt="{{ $shop->name }}">
        </div>
      @endif
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
