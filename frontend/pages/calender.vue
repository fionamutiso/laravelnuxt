<template>
  <div class="min-h-screen bg-pink-50 flex items-center justify-center">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-3xl">
      <h1 class="text-3xl font-bold text-pink-600 mb-6 text-center">Calendar</h1>
      <div class="flex justify-between items-center mb-4">
        <button @click="prevMonth" class="px-3 py-1 bg-pink-200 rounded hover:bg-pink-300">&lt;</button>
        <span class="text-xl font-semibold text-pink-700">{{ monthYear }}</span>
        <button @click="nextMonth" class="px-3 py-1 bg-pink-200 rounded hover:bg-pink-300">&gt;</button>
      </div>
      <div class="grid grid-cols-7 gap-2 mb-4">
        <div v-for="day in weekDays" :key="day" class="text-center font-bold text-pink-500">{{ day }}</div>
        <div v-for="blank in blanks" :key="'b'+blank"></div>
        <div v-for="day in daysInMonth" :key="day" class="text-center">
          <button
            @click="selectDate(day)"
            :class="[
              'w-10 h-10 rounded-full transition',
              selectedDate === day ? 'bg-pink-400 text-white' : 'bg-pink-100 hover:bg-pink-200',
              hasTasks(day) ? 'border-2 border-pink-500 font-bold' : ''
            ]"
          >
            {{ day }}
          </button>
        </div>
      </div>
      <div>
        <h2 class="text-xl font-semibold text-pink-500 mb-2">Tasks for {{ selectedDate ? (monthYear + ' ' + selectedDate) : '...' }}</h2>
        <ul>
          <li v-for="task in tasksForSelectedDate" :key="task.id" class="mb-2 p-3 bg-pink-100 rounded-xl">
            <span class="font-medium">{{ task.title }}</span> - <span class="text-gray-500">{{ task.description }}</span>
          </li>
          <li v-if="tasksForSelectedDate.length === 0" class="text-gray-400">No tasks for this date.</li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
const today = new Date()
const currentMonth = ref(today.getMonth())
const currentYear = ref(today.getFullYear())
const selectedDate = ref(null)
const weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
const tasks = ref([]) // Will be populated from backend later

const daysInMonth = computed(() => {
  return new Date(currentYear.value, currentMonth.value + 1, 0).getDate()
})
const blanks = computed(() => {
  return new Date(currentYear.value, currentMonth.value, 1).getDay()
})
const monthYear = computed(() => {
  return new Date(currentYear.value, currentMonth.value).toLocaleString('default', { month: 'long', year: 'numeric' })
})
function prevMonth() {
  if (currentMonth.value === 0) {
    currentMonth.value = 11
    currentYear.value--
  } else {
    currentMonth.value--
  }
  selectedDate.value = null
}
function nextMonth() {
  if (currentMonth.value === 11) {
    currentMonth.value = 0
    currentYear.value++
  } else {
    currentMonth.value++
  }
  selectedDate.value = null
}
function selectDate(day) {
  selectedDate.value = day
}
function hasTasks(day) {
  // Placeholder: highlight days with tasks (to be implemented with backend)
  return tasks.value.some(task => {
    const d = new Date(task.due_date)
    return d.getDate() === day && d.getMonth() === currentMonth.value && d.getFullYear() === currentYear.value
  })
}
const tasksForSelectedDate = computed(() => {
  if (!selectedDate.value) return []
  return tasks.value.filter(task => {
    const d = new Date(task.due_date)
    return d.getDate() === selectedDate.value && d.getMonth() === currentMonth.value && d.getFullYear() === currentYear.value
  })
})
</script>