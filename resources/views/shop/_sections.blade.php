@foreach($sections as $section)
  @if($section->is_active)
    {{-- HERO --}}
    @if($section->type === 'hero')
      @php $c = $section->content ?? []; $s = $section->settings ?? []; @endphp
      <section class="bs-hero" style="background: {{ $s['bgColor'] ?? "linear-gradient(135deg, {$shop->primary_color}, {$shop->secondary_color})" }}; color: {{ $s['textColor'] ?? '#fff' }}; padding: {{ $s['paddingTop'] ?? 60 }}px 2rem {{ $s['paddingBottom'] ?? 60 }}px; text-align: {{ $c['alignment'] ?? 'center' }};">
        @if(!empty($c['backgroundImage']))
          <div class="bs-hero-bg" style="background-image:url('{{ $c['backgroundImage'] }}'); position:absolute;inset:0;background-size:cover;background-position:center; opacity:0.3;"></div>
        @endif
        <div style="position:relative;z-index:1;max-width:800px;margin:0 auto;">
          <h1 style="font-size:2.8rem;font-weight:800;margin-bottom:1rem;">{{ $c['heading'] ?? '' }}</h1>
          <p style="font-size:1.1rem;opacity:.9;margin-bottom:1.5rem;">{{ $c['subheading'] ?? '' }}</p>
          @if(!empty($c['buttonText']))
            <a href="{{ $c['buttonLink'] ?? '#' }}" style="display:inline-block;padding:.8rem 2rem;background:{{ $shop->primary_color }};color:{{ $shop->secondary_color }};border-radius:30px;font-weight:600;text-decoration:none;">{{ $c['buttonText'] }}</a>
          @endif
        </div>
      </section>
    @endif

    {{-- PRODUCTS --}}
    @if($section->type === 'products')
      @php $c = $section->content ?? []; $s = $section->settings ?? []; @endphp
      <section class="bs-products" style="background:{{ $s['bgColor'] ?? '' }}; padding:{{ $s['paddingTop'] ?? 40 }}px 2rem {{ $s['paddingBottom'] ?? 40 }}px;">
        <h2 style="text-align:center;font-size:1.8rem;font-weight:700;margin-bottom:2rem;color:{{ $shop->primary_color }};">{{ $c['heading'] ?? 'Products' }}</h2>
        <div style="display:grid;grid-template-columns:repeat({{ $c['columns'] ?? 3 }},1fr);gap:1.5rem;max-width:1200px;margin:0 auto;">
          @foreach($staticProducts as $product)
            <div style="background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.08);transition:transform .2s;">
              <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" style="width:100%;height:220px;object-fit:cover;">
              <div style="padding:1rem;">
                <h3 style="font-size:1rem;margin-bottom:.5rem;">{{ $product['name'] }}</h3>
                <span style="color:{{ $shop->primary_color }};font-weight:700;font-size:1.1rem;">${{ number_format($product['price'], 2) }}</span>
              </div>
            </div>
          @endforeach
        </div>
      </section>
    @endif

    {{-- BANNER --}}
    @if($section->type === 'banner')
      @php $c = $section->content ?? []; $s = $section->settings ?? []; @endphp
      <section class="bs-banner" style="position:relative;min-height:{{ $s['height'] ?? 400 }}px;display:flex;align-items:center;background:{{ $s['bgColor'] ?? $shop->primary_color }}; overflow:hidden;">
        @if(!empty($c['image']))
          <img src="{{ $c['image'] }}" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
          <div style="position:absolute;inset:0;background:rgba(0,0,0,.4);"></div>
        @endif
        <div style="position:relative;z-index:1;padding:3rem;max-width:600px;{{ ($c['overlayPosition'] ?? 'left') === 'center' ? 'margin:0 auto;text-align:center;' : '' }}{{ ($c['overlayPosition'] ?? 'left') === 'right' ? 'margin-left:auto;' : '' }}">
          <h2 style="font-size:2rem;font-weight:800;color:{{ $s['textColor'] ?? '#fff' }};margin-bottom:.8rem;">{{ $c['heading'] ?? '' }}</h2>
          <p style="font-size:1rem;color:{{ $s['textColor'] ?? '#fff' }};opacity:.9;margin-bottom:1.2rem;">{{ $c['description'] ?? '' }}</p>
          @if(!empty($c['buttonText']))
            <a href="{{ $c['buttonLink'] ?? '#' }}" style="display:inline-block;padding:.7rem 1.8rem;background:{{ $shop->primary_color }};color:{{ $shop->secondary_color }};border-radius:30px;font-weight:600;text-decoration:none;">{{ $c['buttonText'] }}</a>
          @endif
        </div>
      </section>
    @endif

    {{-- TEXT --}}
    @if($section->type === 'text')
      @php $c = $section->content ?? []; $s = $section->settings ?? []; @endphp
      <section class="bs-text" style="background:{{ $s['bgColor'] ?? '' }}; color:{{ $s['textColor'] ?? '' }}; padding:{{ $s['paddingTop'] ?? 40 }}px 2rem {{ $s['paddingBottom'] ?? 40 }}px; text-align:{{ $c['alignment'] ?? 'left' }};">
        <div style="max-width:{{ $s['maxWidth'] ?? 800 }}px;margin:{{ ($c['alignment'] ?? 'left') === 'center' ? '0 auto' : '0' }};">
          <h2 style="font-size:1.8rem;font-weight:700;margin-bottom:1rem;">{{ $c['heading'] ?? '' }}</h2>
          <div style="line-height:1.8;color:#555;">{!! $c['body'] ?? '' !!}</div>
        </div>
      </section>
    @endif

    {{-- GALLERY --}}
    @if($section->type === 'gallery')
      @php $c = $section->content ?? []; $s = $section->settings ?? []; @endphp
      <section class="bs-gallery" style="background:{{ $s['bgColor'] ?? '' }}; padding:{{ $s['paddingTop'] ?? 40 }}px 2rem {{ $s['paddingBottom'] ?? 40 }}px;">
        <h2 style="text-align:center;font-size:1.8rem;font-weight:700;margin-bottom:2rem;">{{ $c['heading'] ?? 'Gallery' }}</h2>
        <div style="display:grid;grid-template-columns:repeat({{ $c['columns'] ?? 3 }},1fr);gap:{{ $s['gap'] ?? 10 }}px;max-width:1200px;margin:0 auto;">
          @foreach(($c['images'] ?? []) as $img)
            <img src="{{ $img }}" alt="Gallery" style="width:100%;height:250px;object-fit:cover;border-radius:{{ $s['borderRadius'] ?? 8 }}px;">
          @endforeach
        </div>
      </section>
    @endif

    {{-- FEATURES --}}
    @if($section->type === 'features')
      @php $c = $section->content ?? []; $s = $section->settings ?? []; @endphp
      <section class="bs-features" style="background:{{ $s['bgColor'] ?? '' }}; color:{{ $s['textColor'] ?? '' }}; padding:{{ $s['paddingTop'] ?? 40 }}px 2rem {{ $s['paddingBottom'] ?? 40 }}px;">
        <h2 style="text-align:center;font-size:1.8rem;font-weight:700;margin-bottom:2rem;">{{ $c['heading'] ?? '' }}</h2>
        <div style="display:grid;grid-template-columns:repeat({{ $s['columns'] ?? 4 }},1fr);gap:2rem;max-width:1200px;margin:0 auto;text-align:center;">
          @foreach(($c['items'] ?? []) as $item)
            <div style="padding:1.5rem;">
              <div style="width:60px;height:60px;border-radius:50%;background:{{ $shop->primary_color }}15;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="{{ $shop->primary_color }}" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>
              </div>
              <h3 style="font-size:1.05rem;font-weight:700;margin-bottom:.5rem;">{{ $item['title'] ?? '' }}</h3>
              <p style="font-size:.9rem;color:#777;">{{ $item['description'] ?? '' }}</p>
            </div>
          @endforeach
        </div>
      </section>
    @endif

    {{-- TESTIMONIALS --}}
    @if($section->type === 'testimonials')
      @php $c = $section->content ?? []; $s = $section->settings ?? []; @endphp
      <section class="bs-testimonials" style="background:{{ $s['bgColor'] ?? '#f9f9f9' }}; color:{{ $s['textColor'] ?? '' }}; padding:{{ $s['paddingTop'] ?? 40 }}px 2rem {{ $s['paddingBottom'] ?? 40 }}px;">
        <h2 style="text-align:center;font-size:1.8rem;font-weight:700;margin-bottom:2rem;">{{ $c['heading'] ?? '' }}</h2>
        <div style="display:grid;grid-template-columns:repeat({{ $s['columns'] ?? 3 }},1fr);gap:1.5rem;max-width:1200px;margin:0 auto;">
          @foreach(($c['items'] ?? []) as $item)
            <div style="background:#fff;padding:1.5rem;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.05);">
              <p style="font-size:.95rem;color:#555;margin-bottom:1rem;font-style:italic;">"{{ $item['content'] ?? '' }}"</p>
              <div style="display:flex;align-items:center;gap:.8rem;">
                <div style="width:40px;height:40px;border-radius:50%;background:{{ $shop->primary_color }}20;display:flex;align-items:center;justify-content:center;font-weight:700;color:{{ $shop->primary_color }};">{{ strtoupper(substr($item['name'] ?? 'U', 0, 1)) }}</div>
                <div>
                  <strong style="font-size:.9rem;">{{ $item['name'] ?? '' }}</strong>
                  <span style="display:block;font-size:.75rem;color:#999;">{{ $item['role'] ?? '' }}</span>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </section>
    @endif

    {{-- CTA --}}
    @if($section->type === 'cta')
      @php $c = $section->content ?? []; $s = $section->settings ?? []; @endphp
      <section class="bs-cta" style="background:{{ $s['bgColor'] ?? $shop->primary_color }}; color:{{ $s['textColor'] ?? '#fff' }}; padding:{{ $s['paddingTop'] ?? 60 }}px 2rem {{ $s['paddingBottom'] ?? 60 }}px; text-align:center;">
        <div style="max-width:600px;margin:0 auto;">
          <h2 style="font-size:2rem;font-weight:800;margin-bottom:.8rem;">{{ $c['heading'] ?? '' }}</h2>
          <p style="font-size:1rem;opacity:.9;margin-bottom:1.5rem;">{{ $c['description'] ?? '' }}</p>
          @if(!empty($c['buttonText']))
            <a href="{{ $c['buttonLink'] ?? '#' }}" style="display:inline-block;padding:.8rem 2.5rem;background:{{ $s['textColor'] ?? '#fff' }};color:{{ $s['bgColor'] ?? $shop->primary_color }};border-radius:30px;font-weight:700;text-decoration:none;">{{ $c['buttonText'] }}</a>
          @endif
        </div>
      </section>
    @endif

    {{-- CONTACT --}}
    @if($section->type === 'contact')
      @php $c = $section->content ?? []; $s = $section->settings ?? []; @endphp
      <section class="bs-contact" style="background:{{ $s['bgColor'] ?? '' }}; color:{{ $s['textColor'] ?? '' }}; padding:{{ $s['paddingTop'] ?? 40 }}px 2rem {{ $s['paddingBottom'] ?? 40 }}px;">
        <h2 style="text-align:center;font-size:1.8rem;font-weight:700;margin-bottom:2rem;">{{ $c['heading'] ?? 'Contact Us' }}</h2>
        <div style="display:flex;justify-content:center;gap:3rem;max-width:900px;margin:0 auto;flex-wrap:wrap;">
          @if(!empty($c['phone']))
            <div style="text-align:center;">
              <div style="font-size:1.5rem;margin-bottom:.5rem;">📞</div>
              <strong>{{ $c['phone'] }}</strong>
            </div>
          @endif
          @if(!empty($c['email']))
            <div style="text-align:center;">
              <div style="font-size:1.5rem;margin-bottom:.5rem;">✉️</div>
              <strong>{{ $c['email'] }}</strong>
            </div>
          @endif
          @if(!empty($c['address']))
            <div style="text-align:center;">
              <div style="font-size:1.5rem;margin-bottom:.5rem;">📍</div>
              <strong>{{ $c['address'] }}</strong>
            </div>
          @endif
        </div>
        @if(!empty($c['workingHours']))
          <p style="text-align:center;margin-top:1.5rem;color:#777;">🕐 {{ $c['workingHours'] }}</p>
        @endif
      </section>
    @endif

    {{-- DIVIDER --}}
    @if($section->type === 'divider')
      @php $s = $section->settings ?? []; @endphp
      <div style="padding:{{ $s['paddingTop'] ?? 20 }}px 2rem {{ $s['paddingBottom'] ?? 20 }}px;max-width:1200px;margin:0 auto;">
        <hr style="border:none;border-top:{{ $s['thickness'] ?? 1 }}px {{ $c['style'] ?? 'solid' }} {{ $s['color'] ?? '#e0e0e0' }};width:{{ $s['width'] ?? 100 }}%;margin:0 auto;">
      </div>
    @endif
  @endif
@endforeach
