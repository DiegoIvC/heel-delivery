<template>
    <Head title="Ordenes" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Lista de Ordenes</h2>
        </template>
        <div class="bg-white p-4 rounded-lg shadow-lg max-w-7xl mx-auto mt-5">
            <table class="table-auto mb-4 w-full whitespace-no-wrap">
                <thead>
                <tr class="text-xs font-semibold tracking-wide text-left text-white uppercase border-b dark:border-gray-700 bg-gray-600">
                    <th class="px-4 py-2">Cliente</th>
                    <th class="px-4 py-2">Dirección</th>
                    <th class="px-4 py-2">Teléfono</th>
                    <th class="px-4 py-2">Monto Total</th>
                    <th class="px-4 py-2">Repartidor</th>
                    <th class="px-4 py-2">Zona</th>
                    <th class="px-4 py-2">Ver</th>
                    <th class="px-4 py-2">Cambiar Zona</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y">
                <tr v-for="orden in ordenes" :key="orden.id" :class="getZonaClass(orden.zona)" >
                    <td class="px-4 py-2">{{ orden.cliente }}</td>
                    <td class="px-4 py-2">{{ orden.direccion }}</td>
                    <td class="px-4 py-2">{{ orden.telefono }}</td>
                    <td class="px-4 py-2">{{ orden.monto_total.toFixed(2) }} MXN</td>
                    <td class="px-4 py-2">{{ orden.repartidor || 'Sin repartidor' }}</td>
                    <td class="px-4 py-2">{{ orden.zona }}</td>
                    <td class="px-4 py-2">
                        <button @click="verDetalle(orden.id)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd" />
                            </svg>

                        </button>
                    </td>
                    <td class="py-2 px-4">
                        <button @click="abrirModal(orden.id, orden.zona)" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded ml-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                <path fill-rule="evenodd" d="M12 5.25c1.213 0 2.415.046 3.605.135a3.256 3.256 0 0 1 3.01 3.01c.044.583.077 1.17.1 1.759L17.03 8.47a.75.75 0 1 0-1.06 1.06l3 3a.75.75 0 0 0 1.06 0l3-3a.75.75 0 0 0-1.06-1.06l-1.752 1.751c-.023-.65-.06-1.296-.108-1.939a4.756 4.756 0 0 0-4.392-4.392 49.422 49.422 0 0 0-7.436 0A4.756 4.756 0 0 0 3.89 8.282c-.017.224-.033.447-.046.672a.75.75 0 1 0 1.497.092c.013-.217.028-.434.044-.651a3.256 3.256 0 0 1 3.01-3.01c1.19-.09 2.392-.135 3.605-.135Zm-6.97 6.22a.75.75 0 0 0-1.06 0l-3 3a.75.75 0 1 0 1.06 1.06l1.752-1.751c.023.65.06 1.296.108 1.939a4.756 4.756 0 0 0 4.392 4.392 49.413 49.413 0 0 0 7.436 0 4.756 4.756 0 0 0 4.392-4.392c.017-.223.032-.447.046-.672a.75.75 0 0 0-1.497-.092c-.013.217-.028.434-.044.651a3.256 3.256 0 0 1-3.01 3.01 47.953 47.953 0 0 1-7.21 0 3.256 3.256 0 0 1-3.01-3.01 47.759 47.759 0 0 1-.1-1.759L6.97 15.53a.75.75 0 0 0 1.06-1.06l-3-3Z" clip-rule="evenodd" />
                            </svg>

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
                        <button v-for="zona in zonas" :key="zona" @click="cambiarZona(zona)" :class="getZonaClassModal(zona)">
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

const getZonaClass = (zona) => {
    const colorClasses = {
        AMARILLA: 'bg-yellow-200',
        ROJA: 'bg-red-700 text-white',
        SALMON: 'bg-red-300',
        AZUL: 'bg-blue-900 text-white',
        PURPURA: 'bg-purple-900 text-white',
        CELESTE: 'bg-cyan-200',
        ROSA: 'bg-pink-500 text-white',
        GRIS: 'bg-gray-300',
        VERDE: 'bg-green-500 text-white',
    };
    return colorClasses[zona] || 'bg-white';
}
const getZonaClassModal = (zona) => {
    const colorClasses = {
        AMARILLA: 'bg-yellow-200 text-black border border-gray-300 rounded py-2 px-4 mt-2 hover:bg-yellow-300',
        ROJA: 'bg-red-700 text-white border border-red-900 rounded py-2 px-4 mt-2 hover:bg-red-800',
        SALMON: 'bg-red-300 text-black border border-red-500 rounded py-2 px-4 mt-2 hover:bg-red-400',
        AZUL: 'bg-blue-900 text-white border border-blue-700 rounded py-2 px-4 mt-2 hover:bg-blue-600',
        PURPURA: 'bg-purple-900 text-white border border-purple-700 rounded py-2 px-4 mt-2 hover:bg-purple-800',
        CELESTE: 'bg-cyan-200 text-black border border-cyan-400 rounded py-2 px-4 mt-2 hover:bg-cyan-300',
        ROSA: 'bg-pink-500 text-white border border-pink-700 rounded py-2 px-4 mt-2 hover:bg-pink-600',
        GRIS: 'bg-gray-300 text-black border border-gray-500 rounded py-2 px-4 mt-2 hover:bg-gray-400'
    };
    return colorClasses[zona] || 'bg-white';
}
</script>
