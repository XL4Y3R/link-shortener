<!-- resources/js/App.vue -->
<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-white flex flex-col items-center px-4 py-6">
    <!-- Logo ou nome -->
    <header class="mb-8 text-center">
      <h1 class="text-3xl font-bold text-blue-700 tracking-wide">HealthFare Clinic</h1>
      <p class="text-gray-500">Agendamentos</p>
    </header>

    <!-- Conteúdo principal -->
    <main class="w-full max-w-2xl">
      <Agendamento />
    </main>

    <!-- Rodapé simples -->
    <footer class="mt-10 text-sm text-gray-400">
      &copy; {{ new Date().getFullYear() }} HealthFare Clinic. Todos os direitos reservados.
    </footer>
  </div>
</template>

<script setup>
import Agendamento from './components/Agendamento.vue'
</script>
