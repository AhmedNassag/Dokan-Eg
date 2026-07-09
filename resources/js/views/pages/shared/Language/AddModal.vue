<script setup>
const props = defineProps({ modelValue: { type: Boolean, default: false } })
const emit = defineEmits(['update:modelValue', 'submit'])

const isSubmitting = ref(false)
const refForm = ref()
const isFormValid = ref(false)

const formData = ref({ name: '', code: '', direction: 'ltr', is_default: false, status: true })

function closeModal() {
  emit('update:modelValue', false)
  nextTick(() => {
    refForm.value?.reset(); refForm.value?.resetValidation()
    formData.value = { name: '', code: '', direction: 'ltr', is_default: false, status: true }
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
  <VDialog :model-value="modelValue" @update:model-value="closeModal" max-width="500">
    <VCard>
      <VCardTitle>{{ $t('language.Add Language') }}</VCardTitle>
      <VCardText>
        <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
          <VRow>
            <VCol cols="12">
              <AppTextField v-model="formData.name" :rules="[requiredValidator]" :label="$t('language.Language Name')" :placeholder="$t('language.Language Name')" />
            </VCol>
            <VCol cols="6">
              <AppTextField v-model="formData.code" :rules="[requiredValidator]" :label="$t('language.Code')" :placeholder="$t('language.en-ar-fr')" />
            </VCol>
            <VCol cols="6">
              <VSelect v-model="formData.direction" :items="['ltr', 'rtl']" :label="$t('language.Direction')" hide-details />
            </VCol>
            <VCol cols="12">
              <VCheckbox v-model="formData.is_default" :label="$t('language.Default')" color="primary" />
            </VCol>
            <VCol cols="12">
              <VCheckbox v-model="formData.status" :label="$t('language.Active')" color="success" />
            </VCol>
          </VRow>
          <VRow class="mt-2">
            <VCol cols="12">
              <VBtn type="submit" :loading="isSubmitting" class="me-3">{{ $t('language.Submit') }}</VBtn>
              <VBtn variant="tonal" color="error" @click="closeModal">{{ $t('language.Cancel') }}</VBtn>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </VDialog>
</template>
