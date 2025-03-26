<!-- HorarioButton.vue -->
<template>
    <button
      @click="$emit('select', hora)"
      :class="[
        'px-4 py-2 rounded border text-sm',
        selected ? 'bg-blue-600 text-white' : 'bg-gray-100 hover:bg-gray-200'
      ]"
    >
      {{ hora }}
    </button>
  </template>
  
  <script setup>
  defineProps({
    hora: String,
    selected: Boolean
  })
  defineEmits(['select'])
  </script>
  