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
    children: [
      ...createRoutesFromConfig('marketer', [
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
        name: 'marketer-shop-create',
        component: () => import('@/views/pages/shared/Shop/CreateShop.vue'),
        meta: {
          title: 'Create Shop',
          action: 'store',
          subject: 'shop',
        },
      },
      {
        path: 'shop/:shopId/builder',
        name: 'marketer-shop-builder',
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
