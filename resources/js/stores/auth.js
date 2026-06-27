export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const isLoggedIn = computed(() => !!userCookie.value && !!tokenCookie.value)

  const userCookie = useCookie('userData')
  const tokenCookie = useCookie('accessToken')
  const rulesCookie = useCookie('userAbilityRules')

  function setAuth({ accessToken, userData, userAbilityRules }) {
    tokenCookie.value = accessToken
    userCookie.value = userData
    rulesCookie.value = userAbilityRules
    user.value = userData
  }

  async function login(credentials) {
    const res = await $api('/auth/login', {
      method: 'POST',
      body: credentials,
    })

    setAuth(res)
    return res
  }

  async function logout() {
    try {
      await $api('/auth/logout', { method: 'POST' })
    } catch {
      // Even if the server request fails, clear local state
    }

    tokenCookie.value = null
    userCookie.value = null
    rulesCookie.value = null
    user.value = null
  }

  async function fetchUser() {
    try {
      const res = await $api('/user')
      userCookie.value = res.userData
      rulesCookie.value = res.userAbilityRules
      user.value = res.userData
    } catch {
      await logout()
    }
  }

  return {
    user,
    isLoggedIn,
    login,
    logout,
    fetchUser,
    setAuth,
  }
})
