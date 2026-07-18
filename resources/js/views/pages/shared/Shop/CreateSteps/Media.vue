<script setup>
const logoFile = defineModel('logo', { default: null })
const coverFile = defineModel('cover', { default: null })

const logoPreview = ref(null)
const coverPreview = ref(null)

function makePreview(file, target) {
  const f = Array.isArray(file) ? file[0] : file
  if (!f) {
    target.value = null

    return
  }

  const reader = new FileReader()

  reader.onload = e => { target.value = e.target?.result ?? null }
  reader.readAsDataURL(f)
}

watch(logoFile, val => makePreview(val, logoPreview))
watch(coverFile, val => makePreview(val, coverPreview))
</script>

<template>
  <VRow>
    <VCol cols="12">
      <h6 class="text-h6 font-weight-medium">
        {{ $t('shop.Media') }}
      </h6>
      <p class="mb-0">
        {{ $t('shop.Upload Your Shop Logo And Cover') }}
      </p>
    </VCol>

    <VCol
      cols="12"
      md="6"
    >
      <VFileInput
        v-model="logoFile"
        :label="$t('shop.Logo')"
        accept="image/*"
        prepend-icon="tabler-photo"
        show-size
      />
      <VImg
        v-if="logoPreview"
        :src="logoPreview"
        max-width="140"
        max-height="140"
        class="mt-3 rounded border"
        cover
      />
    </VCol>

    <VCol
      cols="12"
      md="6"
    >
      <VFileInput
        v-model="coverFile"
        :label="$t('shop.Cover')"
        accept="image/*"
        prepend-icon="tabler-photo"
        show-size
      />
      <VImg
        v-if="coverPreview"
        :src="coverPreview"
        max-width="100%"
        max-height="160"
        class="mt-3 rounded border"
        cover
      />
    </VCol>
  </VRow>
</template>
