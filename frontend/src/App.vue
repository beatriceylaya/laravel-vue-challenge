<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import BaseModal from './components/BaseModal.vue'

const API_URL = import.meta.env.VITE_API_URL
axios.defaults.headers.common['Accept'] = 'application/json'
axios.defaults.headers.common['Content-Type'] = 'application/json'

type MachineStatus = {
  water?: { current_ml?: number; capacity_ml?: number; percentage?: number }
  coffee?: { current_g?: number; capacity_g?: number; percentage?: number }
  can_make?: string[] | Record<string, boolean>
}

// Modal message
const modal = ref<{ visible: boolean; message: string; type: 'success' | 'error' }>({
  visible: false,
  message: '',
  type: 'success'
})

let modalTimeout: number
const closeModal = () => {
  modal.value.visible = false
}

const showModal = (message: string, type: 'success' | 'error' = 'success') => {
  modal.value.message = message
  modal.value.type = type
  modal.value.visible = true

  clearTimeout(modalTimeout)
  modalTimeout = window.setTimeout(() => {
    modal.value.visible = false
  }, 3000)
}

const waterAmount = ref<number>(0)
const coffeeAmount = ref<number>(0)
const isBusy = ref(false)
const status = ref<MachineStatus | null>(null)

const recipeLabels: Record<string, string> = {
  espresso: 'Espresso',
  double_espresso: 'Double Espresso',
  ristretto: 'Ristretto',
  americano: 'Americano'
}

const canBrew = (type: string): boolean => {
  const canMake = status.value?.can_make
  if (!canMake) return false
  if (Array.isArray(canMake)) {
    const label = recipeLabels[type] ?? type
    return canMake.includes(type) || canMake.includes(label)
  }
  return Boolean(canMake[type])
}

const canMakeRecipes = (): string[] => {
  const canMake = status.value?.can_make
  if (!canMake) return []
  if (Array.isArray(canMake)) return canMake
  return Object.entries(canMake)
    .filter(([, ok]) => ok)
    .map(([type]) => recipeLabels[type] ?? type)
}

const brew = async (type: string) => {
  isBusy.value = true
  try {
    const { data } = await axios.post(`${API_URL}/machine/brew`, { type })
    status.value = data.status ?? status.value
    showModal(data.message, 'success')
  } catch (err: any) {
    console.log(err)
    showModal(err.response?.data?.message || 'Something went wrong while brewing coffee.', 'error')
  } finally {
    isBusy.value = false
  }
}

const getStatus = async () => {
  isBusy.value = true
  try {
    const { data } = await axios.get(`${API_URL}/machine/status`)
    status.value = data
  } catch (err:any) {
    showModal(err.response?.data?.message || 'Something went wrong while getting status of machine', 'error')
  } finally {
    isBusy.value = false
  }
}

const fillCoffee = async (amount: number) => {
  isBusy.value = true
  try {
    const { data } = await axios.post(`${API_URL}/machine/fill-coffee`, { amount })
    status.value = data.status ?? status.value
    showModal(data.message || `Filled coffee with ${amount}g`, 'success')
  } catch (err: any) {
    showModal(err.response?.data?.message || 'Something went wrong while refilling coffee container', 'error')
  } finally {
    isBusy.value = false
  }
}

const fillWater = async (amount: number) => {
  isBusy.value = true
  try {
    const { data } = await axios.post(`${API_URL}/machine/fill-water`, { amount })
    status.value = data.status ?? status.value
    showModal(data.message || `Filled water with ${amount}ml`, 'success')
  } catch (err: any) {
    showModal(err.response?.data?.message || 'Something went wrong while refilling water container', 'error')
  } finally {
    isBusy.value = false
  }
}

onMounted(() => {
  getStatus()
})
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex flex-col items-center justify-center p-6">
    <h1 class="text-3xl font-bold mb-6">Coffee Machine</h1>

    <!-- Coffee Actions -->
    <div class="flex space-x-4 mb-6">
      <button
        @click="brew('espresso')"
        :disabled="isBusy || !canBrew('espresso')"
        class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        Make Espresso
      </button>
      <button
        @click="brew('double_espresso')"
        :disabled="isBusy || !canBrew('double_espresso')"
        class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        Make Double Espresso
      </button>
      <button
        @click="brew('americano')"
        :disabled="isBusy || !canBrew('americano')"
        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        Make Americano
      </button>
      <button
        @click="getStatus"
        :disabled="isBusy"
        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed"
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
          :disabled="isBusy"
          class="ml-2 px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed"
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
          :disabled="isBusy"
          class="ml-2 px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Fill Coffee
        </button>
      </div>
    </div>

    <!-- Machine Details  -->
    <div class="w-full max-w-2xl mb-6 rounded border bg-white p-4 shadow-sm">
      <div class="flex items-center justify-between gap-4">
        <h2 class="text-lg font-semibold">Machine details</h2>
        <span class="text-xs text-gray-500">
          {{ status ? 'Loaded' : 'Fetching data' }}
        </span>
      </div>

      <div v-if="status" class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2">
        <div class="rounded bg-gray-50 p-3">
          <div class="text-sm font-medium text-gray-700">Water</div>
          <div class="mt-1 text-sm text-gray-900">
            {{ status.water?.current_ml ?? 0 }}ml / {{ status.water?.capacity_ml ?? 0 }}ml
            <span class="text-gray-500">({{ status.water?.percentage ?? 0 }}%)</span>
          </div>
        </div>

        <div class="rounded bg-gray-50 p-3">
          <div class="text-sm font-medium text-gray-700">Coffee</div>
          <div class="mt-1 text-sm text-gray-900">
            {{ status.coffee?.current_g ?? 0 }}g / {{ status.coffee?.capacity_g ?? 0 }}g
            <span class="text-gray-500">({{ status.coffee?.percentage ?? 0 }}%)</span>
          </div>
        </div>

        <div class="sm:col-span-2 rounded bg-gray-50 p-3">
          <div class="text-sm font-medium text-gray-700">Can make (recipes)</div>
          <div class="mt-2 flex flex-wrap gap-2">
            <span
              v-for="recipe in canMakeRecipes()"
              :key="recipe"
              class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-medium text-emerald-800"
            >
              {{ recipe }}
            </span>
            <span v-if="canMakeRecipes().length === 0" class="text-sm text-gray-500">
              None (insufficient ingredients)
            </span>
          </div>
        </div>
      </div>
    </div>

    <BaseModal
      v-model="modal.visible"
      :title="modal.type === 'success' ? 'Success' : 'Error'"
      :variant="modal.type"
      @close="closeModal"
    >
      <p class="break-words">
        {{ modal.message }}
      </p>
    </BaseModal>
  </div>
</template>

<style scoped></style>
