<template>
    <div class="max-w-md mx-auto bg-white p-6 rounded-xl shadow space-y-4">
      <h2 class="text-2xl font-bold">Login do Cliente</h2>
  
      <div>
        <label class="block mb-1 font-medium">E-mail</label>
        <input
          type="email"
          v-model="email"
          class="w-full border rounded px-4 py-2 focus:outline-none focus:ring"
          placeholder="ex: cliente@email.com"
        />
      </div>
  
      <div>
        <label class="block mb-1 font-medium">Senha</label>
        <input
          type="password"
          v-model="senha"
          class="w-full border rounded px-4 py-2 focus:outline-none focus:ring"
          placeholder="Sua senha"
        />
      </div>
  
      <div v-if="erro" class="text-red-600 text-sm">{{ erro }}</div>
  
      <button
        @click="fazerLogin"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full"
      >
        Entrar
      </button>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  
  const email = ref('')
  const senha = ref('')
  const erro = ref('')
  
  function fazerLogin() {
    if (!email.value || !senha.value) {
      erro.value = 'Preencha todos os campos.'
      return
    }
  
    erro.value = ''
    // Aqui no futuro chamamos a API com fetch/axios
    alert(`Enviando login: ${email.value}`)
  }
  </script>
  