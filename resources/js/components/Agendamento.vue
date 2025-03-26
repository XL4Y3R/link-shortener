<template>
    <div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow space-y-6">
      <h2 class="text-2xl font-bold mb-4">Agendamento</h2>
  
      <!-- Dados do cliente -->
      <div class="grid grid-cols-2 gap-4">
        <InputField v-model="nome" label="Nome" id="nome" />        
        <InputField v-model="sobrenome" label="Sobrenome" id="sobrenome" />
        <InputField v-model="telefone" label="Telefone" id="telefone" />
        <InputField v-model="email" label="Email" id="Email"  type="email"/>
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
            <HorarioButton
                v-for="hora in grupo"
                :key="hora"
                :hora="hora"
                :selected="horarioSelecionado === hora"
                @select="horarioSelecionado = $event"
              />
            
          </div>
        </div>
        <!-- Botão de envio -->
        <button
          @click="enviarAgendamento"
          :disabled="carregando"
          class="w-full flex justify-center items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-xl shadow transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <span v-if="!carregando">Confirmar Agendamento</span>
          <span v-else>Agendando...</span>
        </button>
      </div>
      <div v-if="mensagem" class="text-green-600 text-sm text-center mt-2">{{ mensagem }}</div>
      <div v-if="erro" class="text-red-600 text-sm text-center mt-2">{{ erro }}</div>
    </div>
  </template>
  
<script setup>
import { ref, computed } from 'vue'
import InputField from './InputField.vue'
import HorarioButton from './HorarioButton.vue'


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
const carregando = ref(false)


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
  carregando.value = true
  try {   
    erro.value = ''
    mensagem.value = ''

    // Validação básica
    if (!nome.value || !sobrenome.value || !telefone.value || !email.value || !dataSelecionada.value || !horarioSelecionado.value) {
      erro.value = 'Por favor, preencha todos os campos e selecione um horário.'
      return
    }

    // Payload com os nomes corretos exigidos pela API
    const payload = {
      name: `${nome.value} ${sobrenome.value}`.trim(),
      phone: telefone.value,
      email: email.value,
      date: dataSelecionada.value,
      time: horarioSelecionado.value
    }

    try {
      const response = await fetch('/api/agendamentos', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload)
      })

      if (!response.ok) {
        const errorData = await response.json()
        throw new Error(errorData.message || 'Erro ao salvar o agendamento.')
      }

      mensagem.value = '✅ Agendamento realizado com sucesso!'

      // Limpa o formulário
      nome.value = ''
      sobrenome.value = ''
      telefone.value = ''
      email.value = ''
      dataSelecionada.value = ''
      horarioSelecionado.value = ''
      horarios.value = []
    } catch (e) {
      erro.value = e.message
    }
  } finally {
    carregando.value = false
  }

  
}

</script>
  
  <style scoped>
  .input {
    @apply w-full border px-4 py-2 rounded focus:outline-none focus:ring;
  }
  </style>
  