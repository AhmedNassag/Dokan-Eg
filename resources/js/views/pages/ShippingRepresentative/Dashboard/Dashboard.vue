<script setup>
const stats = ref([
  { title: 'Pending Deliveries', value: '24', icon: 'tabler-truck-delivery', color: 'primary' },
  { title: 'Completed Today', value: '18', icon: 'tabler-circle-check', color: 'success' },
  { title: 'In Transit', value: '42', icon: 'tabler-map-pin', color: 'warning' },
  { title: 'Total Deliveries', value: '1,560', icon: 'tabler-package', color: 'info' },
])
</script>

<template>
  <VRow>
    <VCol cols="12">
      <div>
        <h4 class="text-h4">{{ $t('Shipping Representative Dashboard') }}</h4>
        <p class="text-body-1 mb-0">{{ $t('Welcome back! Here is your delivery overview.') }}</p>
      </div>
    </VCol>

    <VCol v-for="stat in stats" :key="stat.title" cols="12" sm="6" lg="3">
      <VCard>
        <VCardText class="d-flex align-center gap-3">
          <VAvatar :color="stat.color" variant="tonal" rounded size="48">
            <VIcon :icon="stat.icon" size="28" />
          </VAvatar>
          <div>
            <h5 class="text-h5 mb-0">{{ stat.value }}</h5>
            <span class="text-body-2">{{ stat.title }}</span>
          </div>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
