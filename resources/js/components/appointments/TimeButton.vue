<!-- HorarioButton.vue -->
<template>
    <button
        @click="$emit('select', time)"
        :class="[
            'px-4 py-2 rounded border text-sm',
            selected
                ? 'bg-blue-600 text-white'
                : 'bg-gray-100 hover:bg-gray-200',
        ]"
    >
        {{ time }}
    </button>
</template>

<script setup>
defineProps({
    time: String,
    selected: Boolean,
});
defineEmits(["select"]);
</script>
