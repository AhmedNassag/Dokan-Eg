<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:modelValue', 'submit'])

const isSubmitting = ref(false)
const refForm = ref()
const isFormValid = ref(false)
const formData = ref({
  name: '',
  status: true
})

function closeModal() {
  emit('update:modelValue', false)
  nextTick(() => {
    refForm.value?.reset()
    refForm.value?.resetValidation()
    formData.value = { name: '', status: true }
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
      :title="$t('country.Add Country')"
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
                  :label="$t('country.Name')"
                  :placeholder="$t('country.Country Name')"
                />
              </VCol>

              <VCol cols="12">
                <VCheckbox
                  v-model="formData.status"
                  :label="$t('country.Active')"
                  color="success"
                />
              </VCol>

              <VCol cols="12">
                <VBtn
                  type="submit"
                  :loading="isSubmitting"
                  class="me-3"
                >
                  {{ $t('country.Submit') }}
                </VBtn>
                <VBtn
                  type="reset"
                  variant="tonal"
                  color="error"
                  @click="closeModal"
                >
                  {{ $t('country.Cancel') }}
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>
