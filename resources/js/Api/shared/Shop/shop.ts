import { $api } from '@/utils/api'
import API from '../../api'

class ShopAPI extends API {
  constructor() {
    super('shop')
  }

  // Update supports multipart uploads via POST + _method spoofing,
  // because PHP does not parse multipart bodies on PUT requests.
  async update(id, data) {
    try {
      if (data instanceof FormData) {
        data.append('_method', 'PUT')

        return await $api(`${this.basePath}/${id}`, {
          method: 'POST',
          body: data,
        })
      }

      return await $api(`${this.basePath}/${id}`, {
        method: 'PUT',
        body: data,
      })
    } catch (error) {
      throw error
    }
  }
}

export default ShopAPI
