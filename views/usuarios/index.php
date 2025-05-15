<div class="container mt-5">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="text-center mb-2">Bienvenido a la aplicación para el registro, modificación y eliminación de usuario</h5>
                    <h4 class="text-center mb-2 text-warning bg-dark">Registro de Usuarios</h4>
                </div>
                <div class="card-body">
                    <form id="formUsuarios" method="post" action="procesar_usuario.php">
                        <!-- Campo oculto para ID en caso de edición -->
                        <input type="hidden" id="usuario_id" name="usuario_id">
                        
                        <!-- Nombre y Apellido en la misma fila -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="usuario_nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="usuario_nombre" name="usuario_nombre" maxlength="100" required>
                            </div>
                            <div class="col-md-6">
                                <label for="usuario_apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="usuario_apellido" name="usuario_apellido" maxlength="100" required>
                            </div>
                        </div>
                        
                        <!-- NIT y Teléfono en la misma fila -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="usuario_nit" class="form-label">NIT</label>
                                <input type="text" class="form-control" id="usuario_nit" name="usuario_nit">
                            </div>
                            <div class="col-md-6">
                                <label for="usuario_telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="usuario_telefono" name="usuario_telefono">
                            </div>
                        </div>
                        
                        <!-- Correo en fila completa -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="usuario_correo" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="usuario_correo" name="usuario_correo" maxlength="100" required>
                            </div>
                        </div>
                        
                        <!-- Estado y Situación en la misma fila -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="usuario_estado" class="form-label">Estado</label>
                                <select class="form-select" id="usuario_estado" name="usuario_estado" required>
                                    <option value="A">Activo</option>
                                    <option value="I">Inactivo</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="usuario_situacion" class="form-label">Situación</label>
                                <select class="form-select" id="usuario_situacion" name="usuario_situacion">
                                    <option value="1">Normal</option>
                                    <option value="0">Especial</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Botones centrados y más grandes -->
                        <div class="row mt-5">
                            <div class="col-12 d-flex justify-content-center gap-4">
                                <button type="reset" class="btn btn-secondary btn-lg px-5 py-3 fs-5" id="BtnLimpiar">
                                    <i class="bi bi-x-circle me-2"></i>Limpiar
                                </button>
                                <button type="submit" class="btn btn-primary btn-lg px-5 py-3 fs-5" id="BtnGuardar">
                                    <i class="bi bi-save me-2"></i>Guardar
                                </button>
                                <button type="submit" class="btn btn-primary btn-lg px-5 py-3 fs-5 d-none" id="BtnModificar">
                                    <i class="bi bi-pencil-square me-2"></i>Modificar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/usuarios/index.js') ?>"></script>