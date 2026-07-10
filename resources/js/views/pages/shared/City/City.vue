<script setup>import { useI18n } from 'vue-i18n'
const { t } = useI18n()

import CityAPI from '@/Api/shared/City/city'
import AddModal from './AddModal.vue'
import DeleteModal from './DeleteModal.vue'
import EditModal from './EditModal.vue'

const api = new CityAPI()

const searchQuery = ref('')
const itemsPerPage = ref(10)
const page = ref(1)
const cities = ref([])
const totalCities = ref(0)
const isLoading = ref(false)
const deleteId = ref(null)

const isAddModalOpen = ref(false)
const isEditModalOpen = ref(false)
const isDeleteModalOpen = ref(false)
const selectedCity = ref(null)

const snackbar = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('success')

const headers = [
  { title: t('city.#'), key: 'id', sortable: false },
  { title: t('city.Name'), key: 'name', sortable: false },
  { title: t('city.Country'), key: 'country', sortable: false },
  { title: t('city.Status'), key: 'status', sortable: false },
  { title: t('city.Actions'), key: 'actions', sortable: false },
]

async function fetchCities() {
  isLoading.value = true
  try {
    const res = await api.getAll({
      name: searchQuery.value,
      per_page: itemsPerPage.value,
      page: page.value,
    })

    const items = res.data?.items ?? []

    cities.value = items.map(c => ({
      ...c,
      status: Boolean(c.status),
    }))
    totalCities.value = res.data?.pagination?.total ?? 0
  } catch {
    cities.value = []
    totalCities.value = 0
  } finally {
    isLoading.value = false
  }
}

function formatError(err) {
  const data = err?.response?._data
  if (data?.errors) {
    return Object.values(data.errors).flat().join(', ')
  }

  return data?.message || err?.message || t('city.An Error Occurred')
}

function openAddModal() {
  isAddModalOpen.value = true
}

function openEditModal(city) {
  selectedCity.value = city
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
    snackbarMessage.value = t('city.City Deleted Successfully')
    snackbarColor.value = 'success'
    snackbar.value = true
    await fetchCities()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'
    snackbar.value = true
  } finally {
    deleteId.value = null
  }
}

async function toggleStatus(city) {
  try {
    await api.update(city.id, {
      ...city,
      status: !city.status,
    })
    await fetchCities()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'
    snackbar.value = true
  }
}

async function handleAddSubmit(data) {
  try {
    await api.create(data)
    snackbarMessage.value = t('city.City Created Successfully')
    snackbarColor.value = 'success'
    snackbar.value = true
    await fetchCities()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'
    snackbar.value = true
  }
}

async function handleEditSubmit(data) {
  try {
    await api.update(selectedCity.value.id, data)
    snackbarMessage.value = t('city.City Updated Successfully')
    snackbarColor.value = 'success'
    snackbar.value = true
    await fetchCities()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'
    snackbar.value = true
  } finally {
    selectedCity.value = null
  }
}

watch([itemsPerPage, page, searchQuery], () => {
  fetchCities()
})

fetchCities()
</script>

<template>
  <VRow>
    <VCol cols="12">
      <div class="d-flex flex-wrap align-center">
        <div>
          <h4 class="text-h4">
            {{ $t('city.City Management') }}
          </h4>
          <p class="text-body-1 mb-0">
            {{ $t('city.Manage Your Cities') }}
          </p>
        </div>
        <VSpacer />
        <div class="d-flex align-center flex-wrap gap-4">
          <AppTextField v-model="searchQuery" :placeholder="$t('city.Search')" style="inline-size: 15.625rem;" clearable
            clear-icon="tabler-x" />
          <VBtn v-if="$can('store', 'city')" prepend-icon="tabler-plus" @click="openAddModal">
            {{ $t('city.Add City') }}
          </VBtn>
        </div>
      </div>
    </VCol>

    <VCol cols="12">
      <VCard>
        <VDataTableServer v-model:items-per-page="itemsPerPage" v-model:page="page" :items="cities"
          :items-length="totalCities" :headers="headers" :loading="isLoading" class="text-no-wrap">
          <template #item.id="{ item }">
            <span class="text-body-1 text-high-emphasis">{{ item.id }}</span>
          </template>

          <template #item.name="{ item }">
            <span class="text-body-1 text-high-emphasis">{{ item.name }}</span>
          </template>

          <template #item.country="{ item }">
            <span class="text-body-1">{{ item.country?.name || '—' }}</span>
          </template>

          <template #item.status="{ item }">
            <VSwitch v-if="$can('update', 'city')" :model-value="item.status" color="success" inset hide-details
              @update:model-value="() => toggleStatus(item)" />
            <VChip v-else :color="item.status ? 'success' : 'error'" size="small">
              {{ item.status ? $t('city.Active') : $t('city.Inactive') }}
            </VChip>
          </template>

          <template #item.actions="{ item }">
            <div class="d-flex gap-1">
              <IconBtn v-if="$can('update', 'city')" @click="openEditModal(item)">
                <VIcon icon="tabler-pencil" />
              </IconBtn>
              <IconBtn v-if="$can('destroy', 'city')" @click="confirmDelete(item.id)">
                <VIcon icon="tabler-trash" />
              </IconBtn>
            </div>
          </template>

          <template #bottom>
            <TablePagination v-model:page="page" :items-per-page="itemsPerPage" :total-items="totalCities" />
          </template>
        </VDataTableServer>
      </VCard>
    </VCol>
  </VRow>

  <AddModal v-model="isAddModalOpen" @submit="handleAddSubmit" />

  <EditModal v-model="isEditModalOpen" :city="selectedCity" @submit="handleEditSubmit" />

  <DeleteModal v-model="isDeleteModalOpen" @confirm="handleDelete" />

  <VSnackbar v-model="snackbar" :color="snackbarColor" location="top" timeout="3000">
    {{ snackbarMessage }}
    <template #actions>
      <VBtn color="white" variant="text" @click="snackbar = false">
        {{ $t('city.Close') }}
      </VBtn>
    </template>
  </VSnackbar>
</template>
