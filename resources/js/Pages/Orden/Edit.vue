<template>
    <Head title="Editar Orden" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Orden</h2>
        </template>

        <div class="bg-white p-4 rounded-lg shadow-lg max-w-3xl mx-auto mt-5">

            <!-- Formulario de Orden -->
            <form @submit.prevent="submitOrden">
                <!-- Similar al formulario de creación, pero con valores iniciales -->

                    <div class="mb-4">
                        <label for="cliente" class="block font-bold mb-2">Cliente</label>
                        <div class="relative">
                            <input
                                v-model="form.cliente"
                                type="text"
                                id="cliente"
                                class="border border-gray-300 p-2 pl-10 w-full"
                                placeholder="Escribe el nombre del cliente"
                            />
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="absolute top-1/2 left-3 transform -translate-y-1/2 w-5 h-5 text-gray-500"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z"
                                />
                            </svg>
                        </div>
                        <span v-if="errors.cliente" class="text-red-600">{{ errors.cliente }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="direccion" class="block font-bold mb-2">Dirección</label>
                        <div class="relative">
                            <input
                                v-model="form.direccion"
                                type="text"
                                id="direccion"
                                class="border border-gray-300 p-2 pl-10 w-full"
                                placeholder="Escribe la dirección del cliente"
                            />
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="absolute top-1/2 left-3 transform -translate-y-1/2 w-5 h-5 text-gray-500"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"
                                />
                            </svg>
                        </div>
                        <span v-if="errors.direccion" class="text-red-600">{{ errors.direccion }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="vendedor" class="block font-bold mb-2">Vendedor</label>
                        <div class="relative">
                            <select
                                v-model="form.vendedor"
                                id="vendedor"
                                class="border border-gray-300 p-2 pl-10 w-full"
                            >
                                <option value="">Selecciona un vendedor</option>
                                <option value="diego">Diego</option>
                                <option value="regina">Regina</option>
                                <option value="johan">Johan</option>
                                <option value="vicky">Vicky</option>
                                <option value="shadow">Shadow</option>
                            </select>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="absolute top-1/2 left-3 transform -translate-y-1/2 w-6 h-6 text-gray-500"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                        </div>
                        <span v-if="errors.vendedor" class="text-red-600">{{ errors.vendedor }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="telefono" class="block font-bold mb-2">Teléfono</label>
                        <div class="relative">
                            <input
                                v-model="form.telefono"
                                type="number"
                                id="telefono"
                                class="border border-gray-300 p-2 pl-10 w-full"
                                placeholder="Escribe el teléfono del cliente"
                            />
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="absolute top-1/2 left-3 transform -translate-y-1/2 w-5 h-5 text-gray-500"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"
                                />
                            </svg>
                        </div>
                        <span v-if="errors.telefono" class="text-red-600">{{ errors.telefono }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="costo_entrega" class="block font-bold mb-2">Costo de Entrega</label>
                        <div class="relative">
                            <input
                                v-model="form.costo_entrega"
                                type="number"
                                id="costo_entrega"
                                class="border border-gray-300 p-2 pl-10 w-full"
                                placeholder="Costo de entrega"
                            />
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="absolute top-1/2 left-3 transform -translate-y-1/2 w-5 h-5 text-gray-500"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3 10.5h18m-18 3h18M9 6.75h6M9 9h6M9 12h6M9 14.25h6M9 17h6"
                                />
                            </svg>
                        </div>
                        <span v-if="errors.costo_entrega" class="text-red-600">{{ errors.costo_entrega }}</span>
                    </div>


                    <!-- Otros campos similares -->

                <div class="flex justify-center mt-4">
                    <button @click.prevent="openHamburguesaModal" type="button" class="bg-blue-600 text-white py-2 px-4 w-full rounded mb-4">Añadir Hamburguesa</button>
                </div>


                <!-- Modal para añadir hamburguesa -->
                <div v-if="showHamburguesaModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                    <div class="bg-white p-6 rounded shadow-lg">
                        <h3 class="text-xl font-bold mb-4">Añadir Hamburguesa</h3>
                        <div class="mb-4">
                            <label for="nombre_hamburguesa" class="block font-bold mb-2">Nombre de la Hamburguesa</label>
                            <input v-model="hamburguesa.nombre_hamburguesa" type="text" id="nombre_hamburguesa" class="border border-gray-300 p-2 w-full">
                        </div>

                        <div class="mb-4">
                            <label for="cantidad" class="block font-bold mb-2">Cantidad</label>
                            <input v-model="hamburguesa.cantidad" type="number" id="cantidad" class="border border-gray-300 p-2 w-full">
                        </div>

                        <div class="mb-4">
                            <label for="precio_unitario" class="block font-bold mb-2">Precio Unitario</label>
                            <input v-model="hamburguesa.precio_unitario" type="number" id="precio_unitario" class="border border-gray-300 p-2 w-full">
                        </div>

                        <button @click="addHamburguesa" class="bg-green-600 text-white py-2 px-4 rounded">Añadir</button>
                        <button @click="closeHamburguesaModal" class="bg-gray-600 text-white py-2 px-4 rounded">Cerrar</button>
                    </div>
                </div>

                <!-- Listado de hamburguesas añadidas -->
                <table class="min-w-full bg-white border border-gray-200 rounded-lg mt-4 mb-4">
                    <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre Hamburguesa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Unitario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(hamburguesa, index) in form.detalles" :key="index">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ hamburguesa.nombre_hamburguesa }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ hamburguesa.cantidad }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ hamburguesa.precio_unitario }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ hamburguesa.cantidad * hamburguesa.precio_unitario }}</td>
                    </tr>
                    </tbody>
                </table>

                <div class="flex justify-center mt-4">
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded w-full">Actualizar Orden</button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { reactive, ref, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    orden: Object,
    detalle: Array
});

const form = reactive({
    cliente: props.orden.cliente || '',
    direccion: props.orden.direccion || '',
    telefono: props.orden.telefono || 0,
    estado_entrega: props.orden.estado_entrega || false,
    costo_entrega: props.orden.costo_entrega || 0,
    vendedor: props.orden.vendedor || '',
    detalles: props.orden.detalles || []
});

const hamburguesa = reactive({
    nombre_hamburguesa: '',
    cantidad: 1,
    precio_unitario: 85,
});

const errors = reactive({});
const showHamburguesaModal = ref(false);

const openHamburguesaModal = () => showHamburguesaModal.value = true;
const closeHamburguesaModal = () => showHamburguesaModal.value = false;

const addHamburguesa = () => {
    form.detalles.push({...hamburguesa});
    hamburguesa.nombre_hamburguesa = '';
    hamburguesa.cantidad = 1;
    hamburguesa.precio_unitario = 85;
    closeHamburguesaModal();
};

const submitOrden = () => {
    console.log('Datos del formulario:', form);
    Inertia.put(`/ordenes/${props.orden.id}`, form, {
        onError: (error) => {
            Object.assign(errors, error.response.data.errors);
        },
        onSuccess: () => {
            form.cliente = '';
            form.direccion = '';
            form.telefono = 0;
            form.costo_entrega = 0;
            form.detalles = [];
            form.vendedor = '';
        }
    });
};
</script>

<style scoped>
/* Aquí puedes agregar estilos personalizados */
</style>
