<template>
    <Head title="Ordenes" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Lista de Ordenes</h2>
        </template>
        <div class="bg-white p-4 rounded-lg shadow-lg max-w-7xl mx-auto mt-5">

            <table class="table-auto mb-4 w-full whitespace-no-wrap">
                <thead>
                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                    <th class="px-4 py-2">Cliente</th>
                    <th class="px-4 py-2">Dirección</th>
                    <th class="px-4 py-2">Teléfono</th>
                    <th class="px-4 py-2">Monto Total</th>
                    <th class="px-4 py-2">Estado</th>
                    <th class="px-4 py-2">Repartidor</th>
                    <th class="px-4 py-2">Zona</th>
                    <th class="px-4 py-2">Acciones</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y ">
                <tr v-for="orden in ordenes" :key="orden.id" class="text-gray-900">
                    <td class="px-4 py-2">{{ orden.cliente }}</td>
                    <td class=" px-4 py-2">{{ orden.direccion }}</td>
                    <td class=" px-4 py-2">{{ orden.telefono }}</td>
                    <td class=" px-4 py-2">{{ orden.monto_total.toFixed(2) }} MXN</td>
                    <td class=" px-4 py-2">{{ orden.estado_entrega }}</td>
                    <td class=" px-4 py-2">{{ orden.repartidor }}</td>
                    <td class=" px-4 py-2">{{ orden.zona }}</td>
                    <td class=" px-4 py-2">
                        <button @click="verDetalle(orden.id)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Ver
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

// Props
const props = defineProps({
    ordenes: Array
});

// Methods
const verDetalle = (id) => {
    Inertia.get(`/ordenes/${id}`);
}
</script>
