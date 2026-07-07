<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import CityAPI from '@/Api/shared/City/city'

const props = defineProps({ modelValue: { type: Boolean, default: false }, company: { type: Object, default: null } })
const emit = defineEmits(['update:modelValue', 'submit'])

const cityApi = new CityAPI()

const isSubmitting = ref(false)
const refForm = ref()
const isFormValid = ref(false)
const cities = ref([])

const formData = ref({ name: '', code: '', phone: '', status: true })
const priceRows = ref([])

async function fetchCities() {
  try {
    const res = await cityApi.getAll({ per_page: -1 })
    cities.value = res.data?.items ?? res.data ?? []
  } catch { cities.value = [] }
}

watch(() => props.company, (newVal) => {
  if (newVal) {
    formData.value = {
      name: newVal.name ?? '',
      code: newVal.code ?? '',
      phone: newVal.phone ?? '',
      status: Boolean(newVal.status),
    }
    priceRows.value = newVal.prices?.map(p => ({ city_id: p.city_id, price: p.price })) ?? []
    nextTick(() => refForm.value?.resetValidation())
  }
}, { immediate: true })

function availableCities(currentIndex) {
  const selectedIds = priceRows.value
    .filter((_, i) => i !== currentIndex)
    .map(r => r.city_id)
    .filter(Boolean)
  return cities.value.filter(c => !selectedIds.includes(c.id))
}

const nonNegativeRule = v => v === null || v === '' || Number(v) >= 0 || 'Price cannot be negative'

function addRow() {
  priceRows.value.push({ city_id: null, price: null })
}

function removeRow(index) {
  priceRows.value.splice(index, 1)
}

function closeModal() {
  emit('update:modelValue', false)
  nextTick(() => {
    refForm.value?.reset(); refForm.value?.resetValidation()
    formData.value = { name: '', code: '', phone: '', status: true }
    priceRows.value = []
  })
}

async function onSubmit() {
  refForm.value?.validate().then(async ({ valid }) => {
    if (!valid) return
    isSubmitting.value = true
    try {
      const payload = {
        ...formData.value,
        prices: priceRows.value
          .filter(r => r.city_id && r.price !== '' && r.price !== null && r.price !== undefined)
          .map(r => ({ city_id: Number(r.city_id), price: Number(r.price) })),
      }
      emit('submit', payload)
      closeModal()
    } finally { isSubmitting.value = false }
  })
}

fetchCities()
</script>
<template>
  <VNavigationDrawer temporary :width="500" location="end" class="scrollable-content"
    :model-value="modelValue" @update:model-value="closeModal">
    <AppDrawerHeaderSection :title="$t('Edit Shipping Company')" @cancel="closeModal" />
    <VDivider />
    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat><VCardText>
        <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
          <VRow>
            <VCol cols="12"><AppTextField v-model="formData.name" :rules="[requiredValidator]"
              :label="$t('Company Name')" :placeholder="$t('Company name')" /></VCol>
            <VCol cols="6"><AppTextField v-model="formData.code" :rules="[requiredValidator]"
              :label="$t('Code')" :placeholder="$t('Company code')" /></VCol>
            <VCol cols="6"><AppTextField v-model="formData.phone" :rules="[requiredValidator]"
              :label="$t('Phone')" :placeholder="$t('Company phone')" /></VCol>
            <VCol cols="12"><VCheckbox v-model="formData.status" :label="$t('Active')" color="success" /></VCol>
          </VRow>

          <VDivider class="my-4" />
          <div class="d-flex align-center mb-4">
            <h5 class="text-h5 mb-0">{{ $t('Shipping Prices') }}</h5>
            <VSpacer />
            <VBtn size="small" variant="tonal" prepend-icon="tabler-plus" @click="addRow">{{ $t('Add Price') }}</VBtn>
          </div>

          <VSlideYTransition group>
            <VRow v-for="(row, i) in priceRows" :key="i" align="center" class="mb-3">
              <VCol cols="6">
                <VSelect v-model="row.city_id" :items="availableCities(i)" item-title="name" item-value="id"
                  :label="$t('City')" clearable hide-details />
              </VCol>
              <VCol cols="4">
                <AppTextField v-model="row.price" type="number" :label="$t('Price')"
                  :placeholder="$t('0.00')" :rules="[nonNegativeRule]" min="0" hide-details :suffix="$t('EGP')" />
              </VCol>
              <VCol cols="2" class="text-center">
                <IconBtn @click="removeRow(i)"><VIcon icon="tabler-trash" color="error" /></IconBtn>
              </VCol>
            </VRow>
          </VSlideYTransition>

          <p v-if="!priceRows.length" class="text-body-2 text-medium-emphasis mt-2">{{ $t('No prices added yet') }}</p>

          <VRow class="mt-4"><VCol cols="12">
            <VBtn type="submit" :loading="isSubmitting" class="me-3">{{ $t('Update') }}</VBtn>
            <VBtn type="reset" variant="tonal" color="error" @click="closeModal">{{ $t('Cancel') }}</VBtn>
          </VCol></VRow>
        </VForm>
      </VCardText></VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>