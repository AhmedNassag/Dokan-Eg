import marketerCategoryRoutes from './Modules/Marketer/Category/category'
import marketerDashboardRoutes from './Modules/Marketer/Dashboard/dashboard'
import marketerPermissionRoutes from './Modules/Marketer/Permission/permission'
import marketerRoleRoutes from './Modules/Marketer/Role/role'
import marketerUserRoutes from './Modules/Marketer/User/user'

export const marketerRoutes = [
  {
    path: '/marketer',
    name: 'marketer',
    redirect: { name: 'marketer-dashboard' },
    meta: {
      title: 'Marketer',
    },
    children: [
      ...marketerDashboardRoutes,
      ...marketerCategoryRoutes,
      ...marketerPermissionRoutes,
      ...marketerRoleRoutes,
      ...marketerUserRoutes,
    ],
  },
]
