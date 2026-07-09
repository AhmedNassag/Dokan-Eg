<script setup>import { useI18n } from 'vue-i18n'
const { t, locale, mergeLocaleMessage } = useI18n()

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
const snackbar = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('success')
const isAddDialogOpen = ref(false)
const isEditDialogOpen = ref(false)
const addForm = ref({ group: '', key: '', values: {} })
const editForm = ref({ group: '', key: '', values: {}, ids: {} })
const page = ref(1)
const itemsPerPage = ref(10)
const totalFiltered = ref(0)

const headers = [
  { title: t('Key'), key: 'key', sortable: false },
  { title: t('Value'), key: 'value', sortable: false },
  { title: t('Actions'), key: 'actions', sortable: false },
]

async function fetchLanguages() {
  try {
    const res = await langApi.getAll({ per_page: -1 })
    languages.value = res.data?.items ?? res.data ?? []
    if (languages.value.length && !selectedLang.value) {
      selectedLang.value = languages.value[0].id
    }
    initAddFormValues()
  } catch { languages.value = [] }
}

function initAddFormValues() {
  const values = {}
  languages.value.forEach(l => { values[l.id] = '' })
  addForm.value.values = values
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
  return [{ title: t('All Groups'), value: '' }, ...g.map(name => ({ title: name, value: name }))]
})

const paginatedItems = computed(() => {
  const start = (page.value - 1) * itemsPerPage.value
  return filteredItems.value.slice(start, start + itemsPerPage.value)
})

watch(selectedLang, () => { fetchTranslations() })
watch([groupFilter, searchKey], () => { page.value = 1 })

async function openEditDialog(item) {
  try {
    const res = await transApi.getAll({ group: item.group, key: item.key, per_page: -1 })
    const allLangTranslations = Array.isArray(res.data?.items) ? res.data.items : Array.isArray(res.data) ? res.data : []
    const values = {}
    const ids = {}
    allLangTranslations.forEach(t => {
      ids[t.language_id] = t.id
      values[t.language_id] = t.value ?? ''
    })
    languages.value.forEach(l => {
      if (!(l.id in values)) values[l.id] = ''
      if (!(l.id in ids)) ids[l.id] = null
    })
    editForm.value = { group: item.group, key: item.key, values, ids }
    isEditDialogOpen.value = true
  } catch {
    snackbarMessage.value = t('Error loading translations')
    snackbarColor.value = 'error'; snackbar.value = true
  }
}

async function saveEditDialog() {
  try {
    const promises = languages.value
      .filter(lang => editForm.value.values[lang.id])
      .map(lang => {
        const id = editForm.value.ids[lang.id]
        const payload = {
          language_id: lang.id,
          group: editForm.value.group || null,
          key: editForm.value.key,
          value: editForm.value.values[lang.id],
        }
        return id
          ? transApi.update(id, { ...payload, id })
          : transApi.create(payload)
      })

    await Promise.all(promises)

    await fetchTranslations()

    const exportRes = await transApi.exportByCode(locale.value)
    const exportData = exportRes.data ?? {}
    if (Object.keys(exportData).length) {
      mergeLocaleMessage(locale.value, exportData)
    }

    isEditDialogOpen.value = false
    snackbarMessage.value = t('Translations updated')
    snackbarColor.value = 'success'; snackbar.value = true
  } catch (err) {
    snackbarMessage.value = err?.response?._data?.message || err?.message || t('Error')
    snackbarColor.value = 'error'; snackbar.value = true
  }
}

async function deleteTranslation(id) {
  try {
    await transApi.delete(id)
    items.value = items.value.filter(t => t.id !== id)

    const exportRes = await transApi.exportByCode(locale.value)
    const exportData = exportRes.data ?? {}
    if (Object.keys(exportData).length) {
      mergeLocaleMessage(locale.value, exportData)
    }

    snackbarMessage.value = t('Translation deleted')
    snackbarColor.value = 'success'; snackbar.value = true
  } catch (err) {
    snackbarMessage.value = err?.response?._data?.message || err?.message || t('Error')
    snackbarColor.value = 'error'; snackbar.value = true
  }
}

async function addTranslation() {
  try {
    const promises = languages.value
      .filter(lang => addForm.value.values[lang.id])
      .map(lang => transApi.create({
        language_id: lang.id,
        group: addForm.value.group || null,
        key: addForm.value.key,
        value: addForm.value.values[lang.id],
      }))

    await Promise.all(promises)

    await fetchTranslations()

    const exportRes = await transApi.exportByCode(locale.value)
    const exportData = exportRes.data ?? {}
    if (Object.keys(exportData).length) {
      mergeLocaleMessage(locale.value, exportData)
    }

    isAddDialogOpen.value = false
    addForm.value = { group: '', key: '', values: {} }
    initAddFormValues()
    snackbarMessage.value = t('Translations added')
    snackbarColor.value = 'success'; snackbar.value = true
  } catch (err) {
    snackbarMessage.value = err?.response?._data?.message || err?.message || t('Error')
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
            <span class="text-body-2">{{ item.value || '—' }}</span>
          </template>

          <template #item.actions="{ item }">
            <IconBtn v-if="$can('update', 'translation')" @click="openEditDialog(item)"><VIcon icon="tabler-pencil" /></IconBtn>
            <IconBtn v-if="$can('destroy', 'translation')" @click="deleteTranslation(item.id)"><VIcon icon="tabler-trash" /></IconBtn>
          </template>

          <template #bottom>
            <TablePagination v-model:page="page" :items-per-page="itemsPerPage" :total-items="totalFiltered" />
          </template>
        </VDataTableServer>
      </VCard>
    </VCol>
  </VRow>

  <VDialog v-model="isAddDialogOpen" max-width="600">
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
          <VCol cols="12" v-for="lang in languages" :key="lang.id">
            <AppTextField
              v-model="addForm.values[lang.id]"
              :label="$t('Value') + ' (' + lang.name + ')'"
              :placeholder="$t('Translated text')"
            />
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

  <VDialog v-model="isEditDialogOpen" max-width="600">
    <VCard>
      <VCardTitle>{{ $t('Edit Translation Key') }}</VCardTitle>
      <VCardText>
        <VRow>
          <VCol cols="12">
            <AppTextField :model-value="editForm.group" :label="$t('Group')" readonly />
          </VCol>
          <VCol cols="12">
            <AppTextField :model-value="editForm.key" :label="$t('Key')" readonly />
          </VCol>
          <VCol cols="12" v-for="lang in languages" :key="lang.id">
            <AppTextField
              v-model="editForm.values[lang.id]"
              :label="$t('Value') + ' (' + lang.name + ')'"
              :placeholder="$t('Translated text')"
            />
          </VCol>
        </VRow>
      </VCardText>
      <VCardActions>
        <VSpacer />
        <VBtn variant="tonal" @click="isEditDialogOpen = false">{{ $t('Cancel') }}</VBtn>
        <VBtn color="primary" @click="saveEditDialog">{{ $t('Update') }}</VBtn>
      </VCardActions>
    </VCard>
  </VDialog>

  <VSnackbar v-model="snackbar" :color="snackbarColor" location="top" timeout="2000">
    {{ snackbarMessage }}
    <template #actions><VBtn color="white" variant="text" @click="snackbar = false">{{ $t('Close') }}</VBtn></template>
  </VSnackbar>
</template>
