import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import ApexCharts from 'apexcharts';

Alpine.plugin(persist);

window.Alpine = Alpine;
window.ApexCharts = ApexCharts;

Alpine.start();
