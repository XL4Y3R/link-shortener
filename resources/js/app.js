import './bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css'
import '@coreui/coreui/dist/css/coreui.min.css'
//import * as bootstrap from 'bootstrap'
import { createApp } from 'vue'
import AgendamentoForm from './components/AgendamentoForm.vue'

const app = createApp({})
app.component('agendamento-form', AgendamentoForm)
app.mount('#app')
