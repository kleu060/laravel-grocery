<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3'
import moment from 'moment';

defineProps({
    products: {
        type: Array,
    },
    categories:{
        type: Array
    },
    product_name: {
        type: String,
        required: true,
    },
    chart: Object,
});


</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pak N Save Product List</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">Pak N Save Product - {{ product_name }}</div>
                    


                    <div class="p-12">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-2">Record Date</th>
                                    <th class="px-6 py-2">label</th>
                                    <th class="px-6 py-2">Price Mode</th>
                                    <th class="px-6 py-2">Price Per Item</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product in products">
                                    <td class="px-6 py-2">{{ moment(String(product.created_at )).format('MM/DD/YYYY hh:mm A') }}</td>
                                    <td class="px-6 py-2">{{ product.label }}</td>
                                    <td class="px-6 py-2">{{ product.PriceMode }}</td>
                                    <td class="px-6 py-2">${{ product.PricePerItem }}</td>
                                </tr>
                            </tbody>
                        
                        </table>
                    </div>
                    <div>
                        <apexchart :width="chart.width" :height="chart.height" :type="chart.type" :options="chart.options" :series="chart.series"></apexchart>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
