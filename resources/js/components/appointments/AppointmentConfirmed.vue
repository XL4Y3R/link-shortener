<template>
    <div
        class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow text-center space-y-6 animate-fade-in"
    >
        <h2 class="text-2xl font-bold text-green-600">
            Appointment Confirmed ✅
        </h2>

        <p class="text-gray-600 text-lg">
            Your appointment has been successfully scheduled.
        </p>
        <p>
            <strong>Date:</strong><br />
            {{
                DateTime.fromISO(data.date).toFormat("MM/dd/yyyy") +
                " " +
                DateTime.fromFormat(data.localTime, "HH:mm").toFormat("hh:mm a")
            }}
        </p>
        <!--
        <p>
            <strong>Time:</strong>
            {{
                DateTime.fromFormat(data.localTime, "HH:mm").toFormat("hh:mm a")
            }}
        </p>
        -->
        <p>
            <strong>Timezone:</strong><br />
            {{
                data.timezone +
                " (UTC" +
                DateTime.now().setZone(data.timezone).toFormat("Z") +
                ")"
            }}
        </p>
        <p class="text-sm text-gray-400">
            You will receive an email with the details shortly.
        </p>
        <!--
        <button
            @click="$emit('restart')"
            class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-xl transition-all"
        >
            Book Another Appointment
        </button>
        -->
    </div>
</template>

<script setup>
import { DateTime } from "luxon";

const props = defineProps({
    data: Object, //payload do agendamento
});
</script>

<style scoped>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fade-in {
    animation: fade-in 0.4s ease-out;
}
</style>
