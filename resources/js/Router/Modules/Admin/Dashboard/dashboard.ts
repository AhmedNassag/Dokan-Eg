const AdminDashboard = () => import('@/views/pages/Admin/Dashboard/Dashboard.vue')

const adminDashboardRoutes = [
  {
    name: 'admin-dashboard',
    path: 'dashboard',
    component: AdminDashboard,
    meta: {
      title: 'Dashboard',
    },
  },
]

export default adminDashboardRoutes
