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
    confirmation-question="Are you sure you want to delete this city?"
    confirm-title="Deleted!"
    confirm-msg="City has been deleted successfully."
    cancel-title="Cancelled"
    cancel-msg="City deletion cancelled."
    @update:is-dialog-visible="closeModal"
    @confirm="handleConfirm"
  />
</template>
