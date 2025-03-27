<template>
    <select
        :value="modelValue"
        @change="$emit('update:modelValue', $event.target.value)"
        class="px-2 py-2 border rounded-lg bg-white"
    >
        <option value="+1">🇺🇸 +1</option>
        <option value="+44">🇬🇧 +44</option>
        <option value="+55">🇧🇷 +55</option>
        <option value="+33">🇫🇷 +33</option>
        <!-- adicione mais conforme quiser -->
    </select>
</template>

<script setup>
defineProps({
    modelValue: String,
});

defineEmits(["update:modelValue"]);
</script>
