<script setup>import { useI18n } from 'vue-i18n'
const { t } = useI18n()

const coursesData = [
  {
    title: t('Videography Basic Design Course'),
    views: '1.2k',
    icon: 'tabler-brand-zoom',
    color: 'primary',
  },
  {
    title: t('Basic Front-end Development Course'),
    views: '834',
    icon: 'tabler-code',
    color: 'info',
  },
  {
    title: t('Basic Fundamentals of Photography'),
    views: '3.7k',
    icon: 'tabler-camera',
    color: 'success',
  },
  {
    title: t('Advance Dribble Base Visual Design'),
    views: '2.5k',
    icon: 'tabler-brand-dribbble',
    color: 'warning',
  },
  {
    title: t('Your First Singing Lesson'),
    views: '948',
    icon: 'tabler-microphone-2',
    color: 'error',
  },
]
</script>

<template>
  <VCard>
    <VCardItem :title="$t('Top Courses')">
      <template #append>
        <MoreBtn />
      </template>
    </VCardItem>

    <VCardText>
      <VList class="card-list">
        <VListItem
          v-for="(course, index) in coursesData"
          :key="index"
        >
          <template #prepend>
            <VAvatar
              rounded
              variant="tonal"
              :color="course.color"
              class="me-1"
            >
              <VIcon
                :icon="course.icon"
                size="24"
              />
            </VAvatar>
          </template>

          <VListItemTitle class="me-4">
            <div class="d-flex flex-column">
              <h6 class="text-h6 text-truncate">
                {{ course.title }}
              </h6>
              <div>
                <VChip
                  variant="tonal"
                  color="secondary"
                  label
                  size="small"
                >
                  {{ course.views }} Views
                </VChip>
              </div>
            </div>
          </VListItemTitle>
        </VListItem>
      </VList>
    </VCardText>
  </VCard>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 24px;
}
</style>
