<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Registro de Usuario</div>
                    <div class="card-body">
                        <form id="registroForm">
                            <div class="mb-3">
                                <label for="username" class="form-label">Nombre de Usuario</label>
                                <input type="text" class="form-control" id="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm-password" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="confirm-password" required>
                            </div>
                            <div id="errorMessage" class="alert alert-danger" style="display:none;"></div>
                            <div id="successMessage" class="alert alert-success" style="display:none;"></div>
                            <button type="submit" class="btn btn-primary">Registrarse</button>
                            <a href="/login" class="btn btn-link">Iniciar Sesión</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#registroForm').on('submit', function(e) {
            e.preventDefault();
            
            // Validar que las contraseñas coincidan
            const password = $('#password').val();
            const confirmPassword = $('#confirm-password').val();
            
            if (password !== confirmPassword) {
                $('#errorMessage').text('Las contraseñas no coinciden').show();
                return;
            }

            $.ajax({
                url: '/register',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    username: $('#username').val(),
                    email: $('#email').val(),
                    password: password
                }),
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        $('#successMessage').text(result.message).show();
                        $('#errorMessage').hide();
                        
                        // Redirigir después de 2 segundos
                        setTimeout(() => {
                            window.location.href = result.redirect;
                        }, 2000);
                    } else {
                        $('#errorMessage').text(result.message).show();
                        $('#successMessage').hide();
                    }
                },
                error: function() {
                    $('#errorMessage').text('Error en el registro').show();
                    $('#successMessage').hide();
                }
            });
        });
    });
    </script>
</body>
</html>