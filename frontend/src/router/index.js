import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import { useAuthStore } from '../stores/authStore'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  // Configurar comportamiento de scroll optimizado
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { top: 0, behavior: 'smooth' }
    }
  },
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/products',
      name: 'products',
      component: () => import('../views/ProductsView.vue'),
    },
    {
      path: '/product/:slug',
      name: 'product-detail',
      component: () => import('../views/ProductDetailView.vue'),
    },
    {
      path: '/category/:slug',
      name: 'category',
      component: () => import('../views/CategoryView.vue'),
    },
    {
      path: '/cart',
      name: 'cart',
      component: () => import('../views/CartView.vue'),
    },
    {
      path: '/checkout',
      name: 'checkout',
      component: () => import('../views/CheckoutView.vue'),
    },
    {
      path: '/contact',
      name: 'contact',
      component: () => import('../views/ContactView.vue'),
    },
    {
      path: '/faq',
      name: 'faq',
      component: () => import('../views/FAQView.vue'),
    },
    {
      path: '/tracking',
      name: 'tracking',
      component: () => import('../views/TrackingView.vue'),
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('../views/AboutView.vue'),
    },
    {
      path: '/terms',
      name: 'terms',
      component: () => import('../views/TermsView.vue'),
    },
    {
      path: '/privacy',
      name: 'privacy',
      component: () => import('../views/PrivacyView.vue'),
    },
    {
      path: '/returns',
      name: 'returns',
      component: () => import('../views/ReturnsView.vue'),
    },
    {
      path: '/shipping',
      name: 'shipping',
      component: () => import('../views/ShippingView.vue'),
    },
    {
      path: '/unsubscribe',
      name: 'unsubscribe',
      component: () => import('../views/UnsubscribeView.vue'),
    },
    // Auth routes
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginView.vue'),
      meta: { guest: true }
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('../views/RegisterView.vue'),
      meta: { guest: true }
    },
    {
      path: '/forgot-password',
      name: 'forgot-password',
      component: () => import('../views/ForgotPasswordView.vue'),
      meta: { guest: true }
    },
    {
      path: '/reset-password',
      name: 'reset-password',
      component: () => import('../views/ResetPasswordView.vue'),
      meta: { guest: true }
    },
    // Protected routes
    {
      path: '/account',
      name: 'account',
      component: () => import('../views/AccountView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/wishlist',
      name: 'wishlist',
      component: () => import('../views/WishlistView.vue'),
      meta: { requiresAuth: true }
    },
    // Admin routes
    {
      path: '/admin',
      name: 'admin',
      component: () => import('../views/admin/AdminDashboardView.vue'),
      meta: { requiresAuth: true, requiresManager: true }
    },
    {
      path: '/admin/products',
      name: 'admin-products',
      component: () => import('../views/admin/AdminProductsView.vue'),
      meta: { requiresAuth: true, requiresManager: true }
    },
    {
      path: '/admin/categories',
      name: 'admin-categories',
      component: () => import('../views/admin/AdminCategoriesView.vue'),
      meta: { requiresAuth: true, requiresManager: true }
    },
    {
      path: '/admin/orders',
      name: 'admin-orders',
      component: () => import('../views/admin/AdminOrdersView.vue'),
      meta: { requiresAuth: true, requiresManager: true }
    },
    {
      path: '/admin/coupons',
      name: 'admin-coupons',
      component: () => import('../views/admin/AdminCouponsView.vue'),
      meta: { requiresAuth: true, requiresManager: true }
    },
    {
      path: '/admin/shipments',
      name: 'admin-shipments',
      component: () => import('../views/admin/AdminShipmentsView.vue'),
      meta: { requiresAuth: true, requiresManager: true }
    },
    {
      path: '/admin/users',
      name: 'admin-users',
      component: () => import('../views/admin/AdminUsersView.vue'),
      meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
      path: '/admin/loyalty',
      name: 'admin-loyalty',
      component: () => import('../views/admin/AdminLoyaltyView.vue'),
      meta: { requiresAuth: true, requiresManager: true }
    },
    {
      path: '/admin/questions',
      name: 'admin-questions',
      component: () => import('../views/admin/AdminQuestionsView.vue'),
      meta: { requiresAuth: true, requiresManager: true }
    },
    {
      path: '/admin/shipping-config',
      name: 'admin-shipping-config',
      component: () => import('../views/admin/AdminShippingConfigView.vue'),
      meta: { requiresAuth: true, requiresManager: true }
    },
    {
      path: '/admin/site-config',
      name: 'admin-site-config',
      component: () => import('../views/admin/AdminSiteConfigView.vue'),
      meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
      path: '/loyalty',
      name: 'loyalty',
      component: () => import('../views/LoyaltyView.vue'),
      meta: { requiresAuth: true }
    },
  ]
})

// Navigation guard para rutas protegidas
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()

  // Si la ruta requiere autenticación
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!authStore.isAuthenticated) {
      // Redirigir a login con redirect query
      next({
        path: '/login',
        query: { redirect: to.fullPath }
      })
      return
    }

    // Verificar si requiere permisos de admin
    if (to.matched.some(record => record.meta.requiresAdmin)) {
      if (!authStore.isAdmin) {
        next('/') // Redirigir a home si no es admin
        return
      }
    }

    // Verificar si requiere permisos de manager (manager o admin)
    if (to.matched.some(record => record.meta.requiresManager)) {
      if (!authStore.hasManagerAccess) {
        next('/') // Redirigir a home si no tiene acceso de manager
        return
      }
    }

    next()
  }
  // Si la ruta es solo para invitados (login/register)
  else if (to.matched.some(record => record.meta.guest)) {
    if (authStore.isAuthenticated) {
      // Si ya está autenticado, redirigir a account
      next('/account')
    } else {
      next()
    }
  }
  // Otras rutas
  else {
    next()
  }
})

export default router
