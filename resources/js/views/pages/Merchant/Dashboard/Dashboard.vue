<script setup>
import { useI18n } from 'vue-i18n';

const { t } = useI18n()

const stats = ref([
  { title: t('merchantDashboard.Total Products'), value: '342', icon: 'tabler-box', color: 'primary' },
  { title: t('merchantDashboard.Active Orders'), value: '56', icon: 'tabler-shopping-cart', color: 'success' },
  { title: t('merchantDashboard.Total Sales'), value: '$12,430', icon: 'tabler-currency-dollar', color: 'warning' },
  { title: t('merchantDashboard.Avg Rating'), value: '4.5', icon: 'tabler-star', color: 'info' },
])
</script>

<template>
  <VRow>
    <VCol cols="12">
      <div>
        <h4 class="text-h4">
          {{ $t('merchantDashboard.Merchant Dashboard') }}
        </h4>
        <p class="text-body-1 mb-0">
          {{ $t('merchantDashboard.Welcome back! Here Is Your Store Overview.') }}
        </p>
      </div>
    </VCol>

    <VCol v-for="stat in stats" :key="stat.title" cols="12" sm="6" lg="3">
      <VCard>
        <VCardText class="d-flex align-center gap-3">
          <VAvatar :color="stat.color" variant="tonal" rounded size="48">
            <VIcon :icon="stat.icon" size="28" />
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
