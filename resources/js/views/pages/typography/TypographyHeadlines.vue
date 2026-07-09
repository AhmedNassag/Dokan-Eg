<template>
  <VCard :title="$t('Headlines')">
    <VCardText>
      <VRow>
        <VCol cols="12">
          <h1 class="text-h1">
            {{ $t('Heading 1') }}
          </h1>
          <span>{{ $t('font-size: 2.875rem / line-height: 4.25rem / font-weight: 500') }}</span>
        </VCol>

        <VCol cols="12">
          <h2 class="text-h2">
            {{ $t('Heading 2') }}
          </h2>
          <span>{{ $t('font-size: 2.375rem / line-height: 3.5rem / font-weight: 500') }}</span>
        </VCol>

        <VCol cols="12">
          <h3 class="text-h3">
            {{ $t('Heading 3') }}
          </h3>
          <span>{{ $t('font-size: 1.75rem / line-height: 2.625rem / font-weight: 500') }}</span>
        </VCol>

        <VCol cols="12">
          <h4 class="text-h4">
            {{ $t('Heading 4') }}
          </h4>
          <span>{{ $t('font-size: 1.5rem / line-height: 2.375rem / font-weight: 500') }}</span>
        </VCol>

        <VCol cols="12">
          <h5 class="text-h5">
            {{ $t('Heading 5') }}
          </h5>
          <span>{{ $t('font-size: 1.125rem / line-height: 1.75rem / font-weight: 500') }}</span>
        </VCol>

        <VCol cols="12">
          <h6 class="text-h6">
            {{ $t('Heading 6') }}
          </h6>
          <span>{{ $t('font-size: 0.9375rem / line-height: 1.375rem / font-weight: 500') }}</span>
        </VCol>
      </VRow>
    </VCardText>
  </VCard>
</template>
