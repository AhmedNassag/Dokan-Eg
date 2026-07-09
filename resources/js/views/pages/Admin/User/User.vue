<script setup>import { useI18n } from 'vue-i18n'
const { t } = useI18n()

import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import UserAPI from '@/Api/Admin/User/user'

const api = new UserAPI()

const searchQuery = ref('')
const itemsPerPage = ref(10)
const page = ref(1)
const sortBy = ref()
const orderBy = ref()
const users = ref([])
const totalUsers = ref(0)
const isLoading = ref(false)
const isFormDialogOpen = ref(false)
const isConfirmDialogOpen = ref(false)
const isSubmitting = ref(false)
const deleteId = ref(null)

const isFormValid = ref(false)
const refForm = ref()
const selectedUser = ref(null)
const roles = ref([])
const formData = ref({
  name: '',
  email: '',
  password: '',
  user_type: 'marketer',
  status: 'approved',
  role: '',
})

const snackbar = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('success')

const headers = [
  { title: t('#'), key: 'id', sortable: true },
  { title: t('Name'), key: 'name', sortable: true },
  { title: t('Email'), key: 'email', sortable: true },
  { title: t('Type'), key: 'user_type', sortable: true },
  { title: t('Status'), key: 'status', sortable: true },
  { title: t('Role'), key: 'role', sortable: false },
  { title: t('Actions'), key: 'actions', sortable: false },
]

const userTypeOptions = [
  { title: t('Admin'), value: 'admin' },
  { title: t('Merchant'), value: 'merchant' },
  { title: t('Marketer'), value: 'marketer' },
]

const statusOptions = [
  { title: t('Approved'), value: 'approved' },
  { title: t('Pending'), value: 'pending' },
  { title: t('Rejected'), value: 'rejected' },
  { title: t('Suspended'), value: 'suspended' },
]

const statusColorMap = {
  approved: 'success',
  pending: 'warning',
  rejected: 'error',
  suspended: 'secondary',
}

const updateOptions = options => {
  sortBy.value = options.sortBy[0]?.key
  orderBy.value = options.sortBy[0]?.order
}

async function fetchUsers() {
  isLoading.value = true
  try {
    const res = await api.getAll({
      q: searchQuery.value,
      itemsPerPage: itemsPerPage.value,
      page: page.value,
      sortBy: sortBy.value,
      orderBy: orderBy.value,
    })
    users.value = res.data ?? res.users ?? []
    totalUsers.value = res.total ?? 0
  } catch {
    users.value = []
    totalUsers.value = 0
  } finally {
    isLoading.value = false
  }
}

async function fetchRoles() {
  try {
    const res = await api.getRoles()
    roles.value = Array.isArray(res) ? res : []
  } catch {
    roles.value = []
  }
}

function openAddDialog() {
  selectedUser.value = null
  formData.value = {
    name: '',
    email: '',
    password: '',
    user_type: 'marketer',
    status: 'approved',
    role: roles.value[0]?.id ?? '',
  }
  isFormDialogOpen.value = true
  nextTick(() => refForm.value?.resetValidation())
}

function openEditDialog(user) {
  selectedUser.value = user
  formData.value = {
    name: user.name ?? '',
    email: user.email ?? '',
    password: '',
    user_type: user.user_type ?? 'marketer',
    status: user.status ?? 'approved',
    role: user.roles?.[0]?.id ?? '',
  }
  isFormDialogOpen.value = true
  nextTick(() => refForm.value?.resetValidation())
}

function closeFormDialog() {
  isFormDialogOpen.value = false
  selectedUser.value = null
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
    await fetchUsers()
  } catch {
  } finally {
    isConfirmDialogOpen.value = false
    deleteId.value = null
  }
}

async function onSubmit() {
  refForm.value?.validate().then(async ({ valid }) => {
    if (!valid) return
    isSubmitting.value = true
    try {
      if (selectedUser.value) {
        await api.update(selectedUser.value.id, formData.value)
        snackbarMessage.value = t('User updated successfully')
      } else {
        await api.create(formData.value)
        snackbarMessage.value = t('User created successfully')
      }
      snackbarColor.value = 'success'
      snackbar.value = true
      closeFormDialog()
      await fetchUsers()
    } catch (err) {
      snackbarMessage.value = err?.response?._data?.message || err?.message || t('An error occurred')
      snackbarColor.value = 'error'
      snackbar.value = true
    } finally {
      isSubmitting.value = false
    }
  })
}

watch([itemsPerPage, page, searchQuery], () => {
  fetchUsers()
})

fetchUsers()
fetchRoles()
</script>

<template>
  <VRow>
    <VCol cols="12">
      <div class="d-flex flex-wrap align-center">
        <div>
          <h4 class="text-h4">
            {{ $t('User Management') }}
          </h4>
          <p class="text-body-1 mb-0">
            {{ $t('Manage users and their roles') }}
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
            v-if="$can('store', 'user')"
            prepend-icon="tabler-plus"
            @click="openAddDialog"
          >
            {{ $t('Add User') }}
          </VBtn>
        </div>
      </div>
    </VCol>

    <VCol cols="12">
      <VCard>
        <VDataTableServer
          v-model:items-per-page="itemsPerPage"
          v-model:page="page"
          :items="users"
          :items-length="totalUsers"
          :headers="headers"
          :loading="isLoading"
          class="text-no-wrap"
          @update:options="updateOptions"
        >
          <template #item.id="{ item }">
            <span class="text-body-1 text-high-emphasis">{{ item.id }}</span>
          </template>

          <template #item.name="{ item }">
            <div class="d-flex align-center gap-3">
              <VAvatar
                :color="item.avatar ? undefined : 'primary'"
                :variant="item.avatar ? 'outlined' : 'tonal'"
                size="34"
              >
                <VImg
                  v-if="item.avatar"
                  :src="item.avatar"
                />
                <span v-else class="text-body-2 font-weight-medium">{{ item.name?.charAt(0)?.toUpperCase() }}</span>
              </VAvatar>
              <div>
                <span class="text-body-1 text-high-emphasis">{{ item.name }}</span>
              </div>
            </div>
          </template>

          <template #item.email="{ item }">
            <span class="text-body-1">{{ item.email }}</span>
          </template>

          <template #item.user_type="{ item }">
            <VChip
              size="small"
              :color="item.user_type === 'admin' ? 'primary' : item.user_type === 'merchant' ? 'warning' : 'info'"
              label
            >
              {{ item.user_type }}
            </VChip>
          </template>

          <template #item.status="{ item }">
            <VChip
              size="small"
              :color="statusColorMap[item.status] || 'default'"
              label
            >
              {{ item.status }}
            </VChip>
          </template>

          <template #item.role="{ item }">
            <VChip
              v-for="role in (item.role_names ?? [])"
              :key="role"
              size="x-small"
              color="secondary"
              variant="outlined"
              label
            >
              {{ role }}
            </VChip>
            <span
              v-if="!item.role_names?.length"
              class="text-body-2 text-disabled"
            >—</span>
          </template>

          <template #item.actions="{ item }">
            <div class="d-flex gap-1">
              <IconBtn
                v-if="$can('update', 'user')"
                @click="openEditDialog(item)"
              >
                <VIcon icon="tabler-pencil" />
              </IconBtn>
              <IconBtn
                v-if="$can('destroy', 'user') && item.email !== 'admin@demo.com'"
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
              :total-items="totalUsers"
            />
          </template>
        </VDataTableServer>
      </VCard>
    </VCol>
  </VRow>

  <VNavigationDrawer
    temporary
    :width="450"
    location="end"
    class="scrollable-content"
    :model-value="isFormDialogOpen"
    @update:model-value="closeFormDialog"
  >
    <AppDrawerHeaderSection
      :title="selectedUser ? $t('Edit User') : $t('Add User')"
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
                  :placeholder="$t('Full name')"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="formData.email"
                  :rules="[requiredValidator, emailValidator]"
                  :label="$t('Email')"
                  :placeholder="$t('Email address')"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="formData.password"
                  :label="$t('Password')"
                  :placeholder="selectedUser ? $t('Leave empty to keep current') : $t('Password')"
                  :rules="selectedUser ? [] : [requiredValidator]"
                  type="password"
                />
              </VCol>

              <VCol cols="6">
                <VSelect
                  v-model="formData.user_type"
                  :items="userTypeOptions"
                  :rules="[requiredValidator]"
                  :label="$t('User Type')"
                  item-title="title"
                  item-value="value"
                />
              </VCol>

              <VCol cols="6">
                <VSelect
                  v-model="formData.status"
                  :items="statusOptions"
                  :rules="[requiredValidator]"
                  :label="$t('Status')"
                  item-title="title"
                  item-value="value"
                />
              </VCol>

              <VCol cols="12">
                <VSelect
                  v-model="formData.role"
                  :items="roles"
                  :rules="[requiredValidator]"
                  :label="$t('Role')"
                  item-title="name"
                  item-value="id"
                  :placeholder="$t('Select role')"
                />
              </VCol>

              <VCol cols="12">
                <VBtn
                  type="submit"
                  :loading="isSubmitting"
                  class="me-3"
                >
                  {{ selectedUser ? $t('Update') : $t('Submit') }}
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
    :confirmation-question="$t('Are you sure you want to delete this user?')"
    :confirm-title="$t('Deleted!')"
    :confirm-msg="$t('User has been deleted successfully.')"
    :cancel-title="$t('Cancelled')"
    :cancel-msg="$t('User deletion cancelled.')"
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
        {{ $t('Close') }}
      </VBtn>
    </template>
  </VSnackbar>
</template>
