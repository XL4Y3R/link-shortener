<!-- components/DateSelector.vue -->
<template>
    <div>
        <!--<h3 class="font-semibold mb-2 text-center text-gray-700">
            Select a date:
        </h3>-->
        <LoadingSpinner
            v-if="isLoadingDays"
            message="Loading available days..."
        />
        <div class="overflow-x-auto pb-2">
            <div class="flex gap-2 w-max">
                <DateButton
                    v-for="day in availableDays"
                    :key="day.date"
                    :date="day.date"
                    :available="day.available"
                    :selected="selectedDate === day.date"
                    @select="handleSelect"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import DateButton from "./DateButton.vue";
import LoadingSpinner from "@/components/common/LoadingSpinner.vue";

const emit = defineEmits(["select"]);

const availableDays = ref([]);
const selectedDate = ref(null);
const isLoadingDays = ref(false);

async function fetchAvailableDays() {
    isLoadingDays.value = true;
    try {
        const res = await fetch("/api/available-days");
        if (!res.ok) throw new Error("Error fetching days");
        availableDays.value = await res.json();
    } catch (err) {
        console.error("Error loading days:", err);
    } finally {
        isLoadingDays.value = false;
    }
}

function handleSelect(date) {
    selectedDate.value = date;
    emit("select", date);
}

onMounted(() => {
    fetchAvailableDays();
});
</script>
