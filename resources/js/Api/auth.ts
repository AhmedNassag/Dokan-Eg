import { $api } from '@/utils/api'
import { useAuthStore } from '@/stores/auth'

export async function login(credentials) {
  const authStore = useAuthStore()
  return authStore.login(credentials)
}

export async function logout() {
  const authStore = useAuthStore()
  return authStore.logout()
}

export function getToken() {
  return useCookie('accessToken').value
}

export function getUser() {
  return useCookie('userData').value
}

export function hasRole(role) {
  const user = getUser()
  return user?.user_type === role
}
