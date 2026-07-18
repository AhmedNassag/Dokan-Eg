<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $shop->name }}</title>
  <link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $shop->font_family) }}&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: '{{ $shop->font_family }}', sans-serif; color: #222; background: #fff; line-height: 1.6; }
    a { text-decoration: none; color: inherit; }

    .navbar { padding: 2rem 4rem; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eee; }
    .navbar .logo img { height: 36px; }
    .navbar .logo span { font-size: 1.2rem; font-weight: 600; margin-left: 10px; letter-spacing: .5px; }
    .navbar nav a { margin-left: 2.5rem; font-size: .9rem; color: #666; letter-spacing: .5px; text-transform: uppercase; }
    .navbar nav a:hover { color: {{ $shop->primary_color }}; }

    .hero { text-align: center; padding: 8rem 2rem 5rem; max-width: 700px; margin: 0 auto; }
    .hero h1 { font-size: 3rem; font-weight: 300; letter-spacing: 2px; margin-bottom: 1rem; }
    .hero p { font-size: 1.1rem; color: #888; font-weight: 300; }

    .divider { width: 60px; height: 1px; background: {{ $shop->primary_color }}; margin: 0 auto 3rem; }

    .section-title { text-align: center; font-size: 1.3rem; font-weight: 300; letter-spacing: 4px; text-transform: uppercase; margin: 2rem 0 3rem; color: #999; }

    .products-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1px; max-width: 1100px; margin: 0 auto; padding: 0 2rem 6rem; background: #eee; border: 1px solid #eee; }
    .product-card { background: #fff; text-align: center; padding: 2.5rem 2rem; transition: background .3s; position: relative; }
    .product-card:hover { background: #fafafa; }
    .product-card img { width: 200px; height: 200px; object-fit: cover; margin: 0 auto 1.5rem; display: block; filter: grayscale(20%); transition: filter .3s; }
    .product-card:hover img { filter: grayscale(0); }
    .product-card h3 { font-size: .95rem; font-weight: 400; letter-spacing: 1px; margin-bottom: .8rem; }
    .product-card .price { font-size: 1rem; color: {{ $shop->primary_color }}; font-weight: 500; }
    .product-card .badge { position: absolute; top: 15px; left: 15px; font-size: .7rem; letter-spacing: 1px; text-transform: uppercase; color: {{ $shop->primary_color }}; font-weight: 600; }

    .footer { text-align: center; padding: 3rem; border-top: 1px solid #eee; color: #bbb; font-size: .8rem; letter-spacing: 1px; }
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
      <div class="divider"></div>
      <p>{{ $shop->description ?? 'Less is more. Explore our carefully curated selection.' }}</p>
    </section>

    <h2 class="section-title" id="products">Products</h2>

    <div class="products-grid">
      @foreach($staticProducts as $product)
        <div class="product-card">
          @if($product['badge'])
            <span class="badge">{{ $product['badge'] }}</span>
          @endif
          <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}">
          <h3>{{ $product['name'] }}</h3>
          <span class="price">${{ number_format($product['price'], 2) }}</span>
        </div>
      @endforeach
    </div>
  @endif

  <footer class="footer">
    <p>&copy; {{ date('Y') }} {{ $shop->name }}</p>
  </footer>
</body>
</html>
