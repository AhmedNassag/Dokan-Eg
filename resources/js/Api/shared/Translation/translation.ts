import API from '../../api'

class TranslationAPI extends API {
  constructor() {
    super('translation')
  }

  exportByCode(code) {
    return this.$api(`translations/export?code=${code}`)
  }
}

export default TranslationAPI
