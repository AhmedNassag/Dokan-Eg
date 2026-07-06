import { watch } from 'vue'
import { getI18n } from '@/plugins/i18n/index'
import TranslationAPI from '@/Api/shared/Translation/translation'

const transApi = new TranslationAPI()

async function loadDynamicTranslations(locale) {
  try {
    const res = await transApi.exportByCode(locale)
    const data = res.data ?? {}
    if (Object.keys(data).length) {
      const i18n = getI18n()
      i18n.global.mergeLocaleMessage(locale, data)
    }
  } catch {
    // Silent fail — static JSON files serve as fallback
  }
}

export default function (app) {
  const i18n = getI18n()

  loadDynamicTranslations(i18n.global.locale.value)

  watch(i18n.global.locale, (newLocale) => {
    loadDynamicTranslations(newLocale)
  })
}
