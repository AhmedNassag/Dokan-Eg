<script setup>
const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:modelValue', 'confirm'])

function closeModal() {
  emit('update:modelValue', false)
}

function handleConfirm() {
  emit('confirm')
  closeModal()
}
</script>

<template>
  <ConfirmDialog
    :is-dialog-visible="modelValue"
    :confirmation-question="$t('category.Are You Sure You Want To Delete This Category?')"
    :confirm-title="$t('category.Deleted!')"
    :confirm-msg="$t('category.Category Has Been Deleted Successfully.')"
    :cancel-title="$t('category.Cancelled')"
    :cancel-msg="$t('category.Category Deletion Cancelled.')"
    @update:is-dialog-visible="closeModal"
    @confirm="handleConfirm"
  />
</template>
