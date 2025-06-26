<!DOCTYPE html>
<html>
<head>
    <title>Login Pangkalan LPG</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.min.css">
</head>
<body class="bg-light">
<div class="container" style="margin-top: 80px; max-width: 400px;">
    <div class="card shadow-sm">
        <div class="card-header text-center bg-primary text-white">
            <h4>Login</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ url('login') }}">
                @csrf
                <div class="form-group mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required value="{{ old('username') }}">
                </div>
                <div class="form-group mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Masuk</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session("success") }}',
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session("error") }}',
        timer: 2500,
        showConfirmButton: false
    });
</script>
@endif

</body>
</html>
