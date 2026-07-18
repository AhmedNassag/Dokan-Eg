import { createRoutesFromConfig } from './Modules/shared/routeConfig'

export const adminRoutes = [
  {
    path: '/admin',
    name: 'admin',
    redirect: { name: 'admin-dashboard' },
    meta: {
      title: 'Admin',
    },
    children: [
      ...createRoutesFromConfig('admin', [
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
        name: 'admin-shop-create',
        component: () => import('@/views/pages/shared/Shop/CreateShop.vue'),
        meta: {
          title: 'Create Shop',
          action: 'store',
          subject: 'shop',
        },
      },
      {
        path: 'shop/:shopId/builder',
        name: 'admin-shop-builder',
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
