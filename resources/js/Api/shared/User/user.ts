import API from '../../api'

class UserAPI extends API {
  constructor() {
    super('user')
  }

  async getAll(params = {}) {
    try {
      return await $api('user/list', { query: params })
    } catch (error) {
      throw error
    }
  }

  async getRoles() {
    try {
      return await $api('roles-list')
    } catch (error) {
      throw error
    }
  }
}

export default UserAPI
