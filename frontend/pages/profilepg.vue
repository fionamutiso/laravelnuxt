<template>
  <div class="min-h-screen bg-pink-50 flex items-center justify-center">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-lg">
      <h1 class="text-3xl font-bold text-pink-600 mb-6 text-center">Profile</h1>
      <form class="space-y-6" @submit.prevent="saveProfile">
        <div class="flex flex-col items-center">
          <label for="profilePic" class="mb-2 text-pink-600 font-semibold">Profile Picture</label>
          <input type="file" id="profilePic" class="mb-4" @change="onPicChange" />
          <img :src="profilePicPreview || defaultPic" class="w-24 h-24 rounded-full object-cover border-2 border-pink-300" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
          <input v-model="profile.name" type="text" class="w-full px-3 py-2 border border-pink-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input v-model="profile.email" type="email" class="w-full px-3 py-2 border border-pink-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
          <textarea v-model="profile.bio" class="w-full px-3 py-2 border border-pink-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400 min-h-[60px]"></textarea>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Birthdate</label>
          <input v-model="profile.birthdate" type="date" class="w-full px-3 py-2 border border-pink-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400" />
        </div>
        <button type="submit" class="w-full px-4 py-2 bg-pink-500 hover:bg-pink-600 text-white rounded-xl font-semibold">Save Changes</button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
const defaultPic = 'https://ui-avatars.com/api/?name=User&background=F9A8D4&color=fff'
const profilePicPreview = ref('')
const profile = ref({
  name: '',
  email: '',
  bio: '',
  birthdate: ''
})
function onPicChange(e) {
  const file = e.target.files[0]
  if (file) {
    const reader = new FileReader()
    reader.onload = (ev) => {
      profilePicPreview.value = ev.target.result
    }
    reader.readAsDataURL(file)
  }
}
function saveProfile() {
  alert('Profile saved! (Backend integration coming soon)')
}
</script>