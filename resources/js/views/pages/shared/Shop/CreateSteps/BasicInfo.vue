<script setup>
import { slugify } from '../shopOptions'

const props = defineProps({
  formData: {
    type: Object,
    required: true,
  },
  isAdmin: {
    type: Boolean,
    default: false,
  },
  users: {
    type: Array,
    default: () => [],
  },
})

const slugTouched = ref(false)

watch(() => props.formData.name, val => {
  if (!slugTouched.value)
    props.formData.slug = slugify(val)
})
</script>

<template>
  <VRow>
    <VCol cols="12">
      <h6 class="text-h6 font-weight-medium">
        {{ $t('shop.Basic Information') }}
      </h6>
      <p class="mb-0">
        {{ $t('shop.Enter The Basic Shop Details') }}
      </p>
    </VCol>

    <VCol
      v-if="isAdmin"
      cols="12"
      md="6"
    >
      <AppSelect
        v-model="formData.user_id"
        :items="users"
        item-title="name"
        item-value="id"
        :label="$t('shop.Owner')"
        :placeholder="$t('shop.Select Owner')"
        :rules="[requiredValidator]"
        clearable
        :return-object="false"
      />
    </VCol>

    <VCol
      cols="12"
      :md="isAdmin ? 6 : 12"
    >
      <AppTextField
        v-model="formData.name"
        :rules="[requiredValidator]"
        :label="$t('shop.Name')"
        :placeholder="$t('shop.Shop Name')"
      />
    </VCol>

    <VCol
      cols="12"
      md="6"
    >
      <AppTextField
        v-model="formData.slug"
        :rules="[requiredValidator]"
        :label="$t('shop.Slug')"
        placeholder="my-shop"
        @input="slugTouched = true"
      />
    </VCol>

    <VCol
      cols="12"
      md="6"
    >
      <AppTextField
        v-model="formData.phone"
        :label="$t('shop.Phone')"
      />
    </VCol>

    <VCol cols="12">
      <AppTextarea
        v-model="formData.description"
        :label="$t('shop.Description')"
        rows="3"
      />
    </VCol>

    <VCol
      cols="12"
      md="6"
    >
      <AppTextField
        v-model="formData.email"
        :label="$t('shop.Email')"
        type="email"
      />
    </VCol>

    <VCol
      cols="12"
      md="6"
    >
      <AppTextField
        v-model="formData.website"
        :label="$t('shop.Website')"
        placeholder="https://"
      />
    </VCol>

    <VCol
      v-if="isAdmin"
      cols="12"
      md="6"
    >
      <VCheckbox
        v-model="formData.is_active"
        :label="$t('shop.Active')"
        color="success"
      />
    </VCol>

    <VCol
      v-if="isAdmin"
      cols="12"
      md="6"
    >
      <VCheckbox
        v-model="formData.is_featured"
        :label="$t('shop.Featured')"
        color="warning"
      />
    </VCol>
  </VRow>
</template>
