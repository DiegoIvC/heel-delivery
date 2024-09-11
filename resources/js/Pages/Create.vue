<template>
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Crear Nueva Orden</h1>

        <!-- Formulario de Orden -->
        <form @submit.prevent="submitOrden">
            <div class="mb-4">
                <label for="cliente" class="block font-bold mb-2">Cliente</label>
                <input v-model="form.cliente" type="text" id="cliente" class="border border-gray-300 p-2 w-full">
                <span v-if="errors.cliente" class="text-red-600">{{ errors.cliente }}</span>
            </div>

            <div class="mb-4">
                <label for="direccion" class="block font-bold mb-2">Dirección</label>
                <input v-model="form.direccion" type="text" id="direccion" class="border border-gray-300 p-2 w-full">
                <span v-if="errors.direccion" class="text-red-600">{{ errors.direccion }}</span>
            </div>

            <div class="mb-4">
                <label for="costo_entrega" class="block font-bold mb-2">Costo de Entrega</label>
                <input v-model="form.costo_entrega" type="number" id="costo_entrega" class="border border-gray-300 p-2 w-full">
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
            <ul>
                <li v-for="(hamburguesa, index) in form.detalles" :key="index">
                    {{ hamburguesa.nombre_hamburguesa }} - {{ hamburguesa.cantidad }} x {{ hamburguesa.precio_unitario }} = {{ hamburguesa.cantidad * hamburguesa.precio_unitario }}
                </li>
            </ul>

            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded">Crear Orden</button>
        </form>

        <div v-if="successMessage" class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ successMessage }}
        </div>
    </div>
</template>

<script setup>
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
const successMessage = ref('');
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
