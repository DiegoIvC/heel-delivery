<template>
    <Head title="Ordenes" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Envios realizados</h2>
        </template>
        <div class="bg-white p-4 rounded-lg shadow-lg max-w-7xl mx-auto mt-5">
            <!-- Hacemos la tabla responsive -->
            <div class="overflow-x-auto">
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
                        <th class="px-4 py-2">Entregar</th> <!-- Botón Entregar -->
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                    <tr v-for="orden in ordenes" :key="orden.id" :class="getZonaClass(orden.zona)">
                        <td class="px-4 py-2">{{ orden.cliente }}</td>
                        <td class="px-4 py-2">{{ orden.direccion }}</td>
                        <td class="px-4 py-2">{{ orden.telefono }}</td>
                        <td class="px-4 py-2">{{ orden.monto_total.toFixed(2) }} MXN</td>
                        <td class="px-4 py-2">{{ orden.repartidor || 'Sin repartidor' }}</td>
                        <td class="px-4 py-2">{{ orden.zona }}</td>
                        <td class="px-4 py-2">
                            <button @click="verDetalle(orden.id)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-2 rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                    <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                    <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </td>
                        <td class="px-4 py-2">
                            <button @click="noEntregado(orden.id)" class="bg-red-400 hover:bg-red-700 text-white font-bold py-2 px-2 rounded">
                                NO Entregado
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Modal responsivo -->
            <div v-if="modalVisible" class="fixed inset-0 flex items-center justify-center z-50 bg-gray-900 bg-opacity-50">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                    <h1 class="m-5 text-gray-600 pr-5 text-center">Cambiar repartidor</h1>
                    <table class="table-auto mt-5 w-full">
                        <tbody>
                        <tr v-for="usuario in usuarios" :key="usuario.id">
                            <td class="px-4 py-2 text-center">
                                <button
                                    @click="seleccionarRepartidor(usuario.id)"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                                    {{ usuario.name }}
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="px-4 py-5 text-center">
                        <span class="bg-red-600 rounded-lg text-white cursor-pointer py-2 px-4" @click="cerrarModal">Cerrar</span>
                    </div>
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
    ordenes: Array,
    usuarios: Array,
});

// Data
const modalVisible = ref(false);
const selectedOrderId = ref(null);

// Methods
const verDetalle = (id) => {
    Inertia.get(`/ordenes/envios/realizados/show/${id}`);
}

const abrirModal = (ordenId) => {
    selectedOrderId.value = ordenId;
    modalVisible.value = true;
}

const cerrarModal = () => {
    modalVisible.value = false;
}

const noEntregado = (ordenId) => {
    // Enviamos la solicitud para cambiar el estado de entrega a 1
    Inertia.put(`/ordenes/no/entregar/${ordenId}`, {
        estado_entrega: 0
    }).then(() => {
        console.log(`Orden ${ordenId} no entregada.`);
    }).catch((error) => {
        console.error('Error al entregar la orden:', error);
    });
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

const seleccionarRepartidor = (usuarioId) => {
    if (!selectedOrderId.value) {
        console.error('selectedOrderId no está definido');
        return;
    }

    Inertia.put(`/ordenes/editar/repartidor/${selectedOrderId.value}`, {
        repartidor_id: usuarioId
    }).then(() => {
        cerrarModal();
    }).catch((error) => {
        console.error('Error al actualizar el repartidor:', error);
    });
}
</script>
