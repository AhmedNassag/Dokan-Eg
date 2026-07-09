<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import CountryAPI from '@/Api/shared/Country/country'

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false
  },
  city: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['update:modelValue', 'submit'])

const countryApi = new CountryAPI()

const isSubmitting = ref(false)
const refForm = ref()
const isFormValid = ref(false)
const countries = ref([])
const formData = ref({
  name: '',
  country_id: null,
  status: true
})

async function fetchCountries() {
  try {
    const res = await countryApi.getAll({ per_page: -1 })
    countries.value = res.data?.items ?? res.data ?? []
  } catch {
    countries.value = []
  }
}

watch(() => props.city, (newVal) => {
  if (newVal) {
    formData.value = {
      name: newVal.name ?? '',
      country_id: newVal.country_id ?? null,
      status: Boolean(newVal.status)
    }
    nextTick(() => refForm.value?.resetValidation())
  }
})

function closeModal() {
  emit('update:modelValue', false)
  nextTick(() => {
    refForm.value?.reset()
    refForm.value?.resetValidation()
    formData.value = { name: '', country_id: null, status: true }
  })
}

async function onSubmit() {
  refForm.value?.validate().then(async ({ valid }) => {
    if (!valid) return
    isSubmitting.value = true
    try {
      emit('submit', { ...formData.value })
      closeModal()
    } finally {
      isSubmitting.value = false
    }
  })
}

fetchCountries()
</script>

<template>
  <VNavigationDrawer
    temporary
    :width="400"
    location="end"
    class="scrollable-content"
    :model-value="modelValue"
    @update:model-value="closeModal"
  >
    <AppDrawerHeaderSection
      :title="$t('city.Edit City')"
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
              <VCol cols="12">
                <AppTextField
                  v-model="formData.name"
                  :rules="[requiredValidator]"
                  :label="$t('city.Name')"
                  :placeholder="$t('city.City Name')"
                />
              </VCol>

              <VCol cols="12">
                <VSelect
                  v-model="formData.country_id"
                  :items="countries"
                  item-title="name"
                  item-value="id"
                  :label="$t('city.Country')"
                  :placeholder="$t('city.Select Country')"
                  :rules="[requiredValidator]"
                  clearable
                  :return-object="false"
                />
              </VCol>

              <VCol cols="12">
                <VCheckbox
                  v-model="formData.status"
                  :label="$t('city.Active')"
                  color="success"
                />
              </VCol>

              <VCol cols="12">
                <VBtn
                  type="submit"
                  :loading="isSubmitting"
                  class="me-3"
                >
                  {{ $t('city.Update') }}
                </VBtn>
                <VBtn
                  type="reset"
                  variant="tonal"
                  color="error"
                  @click="closeModal"
                >
                  {{ $t('city.Cancel') }}
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>
