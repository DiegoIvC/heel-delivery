<template>
    <div class="max-w-2xl mx-auto">

        <h1 class="text-2xl font-bold mb-6">Crear Nueva Orden</h1>

        <form @submit.prevent="submit">
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
                <input v-model="form.costo_entrega" type="text" id="costo_entrega" class="border border-gray-300 p-2 w-full">
                <span v-if="errors.costo_entrega" class="text-red-600">{{ errors.costo_entrega }}</span>
            </div>

            <div class="mb-4">
                <label for="estado_entrega" class="block font-bold mb-2">¿Entregado?</label>
                <select v-model="form.estado_entrega" id="estado_entrega" class="border border-gray-300 p-2 w-full">
                    <option :value="true">Sí</option>
                    <option :value="false">No</option>
                </select>
                <span v-if="errors.estado_entrega" class="text-red-600">{{ errors.estado_entrega }}</span>
            </div>

            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded">Crear Orden</button>
        </form>
    </div>
</template>

<script setup>
import { ref, defineProps } from 'vue';
import {useForm} from "@inertiajs/vue3";
import {Inertia} from "@inertiajs/inertia";

const props = defineProps(['errors']);

const form = useForm({
    cliente: '',
    direccion: '',
    costo_entrega: '',
    estado_entrega: false,
});

const submit = () => {
    form.post(route('ordenes.store'), {
        onSuccess: () => {
            form.reset(); // Opcional: limpiar el formulario después del envío
        },
        onError: (errors) => {
            // Manejar errores aquí si es necesario
            console.log(errors);
        },
    });
}
</script>
