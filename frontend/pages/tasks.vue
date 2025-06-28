<template>
  <div class="min-h-screen bg-pink-50">
    <div class="container mx-auto p-5 max-w-4xl font-sans">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-pink-600">Welcome to Your Tasks</h1>
        <button @click="logout" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl transition">Logout</button>
      </div>

      <!-- Filters -->
      <div class="flex flex-wrap gap-4 mb-6 items-center">
        <div>
          <label class="block text-xs font-medium text-gray-700 mb-1">Category</label>
          <select v-model="filters.category_id" class="px-3 py-2 border border-pink-300 rounded-xl">
            <option value="">All</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
          <select v-model="filters.completed" class="px-3 py-2 border border-pink-300 rounded-xl">
            <option value="">All</option>
            <option value="0">Pending</option>
            <option value="1">Completed</option>
          </select>
        </div>
        <button @click="applyFilters" class="px-4 py-2 bg-pink-400 text-white rounded-xl">Apply</button>
        <button @click="resetFilters" class="px-4 py-2 bg-gray-200 text-pink-600 rounded-xl">Reset</button>
      </div>

      <div class="add-task bg-white p-6 rounded-2xl shadow-xl mb-8">
        <h2 class="text-2xl font-semibold mb-4 text-pink-600">Add New Task</h2>
        <form @submit.prevent="addTask" class="space-y-4">
          <div class="form-group">
            <label for="taskTitle" class="block text-sm font-medium text-gray-700 mb-1">Task Title</label>
            <input 
              v-model="newTask.title" 
              type="text" 
              id="taskTitle" 
              placeholder="Enter task title..." 
              required
              class="w-full px-3 py-2 border border-pink-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400"
            >
          </div>
          <div class="form-group">
            <label for="taskDescription" class="block text-sm font-medium text-gray-700 mb-1">Task Description</label>
            <textarea 
              v-model="newTask.description" 
              id="taskDescription" 
              placeholder="Enter task description..."
              class="w-full px-3 py-2 border border-pink-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400 min-h-[100px]"
            ></textarea>
          </div>
          <div class="form-group">
            <label for="dueDate" class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
            <input 
              v-model="newTask.due_date" 
              type="date" 
              id="dueDate" 
              required
              class="w-full px-3 py-2 border border-pink-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400"
            >
          </div>
          <button 
            type="submit" 
            class="px-4 py-2 bg-pink-500 hover:bg-pink-600 text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400 focus:ring-offset-2 transition"
          >
            Add Task
          </button>
        </form>
      </div>

      <div class="todo-list bg-white p-6 rounded-2xl shadow-xl">
        <h2 class="text-2xl font-semibold mb-2 text-pink-600">To-Do List</h2>
        <h3 class="text-lg text-gray-600 mb-4">Your Tasks</h3>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-pink-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-pink-600 uppercase tracking-wider">STATUS</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-pink-600 uppercase tracking-wider">TASK</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-pink-600 uppercase tracking-wider">DUE DATE</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-pink-600 uppercase tracking-wider">ACTIONS</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="tasks.length === 0">
                <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No tasks yet. Add one to get started!</td>
              </tr>
              <tr 
                v-for="task in tasks" 
                :key="task.id" 
                :class="{ 'bg-pink-50': task.completed }"
                class="hover:bg-pink-50 transition"
              >
                <td class="px-6 py-4 whitespace-nowrap">
                  <input 
                    type="checkbox" 
                    v-model="task.completed" 
                    @change="updateTask(task)"
                    class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-pink-300 rounded"
                  >
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm font-medium text-gray-900">{{ task.title }}</div>
                  <div v-if="task.description" class="text-sm text-gray-500">{{ task.description }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDate(task.due_date) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button 
                    @click="editTask(task)" 
                    class="text-pink-600 hover:text-pink-900 mr-3 transition"
                  >
                    Edit
                  </button>
                  <button 
                    @click="deleteTask(task.id)" 
                    class="text-red-600 hover:text-red-900 transition"
                  >
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- Pagination Controls -->
        <div v-if="pagination.total > pagination.per_page" class="flex justify-center mt-6 gap-2">
          <button @click="changePage(pagination.current_page - 1)" :disabled="pagination.current_page === 1" class="px-3 py-1 rounded bg-pink-200 hover:bg-pink-300 disabled:opacity-50">Prev</button>
          <button v-for="page in totalPages" :key="page" @click="changePage(page)" :class="['px-3 py-1 rounded', page === pagination.current_page ? 'bg-pink-500 text-white' : 'bg-pink-100 hover:bg-pink-200']">{{ page }}</button>
          <button @click="changePage(pagination.current_page + 1)" :disabled="pagination.current_page === totalPages" class="px-3 py-1 rounded bg-pink-200 hover:bg-pink-300 disabled:opacity-50">Next</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      tasks: [],
      categories: [],
      newTask: {
        title: '',
        description: '',
        due_date: '',
        category_id: ''
      },
      filters: {
        category_id: '',
        completed: ''
      },
      pagination: {
        current_page: 1,
        last_page: 1,
        per_page: 10,
        total: 0
      },
      editingTask: null
    }
  },
  computed: {
    totalPages() {
      return this.pagination.last_page || 1
    }
  },
  async mounted() {
    // Restrict access: only allow if navigated from dashboard
    if (!this.$route.query.fromDashboard) {
      this.$router.replace('/dashboard')
      return
    }
    await this.fetchCategories()
    await this.fetchTasks()
  },
  methods: {
    async fetchCategories() {
      try {
        const response = await fetch('http://localhost:8000/api/categories')
        if (!response.ok) throw new Error('Failed to fetch categories')
        this.categories = await response.json()
      } catch (error) {
        console.error('Error fetching categories:', error)
      }
    },
    async fetchTasks(page = 1) {
      try {
        let url = `http://localhost:8000/api/tasks?page=${page}`
        if (this.filters.category_id) url += `&category_id=${this.filters.category_id}`
        if (this.filters.completed !== '') url += `&completed=${this.filters.completed}`
        const response = await fetch(url)
        if (!response.ok) throw new Error('Failed to fetch tasks')
        const data = await response.json()
        this.tasks = data.data
        this.pagination = {
          current_page: data.current_page,
          last_page: data.last_page,
          per_page: data.per_page,
          total: data.total
        }
      } catch (error) {
        console.error('Error fetching tasks:', error)
      }
    },
    applyFilters() {
      this.fetchTasks(1)
    },
    resetFilters() {
      this.filters = { category_id: '', completed: '' }
      this.fetchTasks(1)
    },
    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.fetchTasks(page)
      }
    },
    getCategoryName(id) {
      const cat = this.categories.find(c => c.id === id)
      return cat ? cat.name : 'No Category'
    },
    async addTask() {
      try {
        const response = await fetch('http://localhost:8000/api/tasks', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify(this.newTask)
        })
        
        if (!response.ok) {
          throw new Error('Network response was not ok')
        }
        
        await this.fetchTasks()
        this.newTask = { title: '', description: '', due_date: '', category_id: '' }
      } catch (error) {
        console.error('Error adding task:', error)
      }
    },
    async updateTask(task) {
      try {
        const response = await fetch(`http://localhost:8000/api/tasks/${task.id}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify(task)
        })
        
        if (!response.ok) {
          throw new Error('Network response was not ok')
        }
        
        await response.json()
      } catch (error) {
        console.error('Error updating task:', error)
      }
    },
    async deleteTask(id) {
      try {
        const response = await fetch(`http://localhost:8000/api/tasks/${id}`, {
          method: 'DELETE',
          headers: {
            'Accept': 'application/json'
          }
        })
        
        if (!response.ok) {
          throw new Error('Network response was not ok')
        }
        
        this.tasks = this.tasks.filter(task => task.id !== id)
      } catch (error) {
        console.error('Error deleting task:', error)
      }
    },
    editTask(task) {
      this.editingTask = { ...task }
      // You can implement a modal or inline edit form here
      alert('Edit functionality would open a form here')
    },
    formatDate(dateString) {
      const options = { year: 'numeric', month: '2-digit', day: '2-digit' }
      return new Date(dateString).toLocaleDateString('en-US', options)
    },
    async logout() {
      try {
        const token = localStorage.getItem('auth_token')
        await fetch('http://localhost:8000/api/logout', {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
          }
        });
        localStorage.removeItem('auth_token')
        localStorage.removeItem('user')
        window.location.href = '/login';
      } catch (error) {
        console.error('Error logging out:', error);
        // Still clear local storage and redirect even if API call fails
        localStorage.removeItem('auth_token')
        localStorage.removeItem('user')
        window.location.href = '/login';
      }
    }
  }
}
</script>