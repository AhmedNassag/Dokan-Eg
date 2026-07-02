import { createNavItemsFromConfig } from '@/Router/Modules/shared/routeConfig'

export function createNavItems(role, sections = ['dashboard', 'category', 'user', 'role', 'permission', 'order']) {
  return createNavItemsFromConfig(role, sections)
}
