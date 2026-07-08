<script setup>
import UnitAPI from '@/Api/shared/Unit/unit'
import AddModal from './AddModal.vue'
import DeleteModal from './DeleteModal.vue'
import EditModal from './EditModal.vue'

const api = new UnitAPI()

const searchQuery = ref('')
const itemsPerPage = ref(10)
const page = ref(1)
const units = ref([])
const totalUnits = ref(0)
const isLoading = ref(false)
const deleteId = ref(null)

const isAddModalOpen = ref(false)
const isEditModalOpen = ref(false)
const isDeleteModalOpen = ref(false)
const selectedUnit = ref(null)

const snackbar = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('success')

const headers = [
  { title: 'Name', key: 'name' },
  { title: 'Code', key: 'code' },
  { title: 'Short Name', key: 'short_name' },
  { title: 'Status', key: 'status' },
  { title: 'Actions', key: 'actions', sortable: false },
]

async function fetchUnits() {
  isLoading.value = true
  try {
    const res = await api.getAll({
      name: searchQuery.value,
      per_page: itemsPerPage.value,
      page: page.value,
    })

    const items = res.data?.items ?? []
    units.value = items.map(u => ({ ...u, status: Boolean(u.status) }))
    totalUnits.value = res.data?.pagination?.total ?? 0
  } catch {
    units.value = []
    totalUnits.value = 0
  } finally { isLoading.value = false }
}

function openAddModal() { isAddModalOpen.value = true }
function openEditModal(unit) { selectedUnit.value = unit; isEditModalOpen.value = true }
function confirmDelete(id) { deleteId.value = id; isDeleteModalOpen.value = true }

async function handleDelete() {
  if (deleteId.value == null) return
  try {
    await api.delete(deleteId.value)
    snackbarMessage.value = 'Unit deleted successfully'
    snackbarColor.value = 'success'; snackbar.value = true
    await fetchUnits()
  } catch (err) {
    snackbarMessage.value = err?.response?._data?.message || err?.message || 'An error occurred'
    snackbarColor.value = 'error'; snackbar.value = true
  } finally { deleteId.value = null }
}

async function toggleStatus(unit) {
  try {
    await api.update(unit.id, { status: !unit.status })
    await fetchUnits()
  } catch (err) {
    snackbarMessage.value = err?.response?._data?.message || err?.message || 'An error occurred'
    snackbarColor.value = 'error'; snackbar.value = true
  }
}

async function handleAddSubmit(data) {
  try {
    await api.create(data)
    snackbarMessage.value = 'Unit created successfully'
    snackbarColor.value = 'success'; snackbar.value = true
    await fetchUnits()
  } catch (err) {
    snackbarMessage.value = err?.response?._data?.message || err?.message || 'An error occurred'
    snackbarColor.value = 'error'; snackbar.value = true
  }
}

async function handleEditSubmit(data) {
  try {
    await api.update(selectedUnit.value.id, data)
    snackbarMessage.value = 'Unit updated successfully'
    snackbarColor.value = 'success'; snackbar.value = true
    await fetchUnits()
  } catch (err) {
    snackbarMessage.value = err?.response?._data?.message || err?.message || 'An error occurred'
    snackbarColor.value = 'error'; snackbar.value = true
  } finally { selectedUnit.value = null }
}

watch([itemsPerPage, page, searchQuery], () => { fetchUnits() })
fetchUnits()
</script>

<template>
  <VRow>
    <VCol cols="12">
      <div class="d-flex flex-wrap align-center">
        <div>
          <h4 class="text-h4">{{ $t('Unit Management') }}</h4>
          <p class="text-body-1 mb-0">{{ $t('Manage product units') }}</p>
        </div>
        <VSpacer />
        <div class="d-flex align-center flex-wrap gap-4">
          <AppTextField v-model="searchQuery" :placeholder="$t('Search')" style="inline-size: 15.625rem;" clearable clear-icon="tabler-x" />
          <VBtn v-if="$can('store', 'unit')" prepend-icon="tabler-plus" @click="openAddModal">{{ $t('Add Unit') }}</VBtn>
        </div>
      </div>
    </VCol>

    <VCol cols="12">
      <VCard>
        <VDataTableServer v-model:items-per-page="itemsPerPage" v-model:page="page" :items="units"
          :items-length="totalUnits" :headers="headers" :loading="isLoading" class="text-no-wrap">
          <template #item.code="{ item }">
            <VChip size="small" variant="tonal">{{ item.code }}</VChip>
          </template>

          <template #item.short_name="{ item }">
            <span class="text-body-1">{{ item.short_name }}</span>
          </template>

          <template #item.status="{ item }">
            <VSwitch v-if="$can('update', 'unit')" :model-value="item.status"
              @update:model-value="() => toggleStatus(item)" color="success" inset hide-details />
            <VChip v-else :color="item.status ? 'success' : 'error'" size="small">
              {{ item.status ? 'Active' : 'Inactive' }}
            </VChip>
          </template>

          <template #item.actions="{ item }">
            <IconBtn v-if="$can('update', 'unit')" @click="openEditModal(item)"><VIcon icon="tabler-pencil" /></IconBtn>
            <IconBtn v-if="$can('destroy', 'unit')" @click="confirmDelete(item.id)"><VIcon icon="tabler-trash" /></IconBtn>
          </template>

          <template #bottom>
            <TablePagination v-model:page="page" :items-per-page="itemsPerPage" :total-items="totalUnits" />
          </template>
        </VDataTableServer>
      </VCard>
    </VCol>
  </VRow>

  <AddModal v-model="isAddModalOpen" @submit="handleAddSubmit" />
  <EditModal v-model="isEditModalOpen" :unit="selectedUnit" @submit="handleEditSubmit" />
  <DeleteModal v-model="isDeleteModalOpen" @confirm="handleDelete" />

  <VSnackbar v-model="snackbar" :color="snackbarColor" location="top" timeout="3000">
    {{ snackbarMessage }}
    <template #actions><VBtn color="white" variant="text" @click="snackbar = false">Close</VBtn></template>
  </VSnackbar>
</template>
