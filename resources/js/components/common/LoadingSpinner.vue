<template>
    <div class="flex flex-col items-center justify-center py-10 text-gray-600">
        <svg
            class="animate-spin h-8 w-8 text-blue-600 mb-2"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
        >
            <circle
                class="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"
            />
            <path
                class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
            />
        </svg>
        <span class="text-sm">{{ message }}</span>
    </div>
</template>

<script setup>
const props = defineProps({
    message: {
        type: String,
        default: "Loading, please wait...",
    },
});
</script>
