<template>
  <VCardText class="text-center py-4">
    <h4 class="text-h4 text-center">
      {{ $t('Still need help?') }}
    </h4>
    <p class="my-4 text-body-1">
      {{ $t('Our specialists are always happy to help.') }}
      <br>
      {{ $t('Contact us during standard business hours or email us 24/7, and we\'ll get back to you.') }}
    </p>
    <div class="d-flex justify-center gap-4 flex-wrap">
      <VBtn>{{ $t('Visit our community') }}</VBtn>
      <VBtn>{{ $t('Contact us') }}</VBtn>
    </div>
  </VCardText>
</template>
