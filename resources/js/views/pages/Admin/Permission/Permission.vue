<script setup>
import PermissionAPI from '@/Api/Admin/Permission/permission'

const api = new PermissionAPI()

const searchQuery = ref('')
const itemsPerPage = ref(10)
const page = ref(1)
const sortBy = ref()
const orderBy = ref()
const permissions = ref([])
const totalPermissions = ref(0)
const isLoading = ref(false)

const snackbar = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('success')

const headers = [
  { title: '#', key: 'id', sortable: true },
  { title: 'Permission', key: 'name', sortable: true },
]

const updateOptions = options => {
  sortBy.value = options.sortBy[0]?.key
  orderBy.value = options.sortBy[0]?.order
}

async function fetchPermissions() {
  isLoading.value = true
  try {
    const res = await api.getAll({
      q: searchQuery.value,
      itemsPerPage: itemsPerPage.value,
      page: page.value,
      sortBy: sortBy.value,
      orderBy: orderBy.value,
    })

    permissions.value = res.data ?? res.permissions ?? []
    totalPermissions.value = res.total ?? 0
  } catch {
    permissions.value = []
    totalPermissions.value = 0
  } finally {
    isLoading.value = false
  }
}

watch([itemsPerPage, page, searchQuery], () => {
  fetchPermissions()
})

fetchPermissions()
</script>

<template>
  <VRow>
    <VCol cols="12">
      <div class="d-flex flex-wrap align-center">
        <div>
          <h4 class="text-h4">
            {{ $t('Permission Management') }}
          </h4>
          <p class="text-body-1 mb-0">
            {{ $t('Manage permissions') }}
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
        </div>
      </div>
    </VCol>

    <VCol cols="12">
      <VCard>
        <VDataTableServer
          v-model:items-per-page="itemsPerPage"
          v-model:page="page"
          :items="permissions"
          :items-length="totalPermissions"
          :headers="headers"
          :loading="isLoading"
          class="text-no-wrap"
          @update:options="updateOptions"
        >
          <template #item.id="{ item }">
            <span class="text-body-1 text-high-emphasis">{{ item.id }}</span>
          </template>

          <template #item.name="{ item }">
            <VChip
              size="small"
              color="secondary"
              variant="outlined"
              label
            >
              {{ item.name }}
            </VChip>
          </template>

          <template #bottom>
            <TablePagination
              v-model:page="page"
              :items-per-page="itemsPerPage"
              :total-items="totalPermissions"
            />
          </template>
        </VDataTableServer>
      </VCard>
    </VCol>
  </VRow>

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
