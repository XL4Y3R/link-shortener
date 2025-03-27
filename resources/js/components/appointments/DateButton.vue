<!-- components/DateButton.vue -->
<template>
    <button
        @click="$emit('select', date)"
        class="min-w-[100px] flex flex-col items-center border rounded-xl px-3 py-2 transition-all duration-200"
        :class="{
            'border-blue-600 bg-blue-100 text-blue-800 font-semibold shadow':
                selected,
            'hover:bg-gray-100': !selected,
        }"
    >
        <span class="text-sm font-semibold">{{ weekday }}</span>
        <span class="text-xs text-gray-600">{{ formattedDate }}</span>
        <span class="text-[11px] text-gray-500 mt-1"
            >{{ available }} available</span
        >
    </button>
</template>

<script setup>
import { DateTime } from "luxon";

const props = defineProps({
    date: String, // 'YYYY-MM-DD'
    available: Number,
    selected: Boolean,
});

const dt = DateTime.fromISO(props.date);
const weekday = dt.toFormat("ccc"); // Mon, Tue...
const formattedDate = dt.toFormat("LLL d"); // Apr 1, Apr 2...
</script>
