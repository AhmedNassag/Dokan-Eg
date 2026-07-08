import { canNavigate } from '@layouts/plugins/casl'
import { useAuthStore } from '@/stores/auth'

export const setupGuards = router => {
  // 👉 router.beforeEach
  // Docs: https://router.vuejs.org/guide/advanced/navigation-guards.html#global-before-guards
  router.beforeEach(to => {
    if (to.meta.public)
      return

    const authStore = useAuthStore()
    const isLoggedIn = !!(authStore.user || (useCookie('userData').value && useCookie('accessToken').value))

    if (to.meta.unauthenticatedOnly) {
      if (isLoggedIn)
        return '/'
      else
        return undefined
    }
    if (!isLoggedIn)
      return { name: 'login', query: { to: to.fullPath !== '/' ? to.path : undefined } }

    if (!canNavigate(to) && to.matched.length)
      return { name: 'not-authorized' }
  })
}
