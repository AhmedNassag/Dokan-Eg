<script setup>import { useI18n } from 'vue-i18n'
const { t } = useI18n()

import ShippingCompanyAPI from '@/Api/shared/ShippingCompany/shippingCompany'
import AddModal from './AddModal.vue'
import DeleteModal from './DeleteModal.vue'
import EditModal from './EditModal.vue'

const api = new ShippingCompanyAPI()

const searchQuery = ref('')
const items = ref([])
const isLoading = ref(false)
const deleteId = ref(null)
const isAddModalOpen = ref(false)
const isEditModalOpen = ref(false)
const isDeleteModalOpen = ref(false)
const selectedCompany = ref(null)
const snackbar = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('success')

async function fetchCompanies() {
  isLoading.value = true
  try {
    const res = await api.getAll({ name: searchQuery.value, per_page: -1 })
    const data = res.data?.items ?? res.data ?? []

    items.value = data.map(c => ({ ...c, status: Boolean(c.status) }))
  } catch {
    items.value = []
  } finally { isLoading.value = false }
}

function formatError(err) {
  const data = err?.response?._data
  if (data?.errors) {
    return Object.values(data.errors).flat().join(', ')
  }

  return data?.message || err?.message || t('shippingCompany.An Error Occurred')
}

function openAddModal() { isAddModalOpen.value = true }
function openEditModal(company) { selectedCompany.value = company; isEditModalOpen.value = true }
function confirmDelete(id) { deleteId.value = id; isDeleteModalOpen.value = true }

async function handleDelete() {
  if (deleteId.value == null) return
  try {
    await api.delete(deleteId.value)
    snackbarMessage.value = t('shippingCompany.Shipping Company Deleted Successfully')
    snackbarColor.value = 'success'; snackbar.value = true
    await fetchCompanies()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'; snackbar.value = true
  } finally { deleteId.value = null }
}

async function toggleStatus(company) {
  try {
    await api.update(company.id, {
      ...company,
      status: !company.status,
    })
    await fetchCompanies()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'; snackbar.value = true
  }
}

async function handleAddSubmit(data) {
  try {
    await api.create(data)
    snackbarMessage.value = t('shippingCompany.Shipping Company Created Successfully')
    snackbarColor.value = 'success'; snackbar.value = true
    await fetchCompanies()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'; snackbar.value = true
  }
}

async function handleEditSubmit(data) {
  try {
    await api.update(selectedCompany.value.id, data)
    snackbarMessage.value = t('shippingCompany.Shipping Company Updated Successfully')
    snackbarColor.value = 'success'; snackbar.value = true
    await fetchCompanies()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'; snackbar.value = true
  } finally { selectedCompany.value = null }
}

watch(searchQuery, () => { fetchCompanies() })
fetchCompanies()
</script>

<template>
  <VRow>
    <VCol cols="12">
      <div class="d-flex flex-wrap align-center">
        <div>
          <h4 class="text-h4">
            {{ $t('shippingCompany.Shipping Companies') }}
          </h4>
          <p class="text-body-1 mb-0">
            {{ $t('shippingCompany.Manage Your Shipping Companies And Prices') }}
          </p>
        </div>
        <VSpacer />
        <div class="d-flex align-center flex-wrap gap-4">
          <AppTextField v-model="searchQuery" :placeholder="$t('shippingCompany.Search')"
            style="inline-size: 15.625rem;" clearable clear-icon="tabler-x" />
          <VBtn v-if="$can('store', 'shippingCompany')" prepend-icon="tabler-plus" @click="openAddModal">
            {{ $t('shippingCompany.Add Shipping Company') }}
          </VBtn>
        </div>
      </div>
    </VCol>

    <VCol cols="12">
      <VProgressLinear v-if="isLoading" indeterminate color="primary" />

      <VExpansionPanels v-else variant="accordion" class="mt-2">
        <VExpansionPanel v-for="company in items" :key="company.id">
          <VExpansionPanelTitle class="font-weight-medium">
            <div class="d-flex align-center w-100" style="justify-content: space-between;">
              <span>
                <VIcon icon="tabler-building" class="me-2" />{{ company.name }}
              </span>
              <VChip :color="company.status ? 'success' : 'error'" size="small" class="me-4">
                {{ company.status ? $t('shippingCompany.Active') : $t('shippingCompany.Inactive') }}
              </VChip>
            </div>
          </VExpansionPanelTitle>

          <VExpansionPanelText>
            <VRow class="mb-4">
              <VCol cols="4">
                <strong>{{ $t('shippingCompany.Code') }}:</strong> {{ company.code }}
              </VCol>
              <VCol cols="4">
                <strong>{{ $t('shippingCompany.Phone') }}:</strong> {{ company.phone }}
              </VCol>
              <VCol cols="4" class="text-end">
                <IconBtn v-if="$can('update', 'shippingCompany')" @click="openEditModal(company)">
                  <VIcon icon="tabler-pencil" />
                </IconBtn>
                <IconBtn v-if="$can('destroy', 'shippingCompany')" @click="confirmDelete(company.id)">
                  <VIcon icon="tabler-trash" />
                </IconBtn>
                <VSwitch v-if="$can('update', 'shippingCompany')" :model-value="company.status" color="success" inset
                  hide-details class="d-inline-block ms-2" @update:model-value="() => toggleStatus(company)" />
              </VCol>
            </VRow>

            <VDivider class="mb-3" />
            <h6 class="text-h6 mb-3">
              {{ $t('shippingCompany.Shipping Prices') }}
            </h6>

            <VTable v-if="company.prices?.length" class="text-no-wrap">
              <thead>
                <tr>
                  <th>{{ $t('shippingCompany.City') }}</th>
                  <th>{{ $t('shippingCompany.Price') }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="price in company.prices" :key="price.id">
                  <td>{{ price.city?.name || '—' }}</td>
                  <td class="font-weight-medium">
                    {{ Number(price.price).toFixed(2) }} {{ $t('shippingCompany.EGP') }}
                  </td>
                </tr>
              </tbody>
            </VTable>
            <p v-else class="text-body-2 text-medium-emphasis">
              {{ $t('shippingCompany.No prices set') }}
            </p>
          </VExpansionPanelText>
        </VExpansionPanel>

        <VExpansionPanel v-if="!items.length && !isLoading" disabled>
          <VExpansionPanelTitle>{{ $t('shippingCompany.No shipping companies found') }}</VExpansionPanelTitle>
        </VExpansionPanel>
      </VExpansionPanels>
    </VCol>
  </VRow>

  <AddModal v-model="isAddModalOpen" @submit="handleAddSubmit" />
  <EditModal v-model="isEditModalOpen" :company="selectedCompany" @submit="handleEditSubmit" />
  <DeleteModal v-model="isDeleteModalOpen" @confirm="handleDelete" />

  <VSnackbar v-model="snackbar" :color="snackbarColor" location="top" timeout="3000">
    {{ snackbarMessage }}
    <template #actions>
      <VBtn color="white" variant="text" @click="snackbar = false">
        {{ $t('shippingCompany.Close') }}
      </VBtn>
    </template>
  </VSnackbar>
</template>
