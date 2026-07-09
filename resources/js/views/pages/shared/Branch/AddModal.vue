<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import AreaAPI from '@/Api/shared/Area/area'

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:modelValue', 'submit'])

const areaApi = new AreaAPI()

const isSubmitting = ref(false)
const refForm = ref()
const isFormValid = ref(false)
const areas = ref([])
const formData = ref({
  name: '',
  code: '',
  mobile: '',
  address: '',
  area_id: null,
  status: true
})

async function fetchAreas() {
  try {
    const res = await areaApi.getAll({ per_page: -1 })
    areas.value = res.data?.items ?? res.data ?? []
  } catch {
    areas.value = []
  }
}

function closeModal() {
  emit('update:modelValue', false)
  nextTick(() => {
    refForm.value?.reset()
    refForm.value?.resetValidation()
    formData.value = { name: '', code: '', mobile: '', address: '', area_id: null, status: true }
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

fetchAreas()
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
      :title="$t('branch.Add Branch')"
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
                  :label="$t('branch.Name')"
                  :placeholder="$t('branch.Branch Name')"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="formData.code"
                  :rules="[requiredValidator]"
                  :label="$t('branch.Code')"
                  :placeholder="$t('branch.Branch Code')"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="formData.mobile"
                  :rules="[requiredValidator]"
                  :label="$t('branch.Mobile')"
                  :placeholder="$t('branch.Branch Mobile')"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="formData.address"
                  :label="$t('branch.Address')"
                  :placeholder="$t('branch.Branch Address')"
                />
              </VCol>

              <VCol cols="12">
                <VSelect
                  v-model="formData.area_id"
                  :items="areas"
                  item-title="name"
                  item-value="id"
                  :label="$t('branch.Area')"
                  :placeholder="$t('branch.Select Area')"
                  :rules="[requiredValidator]"
                  clearable
                  :return-object="false"
                />
              </VCol>

              <VCol cols="12">
                <VCheckbox
                  v-model="formData.status"
                  :label="$t('branch.Active')"
                  color="success"
                />
              </VCol>

              <VCol cols="12">
                <VBtn
                  type="submit"
                  :loading="isSubmitting"
                  class="me-3"
                >
                  {{ $t('branch.Submit') }}
                </VBtn>
                <VBtn
                  type="reset"
                  variant="tonal"
                  color="error"
                  @click="closeModal"
                >
                  {{ $t('branch.Cancel') }}
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>
