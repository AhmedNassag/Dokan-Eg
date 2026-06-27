import merchantCategoryRoutes from './Modules/Merchant/Category/category'
import merchantDashboardRoutes from './Modules/Merchant/Dashboard/dashboard'
import merchantPermissionRoutes from './Modules/Merchant/Permission/permission'
import merchantRoleRoutes from './Modules/Merchant/Role/role'
import merchantUserRoutes from './Modules/Merchant/User/user'

export const merchantRoutes = [
  {
    path: '/merchant',
    name: 'merchant',
    redirect: { name: 'merchant-dashboard' },
    meta: {
      title: 'Merchant',
    },
    children: [
      ...merchantDashboardRoutes,
      ...merchantCategoryRoutes,
      ...merchantPermissionRoutes,
      ...merchantRoleRoutes,
      ...merchantUserRoutes,
    ],
  },
]
