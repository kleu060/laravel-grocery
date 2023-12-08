<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
// import { inertia } from '@inertiajs/vue3';

import axios from 'axios';
import { ref } from 'vue';


const props = defineProps({
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
    redirect_route: {
        type: String,
        required: true,
    },
    total: {
        type: Number,
        required: true,
    },

});

const changeCategory = (tmpcategory_id) => {
    let url = route(props.redirect_route, { category_id: tmpcategory_id });
    // console.log(props.redirect_route);
    // let url = "/pnsproduct/"+category_id;
    router.visit(url, {
        only: ['products'],
    })
};


const addToCartAction = (product) =>{

    axios
    .post(route("addtocart"),{
        data:{
            quantity:product.quantity
        }
    }).then((response) => {
        console.log(response);
    }).catch((error) => {
        console.error('Error:', error);
    });
    
}

</script>

<template>
    <Head title="PNS Product List" />

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
                            <li class="mr-4 hover:underline md:mr-6 " ><a  href="/">All</a></li>
                            <li class="mr-4 hover:underline md:mr-6 "  v-for="category in categories"><a @click="changeCategory(category.id)" href="#">{{ category.name }}</a></li>
                        </ul>
                    </div>
                    <div class="p-12">
                        <div>Total Products {{total}}</div>
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-2">Category</th>
                                    <th class="px-6 py-2">Product Name</th>
                                    <th class="px-6 py-2">Price Mode</th>
                                    <th class="px-6 py-2">Price Per Item</th>
                                    <th class="px-6 py-2">Label</th>
                                    <th class="px-6 py-2">Add to Cart</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product in products">
                                    <td class="px-6 py-2">{{ product.id}}</td>
                                    <td class="px-6 py-2"><a :href="route('pns.product', { id: product.productId })">{{ product.productName}}</a></td>
                                    <td class="px-6 py-2">{{ product.PriceMode}}</td>
                                    <td class="px-6 py-2">${{ product.PricePerItem}}</td>
                                    <td class="px-6 py-2">{{ product.label}}</td>
                                    <td class="px-6 py-2 flex">
                                        <input type="text"  v-model="product.quantity" class="input-quantity"/>
                                        <button type="button" class="px-6"  @click="addToCartAction(product)">Add</button>
                                    </td>
                                </tr>
                            </tbody>
                        
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
