<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h1 class="mb-4">Bienvenido, <?php echo $_SESSION['username']; ?></h1>
                
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        Lista de Usuarios
                        <a href="/logout" class="btn btn-danger btn-sm">Cerrar Sesión</a>
                    </div>
                    <div class="card-body">
                        <table id="usuariosTable" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre de Usuario</th>
                                    <th>Email</th>
                                    <th>Fecha de Registro</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="usuariosTableBody">
                                <!-- Contenido de usuarios será cargado por Ajax -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    $(document).ready(function() {
        function cargarUsuarios() {
            $.ajax({
                url: '/dashboard',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const tbody = $('#usuariosTableBody');
                        tbody.empty();

                        response.usuarios.forEach(function(usuario) {
                            const fila = `
                                <tr>
                                    <td>${usuario.id}</td>
                                    <td>${usuario.username}</td>
                                    <td>${usuario.email}</td>
                                    <td>${usuario.created_at}</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm eliminar-usuario" data-id="${usuario.id}">Eliminar</button>
                                    </td>
                                </tr>
                            `;
                            tbody.append(fila);
                        });

                        $('#usuariosTable').DataTable({
                            language: {
                                url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
                            }
                        });
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Error al cargar usuarios', 'error');
                }
            });
        }

        cargarUsuarios();

        $(document).on('click', '.eliminar-usuario', function() {
            const usuarioId = $(this).data('id');
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/dashboard/eliminar-usuario',
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({ id: usuarioId }),
                        success: function(response) {
                            const result = JSON.parse(response);
                            if (result.success) {
                                Swal.fire('Eliminado!', 'El usuario ha sido eliminado.', 'success');
                                $('#usuariosTable').DataTable().destroy();
                                cargarUsuarios();
                            } else {
                                Swal.fire('Error', result.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'No se pudo eliminar el usuario', 'error');
                        }
                    });
                }
            });
        });
    });
    </script>
</body>
</html>
