<script setup>import { useI18n } from 'vue-i18n'
const { t } = useI18n()

import CategoryAPI from '@/Api/shared/Category/category'
import AddModal from './AddModal.vue'
import DeleteModal from './DeleteModal.vue'
import EditModal from './EditModal.vue'

const api = new CategoryAPI()

const searchQuery = ref('')
const itemsPerPage = ref(10)
const page = ref(1)
const sortBy = ref()
const orderBy = ref()
const categories = ref([])
const totalCategories = ref(0)
const isLoading = ref(false)
const deleteId = ref(null)

const isAddModalOpen = ref(false)
const isEditModalOpen = ref(false)
const isDeleteModalOpen = ref(false)
const selectedCategory = ref(null)

const snackbar = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('success')

const headers = [
  { title: t('category.#'), key: 'id', sortable: true },
  { title: t('category.Name'), key: 'name', sortable: true },
  { title: t('category.Parent'), key: 'parent', sortable: false },
  { title: t('category.Active'), key: 'is_active', sortable: true },
  { title: t('category.Description'), key: 'description', sortable: false },
  { title: t('category.Actions'), key: 'actions', sortable: false },
]

const updateOptions = options => {
  sortBy.value = options.sortBy[0]?.key
  orderBy.value = options.sortBy[0]?.order
}

async function fetchCategories() {
  isLoading.value = true
  try {
    const res = await api.getAll({
      q: searchQuery.value,
      itemsPerPage: itemsPerPage.value,
      page: page.value,
      sortBy: sortBy.value,
      orderBy: orderBy.value,
    })

    const categoryData = res.data ?? res.categories ?? []

    categories.value = categoryData.map(cat => ({
      ...cat,
      is_active: Boolean(cat.is_active),
    }))
    totalCategories.value = res.total ?? res.totalCategories ?? 0
  } catch {
    categories.value = []
    totalCategories.value = 0
  } finally {
    isLoading.value = false
  }
}

function formatError(err) {
  const data = err?.response?._data
  if (data?.errors) {
    return Object.values(data.errors).flat().join(', ')
  }
  
  return data?.message || err?.message || t('category.An Error Occurred')
}

function openAddModal() {
  isAddModalOpen.value = true
}

function openEditModal(category) {
  selectedCategory.value = category
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
    snackbarMessage.value = t('category.Category Deleted Successfully')
    snackbarColor.value = 'success'
    snackbar.value = true
    await fetchCategories()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'
    snackbar.value = true
  } finally {
    deleteId.value = null
  }
}

async function toggleActive(category) {
  try {
    await api.update(category.id, {
      ...category,
      is_active: !category.is_active,
    })
    await fetchCategories()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'
    snackbar.value = true
  }
}

async function handleAddSubmit(data) {
  try {
    await api.create(data)
    snackbarMessage.value = t('category.Category Created Successfully')
    snackbarColor.value = 'success'
    snackbar.value = true
    await fetchCategories()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'
    snackbar.value = true
  }
}

async function handleEditSubmit(data) {
  try {
    await api.update(selectedCategory.value.id, data)
    snackbarMessage.value = t('category.Category Updated Successfully')
    snackbarColor.value = 'success'
    snackbar.value = true
    await fetchCategories()
  } catch (err) {
    snackbarMessage.value = formatError(err)
    snackbarColor.value = 'error'
    snackbar.value = true
  } finally {
    selectedCategory.value = null
  }
}

watch([itemsPerPage, page, searchQuery], () => {
  fetchCategories()
})

fetchCategories()
</script>

<template>
  <VRow>
    <VCol cols="12">
      <div class="d-flex flex-wrap align-center">
        <div>
          <h4 class="text-h4">
            {{ $t('category.Category Management') }}
          </h4>
          <p class="text-body-1 mb-0">
            {{ $t('category.Manage Your Categories') }}
          </p>
        </div>
        <VSpacer />
        <div class="d-flex align-center flex-wrap gap-4">
          <AppTextField
            v-model="searchQuery"
            :placeholder="$t('category.Search')"
            style="inline-size: 15.625rem;"
            clearable
            clear-icon="tabler-x"
          />
          <VBtn
            v-if="$can('store', 'category')"
            prepend-icon="tabler-plus"
            @click="openAddModal"
          >
            {{ $t('category.Add Category') }}
          </VBtn>
        </div>
      </div>
    </VCol>

    <VCol cols="12">
      <VCard>
        <VDataTableServer
          v-model:items-per-page="itemsPerPage"
          v-model:page="page"
          :items="categories"
          :items-length="totalCategories"
          :headers="headers"
          :loading="isLoading"
          class="text-no-wrap"
          @update:options="updateOptions"
        >
          <template #item.id="{ item }">
            <span class="text-body-1 text-high-emphasis">{{ item.id }}</span>
          </template>

          <template #item.name="{ item }">
            <span class="text-body-1 text-high-emphasis">{{ item.name }}</span>
          </template>

          <template #item.parent="{ item }">
            <span class="text-body-1">{{ item.parent?.name || '—' }}</span>
          </template>

          <template #item.is_active="{ item }">
            <VSwitch
              v-if="$can('update', 'category')"
              :model-value="item.is_active"
              color="success"
              inset
              hide-details
              @update:model-value="() => toggleActive(item)"
            />
            <VChip
              v-else
              :color="item.is_active ? 'success' : 'error'"
              size="small"
            >
              {{ item.is_active ? $t('category.Yes') : $t('category.No') }}
            </VChip>
          </template>

          <template #item.description="{ item }">
            <span class="text-body-1">{{ item.description || '—' }}</span>
          </template>

          <template #item.actions="{ item }">
            <div class="d-flex gap-1">
              <IconBtn
                v-if="$can('update', 'category')"
                @click="openEditModal(item)"
              >
                <VIcon icon="tabler-pencil" />
              </IconBtn>
              <IconBtn
                v-if="$can('destroy', 'category')"
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
              :total-items="totalCategories"
            />
          </template>
        </VDataTableServer>
      </VCard>
    </VCol>
  </VRow>

  <AddModal
    v-model="isAddModalOpen"
    :categories="categories"
    @submit="handleAddSubmit"
  />

  <EditModal
    v-model="isEditModalOpen"
    :category="selectedCategory"
    :categories="categories"
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
        {{ $t('category.Close') }}
      </VBtn>
    </template>
  </VSnackbar>
</template>
