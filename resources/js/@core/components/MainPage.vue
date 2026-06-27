<script setup>
defineProps({
  title: { type: String, default: '' },
  description: { type: String, default: '' },
})
</script>

<template>
  <VRow>
    <VCol cols="12">
      <div class="d-flex flex-wrap align-center">
        <div>
          <h4 class="text-h4">
            {{ title }}
          </h4>
          <p
            v-if="description"
            class="text-body-1 mb-0"
          >
            {{ description }}
          </p>
        </div>
        <VSpacer />
        <slot name="actions" />
      </div>
    </VCol>
    <VCol cols="12">
      <slot />
    </VCol>
  </VRow>
</template>
