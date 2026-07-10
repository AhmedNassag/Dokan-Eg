<script setup>import { useI18n } from 'vue-i18n'
const { t } = useI18n()

const transitions = [
  {
    avatarIcon: 'tabler-wallet',
    avatarColor: 'primary',
    title: t('Wallet'),
    subtitle: t('Starbucks'),
    stats: '-$75',
    profit: false,
  },
  {
    avatarIcon: 'tabler-building-bank',
    avatarColor: 'success',
    title: t('Bank Transfer'),
    subtitle: t('Add Money'),
    stats: '+$480',
    profit: true,
  },
  {
    avatarIcon: 'tabler-brand-paypal',
    avatarColor: 'error',
    title: t('PayPal'),
    subtitle: t('Client Payment'),
    stats: '+$268',
    profit: true,
  },
  {
    avatarIcon: 'tabler-credit-card',
    avatarColor: 'secondary',
    title: t('Master Card'),
    subtitle: t('Ordered iPhone 13'),
    stats: '-$699',
    profit: false,
  },
  {
    avatarIcon: 'tabler-currency-dollar',
    avatarColor: 'info',
    title: t('Bank Transactions'),
    subtitle: t('Refund'),
    stats: '+$98',
    profit: true,
  },
  {
    avatarIcon: 'tabler-brand-paypal',
    avatarColor: 'error',
    title: t('PayPal'),
    subtitle: t('Client Payment'),
    stats: '+$126',
    profit: true,
  },
  {
    avatarIcon: 'tabler-building-bank',
    avatarColor: 'success',
    title: t('Bank Transfer'),
    subtitle: t('Pay Office Rent'),
    stats: '-$1290',
    profit: false,
  },
]

const moreList = [
  {
    title: t('Refresh'),
    value: 'refresh',
  },
  {
    title: t('Download'),
    value: 'Download',
  },
  {
    title: t('View All'),
    value: 'View All',
  },
]
</script>

<template>
  <VCard
    :title="$t('Transactions')"
    :subtitle="$t('Total 58 Transactions done in this Month')"
  >
    <template #append>
      <div class="mt-n4 me-n2">
        <MoreBtn :menu-list="moreList" />
      </div>
    </template>

    <VCardText>
      <VList class="card-list">
        <VListItem
          v-for="transition in transitions"
          :key="transition.title"
        >
          <template #prepend>
            <VAvatar
              size="34"
              :color="transition.avatarColor"
              variant="tonal"
              class="me-1"
              rounded
            >
              <VIcon
                :icon="transition.avatarIcon"
                size="22"
              />
            </VAvatar>
          </template>

          <VListItemTitle class="font-weight-medium">
            {{ transition.title }}
          </VListItemTitle>
          <VListItemSubtitle>
            {{ transition.subtitle }}
          </VListItemSubtitle>

          <template #append>
            <div class="d-flex align-center">
              <span :class="`${transition.profit ? 'text-success' : 'text-error'} font-weight-medium me-2`">{{ transition.stats }}</span>
            </div>
          </template>
        </VListItem>
      </VList>
    </VCardText>
  </VCard>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 16px;
}
</style>
