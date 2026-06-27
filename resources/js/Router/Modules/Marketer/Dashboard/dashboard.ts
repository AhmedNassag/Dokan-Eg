const MarketerDashboard = () => import('@/views/pages/Marketer/Dashboard/Dashboard.vue')

const marketerDashboardRoutes = [
  {
    name: 'marketer-dashboard',
    path: 'dashboard',
    component: MarketerDashboard,
    meta: {
      title: 'Dashboard',
    },
  },
]

export default marketerDashboardRoutes
