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
    children: createRoutesFromConfig('marketer', [
      'dashboard',
      'user',
      'role',
      'permission',
      'language',
      'translation',
      'category',
      'country',
      'city',
      'area',
      'branch',
      'shippingCompany',
      'order',
    ]),
  },
]
