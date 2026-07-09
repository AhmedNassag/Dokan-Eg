<script setup>
const props = defineProps({ modelValue: { type: Boolean, default: false } })
const emit = defineEmits(['update:modelValue', 'confirm'])

function onConfirm() {
  emit('confirm')
  emit('update:modelValue', false)
}
</script>

<template>
  <VDialog :model-value="modelValue" @update:model-value="$emit('update:modelValue', $event)" max-width="400">
    <VCard>
      <VCardTitle>{{ $t('language.Delete Language') }}</VCardTitle>
      <VCardText>{{ $t('language.Are You Sure You Want To Delete This Language?') }}</VCardText>
      <VCardActions>
        <VSpacer />
        <VBtn variant="tonal" @click="$emit('update:modelValue', false)">{{ $t('language.Cancel') }}</VBtn>
        <VBtn color="error" @click="onConfirm">{{ $t('language.Delete') }}</VBtn>
      </VCardActions>
    </VCard>
  </VDialog>
</template>
