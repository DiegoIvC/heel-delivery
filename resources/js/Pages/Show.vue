<template>
    <div>
        <h1 class="text-2xl font-bold mb-4">Detalles de la Orden</h1>
        <div class="mb-4">
            <p><strong>Cliente:</strong> {{ orden.cliente }}</p>
            <p><strong>Dirección:</strong> {{ orden.direccion }}</p>
            <p><strong>Teléfono:</strong> {{ orden.telefono }}</p>
            <p><strong>Costo de Entrega:</strong> {{ orden.costo_entrega.toFixed(2) }} MXN</p>
            <p><strong>Estado de Entrega:</strong> {{ orden.estado_entrega }}</p>
            <p><strong>Repartidor:</strong> {{ repartidor }}</p>
        </div>
        <h2 class="text-xl font-bold mb-2">Detalles de la Orden</h2>
        <table class="table-auto w-full mb-4">
            <thead>
            <tr>
                <th class="px-4 py-2">Nombre Hamburguesa</th>
                <th class="px-4 py-2">Cantidad</th>
                <th class="px-4 py-2">Precio Unitario</th>
                <th class="px-4 py-2">Precio Total</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="detalle in detalles" :key="detalle.id">
                <td class="border px-4 py-2">{{ detalle.nombre_hamburguesa }}</td>
                <td class="border px-4 py-2">{{ detalle.cantidad }}</td>
                <td class="border px-4 py-2">{{ detalle.precio_unitario.toFixed(2) }} MXN</td>
                <td class="border px-4 py-2">{{ detalle.precio_total.toFixed(2) }} MXN</td>
            </tr>
            </tbody>
        </table>
        <inertia-link href="/ordenes" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Volver
        </inertia-link>
    </div>
</template>

<script>
export default {
    props: {
        orden: Object,
        detalles: Array,
        repartidor: String,
    },
}
</script>
