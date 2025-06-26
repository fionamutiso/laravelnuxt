<template>
  <div :class="[theme === 'dark' ? 'dark bg-gray-900' : 'bg-gradient-to-br from-pink-100 to-rose-200', 'min-h-screen p-6']">
    <!-- Top Bar -->
    <div class="flex items-center justify-between mb-8">
      <h1 class="text-2xl font-semibold text-rose-700 animate-pulse">
        🎀 Welcome, {{ user.name }}
      </h1>
      <div class="flex gap-2 items-center">
        <button @click="toggleTheme" class="bg-gray-200 hover:bg-gray-300 text-pink-600 px-4 py-2 rounded-full transition-all shadow-lg">
          {{ theme === 'dark' ? '🌙 Dark' : '☀️ Light' }}
        </button>
        <button
          @click="logout"
          class="bg-rose-500 hover:bg-rose-600 text-white px-4 py-2 rounded-full transition-all shadow-lg"
        >
          Logout
        </button>
      </div>
    </div>

    <!-- Main Body -->
    <div class="flex flex-col items-center justify-center gap-8">
      <!-- Weather Widget -->
      <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-lg w-full max-w-xs flex flex-col items-center mb-4">
        <div class="flex items-center gap-2 mb-2">
          <span class="text-2xl">🌤️</span>
          <span class="text-lg font-semibold text-pink-600 dark:text-pink-200">Weather</span>
        </div>
        <div v-if="weather">
          <div class="text-xl font-bold mb-1">{{ weather.city }}</div>
          <div class="text-2xl mb-1">{{ weather.temp }}°C</div>
          <div class="text-sm text-gray-500 dark:text-gray-300">{{ weather.description }}</div>
        </div>
        <div v-else class="text-gray-400">Loading weather...</div>
      </div>
      <div
        class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-xl w-full max-w-sm text-center hover:scale-105 transition-transform duration-300"
      >
        <p class="text-rose-600 dark:text-pink-200 text-lg mb-4 font-medium">✨ Ready to be productive?</p>
        <div class="flex flex-col gap-4">
          <NuxtLink
            :to="{ path: '/tasks', query: { fromDashboard: 'true' } }"
            class="inline-flex items-center bg-rose-300 dark:bg-pink-700 text-white px-6 py-3 rounded-full shadow hover:bg-rose-400 dark:hover:bg-pink-600 transition duration-200 justify-center"
          >
            <span class="text-xl mr-2">📝</span>
            <span class="font-semibold">Go to Tasks</span>
          </NuxtLink>
          <NuxtLink
            to="/profilepg"
            class="inline-flex items-center bg-pink-400 dark:bg-pink-800 text-white px-6 py-3 rounded-full shadow hover:bg-pink-500 dark:hover:bg-pink-700 transition duration-200 justify-center"
          >
            <span class="text-xl mr-2">👤</span>
            <span class="font-semibold">Profile</span>
          </NuxtLink>
          <NuxtLink
            to="/calender"
            class="inline-flex items-center bg-pink-300 dark:bg-pink-900 text-white px-6 py-3 rounded-full shadow hover:bg-pink-400 dark:hover:bg-pink-800 transition duration-200 justify-center"
          >
            <span class="text-xl mr-2">📅</span>
            <span class="font-semibold">Calendar</span>
          </NuxtLink>
          <NuxtLink
            to="/feedback"
            class="inline-flex items-center bg-pink-200 dark:bg-pink-950 text-white px-6 py-3 rounded-full shadow hover:bg-pink-300 dark:hover:bg-pink-900 transition duration-200 justify-center"
          >
            <span class="text-xl mr-2">💬</span>
            <span class="font-semibold">Feedback</span>
          </NuxtLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { ref, onMounted } from 'vue'

const router = useRouter()
const user = ref({ name: '' })
const theme = ref('light')
const weather = ref(null)

onMounted(async () => {
  try {
    const res = await $fetch('http://localhost:8000/api/user', {
      credentials: 'include',
    })
    user.value = res
  } catch (err) {
    router.push('/login') // redirect to login if not logged in
  }
  // Load theme from localStorage
  const saved = localStorage.getItem('theme')
  if (saved) theme.value = saved
  // Fetch weather for London (demo)
  await fetchWeather()
})

const toggleTheme = () => {
  theme.value = theme.value === 'dark' ? 'light' : 'dark'
  localStorage.setItem('theme', theme.value)
}

const fetchWeather = async () => {
  // Using Open-Meteo public API for demo (no API key required)
  // Docs: https://open-meteo.com/en/docs
  // London: lat=51.5072, lon=-0.1276
  const lat = 51.5072
  const lon = -0.1276
  const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true`
  try {
    const res = await fetch(url)
    const data = await res.json()
    weather.value = {
      city: 'London',
      temp: data.current_weather.temperature,
      description: `Windspeed: ${data.current_weather.windspeed} km/h`
    }
  } catch (e) {
    weather.value = null
  }
}

const logout = async () => {
  await $fetch('http://localhost:8000/api/logout', {
    method: 'POST',
    credentials: 'include',
  })
  router.push('/login')
}
</script>

<style>
.dark {
  background: #18181b !important;
  color: #f9fafb !important;
}
.dark .bg-white {
  background: #27272a !important;
}
.dark .text-rose-700, .dark .text-rose-600, .dark .text-pink-600 {
  color: #f9a8d4 !important;
}
</style>
