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
                        <!-- SVG -->
                    </div>
                    <span v-if="errors.cliente" class="text-red-600">{{ errors.cliente }}</span>
                </div>

                <!-- Otros campos similares -->

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

                <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded">Actualizar Orden</button>
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
    orden: Object
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
