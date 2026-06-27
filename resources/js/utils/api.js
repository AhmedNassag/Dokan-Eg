import { ofetch } from 'ofetch'

export const $api = ofetch.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || '/api',
  async onRequest({ options }) {
    const accessToken = useCookie('accessToken').value
    if (accessToken)
      options.headers.append('Authorization', `Bearer ${accessToken}`)
  },
  async onResponseError({ response }) {
    if (response.status === 401) {
      const tokenCookie = useCookie('accessToken')
      const userCookie = useCookie('userData')
      const rulesCookie = useCookie('userAbilityRules')
      tokenCookie.value = null
      userCookie.value = null
      rulesCookie.value = null
      await navigateTo('/login')
    }
  },
})
