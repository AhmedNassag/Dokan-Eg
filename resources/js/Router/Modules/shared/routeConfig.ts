// Shared route and navigation configuration
export interface RouteConfig {
  title: string
  path: string
  namePrefix: string
  component: () => any
  icon?: { icon: string }
  action?: string
  subject?: string
}

export const routeConfigMap: Record<string, RouteConfig> = {
  dashboard: {
    title: 'Dashboard',
    path: 'dashboard',
    namePrefix: 'dashboard',
    component: null, // Will be set per role
    icon: { icon: 'tabler-smart-home' },
    action: 'read',
    subject: 'dashboard',
  },
  category: {
    title: 'Categories',
    path: 'category',
    namePrefix: 'category',
    component: () => import('@/views/pages/shared/Category/Category.vue'),
    icon: { icon: 'tabler-category' },
    action: 'list',
    subject: 'category',
  },
  user: {
    title: 'Users',
    path: 'user',
    namePrefix: 'user',
    component: () => import('@/views/pages/Admin/User/User.vue'),
    icon: { icon: 'tabler-users' },
    action: 'list',
    subject: 'user',
  },
  role: {
    title: 'Roles',
    path: 'role',
    namePrefix: 'role',
    component: () => import('@/views/pages/Admin/Role/Role.vue'),
    icon: { icon: 'tabler-shield' },
    action: 'list',
    subject: 'role',
  },
  permission: {
    title: 'Permissions',
    path: 'permission',
    namePrefix: 'permission',
    component: () => import('@/views/pages/Admin/Permission/Permission.vue'),
    icon: { icon: 'tabler-lock' },
    action: 'list',
    subject: 'permission',
  },
  order: {
    title: 'Orders',
    path: 'order',
    namePrefix: 'order',
    component: () => import('@/views/pages/shared/Order/Order.vue'),
    icon: { icon: 'tabler-shopping-cart' },
    action: 'list',
    subject: 'order',
  },
}

// Dashboard components per role
export const dashboardComponents = {
  admin: () => import('@/views/pages/Admin/Dashboard/Dashboard.vue'),
  marketer: () => import('@/views/pages/Marketer/Dashboard/Dashboard.vue'),
  merchant: () => import('@/views/pages/Merchant/Dashboard/Dashboard.vue'),
}

// Function to create routes from config
export function createRoutesFromConfig(
  role: string,
  sections: string[] = ['dashboard', 'category', 'user', 'role', 'permission', 'order']
) {
  return sections.map(key => {
    const config = routeConfigMap[key]
    if (!config) return null

    let component = config.component
    if (key === 'dashboard') {
      component = dashboardComponents[role]
    }

    return {
      path: config.path,
      name: `${role}-${config.namePrefix}`,
      component,
      meta: {
        title: config.title,
        action: config.action,
        subject: config.subject,
      },
    }
  }).filter(Boolean)
}

// Function to create navigation items from config (same as createNavItems)
export function createNavItemsFromConfig(
  role: string,
  sections: string[] = ['dashboard', 'category', 'user', 'role', 'permission', 'order']
) {
  const items = [
    { heading: `${role.charAt(0).toUpperCase() + role.slice(1)} Panel` },
  ]

  sections.forEach(key => {
    const config = routeConfigMap[key]
    if (config) {
      items.push({
        title: config.title,
        icon: config.icon,
        to: `${role}-${config.namePrefix}`,
        action: config.action,
        subject: config.subject,
      })
    }
  })

  return items
}
