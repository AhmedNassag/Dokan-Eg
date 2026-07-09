<script setup>import { useI18n } from 'vue-i18n'
const { t } = useI18n()

const assignmentData = [
  {
    title: t('User Experience Design'),
    tasks: 120,
    progress: 72,
    color: 'primary',
  },
  {
    title: t('Basic fundamentals'),
    tasks: 32,
    progress: 48,
    color: 'success',
  },
  {
    title: t('React Native components'),
    tasks: 182,
    progress: 15,
    color: 'error',
  },
  {
    title: t('Basic of music theory'),
    tasks: 56,
    progress: 24,
    color: 'info',
  },
]
</script>

<template>
  <VCard>
    <VCardItem :title="$t('Assignment progress')">
      <template #append>
        <MoreBtn />
      </template>
    </VCardItem>
    <VCardText>
      <VList class="card-list">
        <VListItem
          v-for="assignment in assignmentData"
          :key="assignment.title"
        >
          <template #prepend>
            <VProgressCircular
              v-model="assignment.progress"
              :size="54"
              class="me-4"
              :color="assignment.color"
            >
              <span class="text-body-1 text-high-emphasis font-weight-medium">
                {{ assignment.progress }}%
              </span>
            </VProgressCircular>
          </template>
          <VListItemTitle class="font-weight-medium mb-2 me-2">
            {{ assignment.title }}
          </VListItemTitle>

          <VListItemSubtitle class="me-2">
            {{ assignment.tasks }} {{ $t('Tasks') }}
          </VListItemSubtitle>
          <template #append>
            <VBtn
              icon
              variant="tonal"
              color="secondary"
              rounded
              size="30"
            >
              <VIcon
                icon="tabler-chevron-right"
                class="flip-in-rtl"
                size="20"
              />
            </VBtn>
          </template>
        </VListItem>
      </VList>
    </VCardText>
  </VCard>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 2rem;
}
</style>
