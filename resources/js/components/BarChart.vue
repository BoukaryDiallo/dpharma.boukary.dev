<template>
  <div>
    <canvas ref="canvas"></canvas>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps<{
  labels: string[];
  values: number[];
  label: string;
}>();

const canvas = ref<HTMLCanvasElement | null>(null);
let chart: Chart | null = null;

onMounted(() => {
  if (canvas.value) {
    chart = new Chart(canvas.value, {
      type: 'bar',
      data: {
        labels: props.labels,
        datasets: [
          {
            label: props.label,
            data: props.values,
            backgroundColor: '#2563eb',
          },
        ],
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
        },
      },
    });
  }
});

watch(() => props.values, (newVal) => {
  if (chart) {
    chart.data.datasets[0].data = newVal;
    chart.update();
  }
});
</script>
