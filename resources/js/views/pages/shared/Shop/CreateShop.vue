<script setup>
import { useI18n } from 'vue-i18n'
import ShopAPI from '@/Api/shared/Shop/shop'
import UserAPI from '@/Api/shared/User/user'
import BasicInfo from './CreateSteps/BasicInfo.vue'
import Branding from './CreateSteps/Branding.vue'
import Media from './CreateSteps/Media.vue'
import Review from './CreateSteps/Review.vue'

const { t } = useI18n()
const router = useRouter()
const route = useRoute()

const api = new ShopAPI()
const userApi = new UserAPI()

const userData = useCookie('userData')
const isAdmin = computed(() => userData.value?.user_type === 'admin')
const rolePrefix = computed(() => route.name?.toString().split('-')[0] || 'admin')

const currentStep = ref(0)
const isSubmitting = ref(false)
const refForm = ref()
const users = ref([])

const snackbar = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('success')

const logoFile = ref(null)
const coverFile = ref(null)

const formData = reactive({
  user_id: null,
  name: '',
  slug: '',
  description: '',
  phone: '',
  email: '',
  website: '',
  theme: 'default',
  font_family: 'Cairo',
  primary_color: '#000000',
  secondary_color: '#ffffff',
  is_active: true,
  is_featured: false,
})

const steps = computed(() => [
  { title: t('shop.Basic Info'), subtitle: t('shop.Shop Details'), icon: 'tabler-file-description' },
  { title: t('shop.Branding'), subtitle: t('shop.Theme And Colors'), icon: 'tabler-palette' },
  { title: t('shop.Media'), subtitle: t('shop.Logo And Cover'), icon: 'tabler-photo' },
  { title: t('shop.Review'), subtitle: t('shop.Confirm And Submit'), icon: 'tabler-checks' },
])

const ownerName = computed(() => {
  const u = users.value.find(x => x.id === formData.user_id)

  return u?.name ?? ''
})

async function fetchUsers() {
  if (!isAdmin.value) return
  try {
    const res = await userApi.getAll({ per_page: -1 })

    users.value = res.data?.items ?? res.data ?? []
  } catch {
    users.value = []
  }
}

function formatError(err) {
  const data = err?.response?._data
  if (data?.errors)
    return Object.values(data.errors).flat().join(', ')

  return data?.message || err?.message || t('shop.An Error Occurred')
}

function goToList() {
  router.push({ name: `${rolePrefix.value}-shop` })
}

function buildFormData() {
  const fd = new FormData()

  const fields = { ...formData }
  if (!isAdmin.value) {
    delete fields.user_id
    delete fields.is_featured
  }

  Object.entries(fields).forEach(([key, value]) => {
    if (value === null || value === undefined || value === '') return
    if (typeof value === 'boolean')
      fd.append(key, value ? '1' : '0')
    else
      fd.append(key, value)
  })

  const logo = Array.isArray(logoFile.value) ? logoFile.value[0] : logoFile.value
  const cover = Array.isArray(coverFile.value) ? coverFile.value[0] : coverFile.value
  if (logo)
    fd.append('logo', logo)
  if (cover)
    fd.append('cover', cover)

  return fd
}

async function onSubmit() {
  const { valid } = await refForm.value.validate()
  if (!valid) {
    currentStep.value = 0

    return
  }

  isSubmitting.value = true
  try {
    await api.create(buildFormData())
    snackbarMessage.value = t('shop.Shop Created Successfully')
    snackbarColor.value = 'success'
    snackbar.value = true
    setTimeout(goToList, 800)
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'
    snackbar.value = true
  } finally {
    isSubmitting.value = false
  }
}

fetchUsers()
</script>

<template>
  <VRow>
    <VCol cols="12">
      <div class="d-flex flex-wrap align-center mb-4">
        <div>
          <h4 class="text-h4">
            {{ $t('shop.Create Shop') }}
          </h4>
          <p class="text-body-1 mb-0">
            {{ $t('shop.Set Up Your New Shop') }}
          </p>
        </div>
        <VSpacer />
        <VBtn
          variant="tonal"
          color="secondary"
          prepend-icon="tabler-arrow-left"
          class="flip-in-rtl"
          @click="goToList"
        >
          {{ $t('shop.Back') }}
        </VBtn>
      </div>
    </VCol>

    <VCol cols="12">
      <VCard>
        <VCardText>
          <AppStepper
            v-model:current-step="currentStep"
            :items="steps"
            class="stepper-icon-step-bg"
          />
        </VCardText>

        <VDivider />

        <VCardText>
          <VForm
            ref="refForm"
            @submit.prevent="onSubmit"
          >
            <VWindow
              v-model="currentStep"
              class="disable-tab-transition"
            >
              <VWindowItem>
                <BasicInfo
                  :form-data="formData"
                  :is-admin="isAdmin"
                  :users="users"
                />
              </VWindowItem>

              <VWindowItem>
                <Branding :form-data="formData" />
              </VWindowItem>

              <VWindowItem>
                <Media
                  v-model:logo="logoFile"
                  v-model:cover="coverFile"
                />
              </VWindowItem>

              <VWindowItem>
                <Review
                  :form-data="formData"
                  :is-admin="isAdmin"
                  :owner-name="ownerName"
                />
              </VWindowItem>
            </VWindow>

            <div class="d-flex flex-wrap gap-4 justify-sm-space-between justify-center mt-8">
              <VBtn
                color="secondary"
                variant="tonal"
                :disabled="currentStep === 0"
                @click="currentStep--"
              >
                <VIcon
                  icon="tabler-arrow-left"
                  start
                  class="flip-in-rtl"
                />
                {{ $t('shop.Previous') }}
              </VBtn>

              <VBtn
                v-if="steps.length - 1 === currentStep"
                color="success"
                :loading="isSubmitting"
                @click="onSubmit"
              >
                {{ $t('shop.Submit') }}
              </VBtn>

              <VBtn
                v-else
                @click="currentStep++"
              >
                {{ $t('shop.Next') }}
                <VIcon
                  icon="tabler-arrow-right"
                  end
                  class="flip-in-rtl"
                />
              </VBtn>
            </div>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>

  <VSnackbar
    v-model="snackbar"
    :color="snackbarColor"
    location="top"
    timeout="3000"
  >
    {{ snackbarMessage }}
    <template #actions>
      <VBtn
        color="white"
        variant="text"
        @click="snackbar = false"
      >
        {{ $t('shop.Close') }}
      </VBtn>
    </template>
  </VSnackbar>
</template>
