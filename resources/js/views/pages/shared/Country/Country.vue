<script setup>
import CountryAPI from '@/Api/shared/Country/country'
import AddModal from './AddModal.vue'
import DeleteModal from './DeleteModal.vue'
import EditModal from './EditModal.vue'

const api = new CountryAPI()

const searchQuery = ref('')
const itemsPerPage = ref(10)
const page = ref(1)
const countries = ref([])
const totalCountries = ref(0)
const isLoading = ref(false)
const deleteId = ref(null)

const isAddModalOpen = ref(false)
const isEditModalOpen = ref(false)
const isDeleteModalOpen = ref(false)
const selectedCountry = ref(null)

const snackbar = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('success')

const headers = [
  { title: '#', key: 'id', sortable: false },
  { title: 'Name', key: 'name', sortable: false },
  { title: 'Status', key: 'status', sortable: false },
  { title: 'Actions', key: 'actions', sortable: false },
]

async function fetchCountries() {
  isLoading.value = true
  try {
    const res = await api.getAll({
      name: searchQuery.value,
      per_page: itemsPerPage.value,
      page: page.value,
    })

    const items = res.data?.items ?? []
    countries.value = items.map(c => ({
      ...c,
      status: Boolean(c.status)
    }))
    totalCountries.value = res.data?.pagination?.total ?? 0
  } catch {
    countries.value = []
    totalCountries.value = 0
  } finally {
    isLoading.value = false
  }
}

function openAddModal() {
  isAddModalOpen.value = true
}

function openEditModal(country) {
  selectedCountry.value = country
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
    snackbarMessage.value = 'Country deleted successfully'
    snackbarColor.value = 'success'
    snackbar.value = true
    await fetchCountries()
  } catch (err) {
    snackbarMessage.value = err?.response?._data?.message || err?.message || 'An error occurred'
    snackbarColor.value = 'error'
    snackbar.value = true
  } finally {
    deleteId.value = null
  }
}

async function toggleStatus(country) {
  const newStatus = !country.status
  try {
    await api.update(country.id, { status: newStatus })
    await fetchCountries()
  } catch (err) {
    snackbarMessage.value = err?.response?._data?.message || err?.message || 'An error occurred'
    snackbarColor.value = 'error'
    snackbar.value = true
  }
}

async function handleAddSubmit(data) {
  try {
    await api.create(data)
    snackbarMessage.value = 'Country created successfully'
    snackbarColor.value = 'success'
    snackbar.value = true
    await fetchCountries()
  } catch (err) {
    snackbarMessage.value = err?.response?._data?.message || err?.message || 'An error occurred'
    snackbarColor.value = 'error'
    snackbar.value = true
  }
}

async function handleEditSubmit(data) {
  try {
    await api.update(selectedCountry.value.id, data)
    snackbarMessage.value = 'Country updated successfully'
    snackbarColor.value = 'success'
    snackbar.value = true
    await fetchCountries()
  } catch (err) {
    snackbarMessage.value = err?.response?._data?.message || err?.message || 'An error occurred'
    snackbarColor.value = 'error'
    snackbar.value = true
  } finally {
    selectedCountry.value = null
  }
}

watch([itemsPerPage, page, searchQuery], () => {
  fetchCountries()
})

fetchCountries()
</script>

<template>
  <VRow>
    <VCol cols="12">
      <div class="d-flex flex-wrap align-center">
        <div>
          <h4 class="text-h4">
            {{ $t('Country Management') }}
          </h4>
          <p class="text-body-1 mb-0">
            {{ $t('Manage your countries') }}
          </p>
        </div>
        <VSpacer />
        <div class="d-flex align-center flex-wrap gap-4">
          <AppTextField v-model="searchQuery" :placeholder="$t('Search')" style="inline-size: 15.625rem;" clearable
            clear-icon="tabler-x" />
          <VBtn v-if="$can('store', 'country')" prepend-icon="tabler-plus" @click="openAddModal">
            {{ $t('Add Country') }}
          </VBtn>
        </div>
      </div>
    </VCol>

    <VCol cols="12">
      <VCard>
        <VDataTableServer v-model:items-per-page="itemsPerPage" v-model:page="page" :items="countries"
          :items-length="totalCountries" :headers="headers" :loading="isLoading" class="text-no-wrap">
          <template #item.id="{ item }">
            <span class="text-body-1 text-high-emphasis">{{ item.id }}</span>
          </template>

          <template #item.name="{ item }">
            <span class="text-body-1 text-high-emphasis">{{ item.name }}</span>
          </template>

          <template #item.status="{ item }">
            <VSwitch v-if="$can('update', 'country')" :model-value="item.status"
              @update:model-value="() => toggleStatus(item)" color="success" inset hide-details />
            <VChip v-else :color="item.status ? 'success' : 'error'" size="small">
              {{ item.status ? 'Active' : 'Inactive' }}
            </VChip>
          </template>

          <template #item.actions="{ item }">
            <div class="d-flex gap-1">
              <IconBtn v-if="$can('update', 'country')" @click="openEditModal(item)">
                <VIcon icon="tabler-pencil" />
              </IconBtn>
              <IconBtn v-if="$can('destroy', 'country')" @click="confirmDelete(item.id)">
                <VIcon icon="tabler-trash" />
              </IconBtn>
            </div>
          </template>

          <template #bottom>
            <TablePagination v-model:page="page" :items-per-page="itemsPerPage" :total-items="totalCountries" />
          </template>
        </VDataTableServer>
      </VCard>
    </VCol>
  </VRow>

  <AddModal v-model="isAddModalOpen" @submit="handleAddSubmit" />

  <EditModal v-model="isEditModalOpen" :country="selectedCountry" @submit="handleEditSubmit" />

  <DeleteModal v-model="isDeleteModalOpen" @confirm="handleDelete" />

  <VSnackbar v-model="snackbar" :color="snackbarColor" location="top" timeout="3000">
    {{ snackbarMessage }}
    <template #actions>
      <VBtn color="white" variant="text" @click="snackbar = false">
        Close
      </VBtn>
    </template>
  </VSnackbar>
</template>
