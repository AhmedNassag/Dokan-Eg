<script setup>import { useI18n } from 'vue-i18n'
const { t } = useI18n()

import AreaAPI from '@/API/shared/Area/area'
import AddModal from './AddModal.vue'
import DeleteModal from './DeleteModal.vue'
import EditModal from './EditModal.vue'

const api = new AreaAPI()

const searchQuery = ref('')
const itemsPerPage = ref(10)
const page = ref(1)
const areas = ref([])
const totalAreas = ref(0)
const isLoading = ref(false)
const deleteId = ref(null)

const isAddModalOpen = ref(false)
const isEditModalOpen = ref(false)
const isDeleteModalOpen = ref(false)
const selectedArea = ref(null)

const snackbar = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('success')

const headers = [
  { title: t('#'), key: 'id', sortable: false },
  { title: t('area.Name'), key: 'name', sortable: false },
  { title: t('area.City'), key: 'city', sortable: false },
  { title: t('area.Status'), key: 'status', sortable: false },
  { title: t('area.Actions'), key: 'actions', sortable: false },
]

async function fetchAreas() {
  isLoading.value = true
  try {
    const res = await api.getAll({
      name: searchQuery.value,
      per_page: itemsPerPage.value,
      page: page.value,
    })

    const items = res.data?.items ?? []

    areas.value = items.map(a => ({
      ...a,
      status: Boolean(a.status),
    }))
    totalAreas.value = res.data?.pagination?.total ?? 0
  } catch {
    areas.value = []
    totalAreas.value = 0
  } finally {
    isLoading.value = false
  }
}

function formatError(err) {
  const data = err?.response?._data
  if (data?.errors) {
    return Object.values(data.errors).flat().join(', ')
  }
  
  return data?.message || err?.message || t('area.An Error Occurred')
}

function openAddModal() {
  isAddModalOpen.value = true
}

function openEditModal(area) {
  selectedArea.value = area
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
    snackbarMessage.value = t('area.Area Deleted Successfully')
    snackbarColor.value = 'success'
    snackbar.value = true
    await fetchAreas()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'
    snackbar.value = true
  } finally {
    deleteId.value = null
  }
}

async function toggleStatus(area) {
  try {
    await api.update(area.id, {
      ...area,
      status: !area.status,
    })
    await fetchAreas()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'
    snackbar.value = true
  }
}

async function handleAddSubmit(data) {
  try {
    await api.create(data)
    snackbarMessage.value = t('area.Area Created Successfully')
    snackbarColor.value = 'success'
    snackbar.value = true
    await fetchAreas()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'
    snackbar.value = true
  }
}

async function handleEditSubmit(data) {
  try {
    await api.update(selectedArea.value.id, data)
    snackbarMessage.value = t('area.Area Updated Successfully')
    snackbarColor.value = 'success'
    snackbar.value = true
    await fetchAreas()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'
    snackbar.value = true
  } finally {
    selectedArea.value = null
  }
}

watch([itemsPerPage, page, searchQuery], () => {
  fetchAreas()
})

fetchAreas()
</script>

<template>
  <VRow>
    <VCol cols="12">
      <div class="d-flex flex-wrap align-center">
        <div>
          <h4 class="text-h4">
            {{ $t('area.Area Management') }}
          </h4>
          <p class="text-body-1 mb-0">
            {{ $t('area.Manage Your Areas') }}
          </p>
        </div>
        <VSpacer />
        <div class="d-flex align-center flex-wrap gap-4">
          <AppTextField
            v-model="searchQuery"
            :placeholder="$t('area.Search')"
            style="inline-size: 15.625rem;"
            clearable
            clear-icon="tabler-x"
          />
          <VBtn
            v-if="$can('store', 'area')"
            prepend-icon="tabler-plus"
            @click="openAddModal"
          >
            {{ $t('area.Add Area') }}
          </VBtn>
        </div>
      </div>
    </VCol>

    <VCol cols="12">
      <VCard>
        <VDataTableServer
          v-model:items-per-page="itemsPerPage"
          v-model:page="page"
          :items="areas"
          :items-length="totalAreas"
          :headers="headers"
          :loading="isLoading"
          class="text-no-wrap"
        >
          <template #item.id="{ item }">
            <span class="text-body-1 text-high-emphasis">{{ item.id }}</span>
          </template>

          <template #item.name="{ item }">
            <span class="text-body-1 text-high-emphasis">{{ item.name }}</span>
          </template>

          <template #item.city="{ item }">
            <span class="text-body-1">{{ item.city?.name || '—' }}</span>
          </template>

          <template #item.status="{ item }">
            <VSwitch
              v-if="$can('update', 'area')"
              :model-value="item.status"
              color="success"
              inset
              hide-details
              @update:model-value="() => toggleStatus(item)"
            />
            <VChip
              v-else
              :color="item.status ? 'success' : 'error'"
              size="small"
            >
              {{ item.status ? $t('area.Active') : $t('area.Inactive') }}
            </VChip>
          </template>

          <template #item.actions="{ item }">
            <div class="d-flex gap-1">
              <IconBtn
                v-if="$can('update', 'area')"
                @click="openEditModal(item)"
              >
                <VIcon icon="tabler-pencil" />
              </IconBtn>
              <IconBtn
                v-if="$can('destroy', 'area')"
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
              :total-items="totalAreas"
            />
          </template>
        </VDataTableServer>
      </VCard>
    </VCol>
  </VRow>

  <AddModal
    v-model="isAddModalOpen"
    @submit="handleAddSubmit"
  />

  <EditModal
    v-model="isEditModalOpen"
    :area="selectedArea"
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
        {{ $t('area.Close') }}
      </VBtn>
    </template>
  </VSnackbar>
</template>
