<script setup>
const props = defineProps({ modelValue: { type: Boolean, default: false } })
const emit = defineEmits(['update:modelValue', 'confirm'])
function closeModal() { emit('update:modelValue', false) }
function handleConfirm() { emit('confirm'); closeModal() }
</script>

<template>
  <ConfirmDialog :is-dialog-visible="modelValue"
    :confirmation-question="$t('shippingCompany.Are You Sure You Want To Delete This Shipping Company?')"
    :confirm-title="$t('shippingCompany.Deleted!')" :confirm-msg="$t('shippingCompany.Shipping Company Has Been Deleted Successfully.')"
    :cancel-title="$t('shippingCompany.Cancelled')" :cancel-msg="$t('shippingCompany.Shipping Company Deletion Cancelled.')"
    @update:is-dialog-visible="closeModal" @confirm="handleConfirm" />
</template>
