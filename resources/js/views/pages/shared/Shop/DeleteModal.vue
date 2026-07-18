<script setup>
const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false,
  },
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
    :confirmation-question="$t('shop.Are You Sure You Want To Delete This Shop?')"
    :confirm-title="$t('shop.Deleted!')"
    :confirm-msg="$t('shop.Shop Has Been Deleted Successfully.')"
    :cancel-title="$t('shop.Cancelled')"
    :cancel-msg="$t('shop.Shop Deletion Cancelled.')"
    @update:is-dialog-visible="closeModal"
    @confirm="handleConfirm"
  />
</template>
