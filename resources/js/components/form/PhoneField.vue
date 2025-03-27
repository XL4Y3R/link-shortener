<template>
    <div>
        <label class="text-sm font-medium text-gray-700"> Phone Number </label>
        <div class="grid grid-cols-[auto_1fr] gap-0">
            <select
                v-model="countryCode"
                class="w-18 px-0 py-0 border rounded-lg focus:outline-none focus:ring focus:border-blue-400"
            >
                <option value="+1">🇺🇸 +1</option>
                <option value="+44">🇬🇧 +44</option>
                <option value="+55">🇧🇷 +55</option>
            </select>

            <input
                ref="inputRef"
                type="tel"
                v-model="phoneNumber"
                placeholder="123-456-7890"
                class="w-29 px-0 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400"
                @blur="validatePhone"
            />
        </div>
        <p v-if="!isValid" class="text-red-600 text-sm mt-1">
            Invalid phone number
        </p>
    </div>
</template>

<script setup>
import { ref, watch, defineEmits, defineExpose } from "vue";
import { parsePhoneNumberFromString } from "libphonenumber-js";

const emit = defineEmits(["update:modelValue"]);

const countryCode = ref("+1");
const phoneNumber = ref("");
const isValid = ref(true);
const inputRef = ref(null);

function validatePhone() {
    const digits = `${countryCode.value}${phoneNumber.value}`.replace(
        /\D/g,
        ""
    );
    const parsed = parsePhoneNumberFromString(`+${digits}`);
    isValid.value = parsed?.isValid() || false;
    if (isValid.value) {
        emit("update:modelValue", parsed.format("E.164"));
    } else {
        emit("update:modelValue", "");
    }
}

watch([countryCode, phoneNumber], validatePhone);

defineExpose({ inputRef });
</script>
