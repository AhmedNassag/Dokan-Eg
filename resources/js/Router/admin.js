import adminCategoryRoutes from './Modules/Admin/Category/category'
import adminDashboardRoutes from './Modules/Admin/Dashboard/dashboard'
import adminRoleRoutes from './Modules/Admin/Role/role'
import adminPermissionRoutes from './Modules/Admin/Permission/permission'
import adminUserRoutes from './Modules/Admin/User/user'

export const adminRoutes = [
  {
    path: '/admin',
    name: 'admin',
    redirect: { name: 'admin-dashboard' },
    meta: {
      title: 'Admin',
    },
    children: [
      ...adminDashboardRoutes,
      ...adminCategoryRoutes,
      ...adminRoleRoutes,
      ...adminPermissionRoutes,
      ...adminUserRoutes,
    ],
  },
]
