<template>
    <p
        class="text-red-500 text-xs mt-1 min-h-[1rem] leading-tight transition-opacity duration-200"
        :class="{ 'opacity-0': !message }"
    >
        {{ message || "‎" }}
        <!-- caractere invisível para manter altura -->
    </p>
</template>

<script setup>
defineProps({
    message: String,
});
</script>
