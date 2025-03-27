<!-- HelloUser.vue -->
<template>
    <div class="p-6 bg-white rounded-2xl shadow max-w-md mx-auto space-y-4">
      <h2 class="text-xl font-semibold">👋 Diga seu nome</h2>
  
      <input
        type="text"
        v-model="nome"
        placeholder="Digite seu nome..."
        class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:border-blue-400"
      />
  
      <p v-if="nome" class="text-lg text-green-600">Olá, {{ nome }}!</p>
  
      <button
        v-if="nome"
        @click="nome = ''"
        class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
      >
        Limpar nome
      </button>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  
  const nome = ref('')
  </script>
  