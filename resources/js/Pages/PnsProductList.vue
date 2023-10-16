<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3'

defineProps({
    products: {
        type: Array,
    },
    categories:{
        type: Array
    },
    category_id: {
        type: String,
        required: true,
    },

});

const changeCategory = (tmpcategory_id) => {
    let url = route('pns.listproduct', { category_id: tmpcategory_id });
    console.log(url);
    // let url = "/pnsproduct/"+category_id;
    router.visit(url, {
        only: ['products'],
    })
};

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
                    <div class="p-6 text-gray-900">Pak N Save Product List</div>
                    
                    <div class="p-12 text-center">
                        <h2>Categories</h2> 
                        <ul class="flex flex-wrap items-center justify-center">
                            <li class="mr-4 hover:underline md:mr-6 "  v-for="category in categories"><a @click="changeCategory(category.id)" href="#">{{ category.name }}</a></li>
                        </ul>
                    </div>
                    <div class="p-12">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-2">Category</th>
                                    <th class="px-6 py-2">Product Name</th>
                                    <th class="px-6 py-2">Price Mode</th>
                                    <th class="px-6 py-2">Price Per Item</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product in products">
                                    <td class="px-6 py-2">{{ product.category.name}}</td>
                                    <td class="px-6 py-2"><a :href="route('pns.product', { id: product.productId })">{{ product.productName}}</a></td>
                                    <td class="px-6 py-2">{{ product.PriceMode}}</td>
                                    <td class="px-6 py-2">${{ product.PricePerItem}}</td>
                                </tr>
                            </tbody>
                        
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
