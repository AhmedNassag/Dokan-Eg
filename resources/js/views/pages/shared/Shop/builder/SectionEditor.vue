<script setup>
import { ref, computed, watch } from 'vue'

const props = defineProps({
  section: { type: Object, required: true },
  shop: { type: Object, default: null },
})

const emit = defineEmits(['update', 'save', 'close'])

const localContent = ref(JSON.parse(JSON.stringify(props.section.content || {})))
const localSettings = ref(JSON.parse(JSON.stringify(props.section.settings || {})))
const localTitle = ref(props.section.title || '')

watch(() => props.section, (val) => {
  localContent.value = JSON.parse(JSON.stringify(val.content || {}))
  localSettings.value = JSON.parse(JSON.stringify(val.settings || {}))
  localTitle.value = val.title || ''
}, { deep: true })

function save() {
  emit('save', {
    title: localTitle.value,
    content: localContent.value,
    settings: localSettings.value,
  })
}

function updateLocal() {
  emit('update', {
    title: localTitle.value,
    content: localContent.value,
    settings: localSettings.value,
  })
}

function addItem(key) {
  if (!localContent.value[key]) localContent.value[key] = []
  const templates = {
    items: { icon: 'tabler-star', title: 'New Item', description: 'Description' },
    images: '',
  }
  localContent.value[key].push(templates[key] ?? {})
  updateLocal()
}

function removeItem(key, index) {
  localContent.value[key].splice(index, 1)
  updateLocal()
}

const typeLabel = computed(() => {
  const types = {
    hero: 'Hero Banner', products: 'Products Grid', banner: 'Image Banner',
    text: 'Text Block', gallery: 'Gallery', features: 'Features',
    testimonials: 'Testimonials', cta: 'Call to Action', contact: 'Contact Info', divider: 'Divider',
  }
  return types[props.section.type] || props.section.type
})
</script>

<template>
  <div class="section-editor pa-4">
    <div class="d-flex align-center justify-space-between mb-4">
      <div class="d-flex align-center gap-2">
        <VIcon icon="tabler-pencil" size="18" />
        <span class="text-subtitle-2 font-weight-bold">{{ typeLabel }}</span>
      </div>
      <IconBtn size="small" @click="emit('close')">
        <VIcon icon="tabler-x" size="18" />
      </IconBtn>
    </div>

    <VDivider class="mb-4" />

    <!-- Title -->
    <AppTextField
      v-model="localTitle"
      label="Section Title"
      density="compact"
      class="mb-3"
      @update:model-value="updateLocal"
    />

    <!-- HERO -->
    <template v-if="section.type === 'hero'">
      <AppTextField v-model="localContent.heading" label="Heading" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <AppTextField v-model="localContent.subheading" label="Subheading" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <AppTextField v-model="localContent.buttonText" label="Button Text" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <AppTextField v-model="localContent.buttonLink" label="Button Link" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <AppTextField v-model="localContent.backgroundImage" label="Background Image URL" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <VSelect v-model="localContent.alignment" :items="['left', 'center', 'right']" label="Alignment" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <VDivider class="mb-3" />
      <p class="text-caption font-weight-bold mb-2">Settings</p>
      <AppTextField v-model="localSettings.bgColor" label="Background Color" density="compact" class="mb-2" @update:model-value="updateLocal" />
      <AppTextField v-model="localSettings.textColor" label="Text Color" density="compact" class="mb-2" @update:model-value="updateLocal" />
      <AppTextField v-model.number="localSettings.paddingTop" label="Padding Top (px)" type="number" density="compact" class="mb-2" @update:model-value="updateLocal" />
      <AppTextField v-model.number="localSettings.paddingBottom" label="Padding Bottom (px)" type="number" density="compact" class="mb-2" @update:model-value="updateLocal" />
    </template>

    <!-- PRODUCTS -->
    <template v-if="section.type === 'products'">
      <AppTextField v-model="localContent.heading" label="Heading" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <VSelect v-model="localContent.source" :items="['all', 'featured']" label="Source" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <VSelect v-model.number="localContent.columns" :items="[2, 3, 4]" label="Columns" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <VDivider class="mb-3" />
      <p class="text-caption font-weight-bold mb-2">Settings</p>
      <AppTextField v-model="localSettings.bgColor" label="Background Color" density="compact" class="mb-2" @update:model-value="updateLocal" />
      <AppTextField v-model.number="localSettings.paddingTop" label="Padding Top (px)" type="number" density="compact" class="mb-2" @update:model-value="updateLocal" />
      <AppTextField v-model.number="localSettings.paddingBottom" label="Padding Bottom (px)" type="number" density="compact" class="mb-2" @update:model-value="updateLocal" />
    </template>

    <!-- BANNER -->
    <template v-if="section.type === 'banner'">
      <AppTextField v-model="localContent.heading" label="Heading" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <AppTextarea v-model="localContent.description" label="Description" density="compact" rows="2" class="mb-3" @update:model-value="updateLocal" />
      <AppTextField v-model="localContent.image" label="Image URL" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <AppTextField v-model="localContent.buttonText" label="Button Text" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <AppTextField v-model="localContent.buttonLink" label="Button Link" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <VSelect v-model="localContent.overlayPosition" :items="['left', 'center', 'right']" label="Text Position" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <VDivider class="mb-3" />
      <p class="text-caption font-weight-bold mb-2">Settings</p>
      <AppTextField v-model="localSettings.bgColor" label="Background Color" density="compact" class="mb-2" @update:model-value="updateLocal" />
      <AppTextField v-model="localSettings.textColor" label="Text Color" density="compact" class="mb-2" @update:model-value="updateLocal" />
      <AppTextField v-model.number="localSettings.height" label="Height (px)" type="number" density="compact" class="mb-2" @update:model-value="updateLocal" />
    </template>

    <!-- TEXT -->
    <template v-if="section.type === 'text'">
      <AppTextField v-model="localContent.heading" label="Heading" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <AppTextarea v-model="localContent.body" label="Content" density="compact" rows="5" class="mb-3" @update:model-value="updateLocal" />
      <VSelect v-model="localContent.alignment" :items="['left', 'center', 'right']" label="Alignment" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <VDivider class="mb-3" />
      <p class="text-caption font-weight-bold mb-2">Settings</p>
      <AppTextField v-model="localSettings.bgColor" label="Background Color" density="compact" class="mb-2" @update:model-value="updateLocal" />
      <AppTextField v-model="localSettings.textColor" label="Text Color" density="compact" class="mb-2" @update:model-value="updateLocal" />
      <AppTextField v-model.number="localSettings.maxWidth" label="Max Width (px)" type="number" density="compact" class="mb-2" @update:model-value="updateLocal" />
    </template>

    <!-- GALLERY -->
    <template v-if="section.type === 'gallery'">
      <AppTextField v-model="localContent.heading" label="Heading" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <VSelect v-model.number="localContent.columns" :items="[2, 3, 4]" label="Columns" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <p class="text-caption font-weight-bold mb-2">Images</p>
      <div v-for="(img, i) in (localContent.images || [])" :key="i" class="d-flex align-center gap-2 mb-2">
        <AppTextField v-model="localContent.images[i]" density="compact" hide-details placeholder="Image URL" @update:model-value="updateLocal" />
        <IconBtn size="small" @click="removeItem('images', i)">
          <VIcon icon="tabler-x" size="16" />
        </IconBtn>
      </div>
      <VBtn size="small" variant="tonal" prepend-icon="tabler-plus" class="mb-3" @click="addItem('images')">
        Add Image
      </VBtn>
    </template>

    <!-- FEATURES -->
    <template v-if="section.type === 'features'">
      <AppTextField v-model="localContent.heading" label="Heading" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <p class="text-caption font-weight-bold mb-2">Feature Items</p>
      <div v-for="(item, i) in (localContent.items || [])" :key="i" class="feature-edit-card pa-3 mb-2 rounded">
        <div class="d-flex justify-space-between align-center mb-2">
          <span class="text-caption font-weight-bold">Item {{ i + 1 }}</span>
          <IconBtn size="small" @click="removeItem('items', i)">
            <VIcon icon="tabler-trash" size="14" />
          </IconBtn>
        </div>
        <AppTextField v-model="item.icon" label="Icon (tabler-*)" density="compact" class="mb-2" @update:model-value="updateLocal" />
        <AppTextField v-model="item.title" label="Title" density="compact" class="mb-2" @update:model-value="updateLocal" />
        <AppTextField v-model="item.description" label="Description" density="compact" @update:model-value="updateLocal" />
      </div>
      <VBtn size="small" variant="tonal" prepend-icon="tabler-plus" class="mb-3" @click="addItem('items')">
        Add Feature
      </VBtn>
    </template>

    <!-- TESTIMONIALS -->
    <template v-if="section.type === 'testimonials'">
      <AppTextField v-model="localContent.heading" label="Heading" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <p class="text-caption font-weight-bold mb-2">Testimonials</p>
      <div v-for="(item, i) in (localContent.items || [])" :key="i" class="feature-edit-card pa-3 mb-2 rounded">
        <div class="d-flex justify-space-between align-center mb-2">
          <span class="text-caption font-weight-bold">Review {{ i + 1 }}</span>
          <IconBtn size="small" @click="removeItem('items', i)">
            <VIcon icon="tabler-trash" size="14" />
          </IconBtn>
        </div>
        <AppTextField v-model="item.name" label="Name" density="compact" class="mb-2" @update:model-value="updateLocal" />
        <AppTextField v-model="item.role" label="Role" density="compact" class="mb-2" @update:model-value="updateLocal" />
        <AppTextarea v-model="item.content" label="Review" density="compact" rows="2" class="mb-2" @update:model-value="updateLocal" />
        <VSelect v-model.number="item.rating" :items="[1, 2, 3, 4, 5]" label="Rating" density="compact" @update:model-value="updateLocal" />
      </div>
      <VBtn size="small" variant="tonal" prepend-icon="tabler-plus" class="mb-3" @click="addItem('items')">
        Add Testimonial
      </VBtn>
    </template>

    <!-- CTA -->
    <template v-if="section.type === 'cta'">
      <AppTextField v-model="localContent.heading" label="Heading" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <AppTextarea v-model="localContent.description" label="Description" density="compact" rows="2" class="mb-3" @update:model-value="updateLocal" />
      <AppTextField v-model="localContent.buttonText" label="Button Text" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <AppTextField v-model="localContent.buttonLink" label="Button Link" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <VDivider class="mb-3" />
      <p class="text-caption font-weight-bold mb-2">Settings</p>
      <AppTextField v-model="localSettings.bgColor" label="Background Color" density="compact" class="mb-2" @update:model-value="updateLocal" />
      <AppTextField v-model="localSettings.textColor" label="Text Color" density="compact" class="mb-2" @update:model-value="updateLocal" />
    </template>

    <!-- CONTACT -->
    <template v-if="section.type === 'contact'">
      <AppTextField v-model="localContent.heading" label="Heading" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <AppTextField v-model="localContent.phone" label="Phone" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <AppTextField v-model="localContent.email" label="Email" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <AppTextField v-model="localContent.address" label="Address" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <AppTextField v-model="localContent.workingHours" label="Working Hours" density="compact" class="mb-3" @update:model-value="updateLocal" />
    </template>

    <!-- DIVIDER -->
    <template v-if="section.type === 'divider'">
      <VSelect v-model="localContent.style" :items="['line', 'dots', 'space']" label="Style" density="compact" class="mb-3" @update:model-value="updateLocal" />
      <VDivider class="mb-3" />
      <p class="text-caption font-weight-bold mb-2">Settings</p>
      <AppTextField v-model="localSettings.color" label="Color" density="compact" class="mb-2" @update:model-value="updateLocal" />
      <AppTextField v-model.number="localSettings.thickness" label="Thickness (px)" type="number" density="compact" class="mb-2" @update:model-value="updateLocal" />
    </template>

    <!-- COMMON SETTINGS (except divider) -->
    <template v-if="section.type !== 'divider'">
      <VDivider class="my-3" />
      <AppTextField v-model.number="localSettings.paddingTop" label="Padding Top (px)" type="number" density="compact" class="mb-2" @update:model-value="updateLocal" />
      <AppTextField v-model.number="localSettings.paddingBottom" label="Padding Bottom (px)" type="number" density="compact" class="mb-2" @update:model-value="updateLocal" />
    </template>

    <!-- SAVE -->
    <VDivider class="my-3" />
    <VBtn block color="primary" prepend-icon="tabler-device-floppy" @click="save">
      Save Section
    </VBtn>
  </div>
</template>

<style scoped>
.feature-edit-card {
  border: 1px solid #e8e8e8;
  background: #fafafa;
}
</style>
