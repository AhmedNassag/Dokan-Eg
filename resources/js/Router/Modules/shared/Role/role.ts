export function createRoleRoutes(role: string) {
  const RoleList = () => import('@/views/pages/Admin/Role/Role.vue')
  return [
    {
      path: 'role',
      name: `${role}-role`,
      component: RoleList,
      meta: {
        title: 'Roles',
        action: 'list',
        subject: 'role',
      },
    },
  ]
}
