import { $api } from '@/utils/api'
import { useAuthStore } from '@/stores/auth'

export const apiClient = $api

export function setAuthHeader(token) {
  if (token) {
    apiClient.defaults?.headers?.set?.('Authorization', `Bearer ${token}`)
  }
}

export function clearAuthHeader() {
  apiClient.defaults?.headers?.delete?.('Authorization')
}
