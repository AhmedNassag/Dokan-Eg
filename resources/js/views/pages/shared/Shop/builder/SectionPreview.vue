<script setup>
const props = defineProps({
  section: { type: Object, required: true },
  shop: { type: Object, default: null },
})

const primaryColor = computed(() => props.shop?.primary_color || '#1976d2')
const secondaryColor = computed(() => props.shop?.secondary_color || '#ffffff')
const fontFamily = computed(() => props.shop?.font_family || 'Cairo')

const content = computed(() => props.section.content || {})
const settings = computed(() => props.section.settings || {})
</script>

<template>
  <div
    class="section-preview"
    :style="{
      fontFamily: fontFamily,
      paddingTop: (settings.paddingTop || 20) / 2 + 'px',
      paddingBottom: (settings.paddingBottom || 20) / 2 + 'px',
    }"
  >
    <!-- HERO -->
    <div
      v-if="section.type === 'hero'"
      class="preview-hero"
      :style="{
        background: settings.bgColor || `linear-gradient(135deg, ${primaryColor}, ${secondaryColor})`,
        color: settings.textColor || '#fff',
      }"
    >
      <h2>{{ content.heading || 'Hero Title' }}</h2>
      <p>{{ content.subheading || 'Subtitle goes here' }}</p>
      <span class="preview-btn" :style="{ background: primaryColor, color: secondaryColor }">
        {{ content.buttonText || 'Button' }}
      </span>
    </div>

    <!-- PRODUCTS -->
    <div v-else-if="section.type === 'products'" class="preview-products">
      <h4>{{ content.heading || 'Products' }}</h4>
      <div class="products-row">
        <div v-for="n in (content.columns || 3)" :key="n" class="product-thumb">
          <div class="thumb-img" :style="{ background: '#f0f0f0' }" />
          <div class="thumb-text" />
          <div class="thumb-price" :style="{ color: primaryColor }">$99.99</div>
        </div>
      </div>
    </div>

    <!-- BANNER -->
    <div
      v-else-if="section.type === 'banner'"
      class="preview-banner"
      :style="{ height: (settings.height || 200) / 2 + 'px' }"
    >
      <div class="banner-overlay" :style="{ background: settings.bgColor || primaryColor }" />
      <div class="banner-content" :style="{ color: settings.textColor || '#fff' }">
        <h3>{{ content.heading || 'Banner Title' }}</h3>
        <p>{{ content.description || 'Banner description' }}</p>
      </div>
    </div>

    <!-- TEXT -->
    <div
      v-else-if="section.type === 'text'"
      class="preview-text"
      :style="{ textAlign: content.alignment || 'left', color: settings.textColor || '' }"
    >
      <h4>{{ content.heading || 'Text Block' }}</h4>
      <p class="text-body-2 text-grey">
        {{ content.body || 'Your text content goes here...' }}
      </p>
    </div>

    <!-- GALLERY -->
    <div v-else-if="section.type === 'gallery'" class="preview-gallery">
      <h4>{{ content.heading || 'Gallery' }}</h4>
      <div class="gallery-row">
        <div
          v-for="n in Math.min(content.images?.length || 0, content.columns || 3)"
          :key="n"
          class="gallery-thumb"
        />
        <div v-if="!content.images?.length" class="gallery-empty text-caption text-grey">
          No images added
        </div>
      </div>
    </div>

    <!-- FEATURES -->
    <div v-else-if="section.type === 'features'" class="preview-features">
      <h4>{{ content.heading || 'Features' }}</h4>
      <div class="features-row">
        <div v-for="(item, i) in (content.items || []).slice(0, 4)" :key="i" class="feature-item">
          <VIcon :icon="item.icon || 'tabler-star'" :color="primaryColor" size="28" />
          <span class="text-caption font-weight-bold">{{ item.title }}</span>
        </div>
      </div>
    </div>

    <!-- TESTIMONIALS -->
    <div v-else-if="section.type === 'testimonials'" class="preview-testimonials">
      <h4>{{ content.heading || 'Testimonials' }}</h4>
      <div class="testimonials-row">
        <div v-for="(item, i) in (content.items || []).slice(0, 3)" :key="i" class="testimonial-card">
          <p class="text-caption text-grey">"{{ item.content }}"</p>
          <span class="text-caption font-weight-bold">{{ item.name }}</span>
        </div>
      </div>
    </div>

    <!-- CTA -->
    <div
      v-else-if="section.type === 'cta'"
      class="preview-cta"
      :style="{ background: settings.bgColor || primaryColor, color: settings.textColor || '#fff' }"
    >
      <h3>{{ content.heading || 'Call to Action' }}</h3>
      <p>{{ content.description || 'Description goes here' }}</p>
    </div>

    <!-- CONTACT -->
    <div v-else-if="section.type === 'contact'" class="preview-contact">
      <h4>{{ content.heading || 'Contact Info' }}</h4>
      <div class="contact-row">
        <div class="contact-item">
          <VIcon icon="tabler-phone" size="18" :color="primaryColor" />
          <span class="text-caption">{{ content.phone || 'Phone' }}</span>
        </div>
        <div class="contact-item">
          <VIcon icon="tabler-mail" size="18" :color="primaryColor" />
          <span class="text-caption">{{ content.email || 'Email' }}</span>
        </div>
        <div class="contact-item">
          <VIcon icon="tabler-map-pin" size="18" :color="primaryColor" />
          <span class="text-caption">{{ content.address || 'Address' }}</span>
        </div>
      </div>
    </div>

    <!-- DIVIDER -->
    <div v-else-if="section.type === 'divider'" class="preview-divider">
      <hr :style="{ borderColor: settings.color || '#e0e0e0', borderWidth: (settings.thickness || 1) + 'px' }">
    </div>

    <!-- FALLBACK -->
    <div v-else class="preview-fallback text-grey text-caption">
      {{ section.type }}
    </div>
  </div>
</template>

<style scoped>
.section-preview { padding: 12px 16px; overflow: hidden; }

.preview-hero { padding: 24px; border-radius: 8px; text-align: center; }
.preview-hero h2 { font-size: 1.2rem; margin-bottom: 6px; }
.preview-hero p { font-size: .8rem; opacity: .9; margin-bottom: 10px; }
.preview-btn { display: inline-block; padding: 4px 16px; border-radius: 20px; font-size: .7rem; font-weight: 600; }

.preview-products h4, .preview-gallery h4, .preview-features h4,
.preview-testimonials h4, .preview-contact h4 { font-size: .85rem; margin-bottom: 10px; }
.products-row { display: flex; gap: 10px; }
.product-thumb { flex: 1; }
.thumb-img { height: 60px; border-radius: 6px; }
.thumb-text { height: 10px; background: #e0e0e0; border-radius: 4px; margin-top: 6px; width: 80%; }
.thumb-price { font-size: .75rem; font-weight: 700; margin-top: 4px; }

.preview-banner { position: relative; border-radius: 8px; overflow: hidden; display: flex; align-items: center; }
.banner-overlay { position: absolute; inset: 0; opacity: .85; }
.banner-content { position: relative; z-index: 1; padding: 16px; }
.banner-content h3 { font-size: 1rem; }
.banner-content p { font-size: .75rem; opacity: .9; }

.preview-text { padding: 8px; }
.preview-text h4 { font-size: .85rem; margin-bottom: 6px; }

.gallery-row { display: flex; gap: 6px; }
.gallery-thumb { flex: 1; height: 60px; background: #f0f0f0; border-radius: 6px; }

.features-row { display: flex; gap: 16px; }
.feature-item { display: flex; flex-direction: column; align-items: center; gap: 4px; flex: 1; }

.testimonials-row { display: flex; gap: 10px; }
.testimonial-card { flex: 1; background: #f9f9f9; padding: 10px; border-radius: 8px; }

.preview-cta { padding: 20px; border-radius: 8px; text-align: center; }
.preview-cta h3 { font-size: 1rem; margin-bottom: 4px; }
.preview-cta p { font-size: .8rem; opacity: .9; }

.contact-row { display: flex; gap: 16px; }
.contact-item { display: flex; align-items: center; gap: 6px; }

.preview-divider { padding: 8px 0; }
.preview-divider hr { border: none; border-top-style: solid; }
</style>
