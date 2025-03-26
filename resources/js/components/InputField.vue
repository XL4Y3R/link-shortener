<!-- InputField.vue -->
<template>
    <div class="space-y-1">
      <label :for="id" class="text-sm font-medium text-gray-700">{{ label }}</label>
      <input
        :id="id"
        :type="type"
        v-model="modelValue"
        :placeholder="placeholder"
        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400"
        @input="$emit('update:modelValue', $event.target.value)"
      />
    </div>
  </template>
  
  <script setup>
  defineProps({
    modelValue: String,
    label: String,
    type: {
      type: String,
      default: 'text'
    },
    id: String,
    placeholder: String
  })
  
  defineEmits(['update:modelValue'])
  </script>
  