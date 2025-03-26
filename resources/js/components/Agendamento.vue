<template>
    <div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow space-y-6">
      <h2 class="text-2xl font-bold mb-4">Agendamento</h2>
  
      <!-- Dados do cliente -->
      <div class="grid grid-cols-2 gap-4">
        <input v-model="nome" type="text" placeholder="Nome" class="input" />
        <input v-model="sobrenome" type="text" placeholder="Sobrenome" class="input" />
        <input v-model="telefone" type="text" placeholder="Telefone" class="input" />
        <input v-model="email" type="email" placeholder="Email" class="input" />
      </div>
  
      <!-- Seleção de data -->
      <div>
        <label class="font-medium">Escolha uma data:</label>
        <input type="date" v-model="dataSelecionada" @change="buscarHorarios" class="input mt-1" />
      </div>
  
      <!-- Horários disponíveis -->
      <div v-if="horarios.length" class="space-y-4">
        <h3 class="font-semibold text-lg">Horários disponíveis:</h3>
  
        <div v-for="(grupo, periodo) in horariosPorPeriodo" :key="periodo">
          <h4 class="text-md font-medium mb-1">{{ periodo }}</h4>
          <div class="flex flex-wrap gap-2">
            <button
              v-for="hora in grupo"
              :key="hora"
              @click="horarioSelecionado = hora"
              :class="[
                'px-4 py-2 rounded border cursor-pointer',
                horarioSelecionado === hora ? 'bg-blue-600 text-white' : 'bg-gray-100 hover:bg-gray-200'
              ]"
            >
              {{ hora }}
            </button>
          </div>
        </div>
      </div>
  
      <!-- Botão de envio -->
      <button
        @click="enviarAgendamento"
        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 w-full"
      >
        Confirmar Agendamento
      </button>
    </div>
  </template>
  
  <script setup>
import { ref, computed } from 'vue'
import InputField from './InputField.vue'


const nome = ref('')
const sobrenome = ref('')
const telefone = ref('')
const email = ref('')
const dataSelecionada = ref('')
const horarios = ref([])
const horarioSelecionado = ref('')
const carregandoHorarios = ref(false)
const mensagem = ref('')
const erro = ref('')

// Agrupar horários por período (manhã, tarde, noite)
const horariosPorPeriodo = computed(() => {
  const grupos = {
    Manhã: [],
    Tarde: [],
    Noite: [],
  }

  horarios.value.forEach(h => {
    const [hora] = h.split(':')
    const hNum = parseInt(hora)
    if (hNum < 12) grupos['Manhã'].push(h)
    else if (hNum < 18) grupos['Tarde'].push(h)
    else grupos['Noite'].push(h)
  })

  return grupos
})

// Buscar horários reais da API
async function buscarHorarios() {
  if (!dataSelecionada.value) return

  horarios.value = []
  horarioSelecionado.value = ''
  carregandoHorarios.value = true
  erro.value = ''

  try {
    const response = await fetch(`/api/horarios?date=${dataSelecionada.value}`, {
      headers: {
        'Authorization': 'Bearer f62cfa835ccb11f6ae940b09cef84a54cb353b8989f79cfab9e0a846997a575e',
        'Content-Type': 'application/json',
      }
    })

    if (!response.ok) throw new Error('Erro ao buscar horários')

    const data = await response.json()

    // Mapeia apenas os valores de "time"
    horarios.value = data.map(item => item.time)
  } catch (e) {
    erro.value = 'Não foi possível carregar os horários. Tente novamente.'
  } finally {
    carregandoHorarios.value = false
  }
}


// Enviar agendamento real
async function enviarAgendamento() {
  erro.value = ''
  mensagem.value = ''

  if (!nome.value || !sobrenome.value || !telefone.value || !email.value || !dataSelecionada.value || !horarioSelecionado.value) {
    erro.value = 'Por favor, preencha todos os campos e selecione um horário.'
    return
  }

  const payload = {
    nome: nome.value,
    sobrenome: sobrenome.value,
    telefone: telefone.value,
    email: email.value,
    data: dataSelecionada.value,
    horario: horarioSelecionado.value,
  }

  try {
    const response = await fetch('/api/agendamentos', {
      method: 'POST',
      headers: {
        'Authorization': 'Bearer f62cfa835ccb11f6ae940b09cef84a54cb353b8989f79cfab9e0a846997a575e',
        'Content-Type': 'application/json',
    },
      body: JSON.stringify(payload),
    })

    if (!response.ok) throw new Error('Erro ao agendar')

    mensagem.value = '✅ Agendamento realizado com sucesso!'

    // Limpa os campos
    nome.value = ''
    sobrenome.value = ''
    telefone.value = ''
    email.value = ''
    dataSelecionada.value = ''
    horarioSelecionado.value = ''
    horarios.value = []
  } catch (e) {
    erro.value = 'Não foi possível agendar. Tente novamente mais tarde.'
  }
}
</script>
  
  <style scoped>
  .input {
    @apply w-full border px-4 py-2 rounded focus:outline-none focus:ring;
  }
  </style>
  