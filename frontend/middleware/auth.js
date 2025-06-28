export default defineNuxtRouteMiddleware((to, from) => {
  // Only run on client side
  if (process.server) return

  const token = localStorage.getItem('auth_token')
  
  // If no token and trying to access protected route, redirect to login
  if (!token && to.path !== '/login' && to.path !== '/register') {
    return navigateTo('/login')
  }
  
  // If has token and trying to access login/register, redirect to dashboard
  if (token && (to.path === '/login' || to.path === '/register')) {
    return navigateTo('/dashboard')
  }
}) 