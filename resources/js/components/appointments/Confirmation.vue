<!-- components/appointments/Confirmation.vue -->
<template>
    <div
        class="max-w-md mx-auto text-center bg-white shadow rounded-xl p-8 space-y-6"
    >
        <h2 class="text-2xl font-bold text-green-600">
            Appointment Confirmed ✅
        </h2>

        <div class="text-gray-700 space-y-2 text-sm">
            <p><strong>Name:</strong> {{ data.name }}</p>
            <p><strong>Email:</strong> {{ data.email }}</p>
            <p><strong>Phone:</strong> {{ data.phone }}</p>
            <p><strong>Date:</strong> {{ data.date }}</p>
            <p><strong>Time:</strong> {{ data.localTime }}</p>
            <p><strong>Timezone:</strong> {{ data.timezone }}</p>
        </div>

        <button
            @click="$emit('newAppointment')"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition"
        >
            Book Another
        </button>
    </div>
</template>

<script setup>
const props = defineProps({
    data: Object, //payload do agendamento
});
</script>
