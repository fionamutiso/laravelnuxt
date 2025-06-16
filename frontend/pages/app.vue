
<script setup>
import { ref } from 'vue'
import { useFetch } from '#app'

const email = ref('')
const password = ref('')
const error = ref('')
const success = ref('')
const loading = ref(false)

const login = async () => {
  loading.value = true
  error.value = ''
  success.value = ''

  try {
    const { data, error: fetchError } = await useFetch('http://127.0.0.1:8000/api/login', {
      method: 'POST',
      credentials: 'include',
      body: {
        email: email.value,
        password: password.value
      }
    })

    if (fetchError.value) throw fetchError.value

    success.value = 'Welcome back'
  } catch (e) {
    error.value = 'Wrong email or password'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-pink-100 via-white to-pink-200">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md animate-fade-in">
      <h1 class="text-3xl font-bold text-pink-600 mb-6 text-center">Login </h1>

      <form @submit.prevent="login" class="space-y-4">
        <div>
          <label class="block text-sm text-gray-600 mb-1">Email</label>
          <input
            v-model="email"
            type="email"
            class="w-full px-4 py-2 border border-pink-200 rounded-lg focus:ring-2 focus:ring-pink-400 outline-none transition"
            placeholder="you@example.com"
            required
          />
        </div>

        <div>
          <label class="block text-sm text-gray-600 mb-1">Password</label>
          <input
            v-model="password"
            type="password"
            class="w-full px-4 py-2 border border-pink-200 rounded-lg focus:ring-2 focus:ring-pink-400 outline-none transition"
            placeholder="••••••••"
            required
          />
        </div>

        <button
          type="submit"
          :disabled="loading"
          class="w-full bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 rounded-lg shadow transition-all"
        >
          {{ loading ? 'Logging in…' : 'Enter ' }}
        </button>

        <div class="mt-3 text-sm text-center">
          <p v-if="error" class="text-red-500 animate-shake">{{ error }}</p>
          <p v-if="success" class="text-green-600 animate-pop">{{ success }}</p>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: none;
  }
}
.animate-fade-in {
  animation: fade-in 0.8s ease-out;
}

@keyframes pop {
  0% {
    transform: scale(0.95);
    opacity: 0;
  }
  100% {
    transform: scale(1);
    opacity: 1;
  }
}
.animate-pop {
  animation: pop 0.4s ease-out;
}

@keyframes shake {
  0% { transform: translateX(-3px); }
  25% { transform: translateX(3px); }
  50% { transform: translateX(-3px); }
  75% { transform: translateX(3px); }
  100% { transform: translateX(0); }
}
.animate-shake {
  animation: shake 0.4s ease-in-out;
}
</style>

