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
      <!-- Enhanced Weather Widget -->
      <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg w-full max-w-sm">
        <div class="flex items-center justify-between mb-4">
          <div class="flex items-center gap-2">
            <span class="text-2xl">🌤️</span>
            <span class="text-lg font-semibold text-pink-600 dark:text-pink-200">Weather</span>
          </div>
          <button 
            @click="showLocationSearch = !showLocationSearch"
            class="text-pink-600 hover:text-pink-800 dark:text-pink-200 dark:hover:text-pink-400 transition"
            title="Change location"
          >
            📍
          </button>
        </div>

        <!-- Location Search -->
        <div v-if="showLocationSearch" class="mb-4 p-3 bg-pink-50 dark:bg-gray-700 rounded-xl">
          <div class="flex gap-2 mb-2">
            <input 
              v-model="locationSearch" 
              @input="searchLocations"
              placeholder="Search city..."
              class="flex-1 px-3 py-2 border border-pink-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400 text-sm"
            />
            <button 
              @click="getCurrentLocation"
              class="px-3 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition text-sm"
              title="Use current location"
            >
              📍
            </button>
          </div>
          
          <!-- Search Results -->
          <div v-if="searchResults.length > 0" class="max-h-32 overflow-y-auto">
            <div 
              v-for="result in searchResults" 
              :key="result.id"
              @click="selectLocation(result)"
              class="p-2 hover:bg-pink-100 dark:hover:bg-gray-600 rounded cursor-pointer text-sm"
            >
              {{ result.name }}, {{ result.country }}
            </div>
          </div>
          
          <!-- Popular Cities -->
          <div class="mt-2">
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Popular cities:</p>
            <div class="flex flex-wrap gap-1">
              <button 
                v-for="city in popularCities" 
                :key="city.name"
                @click="selectLocation(city)"
                class="px-2 py-1 bg-pink-200 dark:bg-pink-800 text-pink-700 dark:text-pink-200 rounded text-xs hover:bg-pink-300 dark:hover:bg-pink-700 transition"
              >
                {{ city.name }}
              </button>
            </div>
          </div>
        </div>

        <!-- Weather Display -->
        <div v-if="weather" class="text-center">
          <div class="text-xl font-bold mb-1">{{ weather.city }}</div>
          <div class="text-3xl font-bold mb-2">{{ weather.temp }}°C</div>
          <div class="flex items-center justify-center gap-2 mb-2">
            <span class="text-2xl">{{ getWeatherIcon(weather.weathercode) }}</span>
            <span class="text-sm text-gray-600 dark:text-gray-300">{{ weather.description }}</span>
          </div>
          <div class="grid grid-cols-2 gap-4 text-xs text-gray-500 dark:text-gray-400">
            <div>
              <span class="block">💨 Wind</span>
              <span class="font-semibold">{{ weather.windspeed }} km/h</span>
            </div>
            <div>
              <span class="block">💧 Humidity</span>
              <span class="font-semibold">{{ weather.humidity }}%</span>
            </div>
          </div>
        </div>
        <div v-else-if="weatherError" class="text-center text-red-500 text-sm">
          {{ weatherError }}
        </div>
        <div v-else class="text-center text-gray-400">
          Loading weather...
        </div>
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
import { useAuth } from '../composables/useAuth'

const router = useRouter()
const { getAuthHeaders, logout: authLogout, getUser } = useAuth()
const user = ref({ name: '' })
const theme = ref('light')
const weather = ref(null)
const weatherError = ref('')
const showLocationSearch = ref(false)
const locationSearch = ref('')
const searchResults = ref([])
const currentLocation = ref({
  lat: -1.2921, // Nairobi coordinates
  lon: 36.8219,
  city: 'Nairobi',
  country: 'Kenya'
})

// Popular cities for quick selection
const popularCities = ref([
  { name: 'Nairobi', lat: -1.2921, lon: 36.8219, country: 'Kenya' },
  { name: 'Mombasa', lat: -4.0435, lon: 39.6682, country: 'Kenya' },
  { name: 'Kisumu', lat: -0.0917, lon: 34.7680, country: 'Kenya' },
  { name: 'Nakuru', lat: -0.3031, lon: 36.0800, country: 'Kenya' },
  { name: 'Eldoret', lat: 0.5204, lon: 35.2699, country: 'Kenya' }
])

onMounted(async () => {
  try {
    // Try to get user from localStorage first
    const storedUser = getUser()
    if (storedUser) {
      user.value = storedUser
    } else {
      // Fallback to API call with token
      const res = await $fetch('http://localhost:8000/api/user', {
        headers: getAuthHeaders()
      })
      user.value = res
    }
  } catch (err) {
    router.push('/login') // redirect to login if not logged in
  }
  
  // Load theme from localStorage
  const saved = localStorage.getItem('theme')
  if (saved) theme.value = saved
  
  // Load saved location from localStorage
  const savedLocation = localStorage.getItem('weatherLocation')
  if (savedLocation) {
    currentLocation.value = JSON.parse(savedLocation)
  }
  
  // Fetch weather for current location
  await fetchWeather()
})

const toggleTheme = () => {
  theme.value = theme.value === 'dark' ? 'light' : 'dark'
  localStorage.setItem('theme', theme.value)
}

const fetchWeather = async () => {
  weatherError.value = ''
  try {
    const url = `https://api.open-meteo.com/v1/forecast?latitude=${currentLocation.value.lat}&longitude=${currentLocation.value.lon}&current_weather=true&hourly=relativehumidity_2m&timezone=auto`
    const res = await fetch(url)
    const data = await res.json()
    
    weather.value = {
      city: currentLocation.value.city,
      temp: Math.round(data.current_weather.temperature),
      windspeed: Math.round(data.current_weather.windspeed),
      humidity: Math.round(data.hourly.relativehumidity_2m[new Date().getHours()]),
      weathercode: data.current_weather.weathercode,
      description: getWeatherDescription(data.current_weather.weathercode)
    }
  } catch (e) {
    weatherError.value = 'Failed to load weather data'
    weather.value = null
  }
}

const searchLocations = async () => {
  if (locationSearch.value.length < 2) {
    searchResults.value = []
    return
  }
  
  try {
    const url = `https://geocoding-api.open-meteo.com/v1/search?name=${encodeURIComponent(locationSearch.value)}&count=5&language=en&format=json`
    const res = await fetch(url)
    const data = await res.json()
    
    if (data.results) {
      searchResults.value = data.results.map(result => ({
        id: result.id,
        name: result.name,
        country: result.country,
        lat: result.latitude,
        lon: result.longitude
      }))
    } else {
      searchResults.value = []
    }
  } catch (e) {
    searchResults.value = []
  }
}

const selectLocation = async (location) => {
  currentLocation.value = {
    lat: location.lat,
    lon: location.lon,
    city: location.name,
    country: location.country
  }
  
  // Save to localStorage
  localStorage.setItem('weatherLocation', JSON.stringify(currentLocation.value))
  
  // Close search and fetch new weather
  showLocationSearch.value = false
  locationSearch.value = ''
  searchResults.value = []
  
  await fetchWeather()
}

const getCurrentLocation = () => {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      async (position) => {
        const { latitude, longitude } = position.coords
        
        try {
          // Reverse geocoding to get city name
          const url = `https://geocoding-api.open-meteo.com/v1/reverse?latitude=${latitude}&longitude=${longitude}&language=en&format=json`
          const res = await fetch(url)
          const data = await res.json()
          
          const location = {
            lat: latitude,
            lon: longitude,
            city: data.name || 'Current Location',
            country: data.country || ''
          }
          
          await selectLocation(location)
        } catch (e) {
          // If reverse geocoding fails, still use coordinates
          const location = {
            lat: latitude,
            lon: longitude,
            city: 'Current Location',
            country: ''
          }
          await selectLocation(location)
        }
      },
      (error) => {
        weatherError.value = 'Unable to get current location'
      }
    )
  } else {
    weatherError.value = 'Geolocation not supported'
  }
}

const getWeatherIcon = (code) => {
  const icons = {
    0: '☀️', // Clear sky
    1: '🌤️', // Mainly clear
    2: '⛅', // Partly cloudy
    3: '☁️', // Overcast
    45: '🌫️', // Foggy
    48: '🌫️', // Depositing rime fog
    51: '🌦️', // Light drizzle
    53: '🌦️', // Moderate drizzle
    55: '🌧️', // Dense drizzle
    61: '🌧️', // Slight rain
    63: '🌧️', // Moderate rain
    65: '🌧️', // Heavy rain
    71: '🌨️', // Slight snow
    73: '🌨️', // Moderate snow
    75: '🌨️', // Heavy snow
    77: '🌨️', // Snow grains
    80: '🌦️', // Slight rain showers
    81: '🌧️', // Moderate rain showers
    82: '🌧️', // Violent rain showers
    85: '🌨️', // Slight snow showers
    86: '🌨️', // Heavy snow showers
    95: '⛈️', // Thunderstorm
    96: '⛈️', // Thunderstorm with slight hail
    99: '⛈️'  // Thunderstorm with heavy hail
  }
  return icons[code] || '🌤️'
}

const getWeatherDescription = (code) => {
  const descriptions = {
    0: 'Clear sky',
    1: 'Mainly clear',
    2: 'Partly cloudy',
    3: 'Overcast',
    45: 'Foggy',
    48: 'Depositing rime fog',
    51: 'Light drizzle',
    53: 'Moderate drizzle',
    55: 'Dense drizzle',
    61: 'Slight rain',
    63: 'Moderate rain',
    65: 'Heavy rain',
    71: 'Slight snow',
    73: 'Moderate snow',
    75: 'Heavy snow',
    77: 'Snow grains',
    80: 'Slight rain showers',
    81: 'Moderate rain showers',
    82: 'Violent rain showers',
    85: 'Slight snow showers',
    86: 'Heavy snow showers',
    95: 'Thunderstorm',
    96: 'Thunderstorm with slight hail',
    99: 'Thunderstorm with heavy hail'
  }
  return descriptions[code] || 'Unknown'
}

const logout = async () => {
  try {
    await $fetch('http://localhost:8000/api/logout', {
      method: 'POST',
      headers: getAuthHeaders()
    })
  } catch (err) {
    console.error('Logout error:', err)
  } finally {
    authLogout()
    router.push('/login')
  }
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
