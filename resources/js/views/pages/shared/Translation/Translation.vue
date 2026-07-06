<script setup>
import LanguageAPI from '@/Api/shared/Language/language'
import TranslationAPI from '@/Api/shared/Translation/translation'

const langApi = new LanguageAPI()
const transApi = new TranslationAPI()

const languages = ref([])
const selectedLang = ref(null)
const items = ref([])
const isLoading = ref(false)
const groupFilter = ref('')
const searchKey = ref('')
const editingId = ref(null)
const editValue = ref('')
const snackbar = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('success')
const isAddDialogOpen = ref(false)
const addForm = ref({ group: '', key: '', value: '' })
const page = ref(1)
const itemsPerPage = ref(10)
const totalFiltered = ref(0)

const headers = [
  { title: 'Key', key: 'key', sortable: false },
  { title: 'Value', key: 'value', sortable: false },
  { title: 'Actions', key: 'actions', sortable: false },
]

async function fetchLanguages() {
  try {
    const res = await langApi.getAll({ per_page: -1 })
    languages.value = res.data?.items ?? res.data ?? []
    if (languages.value.length && !selectedLang.value) {
      selectedLang.value = languages.value[0].id
    }
  } catch { languages.value = [] }
}

async function fetchTranslations() {
  if (!selectedLang.value) return
  isLoading.value = true
  page.value = 1
  try {
    const res = await transApi.getAll({ language_id: selectedLang.value, per_page: -1 })
    const data = res.data?.items ?? res.data ?? []
    items.value = data.map(t => ({
      id: t.id,
      language_id: t.language_id,
      group: t.group || '',
      key: t.key,
      value: t.value ?? '',
    }))
  } catch { items.value = [] }
  finally { isLoading.value = false }
}

const filteredItems = computed(() => {
  if (!selectedLang.value) return []
  return items.value.filter(t => {
    if (groupFilter.value && t.group !== groupFilter.value) return false
    if (searchKey.value) {
      const q = searchKey.value.toLowerCase()
      if (!t.key.toLowerCase().includes(q) && !t.value?.toLowerCase().includes(q)) return false
    }
    return true
  })
})

watchEffect(() => { totalFiltered.value = filteredItems.value.length })

const groupOptions = computed(() => {
  const g = [...new Set(items.value.map(t => t.group).filter(Boolean))].sort()
  return [{ title: 'All Groups', value: '' }, ...g.map(name => ({ title: name, value: name }))]
})

const paginatedItems = computed(() => {
  const start = (page.value - 1) * itemsPerPage.value
  return filteredItems.value.slice(start, start + itemsPerPage.value)
})

watch(selectedLang, () => { fetchTranslations() })
watch([groupFilter, searchKey], () => { page.value = 1 })

function startEdit(item) {
  editingId.value = item.id
  editValue.value = item.value ?? ''
}

async function saveEdit(item) {
  try {
    await transApi.update(item.id, { ...item, value: editValue.value })
    item.value = editValue.value
    editingId.value = null
    snackbarMessage.value = 'Translation updated'
    snackbarColor.value = 'success'; snackbar.value = true
  } catch (err) {
    snackbarMessage.value = err?.response?._data?.message || err?.message || 'Error'
    snackbarColor.value = 'error'; snackbar.value = true
  }
}

function cancelEdit() {
  editingId.value = null
  editValue.value = ''
}

async function deleteTranslation(id) {
  try {
    await transApi.delete(id)
    items.value = items.value.filter(t => t.id !== id)
    snackbarMessage.value = 'Translation deleted'
    snackbarColor.value = 'success'; snackbar.value = true
  } catch (err) {
    snackbarMessage.value = err?.response?._data?.message || err?.message || 'Error'
    snackbarColor.value = 'error'; snackbar.value = true
  }
}

async function addTranslation() {
  try {
    const res = await transApi.create({
      language_id: selectedLang.value,
      group: addForm.value.group || null,
      key: addForm.value.key,
      value: addForm.value.value,
    })
    const newItem = res.data ?? { id: Date.now(), language_id: selectedLang.value, group: addForm.value.group || '', key: addForm.value.key, value: addForm.value.value }
    items.value.push(newItem)
    isAddDialogOpen.value = false
    addForm.value = { group: '', key: '', value: '' }
    snackbarMessage.value = 'Translation added'
    snackbarColor.value = 'success'; snackbar.value = true
  } catch (err) {
    snackbarMessage.value = err?.response?._data?.message || err?.message || 'Error'
    snackbarColor.value = 'error'; snackbar.value = true
  }
}

fetchLanguages()
</script>

<template>
  <VRow>
    <VCol cols="12">
      <div class="d-flex flex-wrap align-center">
        <div>
          <h4 class="text-h4">{{ $t('Translations') }}</h4>
          <p class="text-body-1 mb-0">{{ $t('Manage translation keys and values') }}</p>
        </div>
        <VSpacer />
        <VBtn v-if="$can('store', 'translation')" prepend-icon="tabler-plus" @click="isAddDialogOpen = true">
          {{ $t('Add Translation Key') }}
        </VBtn>
      </div>
    </VCol>

    <VCol cols="12">
      <VCard>
        <VCardText class="d-flex flex-wrap align-center gap-4">
          <VSelect v-model="selectedLang" :items="languages" item-title="name" item-value="id"
            :label="$t('Language')" style="inline-size: 200px;" hide-details clearable />
          <VSelect v-model="groupFilter" :items="groupOptions" item-title="title" item-value="value"
            :label="$t('Group')" style="inline-size: 200px;" hide-details clearable />
          <AppTextField v-model="searchKey" :placeholder="$t('Search key or value')"
            style="inline-size: 250px;" clearable hide-details />
        </VCardText>

        <VDivider />

        <VDataTableServer
          v-model:items-per-page="itemsPerPage"
          v-model:page="page"
          :items="paginatedItems"
          :items-length="totalFiltered"
          :headers="headers"
          :loading="isLoading"
          class="text-no-wrap"
        >
          <template #item.key="{ item }">
            <VChip v-if="item.group" size="small" variant="tonal" class="me-1">{{ item.group }}</VChip>
            <code class="text-body-2">{{ item.key }}</code>
          </template>

          <template #item.value="{ item }">
            <VTextField v-if="editingId === item.id" v-model="editValue" dense hide-details
              @keyup.enter="saveEdit(item)" @keyup.esc="cancelEdit" autofocus />
            <span v-else class="text-body-2">{{ item.value || '—' }}</span>
          </template>

          <template #item.actions="{ item }">
            <template v-if="editingId === item.id">
              <IconBtn color="success" @click="saveEdit(item)"><VIcon icon="tabler-check" /></IconBtn>
              <IconBtn color="secondary" @click="cancelEdit"><VIcon icon="tabler-x" /></IconBtn>
            </template>
            <template v-else>
              <IconBtn v-if="$can('update', 'translation')" @click="startEdit(item)"><VIcon icon="tabler-pencil" /></IconBtn>
              <IconBtn v-if="$can('destroy', 'translation')" @click="deleteTranslation(item.id)"><VIcon icon="tabler-trash" /></IconBtn>
            </template>
          </template>

          <template #bottom>
            <TablePagination v-model:page="page" :items-per-page="itemsPerPage" :total-items="totalFiltered" />
          </template>
        </VDataTableServer>
      </VCard>
    </VCol>
  </VRow>

  <VDialog v-model="isAddDialogOpen" max-width="500">
    <VCard>
      <VCardTitle>{{ $t('Add Translation Key') }}</VCardTitle>
      <VCardText>
        <VRow>
          <VCol cols="12">
            <AppTextField v-model="addForm.group" :label="$t('Group (optional)')"
              :placeholder="$t('e.g. $vuetify')" />
          </VCol>
          <VCol cols="12">
            <AppTextField v-model="addForm.key" :rules="[requiredValidator]"
              :label="$t('Key')" :placeholder="$t('translation.key.name')" />
          </VCol>
          <VCol cols="12">
            <AppTextField v-model="addForm.value" :label="$t('Value')" :placeholder="$t('Translated text')" />
          </VCol>
        </VRow>
      </VCardText>
      <VCardActions>
        <VSpacer />
        <VBtn variant="tonal" @click="isAddDialogOpen = false">{{ $t('Cancel') }}</VBtn>
        <VBtn color="primary" @click="addTranslation">{{ $t('Add') }}</VBtn>
      </VCardActions>
    </VCard>
  </VDialog>

  <VSnackbar v-model="snackbar" :color="snackbarColor" location="top" timeout="2000">
    {{ snackbarMessage }}
    <template #actions><VBtn color="white" variant="text" @click="snackbar = false">Close</VBtn></template>
  </VSnackbar>
</template>
