<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import CategoryAPI from '@/Api/shared/Category/category'

const api = new CategoryAPI()

const searchQuery = ref('')
const itemsPerPage = ref(10)
const page = ref(1)
const sortBy = ref()
const orderBy = ref()
const categories = ref([])
const totalCategories = ref(0)
const isLoading = ref(false)
const isFormDialogOpen = ref(false)
const isConfirmDialogOpen = ref(false)
const isSubmitting = ref(false)
const deleteId = ref(null)

const isFormValid = ref(false)
const refForm = ref()
const selectedCategory = ref(null)
const formData = ref({ name: '', description: '', parent_id: null, is_active: true })

const snackbar = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('success')

const headers = [
  { title: '#', key: 'id', sortable: true },
  { title: 'Name', key: 'name', sortable: true },
  { title: 'Parent', key: 'parent', sortable: false },
  { title: 'Active', key: 'is_active', sortable: true },
  { title: 'Description', key: 'description', sortable: false },
  { title: 'Actions', key: 'actions', sortable: false },
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
    
    categories.value = res.data ?? res.categories ?? []
    totalCategories.value = res.total ?? res.totalCategories ?? 0
  } catch {
    categories.value = []
    totalCategories.value = 0
  } finally {
    isLoading.value = false
  }
}

function openAddDialog() {
  selectedCategory.value = null
  formData.value = { name: '', description: '', parent_id: null, is_active: true }
  isFormDialogOpen.value = true
  nextTick(() => refForm.value?.resetValidation())
}

function openEditDialog(category) {
  selectedCategory.value = category
  formData.value = {
    name: category.name ?? '',
    description: category.description ?? '',
    parent_id: category.parent_id ?? null,
    is_active: category.is_active ?? true,
  }
  isFormDialogOpen.value = true
  nextTick(() => refForm.value?.resetValidation())
}

function closeFormDialog() {
  isFormDialogOpen.value = false
  selectedCategory.value = null
  nextTick(() => {
    refForm.value?.reset()
    refForm.value?.resetValidation()
  })
}

function confirmDelete(id) {
  deleteId.value = id
  isConfirmDialogOpen.value = true
}

async function handleDelete() {
  if (deleteId.value == null) return
  try {
    await api.delete(deleteId.value)
    await fetchCategories()
  } catch {
  } finally {
    isConfirmDialogOpen.value = false
    deleteId.value = null
  }
}

async function toggleActive(category) {
  const newActive = !category.is_active
  try {
    console.log('Toggling category id:', category.id, 'from', category.is_active, 'to', newActive)
    await api.update(category.id, { is_active: newActive })
    console.log('Toggle success')
    await fetchCategories()
  } catch (err) {
    console.error('Toggle error:', err)
    snackbarMessage.value = err?.response?._data?.message || err?.message || 'An error occurred'
    snackbarColor.value = 'error'
    snackbar.value = true
  }
}

async function onSubmit() {
  refForm.value?.validate().then(async ({ valid }) => {
    if (!valid) return
    isSubmitting.value = true
    try {
      const data = { ...formData.value }
      console.log('Form data before:', data)
      if (data.parent_id === '' || data.parent_id === null || data.parent_id === undefined) {
        delete data.parent_id
      } else {
        data.parent_id = Number(data.parent_id)
      }
      console.log('Form data after:', data)
      if (selectedCategory.value) {
        await api.update(selectedCategory.value.id, data)
        snackbarMessage.value = 'Category updated successfully'
      } else {
        await api.create(data)
        snackbarMessage.value = 'Category created successfully'
      }
      snackbarColor.value = 'success'
      snackbar.value = true
      closeFormDialog()
      await fetchCategories()
    } catch (err) {
      console.error('Submit error:', err)
      snackbarMessage.value = err?.response?._data?.message || err?.message || 'An error occurred'
      snackbarColor.value = 'error'
      snackbar.value = true
    } finally {
      isSubmitting.value = false
    }
  })
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
            {{ $t('Category Management') }}
          </h4>
          <p class="text-body-1 mb-0">
            {{ $t('Manage your categories') }}
          </p>
        </div>
        <VSpacer />
        <div class="d-flex align-center flex-wrap gap-4">
          <AppTextField
            v-model="searchQuery"
            :placeholder="$t('Search')"
            style="inline-size: 15.625rem;"
            clearable
            clear-icon="tabler-x"
          />
          <VBtn
            v-if="$can('store', 'category')"
            prepend-icon="tabler-plus"
            @click="openAddDialog"
          >
            {{ $t('Add Category') }}
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
            <VBtn
              v-if="$can('update', 'category')"
              :icon="item.is_active ? 'mdi-check-circle' : 'mdi-close-circle'"
              :color="item.is_active ? 'success' : 'error'"
              size="small"
              variant="text"
              @click="toggleActive(item)"
            />
            <VChip v-else :color="item.is_active ? 'success' : 'error'" size="small">
              {{ item.is_active ? 'Yes' : 'No' }}
            </VChip>
          </template>

          <template #item.description="{ item }">
            <span class="text-body-1">{{ item.description || '—' }}</span>
          </template>

          <template #item.actions="{ item }">
            <div class="d-flex gap-1">
              <IconBtn
                v-if="$can('update', 'category')"
                @click="openEditDialog(item)"
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

  <VNavigationDrawer
    temporary
    :width="400"
    location="end"
    class="scrollable-content"
    :model-value="isFormDialogOpen"
    @update:model-value="closeFormDialog"
  >
    <AppDrawerHeaderSection
      :title="selectedCategory ? $t('Edit Category') : $t('Add Category')"
      @cancel="closeFormDialog"
    />

    <VDivider />

    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <VForm
            ref="refForm"
            v-model="isFormValid"
            @submit.prevent="onSubmit"
          >
            <VRow>
              <VCol cols="12">
                <AppTextField
                  v-model="formData.name"
                  :rules="[requiredValidator]"
                  :label="$t('Name')"
                  :placeholder="$t('Category name')"
                />
              </VCol>

              <VCol cols="12">
                <VSelect
                  v-model="formData.parent_id"
                  :items="categories.filter(c => !selectedCategory || c.id !== selectedCategory.id)"
                  item-title="name"
                  item-value="id"
                  :label="$t('Parent Category')"
                  :placeholder="$t('Select parent category')"
                  clearable
                  :return-object="false"
                />
              </VCol>

              <VCol cols="12">
                <VCheckbox
                  v-model="formData.is_active"
                  :label="$t('Active')"
                  color="success"
                />
              </VCol>

              <VCol cols="12">
                <AppTextarea
                  v-model="formData.description"
                  :label="$t('Description')"
                  :placeholder="$t('Category description')"
                  auto-grow
                />
              </VCol>

              <VCol cols="12">
                <VBtn
                  type="submit"
                  :loading="isSubmitting"
                  class="me-3"
                >
                  {{ selectedCategory ? $t('Update') : $t('Submit') }}
                </VBtn>
                <VBtn
                  type="reset"
                  variant="tonal"
                  color="error"
                  @click="closeFormDialog"
                >
                  {{ $t('Cancel') }}
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>

  <ConfirmDialog
    :is-dialog-visible="isConfirmDialogOpen"
    confirmation-question="Are you sure you want to delete this category?"
    confirm-title="Deleted!"
    confirm-msg="Category has been deleted successfully."
    cancel-title="Cancelled"
    cancel-msg="Category deletion cancelled."
    @update:is-dialog-visible="isConfirmDialogOpen = $event"
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
        Close
      </VBtn>
    </template>
  </VSnackbar>
</template>
