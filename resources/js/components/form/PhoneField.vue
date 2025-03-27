<template>
    <div class="flex items-start gap-2">
        <div class="col-span-2 sm:col-span-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Phone Number
            </label>

            <div class="flex gap-2">
                <!-- Country code -->
                <select
                    v-model="phoneCountryCode"
                    class="w-24 px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400"
                >
                    <option value="+1">🇺🇸 +1</option>
                    <option value="+44">🇬🇧 +44</option>
                    <option value="+55">🇧🇷 +55</option>
                    <!-- Adicione mais se quiser -->
                </select>

                <!-- Phone input -->
                <input
                    v-model="phoneNumber"
                    type="tel"
                    placeholder="123-456-7890"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from "vue";
import IMask from "imask";
import { parsePhoneNumberFromString } from "libphonenumber-js";

const emit = defineEmits(["update:modelValue"]);

const props = defineProps({
    modelValue: String,
});

// List of countries (add more as needed)
const countries = [
    {
        code: "US",
        dialCode: "1",
        flag: "🇺🇸",
        label: "+1",
        mask: "(000) 000-0000",
    },
    {
        code: "GB",
        dialCode: "44",
        flag: "🇬🇧",
        label: "+44",
        mask: "00 0000 0000",
    },
    {
        code: "BR",
        dialCode: "55",
        flag: "🇧🇷",
        label: "+55",
        mask: "(00) 00000-0000",
    },
];

const countryCode = ref(countries[0]);
const phoneRaw = ref("");
const error = ref("");
const phoneInput = ref(null);

let maskInstance = null;

// Apply input mask
onMounted(() => {
    maskInstance = IMask(phoneInput.value, {
        mask: countryCode.value.mask,
    });
});

// Update mask when changing country
watch(countryCode, () => {
    if (maskInstance) {
        maskInstance.updateOptions({ mask: countryCode.value.mask });
        phoneRaw.value = "";
        emit("update:modelValue", "");
        error.value = "";
    }
});

// Validate and emit formatted phone
watch(phoneRaw, () => {
    const numberToValidate = `+${
        countryCode.value.dialCode
    }${phoneRaw.value.replace(/\D/g, "")}`;
    const parsed = parsePhoneNumberFromString(numberToValidate);

    if (!parsed || !parsed.isValid()) {
        error.value = "Invalid phone number";
        emit("update:modelValue", "");
    } else {
        error.value = "";
        emit("update:modelValue", parsed.number); // E.164 format
    }
});
</script>
