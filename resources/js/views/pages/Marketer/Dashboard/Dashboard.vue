<script setup>
const stats = ref([
  { title: 'Campaigns', value: '12', icon: 'tabler-bullhorn', color: 'primary' },
  { title: 'Leads Generated', value: '1,240', icon: 'tabler-users', color: 'success' },
  { title: 'Conversion Rate', value: '3.2%', icon: 'tabler-trending-up', color: 'warning' },
  { title: 'ROI', value: '185%', icon: 'tabler-percentage', color: 'info' },
])
</script>

<template>
  <VRow>
    <VCol cols="12">
      <div>
        <h4 class="text-h4">
          {{ $t('Marketer Dashboard') }}
        </h4>
        <p class="text-body-1 mb-0">
          {{ $t('Welcome back! Here is your marketing overview.') }}
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
