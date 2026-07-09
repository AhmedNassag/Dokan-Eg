<script setup>import { useI18n } from 'vue-i18n'
const { t } = useI18n()

const stats = ref([
  { title: t('Total Users'), value: '2,340', icon: 'tabler-users', color: 'primary' },
  { title: t('Active Merchants'), value: '185', icon: 'tabler-building-store', color: 'success' },
  { title: t('Total Orders'), value: '12,450', icon: 'tabler-shopping-cart', color: 'warning' },
  { title: t('Revenue'), value: '$48,200', icon: 'tabler-currency-dollar', color: 'info' },
])
</script>

<template>
  <VRow>
    <VCol cols="12">
      <div>
        <h4 class="text-h4">
          {{ $t('Admin Dashboard') }}
        </h4>
        <p class="text-body-1 mb-0">
          {{ $t('Welcome back, admin. Here is your overview.') }}
        </p>
      </div>
    </VCol>

    <VCol
      v-for="stat in stats"
      :key="stat.title"
      cols="12"
      sm="6"
      lg="3"
    >
      <VCard>
        <VCardText class="d-flex align-center gap-3">
          <VAvatar
            :color="stat.color"
            variant="tonal"
            rounded
            size="48"
          >
            <VIcon
              :icon="stat.icon"
              size="28"
            />
          </VAvatar>
          <div>
            <h5 class="text-h5 mb-0">
              {{ stat.value }}
            </h5>
            <span class="text-body-2">{{ stat.title }}</span>
          </div>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
