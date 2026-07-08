import { createRoutesFromConfig } from './Modules/shared/routeConfig'

// مثال: ل Marketer، نضع فقط dashboard و user (بدون category)
export const marketerRoutes = [
  {
    path: '/marketer',
    name: 'marketer',
    redirect: { name: 'marketer-dashboard' },
    meta: {
      title: 'Marketer',
    },
    children: createRoutesFromConfig('marketer', ['dashboard', 'country', 'city', 'area', 'branch', 'shipping-company', 'language', 'translation', 'brand', 'unit', 'user', 'role', 'permission', 'order']),
  },
]
