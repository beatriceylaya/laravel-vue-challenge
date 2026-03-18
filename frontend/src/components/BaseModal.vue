<script setup lang="ts">
import { nextTick, ref, watch } from 'vue'

type ModalVariant = 'success' | 'error'

const props = withDefaults(
  defineProps<{
    modelValue: boolean
    title?: string
    variant?: ModalVariant
    dismissible?: boolean
  }>(),
  {
    title: 'Message',
    variant: 'success',
    dismissible: true
  }
)

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void
  (e: 'close'): void
}>()

const overlayEl = ref<HTMLDivElement | null>(null)

const close = () => {
  if (!props.dismissible) return
  emit('update:modelValue', false)
  emit('close')
}

const onBackdropClick = (e: MouseEvent) => {
  if (!props.dismissible) return
  if (e.target === e.currentTarget) close()
}

watch(
  () => props.modelValue,
  async (open) => {
    if (!open) return
    await nextTick()
    overlayEl.value?.focus()
  }
)
</script>

<template>
  <transition
    enter-active-class="transition ease-out duration-200"
    enter-from-class="opacity-0"
    enter-to-class="opacity-100"
    leave-active-class="transition ease-in duration-150"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div
      v-if="modelValue"
      class="fixed inset-0 z-50 flex items-center justify-center p-4"
      @click="onBackdropClick"
      @keydown.esc.stop.prevent="close"
      tabindex="0"
      ref="overlayEl"
    >
      <div class="absolute inset-0 bg-black/50" />

      <transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0 translate-y-2 scale-95"
        enter-to-class="opacity-100 translate-y-0 scale-100"
        leave-active-class="transition ease-in duration-150"
        leave-from-class="opacity-100 translate-y-0 scale-100"
        leave-to-class="opacity-0 translate-y-2 scale-95"
      >
        <div
          v-if="modelValue"
          class="relative w-full max-w-md rounded-lg bg-white shadow-xl ring-1 ring-black/5"
          role="dialog"
          aria-modal="true"
        >
          <div class="flex items-start justify-between gap-4 border-b px-5 py-4">
            <div class="min-w-0">
              <p class="text-sm font-semibold text-gray-900">
                {{ title }}
              </p>
            </div>

            <button
              v-if="dismissible"
              type="button"
              class="inline-flex h-8 w-8 items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
              aria-label="Close"
              @click="close"
            >
              <span class="text-xl leading-none">&times;</span>
            </button>
          </div>

          <div class="px-5 py-4">
            <div class="flex items-center gap-3">
              <div
                class="inline-flex h-9 w-9 flex-none items-center justify-center rounded-full"
                :class="variant === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
              >
                <span class="text-base font-bold">{{ variant === 'success' ? '✓' : '!' }}</span>
              </div>
              <div class="min-w-0 text-sm leading-5 text-gray-700">
                <slot />
              </div>
            </div>
          </div>

          <div class="flex items-center justify-end gap-2 border-t px-5 py-4">
            <slot name="actions">
              <button
                v-if="dismissible"
                type="button"
                class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900/30"
                @click="close"
              >
                OK
              </button>
            </slot>
          </div>
        </div>
      </transition>
    </div>
  </transition>
</template>