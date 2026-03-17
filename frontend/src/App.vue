<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'

axios.defaults.headers.common['Accept'] = 'application/json'
axios.defaults.headers.common['Content-Type'] = 'application/json'

const waterAmount = ref<number>(0)
const coffeeAmount = ref<number>(0)
const messages = ref<string[]>([])

const API_URL = import.meta.env.VITE_API_URL

const addMessage = (msg: string) => {
  messages.value.push(msg)
}

const brew = async (type: string) => {
  try {
    const res = await axios.post(`${API_URL}/machine/brew`, { type })
    addMessage(res.data.message)
  } catch (err: any) {
    console.log(err)
    addMessage(err.response?.data?.message || 'Something went wrong while brewing coffee.')
  }
}

const getStatus = async () => {
  try {
    const res = await axios.get(`${API_URL}/machine/status`)
  } catch (err:any) {
    addMessage(err.response?.data?.message || 'Something went wrong while getting status of machine')
  }
}

const fillCoffee = async (amount: number) => {
  try {
    const res = await axios.post(`${API_URL}/machine/fill-coffee`, { amount })
  } catch (err: any) {
    addMessage(err.response?.data?.message || 'Something went wrong while refilling coffee container')
  }
}

const fillWater = async (amount: number) => {
  try {
    const res = await axios.post(`${API_URL}/machine/fill-water`, { amount })
  } catch (err: any) {
    addMessage(err.response?.data?.message || 'Something went wrong while refilling water container')
  }
}
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex flex-col items-center justify-center p-6">
    <h1 class="text-3xl font-bold mb-6">Coffee Machine</h1>

    <!-- Coffee Actions -->
    <div class="flex space-x-4 mb-6">
      <button
        @click="brew('espresso')"
        class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600"
      >
        Make Espresso
      </button>
      <button
        @click="brew('double_espresso')"
        class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600"
      >
        Make Double Espresso
      </button>
      <button
        @click="brew('americano')"
        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
      >
        Make Americano
      </button>
      <button
        @click="getStatus"
        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
      >
        Get Status
      </button>
    </div>

    <!-- Container Fill Inputs -->
    <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 mb-6">
      <div>
        <label class="block mb-1 font-semibold">Add Water (liters)</label>
        <input
          v-model.number="waterAmount"
          type="number"
          min="0"
          class="border rounded px-2 py-1 w-32"
        />
        <button
          @click="fillWater(waterAmount)"
          class="ml-2 px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600"
        >
          Fill Water
        </button>
      </div>

      <div>
        <label class="block mb-1 font-semibold">Add Coffee (grams)</label>
        <input
          v-model.number="coffeeAmount"
          type="number"
          min="0"
          class="border rounded px-2 py-1 w-32"
        />
        <button
          @click="fillCoffee(coffeeAmount)"
          class="ml-2 px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600"
        >
          Fill Coffee
        </button>
      </div>
    </div>

    <div class="w-full max-w-md bg-white p-4 rounded shadow">
      <h2 class="text-xl font-semibold mb-2">Messages</h2>
      <ul class="list-disc list-inside">
        <li v-for="(msg, index) in messages" :key="index">{{ msg }}</li>
      </ul>
    </div>
  </div>
</template>

<style scoped></style>
