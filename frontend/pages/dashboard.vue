<template>
  <div class="min-h-screen bg-gradient-to-br from-pink-100 to-pink-200 flex flex-col">
    <!--  Top Dashboard Navbar -->
    <header class="bg-white shadow-md p-6 flex justify-between items-center rounded-b-3xl">
      <div class="text-2xl font-bold text-pink-600">
        Welcome {{ user.name }} 
      </div>
      <NuxtLink
        to="/login"
        class="bg-pink-500 text-white px-5 py-2 rounded-xl hover:bg-pink-600 transition"
      >
        Logout
      </NuxtLink>
    </header>

    <!-- 📋 Task Content -->
    <main class="flex-grow px-4 py-10">
      <div class="max-w-2xl mx-auto bg-white rounded-3xl shadow-xl p-8">
        <!-- Add Task -->
        <form @submit.prevent="addTask" class="flex mb-6">
          <input
            v-model="newTask"
            type="text"
            placeholder="Add a new task..."
            class="flex-1 p-3 border border-pink-200 rounded-l-xl placeholder-pink-300 focus:outline-pink-400"
          />
          <button
            type="submit"
            class="bg-pink-500 text-white px-6 rounded-r-xl hover:bg-pink-600 transition"
          >
            Add
          </button>
        </form>

        <!-- Task List -->
        <ul class="space-y-2">
          <li
            v-for="task in tasks"
            :key="task.id"
            class="flex justify-between items-center bg-pink-50 border border-pink-100 px-4 py-3 rounded-xl hover:bg-pink-100 transition"
          >
            <span :class="{ 'line-through text-gray-400': task.is_done }" class="font-medium">
              {{ task.title }}
            </span>
            <div class="flex gap-2">
              <button @click="toggleTask(task)" class="text-green-500 hover:scale-110">✔️</button>
              <button @click="deleteTask(task)" class="text-red-500 hover:scale-110">🗑️</button>
            </div>
          </li>
        </ul>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const tasks = ref([])
const newTask = ref('')
const user = ref({})

const fetchUser = async () => {
  const res = await fetch('http://127.0.0.1:8000/api/me', {
    credentials: 'include'
  })
  user.value = await res.json()
}

const fetchTasks = async () => {
  const res = await fetch('http://127.0.0.1:8000/api/tasks', {
    credentials: 'include'
  })
  tasks.value = await res.json()
}

const addTask = async () => {
  if (!newTask.value.trim()) return
  await fetch('http://127.0.0.1:8000/api/tasks', {
    method: 'POST',
    credentials: 'include',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ title: newTask.value })
  })
  newTask.value = ''
  fetchTasks()
}

const toggleTask = async (task) => {
  await fetch(`http://127.0.0.1:8000/api/tasks/${task.id}`, {
    method: 'PUT',
    credentials: 'include',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      title: task.title,
      is_done: !task.is_done
    })
  })
  fetchTasks()
}

const deleteTask = async (task) => {
  await fetch(`http://127.0.0.1:8000/api/tasks/${task.id}`, {
    method: 'DELETE',
    credentials: 'include'
  })
  fetchTasks()
}

onMounted(() => {
  fetchUser()
  fetchTasks()
})
</script>
