import { adminRoutes } from './admin'
import { merchantRoutes } from './merchant'
import { marketerRoutes } from './marketer'

export const roleBasedRoutes = [
  ...adminRoutes,
  ...merchantRoutes,
  ...marketerRoutes,
]
