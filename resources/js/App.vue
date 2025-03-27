<template>
    <div class="flex flex-col min-h-screen">
        <select v-model="$i18n.locale" class="text-sm border p-1 rounded">
            <option value="en">English</option>
            <option value="pt">Português</option>
        </select>
        <header class="text-center p-6">
            <h1 class="text-3xl font-bold text-blue-700 tracking-wide">
                HealthFare Clinic
            </h1>
            <p class="text-gray-500">Portal de Agendamento</p>
        </header>

        <transition name="fade" mode="out-in">
            <div v-if="!selectedState" key="state">
                <StateSelector @stateSelected="handleStateSelected" />
            </div>

            <Appointment
                v-else-if="step === 'form'"
                :timezone="selectedState.timezone"
                :utcOffset="selectedState.offset"
                :state="selectedState.estado"
                key="form"
                @back="resetState"
                @confirmed="handleConfirmed"
            />

            <div v-else-if="step === 'confirmed'" key="confirmed">
                <AppointmentConfirmed
                    @restart="resetState"
                    :data="confirmedData"
                />
            </div>
        </transition>

        <footer class="text-sm text-center text-gray-400 py-4">
            &copy; {{ new Date().getFullYear() }} HealthFare Clinic. Todos os
            direitos reservados.
        </footer>
    </div>
</template>

<script setup>
import { ref } from "vue";
import StateSelector from "@/components/appointments/StateSelector.vue";
import Appointment from "@/components/appointments/Appointment.vue";
import AppointmentConfirmed from "@/components/appointments/AppointmentConfirmed.vue";

const selectedState = ref(null);
const step = ref("form");
const confirmedData = ref(null);

function handleStateSelected(state) {
    selectedState.value = state;
    step.value = "form";
}

function handleConfirmed(data) {
    confirmedData.value = data;
    step.value = "confirmed";
}

function resetState() {
    selectedState.value = null;
    step.value = "form";
}
</script>
