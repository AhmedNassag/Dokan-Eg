<script setup>import { useI18n } from 'vue-i18n'
const { t } = useI18n()

import BranchAPI from '@/Api/shared/Branch/branch'
import AddModal from './AddModal.vue'
import DeleteModal from './DeleteModal.vue'
import EditModal from './EditModal.vue'

const api = new BranchAPI()

const searchQuery = ref('')
const itemsPerPage = ref(10)
const page = ref(1)
const branches = ref([])
const totalBranches = ref(0)
const isLoading = ref(false)
const deleteId = ref(null)

const isAddModalOpen = ref(false)
const isEditModalOpen = ref(false)
const isDeleteModalOpen = ref(false)
const selectedBranch = ref(null)

const snackbar = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('success')

const headers = [
  { title: t('#'), key: 'id', sortable: false },
  { title: t('branch.Name'), key: 'name', sortable: false },
  { title: t('branch.Code'), key: 'code', sortable: false },
  { title: t('branch.Mobile'), key: 'mobile', sortable: false },
  { title: t('branch.Area'), key: 'area', sortable: false },
  { title: t('branch.Status'), key: 'status', sortable: false },
  { title: t('branch.Actions'), key: 'actions', sortable: false },
]

async function fetchBranches() {
  isLoading.value = true
  try {
    const res = await api.getAll({
      name: searchQuery.value,
      per_page: itemsPerPage.value,
      page: page.value,
    })

    const items = res.data?.items ?? []

    branches.value = items.map(b => ({
      ...b,
      status: Boolean(b.status),
    }))
    totalBranches.value = res.data?.pagination?.total ?? 0
  } catch {
    branches.value = []
    totalBranches.value = 0
  } finally {
    isLoading.value = false
  }
}

function formatError(err) {
  const data = err?.response?._data
  if (data?.errors) {
    return Object.values(data.errors).flat().join(', ')
  }

  return data?.message || err?.message || t('branch.An Error Occurred')
}

function openAddModal() {
  isAddModalOpen.value = true
}

function openEditModal(branch) {
  selectedBranch.value = branch
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
    snackbarMessage.value = t('branch.Branch Deleted Successfully')
    snackbarColor.value = 'success'
    snackbar.value = true
    await fetchBranches()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'
    snackbar.value = true
  } finally {
    deleteId.value = null
  }
}

async function toggleStatus(branch) {
  try {
    await api.update(branch.id, {
      ...branch,
      status: !branch.status,
    })
    await fetchBranches()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'
    snackbar.value = true
  }
}

async function handleAddSubmit(data) {
  try {
    await api.create(data)
    snackbarMessage.value = t('branch.Branch Created Successfully')
    snackbarColor.value = 'success'
    snackbar.value = true
    await fetchBranches()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'
    snackbar.value = true
  }
}

async function handleEditSubmit(data) {
  try {
    await api.update(selectedBranch.value.id, data)
    snackbarMessage.value = t('branch.Branch Updated Successfully')
    snackbarColor.value = 'success'
    snackbar.value = true
    await fetchBranches()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'
    snackbar.value = true
  } finally {
    selectedBranch.value = null
  }
}

watch([itemsPerPage, page, searchQuery], () => {
  fetchBranches()
})

fetchBranches()
</script>

<template>
  <VRow>
    <VCol cols="12">
      <div class="d-flex flex-wrap align-center">
        <div>
          <h4 class="text-h4">
            {{ $t('branch.Branch Management') }}
          </h4>
          <p class="text-body-1 mb-0">
            {{ $t('branch.Manage Your Branches') }}
          </p>
        </div>
        <VSpacer />
        <div class="d-flex align-center flex-wrap gap-4">
          <AppTextField v-model="searchQuery" :placeholder="$t('branch.Search')" style="inline-size: 15.625rem;"
            clearable clear-icon="tabler-x" />
          <VBtn v-if="$can('store', 'branch')" prepend-icon="tabler-plus" @click="openAddModal">
            {{ $t('branch.Add Branch') }}
          </VBtn>
        </div>
      </div>
    </VCol>

    <VCol cols="12">
      <VCard>
        <VDataTableServer v-model:items-per-page="itemsPerPage" v-model:page="page" :items="branches"
          :items-length="totalBranches" :headers="headers" :loading="isLoading" class="text-no-wrap">
          <template #item.id="{ item }">
            <span class="text-body-1 text-high-emphasis">{{ item.id }}</span>
          </template>

          <template #item.name="{ item }">
            <span class="text-body-1 text-high-emphasis">{{ item.name }}</span>
          </template>

          <template #item.code="{ item }">
            <span class="text-body-1">{{ item.code }}</span>
          </template>

          <template #item.mobile="{ item }">
            <span class="text-body-1">{{ item.mobile }}</span>
          </template>

          <template #item.area="{ item }">
            <span class="text-body-1">{{ item.area?.name || '—' }}</span>
          </template>

          <template #item.status="{ item }">
            <VSwitch v-if="$can('update', 'branch')" :model-value="item.status" color="success" inset hide-details
              @update:model-value="() => toggleStatus(item)" />
            <VChip v-else :color="item.status ? 'success' : 'error'" size="small">
              {{ item.status ? $t('branch.Active') : $t('branch.Inactive') }}
            </VChip>
          </template>

          <template #item.actions="{ item }">
            <div class="d-flex gap-1">
              <IconBtn v-if="$can('update', 'branch')" @click="openEditModal(item)">
                <VIcon icon="tabler-pencil" />
              </IconBtn>
              <IconBtn v-if="$can('destroy', 'branch')" @click="confirmDelete(item.id)">
                <VIcon icon="tabler-trash" />
              </IconBtn>
            </div>
          </template>

          <template #bottom>
            <TablePagination v-model:page="page" :items-per-page="itemsPerPage" :total-items="totalBranches" />
          </template>
        </VDataTableServer>
      </VCard>
    </VCol>
  </VRow>

  <AddModal v-model="isAddModalOpen" @submit="handleAddSubmit" />

  <EditModal v-model="isEditModalOpen" :branch="selectedBranch" @submit="handleEditSubmit" />

  <DeleteModal v-model="isDeleteModalOpen" @confirm="handleDelete" />

  <VSnackbar v-model="snackbar" :color="snackbarColor" location="top" timeout="3000">
    {{ snackbarMessage }}
    <template #actions>
      <VBtn color="white" variant="text" @click="snackbar = false">
        {{ $t('branch.Close') }}
      </VBtn>
    </template>
  </VSnackbar>
</template>
