<script setup>import { useI18n } from 'vue-i18n'
const { t } = useI18n()

import CountryAPI from '@/API/shared/Country/country'
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
  { title: t('country.#'), key: 'id', sortable: false },
  { title: t('country.Name'), key: 'name', sortable: false },
  { title: t('country.Status'), key: 'status', sortable: false },
  { title: t('country.Actions'), key: 'actions', sortable: false },
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
      status: Boolean(c.status),
    }))
    totalCountries.value = res.data?.pagination?.total ?? 0
  } catch {
    countries.value = []
    totalCountries.value = 0
  } finally {
    isLoading.value = false
  }
}

function formatError(err) {
  const data = err?.response?._data
  if (data?.errors) {
    return Object.values(data.errors).flat().join(', ')
  }
  
  return data?.message || err?.message || t('country.An Error Occurred')
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
    snackbarMessage.value = t('country.Country Deleted Successfully')
    snackbarColor.value = 'success'
    snackbar.value = true
    await fetchCountries()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'
    snackbar.value = true
  } finally {
    deleteId.value = null
  }
}

async function toggleStatus(country) {
  try {
    await api.update(country.id, {
      ...country,
      status: !country.status,
    })
    await fetchCountries()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'
    snackbar.value = true
  }
}

async function handleAddSubmit(data) {
  try {
    await api.create(data)
    snackbarMessage.value = t('country.Country Created Successfully')
    snackbarColor.value = 'success'
    snackbar.value = true
    await fetchCountries()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'
    snackbar.value = true
  }
}

async function handleEditSubmit(data) {
  try {
    await api.update(selectedCountry.value.id, data)
    snackbarMessage.value = t('country.Country Updated Successfully')
    snackbarColor.value = 'success'
    snackbar.value = true
    await fetchCountries()
  } catch (err) {
    snackbarMessage.value = formatError(err)
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
            {{ $t('country.Country Management') }}
          </h4>
          <p class="text-body-1 mb-0">
            {{ $t('country.Manage Your Countries') }}
          </p>
        </div>
        <VSpacer />
        <div class="d-flex align-center flex-wrap gap-4">
          <AppTextField
            v-model="searchQuery"
            :placeholder="$t('country.Search')"
            style="inline-size: 15.625rem;"
            clearable
            clear-icon="tabler-x"
          />
          <VBtn
            v-if="$can('store', 'country')"
            prepend-icon="tabler-plus"
            @click="openAddModal"
          >
            {{ $t('country.Add Country') }}
          </VBtn>
        </div>
      </div>
    </VCol>

    <VCol cols="12">
      <VCard>
        <VDataTableServer
          v-model:items-per-page="itemsPerPage"
          v-model:page="page"
          :items="countries"
          :items-length="totalCountries"
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

          <template #item.status="{ item }">
            <VSwitch
              v-if="$can('update', 'country')"
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
              {{ item.status ? $t('country.Active') : $t('country.Inactive') }}
            </VChip>
          </template>

          <template #item.actions="{ item }">
            <div class="d-flex gap-1">
              <IconBtn
                v-if="$can('update', 'country')"
                @click="openEditModal(item)"
              >
                <VIcon icon="tabler-pencil" />
              </IconBtn>
              <IconBtn
                v-if="$can('destroy', 'country')"
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
              :total-items="totalCountries"
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
    :country="selectedCountry"
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
        {{ $t('country.Close') }}
      </VBtn>
    </template>
  </VSnackbar>
</template>
