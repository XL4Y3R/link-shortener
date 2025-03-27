<template>
    <div class="flex flex-col items-center">
        <button
            @click="emit('back')"
            class="text-sm text-blue-600 underline hover:text-blue-800 mb-4 self-start sm:self-center"
        >
            ← Change state
        </button>

        <div class="max-w-xl w-full bg-white p-6 rounded-xl shadow space-y-6">
            <h2 class="text-2xl font-bold text-center text-gray-800">
                Appointment for {{ state }}
            </h2>
            <p class="text-center text-sm text-gray-600">
                Timezone: {{ timezone }} | UTC{{
                    utcOffset >= 0 ? "+" + utcOffset : utcOffset
                }}
            </p>

            <!-- Select Date -->
            <DateSelector @select="handleDateSelected" />

            <!-- Time Slots -->
            <div v-if="availableTimes.length" class="space-y-4">
                <h3 class="font-semibold text-lg">Available times</h3>

                <div v-for="(group, period) in groupedTimes" :key="period">
                    <h4 class="text-md font-medium mb-1">{{ period }}</h4>
                    <div class="flex flex-wrap gap-2">
                        <TimeButton
                            v-for="time in group"
                            :key="time"
                            :time="time"
                            :selected="selectedTime === time"
                            @select="selectedTime = $event"
                        />
                    </div>
                </div>
            </div>

            <!-- User Info Form -->
            <div class="grid grid-cols-2 gap-4">
                <InputField
                    v-model="firstName"
                    label="First Name"
                    id="firstName"
                />
                <InputField
                    v-model="lastName"
                    label="Last Name"
                    id="lastName"
                />
                <InputField v-model="phone" label="Phone" id="phone" />
                <InputField
                    v-model="email"
                    label="Email"
                    id="email"
                    type="email"
                />
            </div>

            <!-- Submit -->
            <button
                @click="submitAppointment"
                :disabled="isLoading"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-xl shadow transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span v-if="!isLoading">Confirm Appointment</span>
                <span v-else>Booking...</span>
            </button>

            <!-- Messages -->
            <div
                v-if="successMsg"
                class="text-green-600 text-sm text-center mt-2"
            >
                {{ successMsg }}
            </div>
            <div v-if="errorMsg" class="text-red-600 text-sm text-center mt-2">
                {{ errorMsg }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import DateSelector from "./DateSelector.vue";
import TimeButton from "./TimeButton.vue";
import InputField from "./InputField.vue";
import { DateTime } from "luxon";

const props = defineProps({
    timezone: String,
    utcOffset: Number,
    state: String,
});

const emit = defineEmits(["back"]);

const selectedDate = ref(null);
const availableTimes = ref([]);
const selectedTime = ref("");
const isLoading = ref(false);
const successMsg = ref("");
const errorMsg = ref("");

const firstName = ref("");
const lastName = ref("");
const phone = ref("");
const email = ref("");

// Group times by period
const groupedTimes = computed(() => {
    const groups = {
        Morning: [],
        Afternoon: [],
        Evening: [],
    };

    availableTimes.value.forEach((time) => {
        const hour = parseInt(time.split(":")[0]);
        if (hour < 12) groups.Morning.push(time);
        else if (hour < 18) groups.Afternoon.push(time);
        else groups.Evening.push(time);
    });

    return groups;
});

// Handle date selection from DateSelector
function handleDateSelected(date) {
    selectedDate.value = date;
    fetchAvailableTimes(date);
}

// Fetch times for selected date
async function fetchAvailableTimes(date) {
    availableTimes.value = [];
    selectedTime.value = "";
    errorMsg.value = "";

    try {
        const response = await fetch(`/api/available-times?date=${date}`);
        if (!response.ok) throw new Error("Error fetching times");
        const data = await response.json();
        availableTimes.value = data.map((item) => item.time);
    } catch (err) {
        errorMsg.value = "Could not load times. Please try again.";
    }
}

// Submit the appointment
async function submitAppointment() {
    errorMsg.value = "";
    successMsg.value = "";
    isLoading.value = true;

    if (
        !firstName.value ||
        !lastName.value ||
        !phone.value ||
        !email.value ||
        !selectedDate.value ||
        !selectedTime.value
    ) {
        errorMsg.value = "Please fill all fields and select a time.";
        isLoading.value = false;
        return;
    }

    const payload = {
        name: `${firstName.value} ${lastName.value}`.trim(),
        phone: phone.value,
        email: email.value,
        date: selectedDate.value,
        time: selectedTime.value,
        timezone: props.timezone,
        state: props.state,
        offset: props.utcOffset,
    };

    try {
        const response = await fetch("/api/appointments", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload),
        });

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message || "Failed to book");
        }

        successMsg.value = "✅ Appointment successfully booked!";
        firstName.value = "";
        lastName.value = "";
        phone.value = "";
        email.value = "";
        selectedDate.value = null;
        selectedTime.value = "";
        availableTimes.value = [];
    } catch (err) {
        errorMsg.value = err.message;
    } finally {
        isLoading.value = false;
    }
}
</script>
