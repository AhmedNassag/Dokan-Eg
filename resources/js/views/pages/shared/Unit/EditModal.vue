<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import UnitAPI from '@/Api/shared/Unit/unit'

const props = defineProps({
  modelValue: { type: Boolean, default: false },
  unit: { type: Object, default: null }
})

const emit = defineEmits(['update:modelValue', 'submit'])

const unitApi = new UnitAPI()

const isSubmitting = ref(false)
const refForm = ref()
const isFormValid = ref(false)
const units = ref([])
const formData = ref({
  name: '',
  code: '',
  short_name: '',
  base_unit: null,
  operator: '*',
  operator_value: 1,
  status: true,
})

async function fetchUnits() {
  try {
    const res = await unitApi.getAll({ per_page: -1 })
    units.value = res.data?.items ?? res.data ?? []
  } catch { units.value = [] }
}

watch(() => props.unit, (newVal) => {
  if (newVal) {
    formData.value = {
      name: newVal.name ?? '',
      code: newVal.code ?? '',
      short_name: newVal.short_name ?? '',
      base_unit: newVal.base_unit ?? null,
      operator: newVal.operator ?? '*',
      operator_value: newVal.operator_value ?? 1,
      status: Boolean(newVal.status),
    }
    nextTick(() => refForm.value?.resetValidation())
  }
})

function closeModal() {
  emit('update:modelValue', false)
  nextTick(() => {
    refForm.value?.reset()
    refForm.value?.resetValidation()
    formData.value = { name: '', code: '', short_name: '', base_unit: null, operator: '*', operator_value: 1, status: true }
  })
}

async function onSubmit() {
  refForm.value?.validate().then(async ({ valid }) => {
    if (!valid) return
    isSubmitting.value = true
    try {
      emit('submit', { ...formData.value })
      closeModal()
    } finally { isSubmitting.value = false }
  })
}

fetchUnits()
</script>

<template>
  <VNavigationDrawer temporary :width="400" location="end" class="scrollable-content"
    :model-value="modelValue" @update:model-value="closeModal">
    <AppDrawerHeaderSection :title="$t('Edit Unit')" @cancel="closeModal" />
    <VDivider />
    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
            <VRow>
              <VCol cols="12">
                <AppTextField v-model="formData.name" :rules="[requiredValidator]"
                  :label="$t('Name')" :placeholder="$t('Unit name')" />
              </VCol>
              <VCol cols="12">
                <AppTextField v-model="formData.code" :rules="[requiredValidator]"
                  :label="$t('Code')" :placeholder="$t('Unit code')" />
              </VCol>
              <VCol cols="12">
                <AppTextField v-model="formData.short_name" :rules="[requiredValidator]"
                  :label="$t('Short Name')" :placeholder="$t('e.g. kg, L, pc')" />
              </VCol>
              <VCol cols="12">
                <VSelect v-model="formData.base_unit" :items="units" item-title="name" item-value="id"
                  :label="$t('Base Unit')" :placeholder="$t('Select base unit (optional)')"
                  clearable :return-object="false" />
              </VCol>
              <VCol cols="6">
                <VSelect v-model="formData.operator" :items="['*', '/', '+', '-']"
                  :label="$t('Operator')" :placeholder="$t('*')" />
              </VCol>
              <VCol cols="6">
                <AppTextField v-model="formData.operator_value" type="number"
                  :label="$t('Operator Value')" :placeholder="$t('1')" />
              </VCol>
              <VCol cols="12">
                <VCheckbox v-model="formData.status" :label="$t('Active')" color="success" />
              </VCol>
              <VCol cols="12">
                <VBtn type="submit" :loading="isSubmitting" class="me-3">{{ $t('Update') }}</VBtn>
                <VBtn type="reset" variant="tonal" color="error" @click="closeModal">{{ $t('Cancel') }}</VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>
