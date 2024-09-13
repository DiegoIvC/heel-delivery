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
                <tbody class="bg-white divide-y">
                <tr v-for="orden in ordenes" :key="orden.id" class="text-gray-900">
                    <td class="px-4 py-2">{{ orden.cliente }}</td>
                    <td class="px-4 py-2">{{ orden.direccion }}</td>
                    <td class="px-4 py-2">{{ orden.telefono }}</td>
                    <td class="px-4 py-2">{{ orden.monto_total.toFixed(2) }} MXN</td>
                    <td class="px-4 py-2">{{ orden.estado_entrega }}</td>
                    <td class="px-4 py-2">{{ orden.repartidor }}</td>
                    <td class="px-4 py-2">{{ orden.zona }}</td>
                    <td class="px-4 py-2">
                        <button @click="verDetalle(orden.id)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Ver
                        </button>
                        <button @click="abrirModal(orden.id, orden.zona)" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded ml-2">
                            Cambiar Zona
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>

            <!-- Modal -->
            <div v-if="modalVisible" class="fixed inset-0 flex items-center justify-center z-50">
                <div class="bg-white p-4 rounded-lg shadow-lg max-w-sm w-full">
                    <h3 class="text-lg font-bold mb-4">Seleccionar Zona</h3>
                    <div class="flex flex-col">
                        <button v-for="zona in zonas" :key="zona" @click="cambiarZona(zona)" class="py-2 px-4 mb-2 bg-gray-200 rounded hover:bg-gray-300">
                            {{ zona }}
                        </button>
                    </div>
                    <button @click="cerrarModal" class="mt-4 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

// Props
const props = defineProps({
    ordenes: Array
});

// Data
const modalVisible = ref(false);
const zonas = ["AMARILLA", "ROJA", "SALMON", "AZUL", "PURPURA", "CELESTE", "ROSA", "GRIS"];
const selectedOrderId = ref(null);

// Methods
const verDetalle = (id) => {
    Inertia.get(`/ordenes/${id}`);
}

const abrirModal = (id, zona) => {
    selectedOrderId.value = id;
    modalVisible.value = true;
}

const cerrarModal = () => {
    modalVisible.value = false;
}

const cambiarZona = (zona) => {
    Inertia.put(`/ordenes/editar/zona/${selectedOrderId.value}`, { zona });
    cerrarModal();
}
</script>

<style scoped>
/* Add any additional styles for the modal here */
</style>
