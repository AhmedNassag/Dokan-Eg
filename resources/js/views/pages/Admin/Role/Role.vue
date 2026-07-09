<script setup>import { useI18n } from 'vue-i18n'
const { t } = useI18n()

import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import RoleAPI from '@/Api/Admin/Role/role'
import PermissionAPI from '@/Api/Admin/Permission/permission'

const roleApi = new RoleAPI()
const permissionApi = new PermissionAPI()

const searchQuery = ref('')
const itemsPerPage = ref(10)
const page = ref(1)
const sortBy = ref()
const orderBy = ref()
const roles = ref([])
const totalRoles = ref(0)
const isLoading = ref(false)
const isFormDialogOpen = ref(false)
const isConfirmDialogOpen = ref(false)
const isSubmitting = ref(false)
const deleteId = ref(null)

const isFormValid = ref(false)
const refForm = ref()
const selectedRole = ref(null)
const formData = ref({ name: '', permissions: [] })

const allPermissions = ref([])
const snackbar = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('success')

const headers = [
  { title: t('#'), key: 'id', sortable: true },
  { title: t('Role'), key: 'name', sortable: true },
  { title: t('Permissions'), key: 'permissions', sortable: false },
  { title: t('Actions'), key: 'actions', sortable: false },
]

const updateOptions = options => {
  sortBy.value = options.sortBy[0]?.key
  orderBy.value = options.sortBy[0]?.order
}

async function fetchRoles() {
  isLoading.value = true
  try {
    const res = await roleApi.getAll({
      q: searchQuery.value,
      itemsPerPage: itemsPerPage.value,
      page: page.value,
      sortBy: sortBy.value,
      orderBy: orderBy.value,
    })

    roles.value = res.data ?? res.roles ?? []
    totalRoles.value = res.total ?? 0
  } catch {
    roles.value = []
    totalRoles.value = 0
  } finally {
    isLoading.value = false
  }
}

async function fetchAllPermissions() {
  try {
    const res = await permissionApi.getAll({ itemsPerPage: 9999 })

    allPermissions.value = res.data ?? res.permissions ?? []
  } catch {
    allPermissions.value = []
  }
}

const groupedPermissions = computed(() => {
  const groups = {}
  for (const perm of allPermissions.value) {
    const parts = perm.name.split('-')
    const subject = parts.slice(1).join('-')
    if (!groups[subject]) groups[subject] = []
    groups[subject].push({ ...perm, action: parts[0] })
  }

  return groups
})

function openAddDialog() {
  selectedRole.value = null
  formData.value = { name: '', permissions: [] }
  isFormDialogOpen.value = true
  nextTick(() => refForm.value?.resetValidation())
}

function openEditDialog(role) {
  selectedRole.value = role
  formData.value = {
    name: role.name ?? '',
    permissions: (role.permissions ?? []).map(p => p.id),
  }
  isFormDialogOpen.value = true
  nextTick(() => refForm.value?.resetValidation())
}

function closeFormDialog() {
  isFormDialogOpen.value = false
  selectedRole.value = null
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
    await roleApi.delete(deleteId.value)
    await fetchRoles()
  } catch {
  } finally {
    isConfirmDialogOpen.value = false
    deleteId.value = null
  }
}

function togglePermission(permId) {
  const idx = formData.value.permissions.indexOf(permId)
  if (idx === -1) {
    formData.value.permissions.push(permId)
  } else {
    formData.value.permissions.splice(idx, 1)
  }
}

function toggleGroup(subject) {
  const groupIds = groupedPermissions.value[subject]?.map(p => p.id) ?? []
  const allSelected = groupIds.every(id => formData.value.permissions.includes(id))
  if (allSelected) {
    formData.value.permissions = formData.value.permissions.filter(id => !groupIds.includes(id))
  } else {
    const toAdd = groupIds.filter(id => !formData.value.permissions.includes(id))
    
    formData.value.permissions.push(...toAdd)
  }
}

function isGroupSelected(subject) {
  const groupIds = groupedPermissions.value[subject]?.map(p => p.id) ?? []
  
  return groupIds.length > 0 && groupIds.every(id => formData.value.permissions.includes(id))
}

function isGroupPartial(subject) {
  const groupIds = groupedPermissions.value[subject]?.map(p => p.id) ?? []
  const selected = groupIds.filter(id => formData.value.permissions.includes(id)).length
  
  return selected > 0 && selected < groupIds.length
}

async function onSubmit() {
  refForm.value?.validate().then(async ({ valid }) => {
    if (!valid) return
    isSubmitting.value = true
    try {
      if (selectedRole.value) {
        await roleApi.update(selectedRole.value.id, formData.value)
        snackbarMessage.value = t('Role updated successfully')
      } else {
        await roleApi.create(formData.value)
        snackbarMessage.value = t('Role created successfully')
      }
      snackbarColor.value = 'success'
      snackbar.value = true
      closeFormDialog()
      await fetchRoles()
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
  fetchRoles()
})

fetchRoles()
fetchAllPermissions()
</script>

<template>
  <VRow>
    <VCol cols="12">
      <div class="d-flex flex-wrap align-center">
        <div>
          <h4 class="text-h4">
            {{ $t('Role Management') }}
          </h4>
          <p class="text-body-1 mb-0">
            {{ $t('Manage roles and their permissions') }}
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
            v-if="$can('store', 'role')"
            prepend-icon="tabler-plus"
            @click="openAddDialog"
          >
            {{ $t('Add Role') }}
          </VBtn>
        </div>
      </div>
    </VCol>

    <VCol cols="12">
      <VCard>
        <VDataTableServer
          v-model:items-per-page="itemsPerPage"
          v-model:page="page"
          :items="roles"
          :items-length="totalRoles"
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
              :color="item.name === 'admin' ? 'primary' : item.name === 'merchant' ? 'warning' : 'info'"
              size="small"
              label
            >
              {{ item.name }}
            </VChip>
          </template>

          <template #item.permissions="{ item }">
            <div class="d-flex flex-wrap gap-1" style="max-inline-size: 300px;">
              <VChip
                v-for="perm in (item.permissions ?? [])"
                :key="perm.id"
                size="x-small"
                color="secondary"
                variant="outlined"
                label
              >
                {{ perm.name }}
              </VChip>
              <span
                v-if="!item.permissions?.length"
                class="text-body-2 text-disabled"
              >—</span>
            </div>
          </template>

          <template #item.actions="{ item }">
            <div class="d-flex gap-1">
              <IconBtn
                v-if="$can('update', 'role')"
                @click="openEditDialog(item)"
              >
                <VIcon icon="tabler-pencil" />
              </IconBtn>
              <IconBtn
                v-if="$can('destroy', 'role') && !['admin', 'merchant', 'marketer'].includes(item.name)"
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
              :total-items="totalRoles"
            />
          </template>
        </VDataTableServer>
      </VCard>
    </VCol>
  </VRow>

  <VNavigationDrawer
    temporary
    :width="500"
    location="end"
    class="scrollable-content"
    :model-value="isFormDialogOpen"
    @update:model-value="closeFormDialog"
  >
    <AppDrawerHeaderSection
      :title="selectedRole ? $t('Edit Role') : $t('Add Role')"
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
                  :label="$t('Role Name')"
                  :placeholder="$t('Enter role name')"
                />
              </VCol>

              <VCol cols="12">
                <label class="text-body-2 text-high-emphasis font-weight-medium mb-2 d-block">
                  {{ $t('Permissions') }}
                </label>
                <VCard
                  variant="outlined"
                  class="pa-4"
                >
                  <div
                    v-for="(perms, subject) in groupedPermissions"
                    :key="subject"
                    class="mb-3"
                  >
                    <div class="d-flex align-center mb-1">
                      <VCheckbox
                        :model-value="isGroupSelected(subject)"
                        :indeterminate="isGroupPartial(subject)"
                        :label="subject.charAt(0).toUpperCase() + subject.slice(1)"
                        hide-details
                        density="compact"
                        class="font-weight-medium"
                        @click="toggleGroup(subject)"
                      />
                    </div>
                    <div class="d-flex flex-wrap gap-2 ms-6">
                      <VChip
                        v-for="perm in perms"
                        :key="perm.id"
                        :color="formData.permissions.includes(perm.id) ? 'primary' : 'default'"
                        variant="outlined"
                        size="small"
                        label
                        :class="{ 'cursor-pointer': true }"
                        @click="togglePermission(perm.id)"
                      >
                        <template #prepend>
                          <VIcon
                            v-if="formData.permissions.includes(perm.id)"
                            icon="tabler-check"
                            size="16"
                          />
                        </template>
                        {{ perm.action }}
                      </VChip>
                    </div>
                    <VDivider
                      v-if="subject !== Object.keys(groupedPermissions).at(-1)"
                      class="mt-3"
                    />
                  </div>
                  <VAlert
                    v-if="!Object.keys(groupedPermissions).length"
                    type="info"
                    variant="tonal"
                    density="compact"
                    :text="$t('No permissions available')"
                  />
                </VCard>
              </VCol>

              <VCol cols="12">
                <VBtn
                  type="submit"
                  :loading="isSubmitting"
                  class="me-3"
                >
                  {{ selectedRole ? $t('Update') : $t('Submit') }}
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
    :confirmation-question="$t('Are you sure you want to delete this role?')"
    :confirm-title="$t('Deleted!')"
    :confirm-msg="$t('Role has been deleted successfully.')"
    :cancel-title="$t('Cancelled')"
    :cancel-msg="$t('Role deletion cancelled.')"
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
