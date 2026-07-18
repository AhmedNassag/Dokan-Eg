<script setup>
import { useI18n } from 'vue-i18n'
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import ShopSectionAPI from '@/Api/shared/Shop/shopSection'
import ShopAPI from '@/Api/shared/Shop/shop'
import { themeOptions } from './shopOptions'
import { SECTION_TYPES, getDefaultContent, getDefaultSettings } from './builder/sectionConfig'
import SectionPreview from './builder/SectionPreview.vue'
import SectionEditor from './builder/SectionEditor.vue'

const { t } = useI18n()
const route = useRoute()
const router = useRouter()

const shopId = computed(() => Number(route.params.shopId))
const rolePrefix = computed(() => route.name?.toString().split('-')[0] || 'admin')

const api = new ShopSectionAPI(shopId.value)
const shopApi = new ShopAPI()

const shop = ref(null)
const sections = ref([])
const isLoading = ref(false)
const isSaving = ref(false)
const isUpdatingTheme = ref(false)
const activeTab = ref('preview')
const selectedSectionId = ref(null)
const previewKey = ref(0)
const snackbar = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('success')

const currentTheme = computed(() => shop.value?.theme || 'default')

const selectedSection = computed(() =>
  sections.value.find(s => s.id === selectedSectionId.value) ?? null,
)

async function fetchShop() {
  try {
    const res = await shopApi.getById(shopId.value)
    shop.value = res.data?.data ?? res.data
  } catch {
    shop.value = null
  }
}

async function fetchSections() {
  isLoading.value = true
  try {
    const res = await api.getAll()
    sections.value = res.data?.data ?? res.data ?? []
  } catch {
    sections.value = []
  } finally {
    isLoading.value = false
  }
}

async function changeTheme(newTheme) {
  if (newTheme === currentTheme.value) return
  isUpdatingTheme.value = true
  try {
    await shopApi.update(shopId.value, { theme: newTheme })
    shop.value = { ...shop.value, theme: newTheme }
    refreshPreview()
    snackbarMessage.value = t('shop.Theme updated to') + ' ' + newTheme
    snackbarColor.value = 'success'
    snackbar.value = true
  } catch {
    snackbarMessage.value = t('shop.Failed to update theme')
    snackbarColor.value = 'error'
    snackbar.value = true
  } finally {
    isUpdatingTheme.value = false
  }
}

async function addSection(type) {
  const config = SECTION_TYPES.find(s => s.type === type)
  if (!config) return

  try {
    const res = await api.create({
      type,
      title: config.title,
      content: getDefaultContent(type),
      settings: getDefaultSettings(type),
    })
    const newSection = res.data?.data ?? res.data
    sections.value.push(newSection)
    selectedSectionId.value = newSection.id
  } catch {
    snackbarMessage.value = 'Failed to add section'
    snackbarColor.value = 'error'
    snackbar.value = true
  }
}

async function updateSection(sectionId, data) {
  try {
    const res = await api.update(sectionId, data)
    const updated = res.data?.data ?? res.data
    const idx = sections.value.findIndex(s => s.id === sectionId)
    if (idx !== -1)
      sections.value[idx] = updated
    refreshPreview()
  } catch {
    snackbarMessage.value = 'Failed to update section'
    snackbarColor.value = 'error'
    snackbar.value = true
  }
}

async function deleteSection(sectionId) {
  try {
    await api.delete(sectionId)
    sections.value = sections.value.filter(s => s.id !== sectionId)
    if (selectedSectionId.value === sectionId)
      selectedSectionId.value = null
  } catch {
    snackbarMessage.value = 'Failed to delete section'
    snackbarColor.value = 'error'
    snackbar.value = true
  }
}

async function saveOrder() {
  try {
    const ids = sections.value.map(s => s.id)
    await api.reorder(ids)
  } catch {
    // silent
  }
}

function moveUp(index) {
  if (index === 0) return
  const arr = [...sections.value]
  ;[arr[index - 1], arr[index]] = [arr[index], arr[index - 1]]
  sections.value = arr
  saveOrder()
}

function moveDown(index) {
  if (index >= sections.value.length - 1) return
  const arr = [...sections.value]
  ;[arr[index], arr[index + 1]] = [arr[index + 1], arr[index]]
  sections.value = arr
  saveOrder()
}

function selectSection(id) {
  selectedSectionId.value = selectedSectionId.value === id ? null : id
}

function getSectionMeta(type) {
  return SECTION_TYPES.find(s => s.type === type) || { title: type, icon: 'tabler-box', color: 'secondary' }
}

function refreshPreview() {
  previewKey.value++
}

function goBack() {
  router.push({ name: `${rolePrefix.value}-shop` })
}

async function saveAll() {
  isSaving.value = true
  try {
    for (const section of sections.value) {
      await api.update(section.id, {
        content: section.content,
        settings: section.settings,
        title: section.title,
        is_active: section.is_active,
      })
    }
    refreshPreview()
    snackbarMessage.value = 'All changes saved!'
    snackbarColor.value = 'success'
    snackbar.value = true
  } catch {
    snackbarMessage.value = 'Failed to save'
    snackbarColor.value = 'error'
    snackbar.value = true
  } finally {
    isSaving.value = false
  }
}

function updateLocalSection(sectionId, data) {
  const idx = sections.value.findIndex(s => s.id === sectionId)
  if (idx !== -1)
    sections.value[idx] = { ...sections.value[idx], ...data }
}

onMounted(async () => {
  await Promise.all([fetchShop(), fetchSections()])
})
</script>

<template>
  <div class="shop-builder">
    <!-- Top Bar -->
    <div class="builder-topbar">
      <div class="d-flex align-center gap-3">
        <IconBtn @click="goBack">
          <VIcon icon="tabler-arrow-left" />
        </IconBtn>
        <div>
          <h6 class="text-h6 font-weight-bold">
            {{ t('shop.Shop Builder') }}
          </h6>
          <p class="text-caption text-medium-emphasis mb-0">
            {{ shop?.name ?? '...' }}
          </p>
        </div>
      </div>
      <div class="d-flex align-center gap-2">
        <VBtn
          v-if="shop?.slug"
          variant="tonal"
          prepend-icon="tabler-external-link"
          :href="`/store/${shop.slug}`"
          target="_blank"
        >
          {{ t('shop.Preview') }}
        </VBtn>
        <VBtn
          color="primary"
          prepend-icon="tabler-device-floppy"
          :loading="isSaving"
          @click="saveAll"
        >
          {{ t('shop.Save') }}
        </VBtn>
      </div>
    </div>

    <div class="builder-body">
      <!-- LEFT: Sidebar -->
      <aside class="builder-sidebar">
        <!-- Theme Selector -->
        <div class="sidebar-section">
          <h6 class="text-subtitle-2 font-weight-bold mb-2">
            <VIcon icon="tabler-palette" size="16" class="me-1" />
            {{ t('shop.Theme') }}
          </h6>
          <div class="theme-grid">
            <div
              v-for="th in themeOptions"
              :key="th.value"
              class="theme-card"
              :class="{ active: currentTheme === th.value }"
              @click="changeTheme(th.value)"
            >
              <div
                class="theme-preview"
                :class="`theme-${th.value}`"
              />
              <span class="text-caption">{{ th.title }}</span>
              <VIcon
                v-if="currentTheme === th.value"
                icon="tabler-check"
                size="14"
                color="primary"
                class="theme-check"
              />
            </div>
          </div>
          <VProgressLinear
            v-if="isUpdatingTheme"
            indeterminate
            color="primary"
            class="mt-2"
          />
        </div>

        <VDivider />

        <!-- Section Types -->
        <div class="sidebar-section">
          <h6 class="text-subtitle-2 font-weight-bold mb-2">
            <VIcon icon="tabler-layout-grid-add" size="16" class="me-1" />
            {{ t('shop.Add Section') }}
          </h6>
        </div>
        <PerfectScrollbar :options="{ wheelPropagation: false }" style="flex:1;">
          <div class="section-types-grid">
            <div
              v-for="sec in SECTION_TYPES"
              :key="sec.type"
              class="section-type-card"
              @click="addSection(sec.type)"
            >
              <VIcon :icon="sec.icon" :color="sec.color" size="22" />
              <span class="text-caption font-weight-medium">{{ sec.title }}</span>
            </div>
          </div>
        </PerfectScrollbar>
      </aside>

      <!-- CENTER: Preview + Sections -->
      <main class="builder-canvas">
        <div class="canvas-tabs d-flex align-center mb-3">
          <VBtn
            variant="text"
            :class="{ 'text-primary': activeTab === 'preview' }"
            prepend-icon="tabler-eye"
            @click="activeTab = 'preview'"
          >
            {{ t('shop.Preview') }}
          </VBtn>
          <VBtn
            variant="text"
            :class="{ 'text-primary': activeTab === 'sections' }"
            prepend-icon="tabler-stack"
            @click="activeTab = 'sections'"
          >
            {{ t('shop.Sections') }}
            <VBadge
              v-if="sections.length"
              :content="sections.length"
              color="primary"
              inline
              class="ms-1"
            />
          </VBtn>
        </div>

        <template v-if="activeTab === 'preview'">
          <div class="preview-wrapper">
            <div class="preview-toolbar d-flex align-center justify-space-between mb-2">
              <div class="d-flex align-center gap-2">
                <VChip size="small" variant="tonal" color="primary">
                  <VIcon icon="tabler-palette" size="14" start />
                  {{ currentTheme }}
                </VChip>
                <span class="text-caption text-medium-emphasis">/store/{{ shop?.slug }}</span>
              </div>
              <VBtn size="small" variant="tonal" prepend-icon="tabler-refresh" @click="refreshPreview">
                {{ t('shop.Refresh') }}
              </VBtn>
            </div>
            <iframe
              v-if="shop?.slug"
              :key="previewKey"
              :src="`/store/${shop.slug}`"
              class="preview-iframe"
              sandbox="allow-same-origin allow-scripts"
            />
            <div v-else class="d-flex justify-center align-center" style="height: 400px;">
              <VProgressCircular indeterminate color="primary" />
            </div>
          </div>
        </template>

        <template v-else>
          <div v-if="isLoading" class="d-flex justify-center align-center" style="min-height: 300px;">
            <VProgressCircular indeterminate color="primary" />
          </div>

          <div v-else-if="sections.length === 0" class="empty-state">
            <VIcon icon="tabler-layout-grid-add" size="64" color="grey-lighten-1" />
            <h5 class="text-h6 mt-4 text-grey">
              {{ t('shop.No Sections Yet') }}
            </h5>
            <p class="text-body-2 text-grey">
              {{ t('shop.Click a section from the left panel to add it') }}
            </p>
          </div>

          <div v-else class="sections-list">
            <div
              v-for="(section, index) in sections"
              :key="section.id"
              class="section-wrapper"
              :class="{ active: selectedSectionId === section.id }"
              @click="selectSection(section.id)"
            >
              <div class="section-handle-bar d-flex align-center justify-space-between pa-2">
                <div class="d-flex align-center gap-2">
                  <VChip :color="getSectionMeta(section.type).color" size="x-small" label>
                    <VIcon :icon="getSectionMeta(section.type).icon" size="14" start />
                    {{ getSectionMeta(section.type).title }}
                  </VChip>
                  <span class="text-caption text-medium-emphasis">
                    {{ section.title || '' }}
                  </span>
                </div>
                <div class="d-flex align-center gap-1">
                  <IconBtn size="small" :disabled="index === 0" @click.stop="moveUp(index)">
                    <VIcon icon="tabler-arrow-up" size="16" />
                  </IconBtn>
                  <IconBtn size="small" :disabled="index === sections.length - 1" @click.stop="moveDown(index)">
                    <VIcon icon="tabler-arrow-down" size="16" />
                  </IconBtn>
                  <VSwitch
                    :model-value="section.is_active"
                    density="compact"
                    color="success"
                    hide-details
                    @update:model-value="val => updateSection(section.id, { is_active: val })"
                    @click.stop
                  />
                  <IconBtn size="small" @click.stop="deleteSection(section.id)">
                    <VIcon icon="tabler-trash" size="16" />
                  </IconBtn>
                </div>
              </div>
              <SectionPreview :section="section" :shop="shop" />
            </div>
          </div>
        </template>
      </main>

      <!-- RIGHT: Section Editor -->
      <aside class="builder-editor" :class="{ open: selectedSection }">
        <div v-if="selectedSection">
          <SectionEditor
            :section="selectedSection"
            :shop="shop"
            @update="data => updateLocalSection(selectedSection.id, data)"
            @save="data => updateSection(selectedSection.id, data)"
            @close="selectedSectionId = null"
          />
        </div>
        <div v-else class="editor-empty d-flex align-center justify-center" style="height: 100%;">
          <p class="text-body-2 text-grey text-center pa-4">
            {{ t('shop.Select a section to edit') }}
          </p>
        </div>
      </aside>
    </div>

    <VSnackbar v-model="snackbar" :color="snackbarColor" location="top" timeout="3000">
      {{ snackbarMessage }}
    </VSnackbar>
  </div>
</template>

<style scoped>
.shop-builder {
  height: 100vh;
  display: flex;
  flex-direction: column;
  background: #f5f5f5;
}

.builder-topbar {
  background: #fff;
  padding: 0.75rem 1.5rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-bottom: 1px solid #e0e0e0;
  flex-shrink: 0;
}

.builder-body {
  display: flex;
  flex: 1;
  overflow: hidden;
}

.builder-sidebar {
  width: 260px;
  background: #fff;
  border-right: 1px solid #e0e0e0;
  display: flex;
  flex-direction: column;
  flex-shrink: 0;
}

.sidebar-section {
  padding: 12px;
}

.sidebar-section h6 {
  display: flex;
  align-items: center;
}

.theme-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 6px;
}

.theme-card {
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 4px;
  padding: 6px;
  border-radius: 8px;
  border: 2px solid #e8e8e8;
  cursor: pointer;
  transition: all 0.15s;
}

.theme-card:hover {
  border-color: #90caf9;
}

.theme-card.active {
  border-color: #1976d2;
  background: #e3f2fd;
}

.theme-check {
  position: absolute;
  top: 2px;
  right: 2px;
}

.theme-preview {
  width: 100%;
  height: 28px;
  border-radius: 4px;
  overflow: hidden;
}

.theme-default { background: linear-gradient(135deg, #1976d2, #fff); }
.theme-modern { background: linear-gradient(135deg, #7c4dff, #448aff); }
.theme-minimal { background: #f5f5f5; border: 1px solid #e0e0e0; }
.theme-dark { background: linear-gradient(135deg, #212121, #424242); }
.theme-light { background: linear-gradient(135deg, #f5f5f5, #e3f2fd); }
.theme-elegant { background: linear-gradient(135deg, #1a1a1a, #c9a96e); }

.section-types-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 8px;
  padding: 0 12px 12px;
}

.section-type-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 6px;
  padding: 12px 8px;
  border-radius: 8px;
  border: 1px solid #e8e8e8;
  cursor: pointer;
  transition: all 0.15s;
  text-align: center;
}

.section-type-card:hover {
  border-color: #1976d2;
  background: #e3f2fd;
}

.builder-canvas {
  flex: 1;
  overflow-y: auto;
  padding: 20px;
}

.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 400px;
  text-align: center;
}

.preview-wrapper {
  background: #fff;
  border-radius: 8px;
  border: 1px solid #e0e0e0;
  overflow: hidden;
  padding: 12px;
}

.preview-iframe {
  width: 100%;
  height: calc(100vh - 200px);
  border: 1px solid #e8e8e8;
  border-radius: 4px;
}

.sections-list {
  max-width: 800px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.section-wrapper {
  background: #fff;
  border-radius: 8px;
  border: 2px solid transparent;
  overflow: hidden;
  cursor: pointer;
  transition: border-color 0.15s;
}

.section-wrapper:hover {
  border-color: #bdbdbd;
}

.section-wrapper.active {
  border-color: #1976d2;
}

.section-wrapper .section-handle-bar {
  background: #fafafa;
  border-bottom: 1px solid #f0f0f0;
}

.builder-editor {
  width: 360px;
  background: #fff;
  border-left: 1px solid #e0e0e0;
  flex-shrink: 0;
  overflow-y: auto;
}
</style>
