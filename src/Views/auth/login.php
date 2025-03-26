<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Iniciar Sesión</div>
                    <div class="card-body">
                        <form id="loginForm">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" required>
                            </div>
                            <div id="errorMessage" class="alert alert-danger" style="display:none;"></div>
                            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                            <a href="/register" class="btn btn-link">Registrarse</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: '/login',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    email: $('#email').val(),
                    password: $('#password').val()
                }),
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        window.location.href = result.redirect;
                    } else {
                        $('#errorMessage').text(result.message).show();
                    }
                },
                error: function() {
                    $('#errorMessage').text('Error en la conexión').show();
                }
            });
        });
    });
    </script>
</body>
</html>