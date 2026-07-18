<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $shop->name }}</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family={{ str_replace(' ', '+', $shop->font_family) }}&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: '{{ $shop->font_family }}', sans-serif; color: #2c2c2c; background: #fdfbf7; line-height: 1.7; }
    a { text-decoration: none; color: inherit; }
    .serif { font-family: 'Playfair Display', serif; }

    .navbar { background: #fdfbf7; padding: 1.2rem 4rem; display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid {{ $shop->primary_color }}; }
    .navbar .logo img { height: 44px; border-radius: 4px; }
    .navbar .logo span { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 700; margin-left: 14px; color: {{ $shop->primary_color }}; }
    .navbar nav a { margin-left: 2.5rem; font-size: .9rem; color: #666; letter-spacing: 1px; text-transform: uppercase; font-weight: 500; }
    .navbar nav a:hover { color: {{ $shop->primary_color }}; }

    .hero { background: linear-gradient(135deg, {{ $shop->primary_color }}15, {{ $shop->secondary_color }}30); padding: 7rem 3rem; text-align: center; position: relative; overflow: hidden; }
    .hero::before { content: ''; position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 1px; height: 40px; background: {{ $shop->primary_color }}; }
    .hero::after { content: ''; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 1px; height: 40px; background: {{ $shop->primary_color }}; }
    .hero h1 { font-family: 'Playfair Display', serif; font-size: 3.5rem; font-weight: 700; color: #1a1a1a; margin-bottom: .5rem; }
    .hero .tagline { font-family: 'Playfair Display', serif; font-size: 1.3rem; color: {{ $shop->primary_color }}; font-style: italic; margin-bottom: 1rem; }
    .hero p { font-size: 1.05rem; color: #888; max-width: 550px; margin: 0 auto; }
    .hero .ornament { font-size: 1.5rem; color: {{ $shop->primary_color }}; margin: 1.5rem 0; letter-spacing: 8px; }

    .section-title { text-align: center; margin: 4rem 0 2.5rem; }
    .section-title h2 { font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 600; color: #1a1a1a; }
    .section-title .line { width: 60px; height: 2px; background: {{ $shop->primary_color }}; margin: .8rem auto 0; }

    .products-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; max-width: 1100px; margin: 0 auto; padding: 0 2rem 5rem; }
    .product-card { background: #fff; border: 1px solid #f0ece4; border-radius: 4px; overflow: hidden; transition: all .4s; position: relative; }
    .product-card:hover { box-shadow: 0 12px 40px rgba(0,0,0,.08); transform: translateY(-4px); }
    .product-card img { width: 100%; height: 280px; object-fit: cover; }
    .product-card .info { padding: 1.5rem; text-align: center; }
    .product-card .info h3 { font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 600; margin-bottom: .6rem; color: #1a1a1a; }
    .product-card .info .price { font-size: 1.1rem; color: {{ $shop->primary_color }}; font-weight: 600; letter-spacing: .5px; }
    .product-card .info .ornament-sm { font-size: .8rem; color: {{ $shop->primary_color }}; margin: .5rem 0; letter-spacing: 6px; }
    .product-card .badge { position: absolute; top: 15px; right: 15px; border: 1px solid {{ $shop->primary_color }}; color: {{ $shop->primary_color }}; padding: 4px 14px; font-size: .7rem; letter-spacing: 1px; text-transform: uppercase; font-weight: 600; background: #fdfbf7; }

    .footer { background: #1a1a1a; color: #999; text-align: center; padding: 3rem; font-size: .85rem; letter-spacing: .5px; }
    .footer .brand { font-family: 'Playfair Display', serif; font-size: 1.3rem; color: {{ $shop->primary_color }}; margin-bottom: .5rem; }
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
      <a href="#products">Collection</a>
      <a href="#">Contact</a>
    </nav>
  </header>

  @if($sections->count())
    @include('shop._sections')
  @else
    <section class="hero">
      <h1 class="serif">{{ $shop->name }}</h1>
      <p class="tagline serif">Timeless Elegance</p>
      <div class="ornament">&mdash; &mdash; &mdash;</div>
      <p>{{ $shop->description ?? 'Curated with passion, delivered with care. Experience luxury redefined.' }}</p>
    </section>

    <div class="section-title" id="products">
      <h2 class="serif">Our Collection</h2>
      <div class="line"></div>
    </div>

    <div class="products-grid">
      @foreach($staticProducts as $product)
        <div class="product-card">
          @if($product['badge'])
            <span class="badge">{{ $product['badge'] }}</span>
          @endif
          <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}">
          <div class="info">
            <h3 class="serif">{{ $product['name'] }}</h3>
            <div class="ornament-sm">&bull; &bull; &bull;</div>
            <span class="price">${{ number_format($product['price'], 2) }}</span>
          </div>
        </div>
      @endforeach
    </div>
  @endif

  <footer class="footer">
    <div class="brand">{{ $shop->name }}</div>
    <p>&copy; {{ date('Y') }} All rights reserved.</p>
  </footer>
</body>
</html>
