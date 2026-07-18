<script setup>
const props = defineProps({
  formData: {
    type: Object,
    required: true,
  },
  isAdmin: {
    type: Boolean,
    default: false,
  },
  ownerName: {
    type: String,
    default: '',
  },
})
</script>

<template>
  <VRow>
    <VCol cols="12">
      <h6 class="text-h6 font-weight-medium">
        {{ $t('shop.Review') }}
      </h6>
      <p class="mb-0">
        {{ $t('shop.Review Your Shop Details Before Submitting') }}
      </p>
    </VCol>

    <VCol cols="12">
      <VList
        lines="one"
        border
        rounded
      >
        <VListItem
          v-if="isAdmin"
          :title="$t('shop.Owner')"
          :subtitle="ownerName || '—'"
        />
        <VListItem
          :title="$t('shop.Name')"
          :subtitle="formData.name || '—'"
        />
        <VListItem
          :title="$t('shop.Slug')"
          :subtitle="formData.slug || '—'"
        />
        <VListItem
          :title="$t('shop.Description')"
          :subtitle="formData.description || '—'"
        />
        <VListItem
          :title="$t('shop.Phone')"
          :subtitle="formData.phone || '—'"
        />
        <VListItem
          :title="$t('shop.Email')"
          :subtitle="formData.email || '—'"
        />
        <VListItem
          :title="$t('shop.Website')"
          :subtitle="formData.website || '—'"
        />
        <VListItem
          :title="$t('shop.Theme')"
          :subtitle="formData.theme"
        />
        <VListItem
          :title="$t('shop.Font Family')"
          :subtitle="formData.font_family"
        />
        <VListItem :title="$t('shop.Primary Color')">
          <template #subtitle>
            <div class="d-flex align-center gap-2">
              <span :style="{ backgroundColor: formData.primary_color, inlineSize: '16px', blockSize: '16px', borderRadius: '4px', display: 'inline-block' }" />
              {{ formData.primary_color }}
            </div>
          </template>
        </VListItem>
        <VListItem :title="$t('shop.Secondary Color')">
          <template #subtitle>
            <div class="d-flex align-center gap-2">
              <span :style="{ backgroundColor: formData.secondary_color, inlineSize: '16px', blockSize: '16px', borderRadius: '4px', display: 'inline-block' }" />
              {{ formData.secondary_color }}
            </div>
          </template>
        </VListItem>
        <VListItem
          :title="$t('shop.Active')"
          :subtitle="formData.is_active ? $t('shop.Yes') : $t('shop.No')"
        />
        <VListItem
          v-if="isAdmin"
          :title="$t('shop.Featured')"
          :subtitle="formData.is_featured ? $t('shop.Yes') : $t('shop.No')"
        />
      </VList>
    </VCol>
  </VRow>
</template>
