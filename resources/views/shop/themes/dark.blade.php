<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $shop->name }}</title>
  <link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $shop->font_family) }}&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: '{{ $shop->font_family }}', sans-serif; color: #e0e0e0; background: #0d0d0d; line-height: 1.6; }
    a { text-decoration: none; color: inherit; }

    .navbar { background: #111; padding: 1rem 3rem; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #222; }
    .navbar .logo img { height: 40px; border-radius: 8px; }
    .navbar .logo span { font-size: 1.3rem; font-weight: 700; margin-left: 12px; color: {{ $shop->primary_color }}; }
    .navbar nav a { margin-left: 2rem; color: #888; font-weight: 500; transition: color .2s; }
    .navbar nav a:hover { color: {{ $shop->primary_color }}; }

    .hero { background: linear-gradient(180deg, #111 0%, #1a1a1a 100%); padding: 6rem 3rem; text-align: center; border-bottom: 1px solid #222; }
    .hero h1 { font-size: 3rem; font-weight: 800; color: #fff; margin-bottom: 1rem; }
    .hero h1 span { color: {{ $shop->primary_color }}; }
    .hero p { font-size: 1.1rem; color: #777; max-width: 550px; margin: 0 auto; }

    .section-title { text-align: center; font-size: 1.6rem; font-weight: 700; margin: 4rem 0 2rem; color: #fff; }
    .section-title span { color: {{ $shop->primary_color }}; }

    .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1.5rem; max-width: 1200px; margin: 0 auto; padding: 0 2rem 4rem; }
    .product-card { background: #161616; border: 1px solid #222; border-radius: 16px; overflow: hidden; transition: all .3s; position: relative; }
    .product-card:hover { border-color: {{ $shop->primary_color }}; box-shadow: 0 0 30px rgba({{ $shop->primary_color }}, .15); transform: translateY(-4px); }
    .product-card img { width: 100%; height: 220px; object-fit: cover; opacity: .9; transition: opacity .3s; }
    .product-card:hover img { opacity: 1; }
    .product-card .info { padding: 1.2rem; }
    .product-card .info h3 { font-size: 1rem; color: #eee; margin-bottom: .5rem; }
    .product-card .info .price { color: {{ $shop->primary_color }}; font-weight: 700; font-size: 1.15rem; }
    .product-card .badge { position: absolute; top: 12px; left: 12px; background: {{ $shop->primary_color }}; color: #000; padding: 4px 12px; border-radius: 6px; font-size: .75rem; font-weight: 700; }

    .footer { background: #111; border-top: 1px solid #222; text-align: center; padding: 2rem; color: #555; font-size: .85rem; }
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
      <p>{{ $shop->description ?? 'Welcome to the dark side of shopping. Premium products, dark vibes.' }}</p>
    </section>

    <h2 class="section-title" id="products"><span>//</span> Products</h2>

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
