import { createRoutesFromConfig } from './Modules/shared/routeConfig'

export const merchantRoutes = [
  {
    path: '/merchant',
    name: 'merchant',
    redirect: { name: 'merchant-dashboard' },
    meta: {
      title: 'Merchant',
    },
    children: createRoutesFromConfig('merchant', [
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
