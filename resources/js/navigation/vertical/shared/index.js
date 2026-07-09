import { createNavItemsFromConfig } from '@/Router/Modules/shared/routeConfig'

export function createNavItems(role, sections = [
  'dashboard',
  'user',
  'role',
  'permission',
  'category',
  'country',
  'city',
  'area',
  'branch',
  'shippingCompany',
  'language',
  'translation',
  'order',
]) {
  return createNavItemsFromConfig(role, sections)
}
