<script setup>
import { useI18n } from 'vue-i18n'
import ShopAPI from '@/Api/shared/Shop/shop'
import DeleteModal from './DeleteModal.vue'
import EditModal from './EditModal.vue'

const { t } = useI18n()
const router = useRouter()
const route = useRoute()

const api = new ShopAPI()

const userData = useCookie('userData')
const isAdmin = computed(() => userData.value?.user_type === 'admin')
const rolePrefix = computed(() => route.name?.toString().split('-')[0] || 'admin')

const searchQuery = ref('')
const itemsPerPage = ref(10)
const page = ref(1)
const shops = ref([])
const totalShops = ref(0)
const isLoading = ref(false)
const deleteId = ref(null)

const canCreateShop = computed(() => {
  if (isAdmin.value) return true
  return totalShops.value === 0
})

const isEditModalOpen = ref(false)
const isDeleteModalOpen = ref(false)
const selectedShop = ref(null)

const snackbar = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('success')

const headers = computed(() => {
  const base = [
    { title: t('#'), key: 'id', sortable: false },
    { title: t('shop.Logo'), key: 'logo', sortable: false },
    { title: t('shop.Name'), key: 'name', sortable: false },
    { title: t('shop.Owner'), key: 'user', sortable: false },
    { title: t('shop.Status'), key: 'is_active', sortable: false },
  ]

  if (isAdmin.value)
    base.push({ title: t('shop.Featured'), key: 'is_featured', sortable: false })

  base.push({ title: t('shop.Actions'), key: 'actions', sortable: false })

  return base
})

async function fetchShops() {
  isLoading.value = true
  try {
    const res = await api.getAll({
      name: searchQuery.value,
      per_page: itemsPerPage.value,
      page: page.value,
    })

    const items = res.data?.items ?? []

    shops.value = items.map(s => ({
      ...s,
      is_active: Boolean(s.is_active),
      is_featured: Boolean(s.is_featured),
    }))
    totalShops.value = res.data?.pagination?.total ?? 0
  } catch {
    shops.value = []
    totalShops.value = 0
  } finally {
    isLoading.value = false
  }
}

function formatError(err) {
  const data = err?.response?._data
  if (data?.errors)
    return Object.values(data.errors).flat().join(', ')

  return data?.message || err?.message || t('shop.An Error Occurred')
}

function goToCreate() {
  router.push({ name: `${rolePrefix.value}-shop-create` })
}

function visitShop(slug) {
  window.open(`${window.location.origin}/store/${slug}`, '_blank')
}

function goToBuilder(id) {
  router.push({ name: `${rolePrefix.value}-shop-builder`, params: { shopId: id } })
}

function openEditModal(shop) {
  selectedShop.value = shop
  isEditModalOpen.value = true
}

function confirmDelete(id) {
  deleteId.value = id
  isDeleteModalOpen.value = true
}

async function handleDelete() {
  if (deleteId.value == null) return
  try {
    await api.delete(deleteId.value)
    snackbarMessage.value = t('shop.Shop Deleted Successfully')
    snackbarColor.value = 'success'
    snackbar.value = true
    await fetchShops()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'
    snackbar.value = true
  } finally {
    deleteId.value = null
  }
}

async function toggleStatus(shop) {
  try {
    await api.update(shop.id, { name: shop.name, slug: shop.slug, is_active: !shop.is_active })
    await fetchShops()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'
    snackbar.value = true
  }
}

async function handleEditSubmit(data) {
  try {
    await api.update(selectedShop.value.id, data)
    snackbarMessage.value = t('shop.Shop Updated Successfully')
    snackbarColor.value = 'success'
    snackbar.value = true
    await fetchShops()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'
    snackbar.value = true
  } finally {
    selectedShop.value = null
  }
}

watch([itemsPerPage, page, searchQuery], () => {
  fetchShops()
})

fetchShops()
</script>

<template>
  <VRow>
    <VCol cols="12">
      <div class="d-flex flex-wrap align-center">
        <div>
          <h4 class="text-h4">
            {{ $t('shop.Shop Management') }}
          </h4>
          <p class="text-body-1 mb-0">
            {{ $t('shop.Manage Your Shops') }}
          </p>
        </div>
        <VSpacer />
        <div class="d-flex align-center flex-wrap gap-4">
          <AppTextField
            v-model="searchQuery"
            :placeholder="$t('shop.Search')"
            style="inline-size: 15.625rem;"
            clearable
            clear-icon="tabler-x"
          />
          <VBtn
            v-if="$can('store', 'shop') && canCreateShop"
            prepend-icon="tabler-plus"
            @click="goToCreate"
          >
            {{ $t('shop.Add Shop') }}
          </VBtn>
        </div>
      </div>
    </VCol>

    <VCol cols="12">
      <VCard>
        <VDataTableServer
          v-model:items-per-page="itemsPerPage"
          v-model:page="page"
          :items="shops"
          :items-length="totalShops"
          :headers="headers"
          :loading="isLoading"
          class="text-no-wrap"
        >
          <template #item.id="{ item }">
            <span class="text-body-1 text-high-emphasis">{{ item.id }}</span>
          </template>

          <template #item.logo="{ item }">
            <VAvatar
              size="38"
              :image="item.logo || undefined"
              variant="tonal"
              color="primary"
            >
              <VIcon
                v-if="!item.logo"
                icon="tabler-building-store"
              />
            </VAvatar>
          </template>

          <template #item.name="{ item }">
            <span class="text-body-1 text-high-emphasis">{{ item.name }}</span>
          </template>

          <template #item.user="{ item }">
            <span class="text-body-1">{{ item.user?.name || '—' }}</span>
          </template>

          <template #item.is_active="{ item }">
            <VSwitch
              v-if="$can('update', 'shop') && isAdmin"
              :model-value="item.is_active"
              color="success"
              inset
              hide-details
              @update:model-value="() => toggleStatus(item)"
            />
            <VChip
              v-else
              :color="item.is_active ? 'success' : 'error'"
              size="small"
            >
              {{ item.is_active ? $t('shop.Active') : $t('shop.Inactive') }}
            </VChip>
          </template>

          <template #item.is_featured="{ item }">
            <VChip
              :color="item.is_featured ? 'warning' : 'secondary'"
              size="small"
            >
              {{ item.is_featured ? $t('shop.Yes') : $t('shop.No') }}
            </VChip>
          </template>

          <template #item.actions="{ item }">
            <div class="d-flex gap-1">
              <VBtn
                variant="tonal"
                size="small"
                color="primary"
                prepend-icon="tabler-external-link"
                @click="visitShop(item.slug)"
              >
                {{ $t('shop.Visit') }}
              </VBtn>
              <VBtn
                v-if="$can('update', 'shop')"
                variant="tonal"
                size="small"
                color="info"
                prepend-icon="tabler-layout-dashboard"
                @click="goToBuilder(item.id)"
              >
                {{ $t('shop.Customize') }}
              </VBtn>
              <IconBtn
                v-if="$can('update', 'shop')"
                @click="openEditModal(item)"
              >
                <VIcon icon="tabler-pencil" />
              </IconBtn>
              <IconBtn
                v-if="$can('destroy', 'shop')"
                @click="confirmDelete(item.id)"
              >
                <VIcon icon="tabler-trash" />
              </IconBtn>
            </div>
          </template>

          <template #bottom>
            <TablePagination
              v-model:page="page"
              :items-per-page="itemsPerPage"
              :total-items="totalShops"
            />
          </template>
        </VDataTableServer>
      </VCard>
    </VCol>
  </VRow>

  <EditModal
    v-model="isEditModalOpen"
    :shop="selectedShop"
    :is-admin="isAdmin"
    @submit="handleEditSubmit"
  />

  <DeleteModal
    v-model="isDeleteModalOpen"
    @confirm="handleDelete"
  />

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
