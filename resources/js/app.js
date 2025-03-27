import "./bootstrap";
import "bootstrap/dist/css/bootstrap.min.css";
import "@coreui/coreui/dist/css/coreui.min.css";
//import * as bootstrap from 'bootstrap'
import { createApp } from "vue";
//import AgendamentoForm from './components/AgendamentoForm.vue'
import App from "./App.vue";
import i18n from "./i18n";

const app = createApp(App);
//app.component('agendamento-form', AgendamentoForm)
//app.mount('#app')
app.use(i18n);
app.mount("#app");
//createApp(App).mount("#app");
