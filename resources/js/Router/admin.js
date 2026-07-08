import { createRoutesFromConfig } from './Modules/shared/routeConfig'

export const adminRoutes = [
  {
    path: '/admin',
    name: 'admin',
    redirect: { name: 'admin-dashboard' },
    meta: {
      title: 'Admin',
    },
    children: createRoutesFromConfig('admin', ['dashboard', 'category', 'country', 'city', 'area', 'branch', 'shipping-company', 'language', 'translation', 'brand', 'unit', 'user', 'role', 'permission', 'order']),
  },
]
