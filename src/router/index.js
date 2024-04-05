import { createRouter, createWebHistory } from 'vue-router';
import LoginView from '../views/login.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: LoginView
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('../views/Register.vue')
    },
    {
      path: '/user',
      name: 'user',
      component: () => import('../views/user/user.vue'),
      meta: { requiresAuth: true } 
    },
    {
      path: '/myProgress',
      name: 'myProgress',
      component: () => import('../views/user/MyProgress.vue'),
      meta: { requiresAuth: true } 
    },
    {
      path: '/History',
      name: 'History',
      component: () => import('../views/user/History.vue'),
      meta: { requiresAuth: true } 
    },
    {
      path: '/logout',
      name: 'logout',
      meta: { requiresAuth: true }, 
      beforeEnter: (to, from, next) => {
        localStorage.removeItem('token'); 
        next({ name: 'login' });
      }
    }
  ]
});

router.beforeEach((to, from, next) => {
  const isAuthenticated = localStorage.getItem('token'); 
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth); 

  if (requiresAuth && !isAuthenticated) {
    next({ name: 'login' });
  } else if ((to.name === 'login' || to.name === 'register') && isAuthenticated) {
    next({ name: 'user' }); 
  } else {
    next(); 
  }
});



export default router;
