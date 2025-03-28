<template>
    <div>
        <!--<label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
            Phone
        </label>-->

        <div
            class="flex items-center rounded-md border border-gray-300 bg-white px-3 py-0 shadow-sm focus-within:ring-2 focus-within:ring-blue-400 focus-within:ring-offset-1"
        >
            <!-- Country Select -->
            <select
                v-model="selectedCountry"
                class="h-11 rounded-l-md border-r focus:outline-none"
                @change="onCountryChange"
            >
                <option
                    v-for="country in countries"
                    :key="country.code"
                    :value="country.code"
                >
                    {{ country.name }} ({{ country.dialCode }})
                </option>
            </select>

            <!-- Phone Input -->
            <input
                ref="inputRef"
                id="phone"
                type="tel"
                class="h-11 px-3 flex-1 focus:outline-none text-gray-900"
                v-model="phoneNumber"
                :maxlength="maxLength"
                @input="formatPhoneNumber"
                placeholder="123-456-7890"
            />
        </div>

        <!-- Error Message -->
        <p v-if="!isValid" class="text-sm text-red-600 mt-1">
            Invalid phone number
        </p>
    </div>
</template>

<script setup>
import { ref, watch, defineEmits, defineExpose, defineProps } from "vue";
import { AsYouType, parsePhoneNumberFromString } from "libphonenumber-js";

// Props e emits
const props = defineProps({
    modelValue: String,
});
const emit = defineEmits(["update:modelValue"]);

// Países disponíveis
const countries = ref([
    { code: "US", name: "US", dialCode: "+1" },
    { code: "CA", name: "CA", dialCode: "+1" },
    { code: "BR", name: "BR", dialCode: "+55" },
    { code: "MX", name: "MX", dialCode: "+52" },
    { code: "ES", name: "ES", dialCode: "+34" },
]);

const selectedCountry = ref("US");
const phoneNumber = ref("");
const maxLength = ref(15);
const isValid = ref(true);
const inputRef = ref(null);

// Atualiza maxlength manualmente baseado no país
const updateMaxLength = () => {
    switch (selectedCountry.value) {
        case "US":
        case "CA":
            maxLength.value = 14;
            break;
        case "BR":
            maxLength.value = 15;
            break;
        case "MX":
        case "ES":
            maxLength.value = 12;
            break;
        default:
            maxLength.value = 14;
            break;
    }
};

const onCountryChange = () => {
    phoneNumber.value = "";
    emit("update:modelValue", ""); // limpa no pai também
    isValid.value = true;
    updateMaxLength();
};

// Formata e valida o número
const formatPhoneNumber = () => {
    const rawDigits = phoneNumber.value.replace(/\D/g, "");

    const dialCode =
        countries.value.find((c) => c.code === selectedCountry.value)
            ?.dialCode || "";
    const fullNumber = dialCode + rawDigits;

    const parsed = parsePhoneNumberFromString(fullNumber);

    isValid.value = parsed?.isValid() || false;

    // Formata para exibição enquanto digita
    const formatter = new AsYouType(selectedCountry.value);
    phoneNumber.value = formatter.input(phoneNumber.value);

    // Envia para o pai o número em E.164 (ou string vazia se inválido)
    emit("update:modelValue", parsed?.format("E.164") || "");
};

// Sincroniza modelValue com input local
watch(
    () => props.modelValue,
    (newVal) => {
        if (!newVal) phoneNumber.value = "";
    }
);

// Inicializa
updateMaxLength();

// Permite acessar validação externamente (opcional)
defineExpose({ isValid, inputRef });
</script>
