<template>
    <div class="flex flex-col items-center">
        <!-- Botão Voltar -->
        <div class="flex justify-center">
            <button
                @click="$emit('back')"
                class="text-sm text-blue-600 underline hover:text-blue-800 mb-4"
            >
                ← Change state
            </button>
        </div>

        <!-- Card Principal -->
        <div class="max-w-xl w-full bg-white p-8 rounded-xl shadow space-y-8">
            <!-- Header -->
            <div class="text-center">
                <h2
                    class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl"
                >
                    {{ $t("header.title") }} {{ state }}
                </h2>
                <p class="mt-2 text-base text-gray-600">
                    Timezone: {{ timezone }} | UTC{{
                        utcOffset >= 0 ? "+" + utcOffset : utcOffset
                    }}
                </p>
            </div>

            <!-- Formulário -->
            <transition name="fade" mode="out-in">
                <div v-if="step === 'form'" key="form">
                    <form @submit.prevent="submitAppointment">
                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-0"
                        >
                            <!-- First Name -->
                            <div>
                                <label
                                    for="firstName"
                                    class="block text-sm font-semibold text-gray-900"
                                >
                                    {{ $t("form.firstName") }}
                                </label>
                                <div class="mt-2.5">
                                    <InputField
                                        v-model="firstName"
                                        id="firstName"
                                        ref="firstNameRef"
                                        @blur="
                                            firstName =
                                                capitalizeName(firstName)
                                        "
                                        class="w-full"
                                    />
                                    <ErrorMessage :message="errors.firstName" />
                                </div>
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label
                                    for="lastName"
                                    class="block text-sm font-semibold text-gray-900"
                                >
                                    {{ $t("form.lastName") }}
                                </label>
                                <div class="mt-2.5">
                                    <InputField
                                        v-model="lastName"
                                        id="lastName"
                                        ref="lastNameRef"
                                        @blur="
                                            lastName = capitalizeName(lastName)
                                        "
                                        class="w-full"
                                    />
                                    <ErrorMessage :message="errors.lastName" />
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="sm:col-span-2">
                                <label
                                    class="block text-sm font-semibold text-gray-900"
                                >
                                    {{ $t("form.phone") }}
                                </label>
                                <div class="mt-2.5">
                                    <PhoneField
                                        v-model="phone"
                                        ref="phoneRef"
                                        class="w-full"
                                        id="phone"
                                    />
                                    <ErrorMessage :message="errors.phone" />
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="sm:col-span-2">
                                <label
                                    for="email"
                                    class="block text-sm font-semibold text-gray-900"
                                >
                                    {{ $t("form.email") }}
                                </label>
                                <div class="mt-2.5">
                                    <InputField
                                        v-model="email"
                                        id="email"
                                        type="email"
                                        ref="emailRef"
                                        class="w-full"
                                    />
                                    <ErrorMessage :message="errors.email" />
                                </div>
                            </div>

                            <!-- Seletor de Datas -->
                            <div class="sm:col-span-2">
                                <label
                                    class="block text-sm font-semibold text-gray-900"
                                    >Select a date</label
                                >
                                <div class="mt-2.5">
                                    <DateSelector
                                        @select="handleDateSelected"
                                    />
                                    <ErrorMessage
                                        :message="errors.selectedDate"
                                    />
                                </div>
                            </div>

                            <!-- Horários Disponíveis -->
                            <div class="sm:col-span-2">
                                <LoadingSpinner
                                    v-if="isLoadingTimes"
                                    message="Fetching available times..."
                                />

                                <div
                                    v-if="availableTimes.length"
                                    class="space-y-4"
                                >
                                    <h3
                                        class="text-sm font-semibold text-gray-900"
                                    >
                                        Available times:
                                    </h3>
                                    <ErrorMessage
                                        :message="errors.selectedTime"
                                    />

                                    <div
                                        v-for="(group, period) in groupedTimes"
                                        :key="period"
                                        class="mb-3"
                                    >
                                        <h4
                                            class="text-sm font-medium text-gray-700 mb-1"
                                        >
                                            {{ period }}
                                        </h4>
                                        <div class="flex flex-wrap gap-2">
                                            <TimeButton
                                                v-for="time in group"
                                                :key="time.original"
                                                :time="time.local"
                                                :selected="
                                                    selectedTime ===
                                                    time.original
                                                "
                                                @select="
                                                    selectedTime = time.original
                                                "
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="mt-8">
                            <button
                                type="submit"
                                :disabled="isLoading"
                                class="block w-full rounded-md bg-indigo-600 px-4 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {{
                                    isLoading
                                        ? "Booking..."
                                        : "Confirm Appointment"
                                }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tela de Confirmação -->
                <div v-else-if="step === 'confirmation'" key="confirmation">
                    <Confirmation
                        v-if="step === 'confirmed'"
                        :data="confirmedData"
                        @newAppointment="resetForm"
                    />
                </div>
            </transition>

            <!-- Mensagens -->
            <div
                v-if="successMessage"
                class="text-green-600 text-sm text-center mt-2"
            >
                {{ successMessage }}
            </div>
            <div
                v-if="errorMessage"
                class="text-red-600 text-sm text-center mt-2"
            >
                {{ errorMessage }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { nextTick } from "vue";
import { DateTime } from "luxon";
import InputField from "./InputField.vue";
import TimeButton from "./TimeButton.vue";
import DateSelector from "@/components/appointments/DateSelector.vue";
import Confirmation from "@/components/appointments/Confirmation.vue";
import LoadingSpinner from "@/components/common/LoadingSpinner.vue";
import ErrorMessage from "@/components/form/ErrorMessage.vue";
import PhoneField from "@/components/form/PhoneField.vue";

async function focusAndScrollTo(ref) {
    await nextTick();
    const input = ref?.value?.inputRef;
    if (input) {
        input.focus();
        input.scrollIntoView({ behavior: "smooth", block: "center" });
    }
}

const props = defineProps({
    timezone: String,
    utcOffset: Number,
    state: String,
});

const emit = defineEmits(["back", "confirmed"]);

const CLINIC_TIMEZONE = "America/New_York";

const firstName = ref("");
const lastName = ref("");
const phone = ref("");
const email = ref("");

const selectedDate = ref("");
const selectedTime = ref("");
const availableTimes = ref([]);

const timeZoneClient = ref(props.timezone);

const isLoading = ref(false);
const isLoadingTimes = ref(false);
const successMessage = ref("");
const errorMessage = ref("");

const step = ref("form"); // 'form' | 'loading' | 'confirmed'
const confirmedData = ref(null);

const firstNameRef = ref(null);
const lastNameRef = ref(null);
const phoneRef = ref(null);
const emailRef = ref(null);

const errors = ref({
    firstName: "",
    lastName: "",
    email: "",
    phone: "",
    selectedDate: "",
    selectedTime: "",
});

function validateForm() {
    let isValid = true;

    errors.value = {
        firstName: "",
        lastName: "",
        email: "",
        phone: "",
        selectedDate: "",
        selectedTime: "",
    };

    /*
    const parsedPhone = getParsedPhoneNumber();

    if (!parsedPhone) {
        errors.value.phone = "Please enter a valid phone number.";
        isValid = false;
    }
    */
    if (!phoneRef.value?.isValid) {
        errors.value.phone = "Invalid phone number.";
        isValid = false;
    }

    if (!firstName.value) {
        errors.value.firstName = "First name is required";
        isValid = false;
    }

    if (!lastName.value) {
        errors.value.lastName = "Last name is required";
        isValid = false;
    }

    if (!phoneRef.value?.isValid) {
        errors.value.phone = "Please enter a valid phone number.";
        isValid = false;
    }

    if (!email.value) {
        errors.value.email = "Email is required";
        isValid = false;
    }

    if (!selectedDate.value) {
        errors.value.selectedDate = "Select a date";
        isValid = false;
    }

    if (!selectedTime.value) {
        errors.value.selectedTime = "Select a time";
        isValid = false;
    }

    if (!/\S+@\S+\.\S+/.test(email.value)) {
        errors.value.email = "Please enter a valid email address.";
        isValid = false;
    }

    return isValid;
}

// Group available times by time period
const groupedTimes = computed(() => {
    const groups = {
        Morning: [],
        Afternoon: [],
        Evening: [],
    };

    availableTimes.value.forEach((t) => {
        const hour = parseInt(t.local.split(":")[0]);
        if (hour < 12) groups.Morning.push(t);
        else if (hour < 18) groups.Afternoon.push(t);
        else groups.Evening.push(t);
    });

    // Ordenar cada grupo por horário local
    Object.keys(groups).forEach((period) => {
        groups[period].sort((a, b) => {
            return a.local.localeCompare(b.local);
        });
    });

    return groups;
});

// Fetch available times from API
async function fetchAvailableTimes(date) {
    if (!date) return;

    availableTimes.value = [];
    selectedTime.value = "";
    isLoadingTimes.value = true;
    errorMessage.value = "";

    try {
        const response = await fetch(
            `/api/horarios?date=${date}&tz=${timeZoneClient.value}`,
            {
                headers: { "Content-Type": "application/json" },
            }
        );

        if (!response.ok) throw new Error("Failed to fetch times.");

        const data = await response.json();

        availableTimes.value = data.map((item) => {
            const originalTime = item.time;

            const clinicDateTime = DateTime.fromFormat(
                `${date} ${originalTime}`,
                "yyyy-MM-dd HH:mm",
                {
                    zone: CLINIC_TIMEZONE,
                }
            );

            const clientDateTime = clinicDateTime.setZone(timeZoneClient.value);

            return {
                original: originalTime,
                local: clientDateTime.toFormat("HH:mm"),
            };
        });
    } catch (e) {
        errorMessage.value =
            "Could not load available times. Please try again.";
    } finally {
        isLoadingTimes.value = false;
    }
}

function handleDateSelected(date) {
    selectedDate.value = date;
    fetchAvailableTimes(date);
}

async function submitAppointment() {
    if (!validateForm()) {
        return;
    }
    isLoading.value = true;
    errorMessage.value = "";
    successMessage.value = "";

    if (
        !firstName.value ||
        !lastName.value ||
        !phone.value ||
        !email.value ||
        !selectedDate.value ||
        !selectedTime.value
    ) {
        errorMessage.value = "Please fill in all fields and select a time.";
        isLoading.value = false;
        return;
    }

    const localTime =
        availableTimes.value.find((t) => t.original === selectedTime.value)
            ?.local || selectedTime.value;

    const payload = {
        name: `${firstName.value} ${lastName.value}`.trim(),
        phone: phone.value, //phoneCode.value + phone.value,
        //countryCode: phoneData.value.countryCallingCode, // "1"
        //nationalNumber: phoneData.value.nationalNumber, // "2131313131"
        email: email.value,
        date: selectedDate.value,
        time: selectedTime.value,
        localTime,
        timezone: timeZoneClient.value,
        state: props.state,
        offset: props.utcOffset,
    };
    //console.log("Payload enviado:", payload);

    try {
        const response = await fetch("/api/agendamentos", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload),
        });

        if (!response.ok) {
            const error = await response.json();
            console.error("Erro:", errorData);
            throw new Error(error.message || "Failed to book appointment.");
        }

        successMessage.value = "✅ Appointment successfully booked!";
    } catch (err) {
        if (err.errors) {
            const errorKeys = Object.keys(err.errors);

            if (errorKeys.includes("firstName")) {
                focusAndScrollTo(firstNameRef);
            } else if (errorKeys.includes("lastName")) {
                focusAndScrollTo(lastNameRef);
            } else if (errorKeys.includes("email")) {
                focusAndScrollTo(emailRef);
            } else if (errorKeys.includes("phone")) {
                focusAndScrollTo(phoneRef);
            }

            const messages = Object.values(err.errors).flat();
            erro.value = messages.join("\n");
        } else {
            erro.value = err.message || "Erro inesperado";
        }
        errorMessage.value = err.message;
        console.error("Erro geral:", err);
    } finally {
        isLoading.value = false;
        confirmedData.value = payload;
        step.value = "confirmed";
        emit("confirmed", {
            name: `${firstName.value} ${lastName.value}`,
            date: selectedDate.value,
            time: selectedTime.value,
            timezone: props.timezone,
            localTime,
            email: email.value,
            phone: phone.value,
        });
        // Clear form
        firstName.value = "";
        lastName.value = "";
        phone.value = "";
        email.value = "";
        selectedDate.value = "";
        selectedTime.value = "";
        availableTimes.value = [];
    }
}

function resetForm() {
    step.value = "form";

    // Opcional: limpa os campos
    firstName.value = "";
    lastName.value = "";
    phone.value = "";
    email.value = "";
    selectedDate.value = "";
    selectedTime.value = "";
    availableTimes.value = [];
}

function capitalizeName(name) {
    return name
        .toLowerCase()
        .split(" ")
        .map((part) => part.charAt(0).toUpperCase() + part.slice(1))
        .join(" ");
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.3s ease;
}
.fade-enter-from {
    opacity: 0;
    transform: translateY(10px) scale(0.98);
}
.fade-leave-to {
    opacity: 0;
    transform: translateY(-10px) scale(0.98);
}
.input {
    @apply w-full border px-4 py-2 rounded focus:outline-none focus:ring;
}
</style>
