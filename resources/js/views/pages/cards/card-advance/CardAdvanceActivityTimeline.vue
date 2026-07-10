<script setup>import { useI18n } from 'vue-i18n'
const { t } = useI18n()

import avatar1 from '@images/avatars/avatar-1.png'
import avatar2 from '@images/avatars/avatar-2.png'
import avatar3 from '@images/avatars/avatar-3.png'
import pdf from '@images/icons/project-icons/pdf.png'

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
      <template #prepend>
        <VIcon
          icon="tabler-list-details"
          size="24"
          color="high-emphasis"
          class="me-1"
        />
      </template>

      <VCardTitle>{{ $t('Activity Timeline') }}</VCardTitle>

      <template #append>
        <div class="me-n2">
          <MoreBtn :menu-list="moreList" />
        </div>
      </template>
    </VCardItem>

    <VCardText>
      <VTimeline
        side="end"
        align="start"
        line-inset="8"
        truncate-line="start"
        density="compact"
      >
        <!-- SECTION Timeline Item: Flight -->
        <VTimelineItem
          dot-color="primary"
          size="x-small"
        >
          <!-- 👉 Header -->
          <div class="d-flex justify-space-between align-center gap-2 flex-wrap mb-2">
            <span class="app-timeline-title">
              {{ $t('12 Invoices have been paid') }}
            </span>
            <span class="app-timeline-meta">{{ $t('12 min ago') }}</span>
          </div>

          <!-- 👉 Content -->
          <div class="app-timeline-text mt-1">
            {{ $t('Invoices have been paid to the company') }}
          </div>

          <div class="d-inline-flex align-center timeline-chip mt-2">
            <img
              :src="pdf"
              height="20"
              class="me-2"
              alt="img"
            >
            <span class="app-timeline-text font-weight-medium">
              {{ $t('invoice.pdf') }}
            </span>
          </div>
        </VTimelineItem>
        <!-- !SECTION -->

        <!-- SECTION Timeline Item: Interview Schedule -->
        <VTimelineItem
          size="x-small"
          dot-color="success"
        >
          <!-- 👉 Header -->
          <div class="d-flex justify-space-between align-center flex-wrap mb-2">
            <div class="app-timeline-title">
              {{ $t('Client Meeting') }}
            </div>
            <span class="app-timeline-meta">{{ $t('45 min ago') }}</span>
          </div>

          <div class="app-timeline-text mt-1">
            {{ $t('Project meeting with john @10:15am') }}
          </div>

          <!-- 👉 Person -->
          <div class="d-flex justify-space-between align-center flex-wrap">
            <!-- 👉 Avatar & Personal Info -->
            <div class="d-flex align-center mt-2">
              <VAvatar
                size="32"
                class="me-2"
                :image="avatar1"
              />
              <div class="d-flex flex-column">
                <p class="text-sm font-weight-medium text-medium-emphasis mb-0">
                  {{ $t('Lester McCarthy (Client)') }}
                </p>
                <span class="text-sm">{{ $t('CEO of Pixinvent') }}</span>
              </div>
            </div>
          </div>
        </VTimelineItem>
        <!-- !SECTION -->

        <!-- SECTION Design Review -->
        <VTimelineItem
          size="x-small"
          dot-color="info"
        >
          <!-- 👉 Header -->
          <div class="d-flex justify-space-between align-center flex-wrap mb-2">
            <span class="app-timeline-title">
              {{ $t('Create a new project for client') }}
            </span>
            <span class="app-timeline-meta">{{ $t('2 Day Ago') }}</span>
          </div>

          <!-- 👉 Content -->
          <p class="app-timeline-text mt-1 mb-2">
            {{ $t('6 team members in a project') }}
          </p>

          <div class="v-avatar-group demo-avatar-group">
            <VAvatar :size="40">
              <VImg :src="avatar1" />
              <VTooltip
                activator="parent"
                location="top"
              >
                John Doe
              </VTooltip>
            </VAvatar>

            <VAvatar :size="40">
              <VImg :src="avatar2" />
              <VTooltip
                activator="parent"
                location="top"
              >
                {{ $t('Jennie Obrien') }}
              </VTooltip>
            </VAvatar>

            <VAvatar :size="40">
              <VImg :src="avatar3" />
              <VTooltip
                activator="parent"
                location="top"
              >
                {{ $t('Peter Harper') }}
              </VTooltip>
            </VAvatar>

            <VAvatar
              :size="40"
              :color="$vuetify.theme.current.dark ? '#373b50' : '#eeedf0'"
            >
              +3
            </VAvatar>
          </div>
        </VTimelineItem>
        <!-- !SECTION -->
      </VTimeline>
    </VCardText>
  </VCard>
</template>
