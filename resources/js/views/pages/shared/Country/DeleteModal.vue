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
    :confirmation-question="$t('Are you sure you want to delete this country?')"
    :confirm-title="$t('Deleted!')"
    :confirm-msg="$t('Country has been deleted successfully.')"
    :cancel-title="$t('Cancelled')"
    :cancel-msg="$t('Country deletion cancelled.')"
    @update:is-dialog-visible="closeModal"
    @confirm="handleConfirm"
  />
</template>
