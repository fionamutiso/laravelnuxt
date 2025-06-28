<template>
  <div class="min-h-screen flex items-center justify-center bg-pink-50 px-4 py-10">
    <form
      class="bg-white w-full max-w-md p-8 rounded-2xl shadow-xl space-y-6"
      @submit.prevent="handleRegister"
    >
      <h1 class="text-3xl font-bold text-center text-pink-600">Register </h1>

      <div class="space-y-4">
        <div>
          <input
            type="text"
            v-model="name"
            placeholder="Full Name"
            required
            class="w-full p-3 border border-pink-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400"
            @input="validateName"
          />
          <p v-if="nameError" class="text-xs text-red-500 mt-1">{{ nameError }}</p>
        </div>
        <div>
          <input
            type="email"
            v-model="email"
            placeholder="Email address"
            required
            class="w-full p-3 border border-pink-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400"
            @input="validateEmail"
          />
          <p v-if="emailError" class="text-xs text-red-500 mt-1">{{ emailError }}</p>
        </div>
        <div>
          <input
            type="password"
            v-model="password"
            placeholder="Password"
            required
            class="w-full p-3 border border-pink-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400"
            @input="validatePassword"
          />
          <p v-if="passwordError" class="text-xs text-red-500 mt-1">{{ passwordError }}</p>
        </div>
      </div>

      <button
        type="submit"
        :disabled="loading || hasErrors"
        class="w-full py-3 bg-pink-500 hover:bg-pink-600 text-white font-semibold rounded-xl transition"
      >
        {{ loading ? 'Registering...' : 'Register' }}
      </button>

      <div v-if="successMsg" class="text-center text-green-600 bg-green-50 border border-green-200 rounded p-2 mt-2">{{ successMsg }}</div>
      <div v-if="errorMsg" class="text-center text-red-600 bg-red-50 border border-red-200 rounded p-2 mt-2">{{ errorMsg }}</div>

      <p class="text-center text-sm">
        Already have an account?
        <NuxtLink to="/login" class="text-pink-500 hover:underline ml-1">Login</NuxtLink>
      </p>
    </form>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useAuth } from '../composables/useAuth'

const { setToken, setUser } = useAuth()

const name = ref('')
const email = ref('')
const password = ref('')
const loading = ref(false)
const errorMsg = ref('')
const successMsg = ref('')

const nameError = ref('')
const emailError = ref('')
const passwordError = ref('')

const validateName = () => {
  nameError.value = name.value.length < 2 ? 'Name must be at least 2 characters.' : ''
}
const validateEmail = () => {
  emailError.value = !/^\S+@\S+\.\S+$/.test(email.value) ? 'Enter a valid email address.' : ''
}
const validatePassword = () => {
  passwordError.value = password.value.length < 6 ? 'Password must be at least 6 characters.' : ''
}
const hasErrors = computed(() => nameError.value || emailError.value || passwordError.value)

const handleRegister = async () => {
  validateName(); validateEmail(); validatePassword();
  if (hasErrors.value) return
  loading.value = true
  errorMsg.value = ''
  successMsg.value = ''
  
  try {
    const { data, error } = await useFetch('http://localhost:8000/api/register', {
      method: 'POST',
      body: {
        name: name.value,
        email: email.value,
        password: password.value
      },
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    })
    
    if (error.value) {
      errorMsg.value = error.value.data?.message || 'Registration failed!'
    } else {
      // Store the token and user using the composable
      if (data.value?.token) {
        setToken(data.value.token)
        setUser(data.value.user)
      }
      successMsg.value = 'Registration successful! Redirecting...'
      setTimeout(() => navigateTo('/dashboard'), 1200)
    }
  } catch (err) {
    errorMsg.value = 'Registration failed. Please try again.'
    console.error(err)
  } finally {
    loading.value = false
  }
}
</script>

