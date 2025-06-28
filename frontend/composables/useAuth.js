export const useAuth = () => {
  const getToken = () => {
    if (process.client) {
      return localStorage.getItem('auth_token')
    }
    return null
  }

  const getUser = () => {
    if (process.client) {
      const userStr = localStorage.getItem('user')
      return userStr ? JSON.parse(userStr) : null
    }
    return null
  }

  const setToken = (token) => {
    if (process.client) {
      localStorage.setItem('auth_token', token)
    }
  }

  const setUser = (user) => {
    if (process.client) {
      localStorage.setItem('user', JSON.stringify(user))
    }
  }

  const logout = () => {
    if (process.client) {
      localStorage.removeItem('auth_token')
      localStorage.removeItem('user')
    }
  }

  const isAuthenticated = () => {
    return !!getToken()
  }

  const getAuthHeaders = () => {
    const token = getToken()
    return {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      ...(token && { 'Authorization': `Bearer ${token}` })
    }
  }

  return {
    getToken,
    getUser,
    setToken,
    setUser,
    logout,
    isAuthenticated,
    getAuthHeaders
  }
} 