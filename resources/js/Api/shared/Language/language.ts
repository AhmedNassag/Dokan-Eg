import API from '../../api'
import { $api } from '@/utils/api'

class LanguageAPI extends API {
  constructor() {
    super('language')
  }

  async setDefault(id) {
    try {
      return await $api(`language/${id}/set-default`, { method: 'PUT' })
    } catch (error) {
      throw error
    }
  }
}

export default LanguageAPI
