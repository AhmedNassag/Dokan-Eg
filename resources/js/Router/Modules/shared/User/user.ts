export function createUserRoutes(role: string) {
  const UserList = () => import('@/views/pages/Admin/User/User.vue')
  return [
    {
      path: 'user',
      name: `${role}-user`,
      component: UserList,
      meta: {
        title: 'Users',
        action: 'list',
        subject: 'user',
      },
    },
  ]
}
