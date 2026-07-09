<script setup>import { useI18n } from 'vue-i18n'
const { t } = useI18n()

import bootstrapLogo from '@images/icons/brands/bootstrap-logo.png'
import figmaLogo from '@images/icons/brands/figma-logo.png'
import laravelLogo from '@images/icons/brands/laravel-logo.png'
import reactLogo from '@images/icons/brands/react-logo.png'
import sketchLogo from '@images/icons/brands/sketch-logo.png'
import vuejsLogo from '@images/icons/brands/vuejs-logo.png'

const activeProjects = [
  {
    avatarImg: laravelLogo,
    title: t('Laravel'),
    subtitle: t('Ecommerce'),
    stats: '65',
    progressColor: 'error',
  },
  {
    avatarImg: figmaLogo,
    title: t('Figma'),
    subtitle: t('App UI Kit'),
    stats: '86',
    progressColor: 'primary',
  },
  {
    avatarImg: vuejsLogo,
    title: t('VueJs'),
    subtitle: t('Calendar App'),
    stats: '90',
    progressColor: 'success',
  },
  {
    avatarImg: reactLogo,
    title: t('React'),
    subtitle: t('Dashboard'),
    stats: '37',
    progressColor: 'info',
  },
  {
    avatarImg: bootstrapLogo,
    title: t('Bootstrap'),
    subtitle: t('Website'),
    stats: '22',
    progressColor: 'primary',
  },
  {
    avatarImg: sketchLogo,
    title: t('Sketch'),
    subtitle: t('Website Design'),
    stats: '29',
    progressColor: 'warning',
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
  <VCard>
    <VCardItem>
      <VCardTitle>{{ $t('Active Projects') }}</VCardTitle>
      <VCardSubtitle>
        {{ $t('Average 72% completed') }}
      </VCardSubtitle>
      <template #append>
        <div class="mt-n4 me-n2">
          <MoreBtn :menu-list="moreList" />
        </div>
      </template>
    </VCardItem>

    <VCardText>
      <VList class="card-list">
        <VListItem
          v-for="project in activeProjects"
          :key="project.title"
        >
          <template #prepend>
            <VAvatar
              size="34"
              rounded
              class="me-1"
            >
              <img :src="project.avatarImg">
            </VAvatar>
          </template>

          <VListItemTitle class="font-weight-medium">
            {{ project.title }}
          </VListItemTitle>
          <VListItemSubtitle class="me-4">
            {{ project.subtitle }}
          </VListItemSubtitle>

          <template #append>
            <div class="d-flex align-center gap-x-4">
              <div style="inline-size: 4.875rem;">
                <VProgressLinear
                  :model-value="project.stats"
                  :color="project.progressColor"
                  height="8"
                  rounded-bar
                  rounded
                />
              </div>
              <span class="text-disabled">{{ project.stats }}%</span>
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
