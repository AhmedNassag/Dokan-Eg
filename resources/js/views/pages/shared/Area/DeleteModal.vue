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
    :confirmation-question="$t('area.Are You Sure You Want To Delete This Area?')"
    :confirm-title="$t('area.Deleted!')"
    :confirm-msg="$t('area.Area Has Been Deleted Successfully.')"
    :cancel-title="$t('area.Cancelled')"
    :cancel-msg="$t('area.Area Deletion Cancelled.')"
    @update:is-dialog-visible="closeModal"
    @confirm="handleConfirm"
  />
</template>
