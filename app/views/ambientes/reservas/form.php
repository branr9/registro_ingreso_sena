<?php require APP_PATH . '/views/layouts/header.php'; $editing=!empty($reserva); ?>
<div class="w-full max-w-4xl mx-auto">
    <?php require APP_PATH . '/views/ambientes/_nav.php'; ?>
    <h2 class="text-2xl font-extrabold text-primary-700 flex items-center gap-2 mb-6"><i class="fas fa-calendar-<?= $editing?'edit':'plus' ?>"></i> <?= $editing?'Editar':'Nueva' ?> Reserva</h2>
    <div class="bg-white/85 backdrop-blur-md border border-primary-100 rounded-3xl shadow-xl p-6 md:p-8">
        <form method="POST" action="<?= baseUrl($editing?'/ambientes/reservas/actualizar/'.$reserva['id']:'/ambientes/reservas/guardar') ?>" class="space-y-5" id="reservaForm">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
            <div><label for="ambiente_id" class="block text-sm font-semibold text-gray-700 mb-1"><i class="fas fa-building text-primary-600"></i> Ambiente *</label><select id="ambiente_id" name="ambiente_id" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none" required><option value="">Seleccione...</option><?php foreach($ambientes as $a): $selected=(int)($reserva['ambiente_id']??$preselectedAmbiente)===(int)$a['id']; ?><option value="<?= $a['id'] ?>" <?= $selected?'selected':'' ?>><?= e($a['codigo'].' - '.$a['nombre'].' ('.$a['capacidad'].' personas)') ?></option><?php endforeach; ?></select></div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div><label for="responsable_nombre" class="block text-sm font-semibold text-gray-700 mb-1"><i class="fas fa-user text-primary-600"></i> Nombre del responsable *</label><input id="responsable_nombre" name="responsable_nombre" type="text" minlength="3" maxlength="150" value="<?= e($reserva['responsable_nombre']??'') ?>" placeholder="Nombre de quien utilizará el ambiente" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none" required></div>
                <div><label for="responsable_documento" class="block text-sm font-semibold text-gray-700 mb-1"><i class="fas fa-id-card text-primary-600"></i> Documento del responsable</label><input id="responsable_documento" name="responsable_documento" type="text" maxlength="30" value="<?= e($reserva['responsable_documento']??'') ?>" placeholder="Opcional" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none"></div>
            </div>
            <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-2xl px-4 py-3 text-sm"><i class="fas fa-info-circle mr-1"></i> El responsable no necesita estar registrado como usuario del sistema.</div>
            <div><label for="titulo" class="block text-sm font-semibold text-gray-700 mb-1"><i class="fas fa-heading text-primary-600"></i> Actividad o motivo *</label><input id="titulo" name="titulo" minlength="3" maxlength="160" value="<?= e($reserva['titulo']??'') ?>" placeholder="Ej: Formación ficha 2678901" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none" required></div>
            <?php $defaultStart=$preselectedDate.'T'.$preselectedStart; $defaultEnd=$preselectedDate.'T'.$preselectedEnd; ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5"><div><label for="inicio" class="block text-sm font-semibold text-gray-700 mb-1"><i class="fas fa-clock text-primary-600"></i> Inicio *</label><input type="datetime-local" id="inicio" name="inicio" value="<?= $editing?date('Y-m-d\TH:i',strtotime($reserva['inicio'])):$defaultStart ?>" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none" required></div><div><label for="fin" class="block text-sm font-semibold text-gray-700 mb-1"><i class="fas fa-clock text-primary-600"></i> Fin *</label><input type="datetime-local" id="fin" name="fin" value="<?= $editing?date('Y-m-d\TH:i',strtotime($reserva['fin'])):$defaultEnd ?>" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none" required></div></div>
            <div><label for="descripcion" class="block text-sm font-semibold text-gray-700 mb-1"><i class="fas fa-comment text-primary-600"></i> Descripción</label><textarea id="descripcion" name="descripcion" maxlength="255" rows="3" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none" placeholder="Información adicional (opcional)"><?= e($reserva['descripcion']??'') ?></textarea></div>
            <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl px-4 py-3 text-sm"><i class="fas fa-shield-alt mr-1"></i> El sistema comprobará la disponibilidad al guardar y rechazará cualquier horario superpuesto.</div>
            <div class="flex flex-wrap gap-3"><button class="inline-flex items-center gap-2 rounded-2xl bg-primary-700 hover:bg-primary-800 text-white font-semibold px-5 py-2.5 shadow-md"><i class="fas fa-save"></i> Guardar Reserva</button><a href="<?= baseUrl('/ambientes/reservas') ?>" class="inline-flex items-center gap-2 rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-5 py-2.5"><i class="fas fa-times"></i> Cancelar</a></div>
        </form>
    </div>
</div>
<script>
(function(){
    const start=document.getElementById('inicio'); const end=document.getElementById('fin');
    function sync(){ if(!start.value) return; end.min=start.value; if(!end.value||end.value<=start.value){ const d=new Date(start.value); d.setHours(d.getHours()+1); end.value=d.getFullYear()+'-'+String(d.getMonth()+1).padStart(2,'0')+'-'+String(d.getDate()).padStart(2,'0')+'T'+String(d.getHours()).padStart(2,'0')+':'+String(d.getMinutes()).padStart(2,'0'); }}
    start.addEventListener('change',sync); sync();
})();
</script>
<?php require APP_PATH . '/views/layouts/footer.php'; ?>
