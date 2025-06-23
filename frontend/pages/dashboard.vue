<template>
  <div class="min-h-screen bg-gradient-to-br from-pink-100 to-rose-200 p-6">
    <!-- Top Bar -->
    <div class="flex items-center justify-between mb-8">
      <h1 class="text-2xl font-semibold text-rose-700 animate-pulse">
        🎀 Welcome, {{ user.name }}
      </h1>

      <button
        @click="logout"
        class="bg-rose-500 hover:bg-rose-600 text-white px-4 py-2 rounded-full transition-all shadow-lg"
      >
        Logout
      </button>
    </div>

    <!-- Main Body -->
    <div class="flex flex-col items-center justify-center">
      <div
        class="bg-white p-6 rounded-3xl shadow-xl w-full max-w-sm text-center hover:scale-105 transition-transform duration-300"
      >
        <p class="text-rose-600 text-lg mb-4 font-medium">✨ Ready to be productive?</p>

        <NuxtLink
          to="/tasks"
          class="inline-flex items-center bg-rose-300 text-white px-6 py-3 rounded-full shadow hover:bg-rose-400 transition duration-200"
        >
          <span class="text-xl mr-2">📝</span>
          <span class="font-semibold">Go to Tasks</span>
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { ref, onMounted } from 'vue'

const router = useRouter()
const user = ref({ name: '' })

onMounted(async () => {
  try {
    const res = await $fetch('http://localhost:8000/api/user', {
      credentials: 'include',
    })
    user.value = res
  } catch (err) {
    router.push('/login') // redirect to login if not logged in
  }
})

const logout = async () => {
  await $fetch('http://localhost:8000/api/logout', {
    method: 'POST',
    credentials: 'include',
  })
  router.push('/login')
}
</script>
