const MerchantDashboard = () => import('@/views/pages/Merchant/Dashboard/Dashboard.vue')

const merchantDashboardRoutes = [
  {
    name: 'merchant-dashboard',
    path: 'dashboard',
    component: MerchantDashboard,
    meta: {
      title: 'Dashboard',
    },
  },
]

export default merchantDashboardRoutes
