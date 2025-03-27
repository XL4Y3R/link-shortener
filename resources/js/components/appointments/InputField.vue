<template>
    <div class="space-y-1">
        <label :for="id" class="text-sm font-medium text-gray-700">{{
            label
        }}</label>
        <input
            :id="id"
            :type="type"
            :value="modelValue"
            :placeholder="placeholder"
            @input="$emit('update:modelValue', $event.target.value)"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400"
            ref="inputRef"
        />
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";

const inputRef = ref(null);
defineExpose({ inputRef }); //deixa o DOM disponível pro componente pai

defineProps({
    modelValue: [String, Number],
    label: String,
    type: {
        type: String,
        default: "text",
    },
    id: String,
    placeholder: String,
});

defineEmits(["update:modelValue"]);
</script>
