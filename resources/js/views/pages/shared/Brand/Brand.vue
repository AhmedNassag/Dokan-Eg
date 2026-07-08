<script setup>
import BrandAPI from '@/Api/shared/Brand/brand'
import AddModal from './AddModal.vue'
import DeleteModal from './DeleteModal.vue'
import EditModal from './EditModal.vue'

const api = new BrandAPI()

const searchQuery = ref('')
const itemsPerPage = ref(10)
const page = ref(1)
const brands = ref([])
const totalBrands = ref(0)
const isLoading = ref(false)
const deleteId = ref(null)

const isAddModalOpen = ref(false)
const isEditModalOpen = ref(false)
const isDeleteModalOpen = ref(false)
const selectedBrand = ref(null)

const snackbar = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('success')

const headers = [
  { title: 'Name', key: 'name' },
  { title: 'Code', key: 'code' },
  { title: 'Status', key: 'status' },
  { title: 'Actions', key: 'actions', sortable: false },
]

async function fetchBrands() {
  isLoading.value = true
  try {
    const res = await api.getAll({
      name: searchQuery.value,
      per_page: itemsPerPage.value,
      page: page.value,
    })

    const items = res.data?.items ?? []
    brands.value = items.map(b => ({ ...b, status: Boolean(b.status) }))
    totalBrands.value = res.data?.pagination?.total ?? 0
  } catch {
    brands.value = []
    totalBrands.value = 0
  } finally { isLoading.value = false }
}

function openAddModal() { isAddModalOpen.value = true }
function openEditModal(brand) { selectedBrand.value = brand; isEditModalOpen.value = true }
function confirmDelete(id) { deleteId.value = id; isDeleteModalOpen.value = true }

async function handleDelete() {
  if (deleteId.value == null) return
  try {
    await api.delete(deleteId.value)
    snackbarMessage.value = 'Brand deleted successfully'
    snackbarColor.value = 'success'; snackbar.value = true
    await fetchBrands()
  } catch (err) {
    snackbarMessage.value = err?.response?._data?.message || err?.message || 'An error occurred'
    snackbarColor.value = 'error'; snackbar.value = true
  } finally { deleteId.value = null }
}

async function toggleStatus(brand) {
  try {
    await api.update(brand.id, { status: !brand.status })
    await fetchBrands()
  } catch (err) {
    snackbarMessage.value = err?.response?._data?.message || err?.message || 'An error occurred'
    snackbarColor.value = 'error'; snackbar.value = true
  }
}

async function handleAddSubmit(data) {
  try {
    await api.create(data)
    snackbarMessage.value = 'Brand created successfully'
    snackbarColor.value = 'success'; snackbar.value = true
    await fetchBrands()
  } catch (err) {
    snackbarMessage.value = err?.response?._data?.message || err?.message || 'An error occurred'
    snackbarColor.value = 'error'; snackbar.value = true
  }
}

async function handleEditSubmit(data) {
  try {
    await api.update(selectedBrand.value.id, data)
    snackbarMessage.value = 'Brand updated successfully'
    snackbarColor.value = 'success'; snackbar.value = true
    await fetchBrands()
  } catch (err) {
    snackbarMessage.value = err?.response?._data?.message || err?.message || 'An error occurred'
    snackbarColor.value = 'error'; snackbar.value = true
  } finally { selectedBrand.value = null }
}

watch([itemsPerPage, page, searchQuery], () => { fetchBrands() })
fetchBrands()
</script>

<template>
  <VRow>
    <VCol cols="12">
      <div class="d-flex flex-wrap align-center">
        <div>
          <h4 class="text-h4">{{ $t('Brand Management') }}</h4>
          <p class="text-body-1 mb-0">{{ $t('Manage product brands') }}</p>
        </div>
        <VSpacer />
        <div class="d-flex align-center flex-wrap gap-4">
          <AppTextField v-model="searchQuery" :placeholder="$t('Search')" style="inline-size: 15.625rem;" clearable clear-icon="tabler-x" />
          <VBtn v-if="$can('store', 'brand')" prepend-icon="tabler-plus" @click="openAddModal">{{ $t('Add Brand') }}</VBtn>
        </div>
      </div>
    </VCol>

    <VCol cols="12">
      <VCard>
        <VDataTableServer v-model:items-per-page="itemsPerPage" v-model:page="page" :items="brands"
          :items-length="totalBrands" :headers="headers" :loading="isLoading" class="text-no-wrap">
          <template #item.code="{ item }">
            <VChip size="small" variant="tonal">{{ item.code }}</VChip>
          </template>

          <template #item.status="{ item }">
            <VSwitch v-if="$can('update', 'brand')" :model-value="item.status"
              @update:model-value="() => toggleStatus(item)" color="success" inset hide-details />
            <VChip v-else :color="item.status ? 'success' : 'error'" size="small">
              {{ item.status ? 'Active' : 'Inactive' }}
            </VChip>
          </template>

          <template #item.actions="{ item }">
            <IconBtn v-if="$can('update', 'brand')" @click="openEditModal(item)"><VIcon icon="tabler-pencil" /></IconBtn>
            <IconBtn v-if="$can('destroy', 'brand')" @click="confirmDelete(item.id)"><VIcon icon="tabler-trash" /></IconBtn>
          </template>

          <template #bottom>
            <TablePagination v-model:page="page" :items-per-page="itemsPerPage" :total-items="totalBrands" />
          </template>
        </VDataTableServer>
      </VCard>
    </VCol>
  </VRow>

  <AddModal v-model="isAddModalOpen" @submit="handleAddSubmit" />
  <EditModal v-model="isEditModalOpen" :brand="selectedBrand" @submit="handleEditSubmit" />
  <DeleteModal v-model="isDeleteModalOpen" @confirm="handleDelete" />

  <VSnackbar v-model="snackbar" :color="snackbarColor" location="top" timeout="3000">
    {{ snackbarMessage }}
    <template #actions><VBtn color="white" variant="text" @click="snackbar = false">Close</VBtn></template>
  </VSnackbar>
</template>
