import API from '../../api'
import { $api } from '@/utils/api'

class TranslationAPI extends API {
  constructor() {
    super('translation')
  }

  exportByCode(code) {
    return $api(`translations/export?code=${code}`)
  }
}

export default TranslationAPI
