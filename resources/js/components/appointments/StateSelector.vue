<template>
    <div class="max-w-2xl mx-auto text-center space-y-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Which state do you live in?
        </h1>
        <p class="text-gray-500">
            This helps us show times in your local timezone.
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <button
                v-for="estado in estadosEua"
                :key="estado.estado"
                @click="selecionarEstado(estado)"
                class="bg-emerald-600 text-white py-3 px-6 rounded-xl hover:bg-emerald-700 transition-all"
            >
                {{ estado.estado }}
            </button>
        </div>
    </div>
</template>

<script setup>
//import { defineEmits } from 'vue'

const emit = defineEmits(["stateSelected"]);

const estadosEua = [
    { estado: "Alabama", timezone: "America/Chicago", offset: -6 },
    { estado: "Alaska", timezone: "America/Anchorage", offset: -9 },
    { estado: "Arizona", timezone: "America/Phoenix", offset: -7 },
    { estado: "Arkansas", timezone: "America/Chicago", offset: -6 },
    { estado: "California", timezone: "America/Los_Angeles", offset: -8 },
    { estado: "Colorado", timezone: "America/Denver", offset: -7 },
    { estado: "Connecticut", timezone: "America/New_York", offset: -5 },
    { estado: "Delaware", timezone: "America/New_York", offset: -5 },
    { estado: "Florida", timezone: "America/New_York", offset: -5 },
    { estado: "Georgia", timezone: "America/New_York", offset: -5 },
    { estado: "Hawaii", timezone: "Pacific/Honolulu", offset: -10 },
    { estado: "Idaho", timezone: "America/Denver", offset: -7 },
    { estado: "Illinois", timezone: "America/Chicago", offset: -6 },
    { estado: "Indiana", timezone: "America/Indiana/Indianapolis", offset: -5 },
    { estado: "Iowa", timezone: "America/Chicago", offset: -6 },
    { estado: "Kansas", timezone: "America/Chicago", offset: -6 },
    { estado: "Kentucky", timezone: "America/New_York", offset: -5 },
    { estado: "Louisiana", timezone: "America/Chicago", offset: -6 },
    { estado: "Maine", timezone: "America/New_York", offset: -5 },
    { estado: "Maryland", timezone: "America/New_York", offset: -5 },
    { estado: "Massachusetts", timezone: "America/New_York", offset: -5 },
    { estado: "Michigan", timezone: "America/Detroit", offset: -5 },
    { estado: "Minnesota", timezone: "America/Chicago", offset: -6 },
    { estado: "Mississippi", timezone: "America/Chicago", offset: -6 },
    { estado: "Missouri", timezone: "America/Chicago", offset: -6 },
    { estado: "Montana", timezone: "America/Denver", offset: -7 },
    { estado: "Nebraska", timezone: "America/Chicago", offset: -6 },
    { estado: "Nevada", timezone: "America/Los_Angeles", offset: -8 },
    { estado: "New Hampshire", timezone: "America/New_York", offset: -5 },
    { estado: "New Jersey", timezone: "America/New_York", offset: -5 },
    { estado: "New Mexico", timezone: "America/Denver", offset: -7 },
    { estado: "New York", timezone: "America/New_York", offset: -5 },
    { estado: "North Carolina", timezone: "America/New_York", offset: -5 },
    { estado: "North Dakota", timezone: "America/Chicago", offset: -6 },
    { estado: "Ohio", timezone: "America/New_York", offset: -5 },
    { estado: "Oklahoma", timezone: "America/Chicago", offset: -6 },
    { estado: "Oregon", timezone: "America/Los_Angeles", offset: -8 },
    { estado: "Pennsylvania", timezone: "America/New_York", offset: -5 },
    { estado: "Rhode Island", timezone: "America/New_York", offset: -5 },
    { estado: "South Carolina", timezone: "America/New_York", offset: -5 },
    { estado: "South Dakota", timezone: "America/Chicago", offset: -6 },
    { estado: "Tennessee", timezone: "America/Chicago", offset: -6 },
    { estado: "Texas", timezone: "America/Chicago", offset: -6 },
    { estado: "Utah", timezone: "America/Denver", offset: -7 },
    { estado: "Vermont", timezone: "America/New_York", offset: -5 },
    { estado: "Virginia", timezone: "America/New_York", offset: -5 },
    { estado: "Washington", timezone: "America/Los_Angeles", offset: -8 },
    { estado: "West Virginia", timezone: "America/New_York", offset: -5 },
    { estado: "Wisconsin", timezone: "America/Chicago", offset: -6 },
    { estado: "Wyoming", timezone: "America/Denver", offset: -7 },
];

function selecionarEstado(estado) {
    emit("stateSelected", estado);
}
</script>
