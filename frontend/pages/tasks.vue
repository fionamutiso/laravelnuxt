<template>
  <div class="container mx-auto p-5 max-w-4xl font-sans">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Welcome</h1>
      <button @click="logout" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition">Logout</button>
    </div>

    <div class="add-task bg-white p-6 rounded-lg shadow-md mb-8">
      <h2 class="text-2xl font-semibold mb-4">Add New Task</h2>
      <form @submit.prevent="addTask" class="space-y-4">
        <div class="form-group">
          <label for="taskTitle" class="block text-sm font-medium text-gray-700 mb-1">Task Title</label>
          <input 
            v-model="newTask.title" 
            type="text" 
            id="taskTitle" 
            placeholder="Enter task title..." 
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
        </div>
        <div class="form-group">
          <label for="taskDescription" class="block text-sm font-medium text-gray-700 mb-1">Task Description</label>
          <textarea 
            v-model="newTask.description" 
            id="taskDescription" 
            placeholder="Enter task description..."
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 min-h-[100px]"
          ></textarea>
        </div>
        <div class="form-group">
          <label for="dueDate" class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
          <input 
            v-model="newTask.due_date" 
            type="date" 
            id="dueDate" 
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
        </div>
        <button 
          type="submit" 
          class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
          Add Task
        </button>
      </form>
    </div>

    <div class="todo-list bg-white p-6 rounded-lg shadow-md">
      <h2 class="text-2xl font-semibold mb-2">To-Do List</h2>
      <h3 class="text-lg text-gray-600 mb-4">Your Tasks</h3>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STATUS</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TASK</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DUE DATE</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ACTIONS</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-if="tasks.length === 0">
              <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No tasks yet. Add one to get started!</td>
            </tr>
            <tr 
              v-for="task in tasks" 
              :key="task.id" 
              :class="{ 'bg-gray-50': task.completed }"
              class="hover:bg-gray-50"
            >
              <td class="px-6 py-4 whitespace-nowrap">
                <input 
                  type="checkbox" 
                  v-model="task.completed" 
                  @change="updateTask(task)"
                  class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
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
                  class="text-indigo-600 hover:text-indigo-900 mr-3"
                >
                  Edit
                </button>
                <button 
                  @click="deleteTask(task.id)" 
                  class="text-red-600 hover:text-red-900"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      tasks: [],
      newTask: {
        title: '',
        description: '',
        due_date: ''
      },
      editingTask: null
    }
  },
  async mounted() {
    await this.fetchTasks()
  },
  methods: {
    async fetchTasks() {
      try {
        const response = await fetch('http://localhost:8000/api/tasks')
        if (!response.ok) throw new Error('Network response was not ok')
        this.tasks = await response.json()
      } catch (error) {
        console.error('Error fetching tasks:', error)
      }
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
        
        if (!response.ok) throw new Error('Network response was not ok')
        
        await this.fetchTasks()
        this.newTask = { title: '', description: '', due_date: '' }
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
        
        if (!response.ok) throw new Error('Network response was not ok')
        
        await response.json()
      } catch (error) {
        console.error('Error updating task:', error)
      }
    },
    async deleteTask(id) {
      try {
        const response = await fetch(`http://localhost:8000/api/tasks/${id}`, {
          method: 'DELETE'
        })
        
        if (!response.ok) throw new Error('Network response was not ok')
        
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
        await fetch('http://localhost:8000/api/logout', {
          method: 'POST',
          credentials: 'include',
        });
        window.location.href = '/login';
      } catch (error) {
        console.error('Error logging out:', error);
      }
    }
  }
}
</script>