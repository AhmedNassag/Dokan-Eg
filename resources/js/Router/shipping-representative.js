import { createRoutesFromConfig } from './Modules/shared/routeConfig'

export const shippingRepresentativeRoutes = [
  {
    path: '/shipping-representative',
    name: 'shipping-representative',
    redirect: { name: 'shipping-representative-dashboard' },
    meta: {
      title: 'Shipping Representative',
    },
    children: createRoutesFromConfig('shipping-representative', ['dashboard', 'order']),
  },
]
