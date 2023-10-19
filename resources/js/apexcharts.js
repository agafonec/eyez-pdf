import { createApp } from 'vue';
import VueApexCharts from 'vue3-apexcharts';

const app = createApp({});

app.use(VueApexCharts);
app.component('apexchart', VueApexCharts);

export default app;
