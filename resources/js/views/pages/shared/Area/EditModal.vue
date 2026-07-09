<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import CityAPI from '@/Api/shared/City/city'

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false
  },
  area: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['update:modelValue', 'submit'])

const cityApi = new CityAPI()

const isSubmitting = ref(false)
const refForm = ref()
const isFormValid = ref(false)
const cities = ref([])
const formData = ref({
  name: '',
  city_id: null,
  status: true
})

async function fetchCities() {
  try {
    const res = await cityApi.getAll({ per_page: -1 })
    cities.value = res.data?.items ?? res.data ?? []
  } catch {
    cities.value = []
  }
}

watch(() => props.area, (newVal) => {
  if (newVal) {
    formData.value = {
      name: newVal.name ?? '',
      city_id: newVal.city_id ?? null,
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
    formData.value = { name: '', city_id: null, status: true }
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

fetchCities()
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
      :title="$t('area.Edit Area')"
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
                  :label="$t('area.Name')"
                  :placeholder="$t('area.Area Name')"
                />
              </VCol>

              <VCol cols="12">
                <VSelect
                  v-model="formData.city_id"
                  :items="cities"
                  item-title="name"
                  item-value="id"
                  :label="$t('area.City')"
                  :placeholder="$t('area.Select City')"
                  :rules="[requiredValidator]"
                  clearable
                  :return-object="false"
                />
              </VCol>

              <VCol cols="12">
                <VCheckbox
                  v-model="formData.status"
                  :label="$t('area.Active')"
                  color="success"
                />
              </VCol>

              <VCol cols="12">
                <VBtn
                  type="submit"
                  :loading="isSubmitting"
                  class="me-3"
                >
                  {{ $t('area.Update') }}
                </VBtn>
                <VBtn
                  type="reset"
                  variant="tonal"
                  color="error"
                  @click="closeModal"
                >
                  {{ $t('area.Cancel') }}
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>
