<div class="container mt-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>Registro de Usuarios</h4>
                    </div>
                    <div class="card-body">
                        <form id="formUsuarios" method="post" action="procesar_usuario.php">
                            <!-- Campo oculto para ID en caso de edición -->
                            <input type="hidden" id="usuario_id" name="usuario_id">
                            
                            <!-- Nombre -->
                            <div class="mb-3">
                                <label for="usuario_nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="usuario_nombre" name="usuario_nombre" maxlength="100" required>
                            </div>
                            
                            <!-- Apellido -->
                            <div class="mb-3">
                                <label for="usuario_apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="usuario_apellido" name="usuario_apellido" maxlength="100" required>
                            </div>
                            
                            <!-- NIT -->
                            <div class="mb-3">
                                <label for="usuario_nit" class="form-label">NIT</label>
                                <input type="number" class="form-control" id="usuario_nit" name="usuario_nit">
                            </div>
                            
                            <!-- Teléfono -->
                            <div class="mb-3">
                                <label for="usuario_telefono" class="form-label">Teléfono</label>
                                <input type="number" class="form-control" id="usuario_telefono" name="usuario_telefono">
                            </div>
                            
                            <!-- Correo -->
                            <div class="mb-3">
                                <label for="usuario_correo" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="usuario_correo" name="usuario_correo" maxlength="100" required>
                            </div>
                            
                            <!-- Estado -->
                            <div class="mb-3">
                                <label for="usuario_estado" class="form-label">Estado</label>
                                <select class="form-select" id="usuario_estado" name="usuario_estado" required>
                                    <option value="A">Activo</option>
                                    <option value="I">Inactivo</option>
                                </select>
                            </div>
                            
                            <!-- Situación -->
                            <div class="mb-3">
                                <label for="usuario_situacion" class="form-label">Situación</label>
                                <select class="form-select" id="usuario_situacion" name="usuario_situacion">
                                    <option value="1">Normal</option>
                                    <option value="0">Especial</option>
                                </select>
                            </div>
                             <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="reset" class="btn btn-secondary me-md-2">Limpiar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>