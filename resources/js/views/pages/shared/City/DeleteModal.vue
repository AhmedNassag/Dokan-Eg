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
    :confirmation-question="$t('city.Are You Sure You Want to Delete This City?')"
    :confirm-title="$t('city.Deleted!')"
    :confirm-msg="$t('city.City Has Been Deleted Successfully.')"
    :cancel-title="$t('city.Cancelled')"
    :cancel-msg="$t('city.City Deletion Cancelled.')"
    @update:is-dialog-visible="closeModal"
    @confirm="handleConfirm"
  />
</template>
