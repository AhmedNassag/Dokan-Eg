import { adminRoutes } from './admin'
import { merchantRoutes } from './merchant'
import { marketerRoutes } from './marketer'
import { shippingRepresentativeRoutes } from './shipping-representative'

export const roleBasedRoutes = [
  ...adminRoutes,
  ...merchantRoutes,
  ...marketerRoutes,
  ...shippingRepresentativeRoutes,
]
