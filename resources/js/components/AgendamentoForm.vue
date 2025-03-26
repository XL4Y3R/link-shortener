<template>
    <div class="container mt-4">
        <CCard>
            <CCardHeader>
                <h5>Agende seu atendimento</h5>
            </CCardHeader>
            <CCardBody>
                <CForm @submit.prevent="submitForm">
                    <CFormInput v-model="form.name" label="Nome" required />
                    <CFormInput v-model="form.phone" label="Telefone" required />
                    <CFormInput v-model="form.email" label="Email" type="email" />

                    <CFormInput v-model="form.date" label="Data" type="date" required @change="fetchHorarios" />

                    <CFormSelect
                    v-model="form.time"
                    label="Horário"
                    :disabled="carregandoHorarios || horariosDisponiveis.length === 0"
                    >
                    <option value="" disabled>Selecione um horário</option>
                    <option v-for="h in horariosDisponiveis" :key="h.value" :value="h.value">
                        {{ h.label }}
                    </option>
                    </CFormSelect>
                    <div v-if="carregandoHorarios" class="text-muted mt-1">
                    Carregando horários...
                    </div>

                    <div v-if="mensagemHorarios && !carregandoHorarios" class="text-danger mt-1">
                    {{ mensagemHorarios }}
                    </div>

                    <CButton
                    type="button"
                    @click="submitForm"
                    :disabled="!form.time || carregandoHorarios || carregandoAgendamento"
                    class="mt-3"
                    >
                    {{ carregandoAgendamento ? 'Agendando...' : 'Agendar' }}
                    </CButton>
                </CForm>
            </CCardBody>
        </CCard>
    </div>
</template>

<script setup>
import {
    CCard,
    CCardHeader,
    CCardBody,
    CForm,
    CFormInput,
    CFormSelect,
    CButton
} from '@coreui/vue'
import { ref, watch } from 'vue'
import axios from 'axios'

const form = ref({
    name: '',
    phone: '',
    email: '',
    date: '',
    time: ''
})

const horariosDisponiveis = ref([])
const mensagemHorarios = ref(null)
const carregandoHorarios = ref(false)
const carregandoAgendamento = ref(false)

const fetchHorarios = async () => {
  if (!form.value.date) return

  mensagemHorarios.value = null
  horariosDisponiveis.value = []
  carregandoHorarios.value = true

  try {
    const response = await axios.get('https://teste.xl4y3r.com/api/available-times', {
      params: { date: form.value.date },
      headers: {
        Authorization: 'Bearer f62cfa835ccb11f6ae940b09cef84a54cb353b8989f79cfab9e0a846997a575e'
      }
    })

    const horarios = response.data.map((item) => ({
      value: item,
      label: item
    }))

    horariosDisponiveis.value = horarios

    mensagemHorarios.value = horarios.length === 0
      ? 'Nenhum horário disponível para esta data.'
      : null
  } catch (err) {
    console.error('Erro ao buscar horários:', err)
    mensagemHorarios.value = 'Erro ao buscar horários. Tente novamente mais tarde.'
  } finally {
    carregandoHorarios.value = false
  }
}

const submitForm = async () => {
  carregandoAgendamento.value = true

  try {
    await axios.post('https://teste.xl4y3r.com/api/appointments', {
      customer_name: form.value.name,
      phone: form.value.phone,
      email: form.value.email,
      scheduled_at: `${form.value.date} ${form.value.time}`
    }, {
      headers: {
        Authorization: 'Bearer f62cfa835ccb11f6ae940b09cef84a54cb353b8989f79cfab9e0a846997a575e'
      }
    })

    alert('Agendamento realizado com sucesso!')
    form.value = { name: '', phone: '', email: '', date: '', time: '' }
    horariosDisponiveis.value = []
    mensagemHorarios.value = null

  } catch (err) {
    alert('Erro ao agendar: ' + (err.response?.data?.message || err.message))
  } finally {
    carregandoAgendamento.value = false
  }
}
</script>
