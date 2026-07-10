<script setup>
import { useConfigStore } from '@core/stores/config'
import LanguageAPI from '@/Api/shared/Language/language'

const props = defineProps({
  languages: {
    type: Array,
    default: () => [],
  },
  location: {
    type: null,
    required: false,
    default: 'bottom end',
  },
})

const langApi = new LanguageAPI()
const configStore = useConfigStore()
const { locale } = useI18n({ useScope: 'global' })

const availableLanguages = ref([])

async function fetchLanguages() {
  try {
    const res = await langApi.getAll({ status: 1, per_page: -1 })
    const langs = Array.isArray(res.data) ? res.data : []
    availableLanguages.value = langs

    const current = langs.find(l => l.code === locale.value)
    if (current) {
      configStore.isAppRTL = current.direction === 'rtl'
    }
  } catch {
    availableLanguages.value = props.languages.length
      ? props.languages.map(l => ({ code: l.i18nLang, name: l.label, direction: l.isRTL ? 'rtl' : 'ltr' }))
      : []
  }
}

function selectLanguage(lang) {
  configStore.isAppRTL = lang.direction === 'rtl'
  locale.value = lang.code
}

fetchLanguages()
</script>

<template>
  <IconBtn>
    <VIcon icon="tabler-language" />

    <VMenu
      activator="parent"
      :location="props.location"
      offset="12px"
      width="175"
    >
      <VList
        :selected="[locale]"
        color="primary"
      >
        <VListItem
          v-for="lang in availableLanguages"
          :key="lang.code"
          :value="lang.code"
          @click="selectLanguage(lang)"
        >
          <VListItemTitle>{{ lang.name }}</VListItemTitle>
        </VListItem>
      </VList>
    </VMenu>
  </IconBtn>
</template>
