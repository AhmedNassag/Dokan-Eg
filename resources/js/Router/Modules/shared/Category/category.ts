export function createCategoryRoutes(role: string) {
  const CategoryList = () => import('@/views/pages/shared/Category/Category.vue')
  return [
    {
      path: 'category',
      name: `${role}-category`,
      component: CategoryList,
      meta: {
        title: 'Category',
        action: 'list',
        subject: 'category',
      },
    },
  ]
}
