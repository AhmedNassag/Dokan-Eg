import { createRoutesFromConfig } from './Modules/shared/routeConfig'

export const merchantRoutes = [
  {
    path: '/merchant',
    name: 'merchant',
    redirect: { name: 'merchant-dashboard' },
    meta: {
      title: 'Merchant',
    },
    children: createRoutesFromConfig('merchant', ['dashboard', 'category', 'country', 'city', 'area', 'branch', 'shipping-company', 'language', 'translation', 'brand', 'unit', 'user', 'role', 'permission']),
  },
]
