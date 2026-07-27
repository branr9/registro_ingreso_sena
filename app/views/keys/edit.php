<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="w-full max-w-3xl mx-auto">
    <h1 class="text-2xl font-extrabold text-primary-700 flex items-center gap-2 mb-6">
        <i class="fas fa-edit"></i> Editar Aula
    </h1>

    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl p-6 md:p-8 w-full">
        <form method="POST" action="<?= baseUrl('/control-llaves/update/' . $aula['id']) ?>" class="space-y-5">
            <div>
                <label for="nombre" class="block text-sm font-semibold text-gray-700 mb-1">
                    <i class="fas fa-door-open text-primary-600"></i> Nombre del Aula *
                </label>
                <input type="text"
                       id="nombre"
                       name="nombre"
                       class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                       value="<?= e($aula['nombre']) ?>"
                       required>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="capacidad" class="block text-sm font-semibold text-gray-700 mb-1">
                        <i class="fas fa-users text-primary-600"></i> Capacidad (personas) *
                    </label>
                    <input type="number"
                           id="capacidad"
                           name="capacidad"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                           min="1"
                           value="<?= e($aula['capacidad']) ?>"
                           required>
                </div>

                <div>
                    <label for="cantidad_llaves" class="block text-sm font-semibold text-gray-700 mb-1">
                        <i class="fas fa-key text-primary-600"></i> Cantidad de Llaves *
                    </label>
                    <input type="number"
                           id="cantidad_llaves"
                           name="cantidad_llaves"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                           min="1"
                           value="<?= e($aula['cantidad_llaves']) ?>"
                           required>
                </div>
            </div>

            <div>
                <label for="observaciones" class="block text-sm font-semibold text-gray-700 mb-1">
                    <i class="fas fa-comment text-primary-600"></i> Observaciones
                </label>
                <textarea id="observaciones"
                          name="observaciones"
                          rows="3"
                          class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"><?= e($aula['observaciones']) ?></textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-2xl bg-primary-700 hover:bg-primary-800 transition text-white font-semibold px-5 py-2.5 shadow-md">
                    <i class="fas fa-save"></i> Actualizar
                </button>
                <a href="<?= baseUrl('/control-llaves') ?>"
                   class="inline-flex items-center gap-2 rounded-2xl bg-gray-100 hover:bg-gray-200 transition text-gray-700 font-semibold px-5 py-2.5">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
