<template>
    <Head title="Ordenes" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Lista de Ordenes</h2>
        </template>

        <div class="bg-white p-4 rounded-lg shadow-lg max-w-3xl mx-auto mt-5">
            <h1 class="text-2xl font-bold mb-4">Detalles de la Orden</h1>
            <p><strong>Cliente:</strong> {{ orden.cliente }}</p>
            <p><strong>Dirección:</strong> {{ orden.direccion }}</p>
            <p><strong>Costo de entrega:</strong> {{ costoEntregaFixed }}</p>

            <!-- Tabla de detalles de la orden -->
            <table class="min-w-full bg-white border border-gray-200 rounded-lg mt-4">
                <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre Hamburguesa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Unitario</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Total</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="detalle in detalles" :key="detalle.id">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ detalle.nombre_hamburguesa }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ detalle.cantidad }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ detalle.precio_unitario }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ detalle.precio_total }}</td>
                </tr>
                </tbody>
            </table>

            <!-- Usar el botón para confirmar y navegar -->
            <button @click="confirmAndEdit" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Editar Orden
            </button>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';  // Importar Inertia para la navegación

// Definir los props recibidos
const props = defineProps({
    orden: Object,
    detalles: Array,  // Añadir detalles aquí
});

// Convertir costo_entrega a número y aplicar toFixed
const costoEntregaFixed = computed(() => {
    return parseFloat(props.orden.costo_entrega).toFixed(2);
});

const confirmAndEdit = () => {
    if (window.confirm('¿Estás seguro de que deseas editar esta orden? SI EDITAS LA ORDEN LAS HAMBURGUESAS QUE ESTAN REGISTRADAS SERAN ELIMINADAS')) {
        Inertia.visit(`/ordenes/${props.orden.id}/editar`);  // Usar Inertia para la navegación
    }
};
</script>
