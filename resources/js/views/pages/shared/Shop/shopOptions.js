// Mirrors App\Enums\ShopTheme and App\Enums\ShopFontFamily
export const themeOptions = [
  { value: 'default', title: 'Default' },
  { value: 'modern', title: 'Modern' },
  { value: 'minimal', title: 'Minimal' },
  { value: 'dark', title: 'Dark' },
  { value: 'light', title: 'Light' },
  { value: 'elegant', title: 'Elegant' },
]

export const fontOptions = [
  { value: 'Cairo', title: 'Cairo' },
  { value: 'Tajawal', title: 'Tajawal' },
  { value: 'Almarai', title: 'Almarai' },
  { value: 'Noto Kufi Arabic', title: 'Noto Kufi Arabic' },
  { value: 'Inter', title: 'Inter' },
  { value: 'Roboto', title: 'Roboto' },
]

export function slugify(text) {
  return String(text ?? '')
    .toString()
    .normalize('NFKD')
    .toLowerCase()
    .trim()
    .replace(/[\u0621-\u064A]+/g, m => m) // keep arabic letters
    .replace(/\s+/g, '-')
    .replace(/[^\w\u0621-\u064A-]+/g, '')
    .replace(/-{2,}/g, '-')
    .replace(/^-+|-+$/g, '')
}
