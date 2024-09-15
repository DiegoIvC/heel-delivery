<template>
    <Head title="Ordenes" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Orden: {{orden.id}}</h2>
        </template>

        <div class="bg-white p-4 rounded-lg shadow-lg max-w-3xl mx-auto mt-5">
            <button @click="regresar" class="bg-gray-600 text-white rounded-lg py-2 px-4 text-lg mb-5 flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                </svg>
                <span>Regresar</span>
            </button>
            <div class="flex flex-col md:flex-row items-start space-y-4 md:space-y-0 md:space-x-6 p-6 bg-white shadow-md rounded-lg">
                <!-- Detalles de la Orden -->
                <div class="flex-1">
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-4">Detalles de la Orden</h1>

                    <div class="space-y-4">
                        <div class="flex flex-col md:flex-row items-start">
                            <span class="text-lg font-semibold text-gray-700 mr-2">Cliente:</span>
                            <p class="text-gray-800">{{ orden.cliente }}</p>
                        </div>

                        <div class="flex flex-col md:flex-row items-start">
                            <span class="text-lg font-semibold text-gray-700 mr-2">Dirección:</span>
                            <p class="text-gray-800">{{ orden.direccion }}</p>
                        </div>

                        <div class="flex flex-col md:flex-row items-start">
                            <span class="text-lg font-semibold text-gray-700 mr-2">Costo de entrega:</span>
                            <p class="text-gray-800">{{ costoEntregaFixed }}</p>
                        </div>

                        <div class="flex flex-col md:flex-row items-start">
                            <span class="text-lg font-semibold text-gray-700 mr-2">Repartidor:</span>
                            <p class="text-gray-800">{{ orden.repartidor_nombre || 'Sin repartidor asignado' }}</p>
                        </div>

                        <div class="flex flex-col md:flex-row items-start">
                            <span class="text-lg font-semibold text-gray-700 mr-2">Zona:</span>
                            <p class="text-gray-800">{{ orden.zona }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de detalles de la orden -->
            <table class="min-w-full bg-white border border-gray-200 rounded-lg mt-4">
                <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hamburguesa</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Unitario</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Total</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="detalle in detalles" :key="detalle.id">
                    <td class="px-4 py-3 text-sm text-gray-900">{{ detalle.nombre_hamburguesa }}</td>
                    <td class="px-4 py-3 text-sm text-red-500">{{ detalle.cantidad }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ detalle.precio_unitario }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ detalle.precio_total }}</td>
                </tr>
                </tbody>
            </table>
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

const confirmAndFinish = () => {
    if (window.confirm('¿Estás seguro de que deseas TERMINAR esta orden?')) {
        Inertia.visit(`/ordenes/${props.orden.id}/terminar`);  // Usar Inertia para la navegación
    }
}
const regresar = () => {
    Inertia.visit(document.referrer, {
        method: 'get',
        preserveState: true
    });
}
</script>
