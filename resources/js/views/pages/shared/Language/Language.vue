<script setup>import { useI18n } from 'vue-i18n'
const { t } = useI18n()

import LanguageAPI from '@/Api/shared/Language/language'
import AddModal from './AddModal.vue'
import DeleteModal from './DeleteModal.vue'
import EditModal from './EditModal.vue'

const api = new LanguageAPI()

const searchQuery = ref('')
const items = ref([])
const isLoading = ref(false)
const deleteId = ref(null)
const isAddModalOpen = ref(false)
const isEditModalOpen = ref(false)
const isDeleteModalOpen = ref(false)
const selectedItem = ref(null)
const snackbar = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('success')
const page = ref(1)
const itemsPerPage = ref(10)
const totalLanguages = ref(0)

const headers = [
  { title: t('language.Name'), key: 'name' },
  { title: t('language.Code'), key: 'code' },
  { title: t('language.Direction'), key: 'direction' },
  { title: t('language.Default'), key: 'is_default' },
  { title: t('language.Status'), key: 'status' },
  { title: t('language.Actions'), key: 'actions', sortable: false },
]

async function fetchItems() {
  isLoading.value = true
  try {
    const res = await api.getAll({
      name: searchQuery.value,
      per_page: itemsPerPage.value,
      page: page.value,
    })
    const data = res.data?.items ?? []
    items.value = data.map(c => ({ ...c, status: Boolean(c.status), is_default: Boolean(c.is_default) }))
    totalLanguages.value = res.data?.pagination?.total ?? 0
  } catch {
    items.value = []
    totalLanguages.value = 0
  } finally { isLoading.value = false }
}

function formatError(err) {
  const data = err?.response?._data
  if (data?.errors) {
    return Object.values(data.errors).flat().join(', ')
  }
  return data?.message || err?.message || t('language.An Error Occurred')
}

function openAddModal() { isAddModalOpen.value = true }
function openEditModal(item) { selectedItem.value = item; isEditModalOpen.value = true }
function confirmDelete(id) { deleteId.value = id; isDeleteModalOpen.value = true }

async function handleDelete() {
  if (deleteId.value == null) return
  try {
    await api.delete(deleteId.value)
    snackbarMessage.value = t('language.Language Deleted Successfully')
    snackbarColor.value = 'success'; snackbar.value = true
    await fetchItems()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'; snackbar.value = true
  } finally { deleteId.value = null }
}

async function toggleStatus(item) {
  try {
    await api.update(item.id, {
      name: item.name,
      code: item.code,
      direction: item.direction,
      status: !item.status,
    })
    await fetchItems()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'; snackbar.value = true
  }
}

async function toggleDefault(item) {
  if (item.is_default) return
  try {
    await api.setDefault(item.id)
    await fetchItems()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'; snackbar.value = true
  }
}

async function handleAddSubmit(data) {
  try {
    await api.create(data)
    snackbarMessage.value = t('language.Language Created Successfully')
    snackbarColor.value = 'success'; snackbar.value = true
    await fetchItems()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'; snackbar.value = true
  }
}

async function handleEditSubmit(data) {
  try {
    await api.update(selectedItem.value.id, data)
    snackbarMessage.value = t('language.Language Updated Successfully')
    snackbarColor.value = 'success'; snackbar.value = true
    await fetchItems()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'; snackbar.value = true
  } finally { selectedItem.value = null }
}

watch([itemsPerPage, page, searchQuery], () => { fetchItems() })
fetchItems()
</script>

<template>
  <VRow>
    <VCol cols="12">
      <div class="d-flex flex-wrap align-center">
        <div>
          <h4 class="text-h4">{{ $t('language.Languages') }}</h4>
          <p class="text-body-1 mb-0">{{ $t('language.Manage System Languages') }}</p>
        </div>
        <VSpacer />
        <div class="d-flex align-center flex-wrap gap-4">
          <AppTextField v-model="searchQuery" :placeholder="$t('language.Search')" style="inline-size: 15.625rem;" clearable clear-icon="tabler-x" />
          <VBtn v-if="$can('store', 'language')" prepend-icon="tabler-plus" @click="openAddModal">{{ $t('language.Add Language') }}</VBtn>
        </div>
      </div>
    </VCol>

    <VCol cols="12">
      <VDataTableServer
        v-model:items-per-page="itemsPerPage"
        v-model:page="page"
        :items="items"
        :items-length="totalLanguages"
        :headers="headers"
        :loading="isLoading"
        class="text-no-wrap"
      >
        <template #item.code="{ item }">
          <VChip size="small" variant="tonal">{{ item.code }}</VChip>
        </template>

        <template #item.direction="{ item }">
          {{ item.direction }}
        </template>

        <template #item.is_default="{ item }">
          <VSwitch v-if="$can('update', 'language')" :model-value="item.is_default" @update:model-value="() => toggleDefault(item)" color="primary" inset hide-details />
          <VChip v-else :color="item.is_default ? 'primary' : 'default'" size="small">{{ item.is_default ? $t('language.Yes') : $t('language.No') }}</VChip>
        </template>

        <template #item.status="{ item }">
          <VSwitch v-if="$can('update', 'language')" :model-value="item.status" @update:model-value="() => toggleStatus(item)" color="success" inset hide-details />
          <VChip v-else :color="item.status ? 'success' : 'error'" size="small">{{ item.status ? $t('language.Active') : $t('language.Inactive') }}</VChip>
        </template>

        <template #item.actions="{ item }">
          <IconBtn v-if="$can('update', 'language')" @click="openEditModal(item)"><VIcon icon="tabler-pencil" /></IconBtn>
          <IconBtn v-if="$can('destroy', 'language')" @click="confirmDelete(item.id)"><VIcon icon="tabler-trash" /></IconBtn>
        </template>

        <template #bottom>
          <TablePagination v-model:page="page" :items-per-page="itemsPerPage" :total-items="totalLanguages" />
        </template>
      </VDataTableServer>
    </VCol>
  </VRow>

  <AddModal v-model="isAddModalOpen" @submit="handleAddSubmit" />
  <EditModal v-model="isEditModalOpen" :item="selectedItem" @submit="handleEditSubmit" />
  <DeleteModal v-model="isDeleteModalOpen" @confirm="handleDelete" />

  <VSnackbar v-model="snackbar" :color="snackbarColor" location="top" timeout="3000">
    {{ snackbarMessage }}
    <template #actions>
      <VBtn color="white" variant="text" @click="snackbar = false">{{ $t('language.Close') }}</VBtn>
    </template>
  </VSnackbar>
</template>
