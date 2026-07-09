<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false
  },
  category: {
    type: Object,
    default: null
  },
  categories: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['update:modelValue', 'submit'])

const isSubmitting = ref(false)
const refForm = ref()
const isFormValid = ref(false)
const formData = ref({
  name: '',
  description: '',
  parent_id: null,
  is_active: true
})

watch(() => props.category, (newVal) => {
  if (newVal) {
    formData.value = {
      name: newVal.name ?? '',
      description: newVal.description ?? '',
      parent_id: newVal.parent_id ?? null,
      is_active: Boolean(newVal.is_active)
    }
    nextTick(() => refForm.value?.resetValidation())
  }
})

function closeModal() {
  emit('update:modelValue', false)
  nextTick(() => {
    refForm.value?.reset()
    refForm.value?.resetValidation()
    formData.value = { name: '', description: '', parent_id: null, is_active: true }
  })
}

async function onSubmit() {
  refForm.value?.validate().then(async ({ valid }) => {
    if (!valid) return
    isSubmitting.value = true
    try {
      const data = { ...formData.value }
      if (data.parent_id === '' || data.parent_id === null || data.parent_id === undefined) {
        delete data.parent_id
      } else {
        data.parent_id = Number(data.parent_id)
      }
      emit('submit', data)
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
      :title="$t('category.Edit Category')"
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
                  :label="$t('category.Name')"
                  :placeholder="$t('category.Category Name')"
                />
              </VCol>

              <VCol cols="12">
                <VSelect
                  v-model="formData.parent_id"
                  :items="categories.filter(c => !category || c.id !== category.id)"
                  item-title="name"
                  item-value="id"
                  :label="$t('category.Parent Category')"
                  :placeholder="$t('category.Select Parent Category')"
                  clearable
                  :return-object="false"
                />
              </VCol>

              <VCol cols="12">
                <VCheckbox
                  v-model="formData.is_active"
                  :label="$t('category.Active')"
                  color="success"
                />
              </VCol>

              <VCol cols="12">
                <AppTextarea
                  v-model="formData.description"
                  :label="$t('category.Description')"
                  :placeholder="$t('category.Category Description')"
                  auto-grow
                />
              </VCol>

              <VCol cols="12">
                <VBtn
                  type="submit"
                  :loading="isSubmitting"
                  class="me-3"
                >
                  {{ $t('category.Update') }}
                </VBtn>
                <VBtn
                  type="reset"
                  variant="tonal"
                  color="error"
                  @click="closeModal"
                >
                  {{ $t('category.Cancel') }}
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>
