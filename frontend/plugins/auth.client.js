export default defineNuxtPlugin(() => {
  // Add authentication helper to the app
  const auth = {
    getToken: () => {
      if (process.client) {
        return localStorage.getItem('auth_token')
      }
      return null
    },
    getUser: () => {
      if (process.client) {
        const userStr = localStorage.getItem('user')
        return userStr ? JSON.parse(userStr) : null
      }
      return null
    },
    setToken: (token) => {
      if (process.client) {
        localStorage.setItem('auth_token', token)
      }
    },
    setUser: (user) => {
      if (process.client) {
        localStorage.setItem('user', JSON.stringify(user))
      }
    },
    logout: () => {
      if (process.client) {
        localStorage.removeItem('auth_token')
        localStorage.removeItem('user')
        navigateTo('/login')
      }
    },
    isAuthenticated: () => {
      return !!auth.getToken()
    },
    getAuthHeaders: () => {
      const token = auth.getToken()
      return {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        ...(token && { 'Authorization': `Bearer ${token}` })
      }
    }
  }

  return {
    provide: {
      auth
    }
  }
}) 