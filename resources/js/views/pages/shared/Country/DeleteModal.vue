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
    :confirmation-question="$t('country.Are You Sure You Want To Delete This Country?')"
    :confirm-title="$t('country.Deleted!')"
    :confirm-msg="$t('country.Country Has Been Deleted Successfully.')"
    :cancel-title="$t('country.Cancelled')"
    :cancel-msg="$t('country.Country Deletion Cancelled.')"
    @update:is-dialog-visible="closeModal"
    @confirm="handleConfirm"
  />
</template>
