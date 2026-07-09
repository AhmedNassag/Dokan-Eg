<script setup>import { useI18n } from 'vue-i18n'
const { t } = useI18n()

import { VIcon } from 'vuetify/components/VIcon'
import sliderBar1 from '@images/illustrations/sidebar-pic-1.png'
import sliderBar2 from '@images/illustrations/sidebar-pic-2.png'
import sliderBar3 from '@images/illustrations/sidebar-pic-3.png'

const websiteAnalytics = [
  {
    name: t('Traffic'),
    slideImg: sliderBar1,
    data: [
      {
        number: '1.5k',
        text: t('Sessions'),
      },
      {
        number: '3.1k',
        text: t('Page Views'),
      },
      {
        number: '1.2k',
        text: t('Leads'),
      },
      {
        number: '12%',
        text: t('Conversions'),
      },
    ],
  },
  {
        name: t('Spending'),
    slideImg: sliderBar2,
    data: [
      {
        number: '12h',
        text: t('Spend'),
      },
      {
        number: '182',
        text: t('Order Size'),
      },
      {
        number: '127',
        text: t('Order'),
      },
      {
        number: '23k',
        text: t('Items'),
      },
    ],
  },
  {
        name: t('Revenue Sources'),
    slideImg: sliderBar3,
    data: [
      {
        number: '268',
        text: t('Direct'),
      },
      {
        number: '890',
        text: t('Organic'),
      },
      {
        number: '622',
        text: t('Referral'),
      },
      {
        number: '1.2k',
        text: t('Campaign'),
      },
    ],
  },
]
</script>

<template>
  <VCard color="primary">
    <VCarousel
      cycle
      :continuous="false"
      :show-arrows="false"
      hide-delimiter-background
      :delimiter-icon="() => h(VIcon, { icon: 'fa-circle', size: '8' })"
      height="auto"
      class="carousel-delimiter-top-end web-analytics-carousel"
    >
      <VCarouselItem
        v-for="item in websiteAnalytics"
        :key="item.name"
      >
        <VCardText class="position-relative">
          <VRow>
            <VCol cols="12">
              <h5 class="text-h5 text-white">
                {{ $t('Website Analytics') }}
              </h5>
              <p class="text-sm mb-0">
                {{ $t('Total 28.5% Conversion Rate') }}
              </p>
            </VCol>

            <VCol
              cols="12"
              sm="6"
              order="2"
              order-sm="1"
            >
              <VRow>
                <VCol
                  cols="12"
                  class="pb-0 pt-1"
                >
                  <h6 class="text-h6 text-white mb-1 mt-5">
                    {{ item.name }}
                  </h6>
                </VCol>

                <VCol
                  v-for="d in item.data"
                  :key="d.number"
                  cols="6"
                  class="text-no-wrap pb-2"
                >
                  <VChip
                    label
                    variant="flat"
                    size="default"
                    color="rgb(var(--v-theme-primary-darken-1))"
                    class="font-weight-medium text-white rounded me-2 px-2"
                    style="block-size: 30px;"
                  >
                    <span class="text-base">{{ d.number }}</span>
                  </VChip>
                  <span class="d-inline-block">{{ d.text }}</span>
                </VCol>
              </VRow>
            </VCol>

            <VCol
              cols="12"
              sm="6"
              order="1"
              order-sm="2"
              class="text-center"
            >
              <img
                :src="item.slideImg"
                class="card-website-analytics-img"
                style="filter: drop-shadow(0 4px 60px rgba(0, 0, 0, 50%));"
              >
            </VCol>
          </VRow>
        </VCardText>
      </VCarouselItem>
    </VCarousel>
  </VCard>
</template>

<style lang="scss">
.card-website-analytics-img {
  block-size: 150px;
}

@media screen and (min-width: 600px) {
  .card-website-analytics-img {
    position: absolute;
    margin: auto;
    inset-block-end: 2rem;
    inset-inline-end: 2rem;
  }
}

.web-analytics-carousel {
  .v-carousel__controls {
    .v-carousel__controls__item {
      &.v-btn--active {
        .v-icon {
          opacity: 1 !important;
        }
      }
    }
  }
}
</style>
