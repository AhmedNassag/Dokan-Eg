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
    :confirmation-question="$t('branch.Are You Sure You Want To Delete This Branch?')"
    :confirm-title="$t('branch.Deleted!')"
    :confirm-msg="$t('branch.Branch Has Been Deleted Successfully.')"
    :cancel-title="$t('branch.Cancelled')"
    :cancel-msg="$t('branch.Branch Deletion Cancelled.')"
    @update:is-dialog-visible="closeModal"
    @confirm="handleConfirm"
  />
</template>
