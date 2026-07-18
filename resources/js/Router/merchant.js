import { createRoutesFromConfig } from './Modules/shared/routeConfig'

export const merchantRoutes = [
  {
    path: '/merchant',
    name: 'merchant',
    redirect: { name: 'merchant-dashboard' },
    meta: {
      title: 'Merchant',
    },
    children: [
      ...createRoutesFromConfig('merchant', [
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
        'shop',
        'order',
      ]),
      {
        path: 'shop/create',
        name: 'merchant-shop-create',
        component: () => import('@/views/pages/shared/Shop/CreateShop.vue'),
        meta: {
          title: 'Create Shop',
          action: 'store',
          subject: 'shop',
        },
      },
      {
        path: 'shop/:shopId/builder',
        name: 'merchant-shop-builder',
        component: () => import('@/views/pages/shared/Shop/ShopBuilder.vue'),
        meta: {
          title: 'Shop Builder',
          action: 'update',
          subject: 'shop',
          layout: 'blank',
        },
      },
    ],
  },
]
