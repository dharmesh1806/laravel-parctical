<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Laravel Demo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"> <a class="nav-link " href="users">Users</a></li>
                <li class="nav-item"> <a class="nav-link" href="role">Roles</a></li>
            </ul>
            <div class="">
                <button class="btn btn-danger" onclick="logout()">Logout</button>
            </div>
        </div>
    </div>
</nav>

<script>
    function logout() {
        localStorage.removeItem('accessToken')
        window.location.href = "/login"
    }
</script>