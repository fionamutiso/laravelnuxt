<template>
  <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-pink-100 to-pink-300">
    <div class="bg-white rounded-3xl shadow-2xl p-10 max-w-xl text-center">
      <h1 class="text-4xl font-bold text-pink-600 mb-4"> Welcome </h1>
      <p class="text-gray-600 text-lg mb-6">
        You're logged in 
      </p>

      <NuxtLink to="/login" class="bg-pink-500 text-white px-6 py-3 rounded-xl hover:bg-pink-600 transition">
        Logout
      </NuxtLink>
    </div>
  </div>
</template>


