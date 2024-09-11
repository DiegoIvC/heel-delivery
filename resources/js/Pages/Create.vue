<template>
    <Head title="Orden" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Crear Nueva Orden</h1>

        <!-- Formulario de Orden -->
        <form @submit.prevent="submitOrden">
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
                        placeholder="Escribe la direccion del cliente"
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
                            d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                        />
                    </svg>
                </div>
                <span v-if="errors.costo_entrega" class="text-red-600">{{ errors.costo_entrega }}</span>
            </div>

            <button @click.prevent="openHamburguesaModal" type="button" class="bg-blue-600 text-white py-2 px-4 rounded mb-4">Añadir Hamburguesa</button>

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
            <ul class="list-disc pl-5 space-y-2">
                <li v-for="(hamburguesa, index) in form.detalles" :key="index" class="bg-gray-100 p-3 rounded shadow-md">
                    <span class="font-semibold">{{ hamburguesa.nombre_hamburguesa }}</span> -
                    <span class="text-gray-600">{{ hamburguesa.cantidad }} x {{ hamburguesa.precio_unitario }}</span> =
                    <span class="font-bold">{{ hamburguesa.cantidad * hamburguesa.precio_unitario }}</span>
                </li>
            </ul>

            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded">Crear Orden</button>
        </form>

    </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const form = reactive({
    cliente: '',
    direccion: '',
    estado_entrega: false,
    costo_entrega: 0,
    detalles: []
});

const hamburguesa = reactive({
    nombre_hamburguesa: '',
    cantidad: 1,
    precio_unitario: 0,
});

const errors = reactive({});
const successMessage = "Orden creada con éxito";
const showHamburguesaModal = ref(false);

const openHamburguesaModal = () => showHamburguesaModal.value = true;
const closeHamburguesaModal = () => showHamburguesaModal.value = false;

const addHamburguesa = () => {
    form.detalles.push({...hamburguesa});
    closeHamburguesaModal();
};

const submitOrden = () => {
    console.log(form);  // Verifica los datos
    Inertia.post('/ordenes', form, {
        onError: (error) => {
            Object.assign(errors, error.response.data.errors);
        },
        onSuccess: () => {
            successMessage.value = 'Orden creada con éxito';
            form.cliente = '';
            form.direccion = '';
            form.costo_entrega = 0;
            form.detalles = [];
        }
    });
};
</script>

<style scoped>
/* Aquí puedes agregar estilos personalizados */
</style>
