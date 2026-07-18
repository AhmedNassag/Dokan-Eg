import { $api } from '@/utils/api'

class ShopSectionAPI {
  constructor(shopId) {
    this.basePath = `shop/${shopId}/sections`
  }

  async getAll() {
    return await $api(this.basePath)
  }

  async create(data) {
    return await $api(this.basePath, { method: 'POST', body: data })
  }

  async update(sectionId, data) {
    return await $api(`${this.basePath}/${sectionId}`, {
      method: 'PUT',
      body: data,
    })
  }

  async delete(sectionId) {
    return await $api(`${this.basePath}/${sectionId}`, { method: 'DELETE' })
  }

  async reorder(ids) {
    return await $api(`${this.basePath}/reorder`, {
      method: 'POST',
      body: { ids },
    })
  }
}

export default ShopSectionAPI
