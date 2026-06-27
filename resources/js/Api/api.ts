import { $api } from '@/utils/api'

export default class API {
  constructor(basePath) {
    this.basePath = basePath
  }

  async getAll(params = {}) {
    try {
      return await $api(this.basePath, { query: params })
    } catch (error) {
      throw error
    }
  }

  async getById(id) {
    try {
      return await $api(`${this.basePath}/${id}`)
    } catch (error) {
      throw error
    }
  }

  async create(data) {
    try {
      return await $api(this.basePath, { method: 'POST', body: data })
    } catch (error) {
      throw error
    }
  }

  async update(id, data) {
    try {
      return await $api(`${this.basePath}/${id}`, {
        method: 'PUT',
        body: data,
      })
    } catch (error) {
      throw error
    }
  }

  async delete(id) {
    try {
      return await $api(`${this.basePath}/${id}`, { method: 'DELETE' })
    } catch (error) {
      throw error
    }
  }
}
