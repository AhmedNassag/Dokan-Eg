export function createPermissionRoutes(role: string) {
  const PermissionList = () => import('@/views/pages/Admin/Permission/Permission.vue')
  return [
    {
      path: 'permission',
      name: `${role}-permission`,
      component: PermissionList,
      meta: {
        title: 'Permissions',
        action: 'list',
        subject: 'permission',
      },
    },
  ]
}
