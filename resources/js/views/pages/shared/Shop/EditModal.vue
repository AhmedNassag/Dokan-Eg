<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import UserAPI from '@/Api/shared/User/user'
import { fontOptions, slugify, themeOptions } from './shopOptions'

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false,
  },
  shop: {
    type: Object,
    default: null,
  },
  isAdmin: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['update:modelValue', 'submit'])

const userApi = new UserAPI()

const isSubmitting = ref(false)
const refForm = ref()
const isFormValid = ref(false)
const users = ref([])
const slugTouched = ref(false)
const logoFile = ref(null)
const coverFile = ref(null)

const formData = ref({
  user_id: null,
  name: '',
  slug: '',
  description: '',
  theme: 'default',
  font_family: 'Cairo',
  primary_color: '#000000',
  secondary_color: '#ffffff',
  phone: '',
  email: '',
  website: '',
  is_active: true,
  is_featured: false,
})

async function fetchUsers() {
  if (!props.isAdmin) return
  try {
    const res = await userApi.getAll({ per_page: -1 })

    users.value = res.data?.items ?? res.data ?? []
  } catch {
    users.value = []
  }
}

watch(() => formData.value.name, val => {
  if (!slugTouched.value)
    formData.value.slug = slugify(val)
})

watch(() => props.shop, newVal => {
  if (newVal) {
    formData.value = {
      user_id: newVal.user_id ?? null,
      name: newVal.name ?? '',
      slug: newVal.slug ?? '',
      description: newVal.description ?? '',
      theme: newVal.theme ?? 'default',
      font_family: newVal.font_family ?? 'Cairo',
      primary_color: newVal.primary_color ?? '#000000',
      secondary_color: newVal.secondary_color ?? '#ffffff',
      phone: newVal.phone ?? '',
      email: newVal.email ?? '',
      website: newVal.website ?? '',
      is_active: Boolean(newVal.is_active),
      is_featured: Boolean(newVal.is_featured),
    }
    slugTouched.value = true
    logoFile.value = null
    coverFile.value = null
    nextTick(() => refForm.value?.resetValidation())
  }
})

function closeModal() {
  emit('update:modelValue', false)
}

function buildPayload() {
  const hasFiles = logoFile.value || coverFile.value

  const fields = { ...formData.value }
  if (!props.isAdmin) {
    delete fields.user_id
    delete fields.is_featured
  }

  if (!hasFiles)
    return fields

  const fd = new FormData()

  Object.entries(fields).forEach(([key, value]) => {
    if (value === null || value === undefined) return
    if (typeof value === 'boolean')
      fd.append(key, value ? '1' : '0')
    else
      fd.append(key, value)
  })
  if (logoFile.value)
    fd.append('logo', logoFile.value)
  if (coverFile.value)
    fd.append('cover', coverFile.value)

  return fd
}

async function onSubmit() {
  refForm.value?.validate().then(async ({ valid }) => {
    if (!valid) return
    isSubmitting.value = true
    try {
      emit('submit', buildPayload())
      closeModal()
    } finally {
      isSubmitting.value = false
    }
  })
}

fetchUsers()
</script>

<template>
  <VNavigationDrawer
    temporary
    :width="440"
    location="end"
    class="scrollable-content"
    :model-value="modelValue"
    @update:model-value="closeModal"
  >
    <AppDrawerHeaderSection
      :title="$t('shop.Edit Shop')"
      @cancel="closeModal"
    />

    <VDivider />

    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <VForm
            ref="refForm"
            v-model="isFormValid"
            @submit.prevent="onSubmit"
          >
            <VRow>
              <VCol
                v-if="isAdmin"
                cols="12"
              >
                <VSelect
                  v-model="formData.user_id"
                  :items="users"
                  item-title="name"
                  item-value="id"
                  :label="$t('shop.Owner')"
                  :placeholder="$t('shop.Select Owner')"
                  clearable
                  :return-object="false"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="formData.name"
                  :rules="[requiredValidator]"
                  :label="$t('shop.Name')"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="formData.slug"
                  :rules="[requiredValidator]"
                  :label="$t('shop.Slug')"
                  @input="slugTouched = true"
                />
              </VCol>

              <VCol cols="12">
                <AppTextarea
                  v-model="formData.description"
                  :label="$t('shop.Description')"
                  rows="2"
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
              <VCol
                cols="12"
                md="6"
              >
                <AppTextField
                  v-model="formData.email"
                  :label="$t('shop.Email')"
                />
              </VCol>
              <VCol cols="12">
                <AppTextField
                  v-model="formData.website"
                  :label="$t('shop.Website')"
                />
              </VCol>

              <VCol
                cols="12"
                md="6"
              >
                <AppSelect
                  v-model="formData.theme"
                  :items="themeOptions"
                  :label="$t('shop.Theme')"
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
                />
              </VCol>

              <VCol
                cols="12"
                md="6"
              >
                <label class="text-caption">{{ $t('shop.Primary Color') }}</label>
                <VColorPicker
                  v-model="formData.primary_color"
                  mode="hex"
                  hide-inputs
                  elevation="0"
                />
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <label class="text-caption">{{ $t('shop.Secondary Color') }}</label>
                <VColorPicker
                  v-model="formData.secondary_color"
                  mode="hex"
                  hide-inputs
                  elevation="0"
                />
              </VCol>

              <VCol cols="12">
                <VFileInput
                  v-model="logoFile"
                  :label="$t('shop.Logo')"
                  accept="image/*"
                  prepend-icon="tabler-photo"
                />
              </VCol>
              <VCol cols="12">
                <VFileInput
                  v-model="coverFile"
                  :label="$t('shop.Cover')"
                  accept="image/*"
                  prepend-icon="tabler-photo"
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

              <VCol cols="12">
                <VBtn
                  type="submit"
                  :loading="isSubmitting"
                  class="me-3"
                >
                  {{ $t('shop.Update') }}
                </VBtn>
                <VBtn
                  type="reset"
                  variant="tonal"
                  color="error"
                  @click="closeModal"
                >
                  {{ $t('shop.Cancel') }}
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>
