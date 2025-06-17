<template>
  <div class="min-h-screen flex items-center justify-center bg-pink-50 px-4 py-10">
    <form
      class="bg-white w-full max-w-md p-8 rounded-2xl shadow-xl space-y-6"
      @submit.prevent="handleRegister"
    >
      <h1 class="text-3xl font-bold text-center text-pink-600">Register </h1>

      <div class="space-y-4">
        <input
          type="text"
          v-model="name"
          placeholder="Full Name"
          required
          class="w-full p-3 border border-pink-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400"
        />

        <input
          type="email"
          v-model="email"
          placeholder="Email address"
          required
          class="w-full p-3 border border-pink-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400"
        />

        <input
          type="password"
          v-model="password"
          placeholder="Password"
          required
          class="w-full p-3 border border-pink-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400"
        />
      </div>

      <button
        type="submit"
        :disabled="loading"
        class="w-full py-3 bg-pink-500 hover:bg-pink-600 text-white font-semibold rounded-xl transition"
      >
        {{ loading ? 'Registering...' : 'Register' }}
      </button>

      <p v-if="errorMsg" class="text-center text-sm text-red-500">
        {{ errorMsg }}
      </p>

      <p class="text-center text-sm">
        Already have an account?
        <NuxtLink to="/login" class="text-pink-500 hover:underline ml-1">Login</NuxtLink>
      </p>
    </form>
  </div>
</template>

<script setup>
const name = ref('')
const email = ref('')
const password = ref('')
const loading = ref(false)
const errorMsg = ref('')

const handleRegister = async () => {
  loading.value = true
  errorMsg.value = ''

  const { data, error } = await useFetch('http://localhost:8000/api/register', {
    method: 'POST',
    body: {
      name: name.value,
      email: email.value,
      password: password.value
    },
    headers: {
      accept: 'application/json'
    },
    credentials: 'include'
  })

  loading.value = false

  if (error.value) {
    errorMsg.value = error.value.data?.message || 'Registration failed!'
  } else {
    console.log('Registered!', data.value)
    navigateTo('/dashboard')
  }
}
</script>

