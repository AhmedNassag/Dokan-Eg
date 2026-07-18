<script setup>
import { fontOptions, themeOptions } from '../shopOptions'

const props = defineProps({
  formData: {
    type: Object,
    required: true,
  },
})
</script>

<template>
  <VRow>
    <VCol cols="12">
      <h6 class="text-h6 font-weight-medium">
        {{ $t('shop.Branding') }}
      </h6>
      <p class="mb-0">
        {{ $t('shop.Customize Your Shop Appearance') }}
      </p>
    </VCol>

    <VCol
      cols="12"
      md="6"
    >
      <AppSelect
        v-model="formData.theme"
        :items="themeOptions"
        :label="$t('shop.Theme')"
        :placeholder="$t('shop.Select Theme')"
      />
    </VCol>

    <VCol
      cols="12"
      md="6"
    >
      <AppSelect
        v-model="formData.font_family"
        :items="fontOptions"
        :label="$t('shop.Font Family')"
        :placeholder="$t('shop.Select Font')"
      />
    </VCol>

    <VCol
      cols="12"
      md="6"
    >
      <VCard
        variant="outlined"
        class="pa-3"
      >
        <p class="text-body-2 mb-2">
          {{ $t('shop.Primary Color') }}
        </p>
        <div class="d-flex align-center gap-3">
          <VColorPicker
            v-model="formData.primary_color"
            mode="hex"
            hide-inputs
            elevation="0"
          />
          <VChip
            :style="{ backgroundColor: formData.primary_color }"
            label
          >
            {{ formData.primary_color }}
          </VChip>
        </div>
      </VCard>
    </VCol>

    <VCol
      cols="12"
      md="6"
    >
      <VCard
        variant="outlined"
        class="pa-3"
      >
        <p class="text-body-2 mb-2">
          {{ $t('shop.Secondary Color') }}
        </p>
        <div class="d-flex align-center gap-3">
          <VColorPicker
            v-model="formData.secondary_color"
            mode="hex"
            hide-inputs
            elevation="0"
          />
          <VChip
            :style="{ backgroundColor: formData.secondary_color }"
            label
          >
            {{ formData.secondary_color }}
          </VChip>
        </div>
      </VCard>
    </VCol>
  </VRow>
</template>
