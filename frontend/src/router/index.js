import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import { useAuthStore } from '../stores/authStore'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
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
      path: '/about',
      name: 'about',
      component: () => import('../views/AboutView.vue'),
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
    // Protected routes
    {
      path: '/account',
      name: 'account',
      component: () => import('../views/AccountView.vue'),
      meta: { requiresAuth: true }
    },
  ],
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { top: 0 }
    }
  }
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
    } else {
      next()
    }
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
