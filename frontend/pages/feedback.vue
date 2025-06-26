<template>
  <div class="min-h-screen bg-pink-50 p-6">
    <div class="container mx-auto max-w-4xl">
      <h1 class="text-3xl font-bold text-pink-600 mb-8 text-center">Feedback</h1>
      
      <!-- Feedback Form -->
      <div class="bg-white p-6 rounded-2xl shadow-xl mb-8">
        <h2 class="text-2xl font-semibold mb-4 text-pink-600">Submit Feedback</h2>
        <form @submit.prevent="submitFeedback" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
            <input 
              v-model="feedbackForm.subject" 
              type="text" 
              required
              class="w-full px-3 py-2 border border-pink-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400"
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
            <textarea 
              v-model="feedbackForm.message" 
              required
              rows="4"
              class="w-full px-3 py-2 border border-pink-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400"
            ></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
            <div class="flex gap-2">
              <button 
                v-for="star in 5" 
                :key="star"
                type="button"
                @click="feedbackForm.rating = star"
                :class="['text-2xl', star <= feedbackForm.rating ? 'text-yellow-400' : 'text-gray-300']"
              >
                ★
              </button>
            </div>
          </div>
          <button 
            type="submit" 
            :disabled="loading"
            class="w-full px-4 py-2 bg-pink-500 hover:bg-pink-600 text-white rounded-xl font-semibold disabled:opacity-50"
          >
            {{ loading ? 'Submitting...' : 'Submit Feedback' }}
          </button>
        </form>
        
        <div v-if="successMsg" class="text-center text-green-600 bg-green-50 border border-green-200 rounded p-2 mt-4">{{ successMsg }}</div>
        <div v-if="errorMsg" class="text-center text-red-600 bg-red-50 border border-red-200 rounded p-2 mt-4">{{ errorMsg }}</div>
      </div>

      <!-- Feedback List -->
      <div class="bg-white p-6 rounded-2xl shadow-xl">
        <h2 class="text-2xl font-semibold mb-4 text-pink-600">All Feedback</h2>
        <div v-if="loading" class="text-center text-gray-500">Loading...</div>
        <div v-else-if="feedbackList.length === 0" class="text-center text-gray-500">No feedback yet.</div>
        <div v-else class="space-y-4">
          <div 
            v-for="feedback in feedbackList" 
            :key="feedback.id"
            class="border border-pink-200 rounded-xl p-4"
          >
            <div class="flex justify-between items-start mb-2">
              <h3 class="font-semibold text-pink-600">{{ feedback.subject }}</h3>
              <div class="flex items-center gap-2">
                <div class="flex">
                  <span 
                    v-for="star in 5" 
                    :key="star"
                    :class="['text-lg', star <= feedback.rating ? 'text-yellow-400' : 'text-gray-300']"
                  >
                    ★
                  </span>
                </div>
                <span class="text-xs bg-pink-100 text-pink-600 px-2 py-1 rounded">{{ feedback.status }}</span>
              </div>
            </div>
            <p class="text-gray-600 mb-2">{{ feedback.message }}</p>
            <div class="text-xs text-gray-400">
              By {{ feedback.user?.name || 'Anonymous' }} on {{ formatDate(feedback.created_at) }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const feedbackForm = ref({
  subject: '',
  message: '',
  rating: 5
})
const feedbackList = ref([])
const loading = ref(false)
const successMsg = ref('')
const errorMsg = ref('')

onMounted(() => {
  fetchFeedback()
})

const submitFeedback = async () => {
  loading.value = true
  successMsg.value = ''
  errorMsg.value = ''
  
  try {
    const response = await fetch('http://localhost:8000/api/feedback', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(feedbackForm.value)
    })
    
    if (!response.ok) throw new Error('Failed to submit feedback')
    
    successMsg.value = 'Feedback submitted successfully!'
    feedbackForm.value = { subject: '', message: '', rating: 5 }
    await fetchFeedback()
  } catch (error) {
    errorMsg.value = 'Failed to submit feedback. Please try again.'
  } finally {
    loading.value = false
  }
}

const fetchFeedback = async () => {
  loading.value = true
  try {
    const response = await fetch('http://localhost:8000/api/feedback')
    if (!response.ok) throw new Error('Failed to fetch feedback')
    feedbackList.value = await response.json()
  } catch (error) {
    console.error('Error fetching feedback:', error)
  } finally {
    loading.value = false
  }
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}
</script> 