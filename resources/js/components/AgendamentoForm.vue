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

                    <CFormSelect v-model="form.time" :options="horariosDisponiveis" label="Horário" required />

                    <CButton type="submit" color="primary" class="mt-3">Agendar</CButton>
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
import { ref } from 'vue'
import axios from 'axios'

const form = ref({
    name: '',
    phone: '',
    email: '',
    date: '',
    time: ''
})

const horariosDisponiveis = ref([])

const fetchHorarios = async () => {
    if (!form.value.date) return

    try {
        const response = await axios.get('https://teste.xl4y3r.com/api/available-times', {
            params: { date: form.value.date },
            headers: {
                Authorization: 'Bearer f62cfa835ccb11f6ae940b09cef84a54cb353b8989f79cfab9e0a846997a575e'
            }
        })

        horariosDisponiveis.value = response.data.map((item) => ({
            value: item.time,
            label: item.time
        }))
    } catch (err) {
        console.error('Erro ao buscar horários:', err)
    }
}

const submitForm = async () => {
    try {
        await axios.post('https://teste.xl4y3r.com/api/appointments', {
            name: form.value.name,
            phone: form.value.phone,
            email: form.value.email,
            date: form.value.date,
            time: form.value.time,
            scheduled_at: `${form.value.date} ${form.value.time}` //${form.value.time.padStart(5, '0')}

        }, {
            headers: {
                Authorization: 'Bearer f62cfa835ccb11f6ae940b09cef84a54cb353b8989f79cfab9e0a846997a575e'
            }
        })

        alert('Agendamento realizado com sucesso!')
    } catch (err) {
        alert('Erro ao agendar: ' + (err.response?.data?.message || err.message))
    }
}
</script>
