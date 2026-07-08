<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'

const props = defineProps({
  modelValue: { type: Boolean, default: false },
  brand: { type: Object, default: null }
})

const emit = defineEmits(['update:modelValue', 'submit'])

const isSubmitting = ref(false)
const refForm = ref()
const isFormValid = ref(false)
const formData = ref({ name: '', code: '', status: true })

watch(() => props.brand, (newVal) => {
  if (newVal) {
    formData.value = {
      name: newVal.name ?? '',
      code: newVal.code ?? '',
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
    formData.value = { name: '', code: '', status: true }
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
</script>

<template>
  <VNavigationDrawer temporary :width="400" location="end" class="scrollable-content"
    :model-value="modelValue" @update:model-value="closeModal">
    <AppDrawerHeaderSection :title="$t('Edit Brand')" @cancel="closeModal" />
    <VDivider />
    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
            <VRow>
              <VCol cols="12">
                <AppTextField v-model="formData.name" :rules="[requiredValidator]"
                  :label="$t('Name')" :placeholder="$t('Brand name')" />
              </VCol>
              <VCol cols="12">
                <AppTextField v-model="formData.code" :rules="[requiredValidator]"
                  :label="$t('Code')" :placeholder="$t('Brand code')" />
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
