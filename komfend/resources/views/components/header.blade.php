<header class="app-header">
    <nav class="navbar navbar-expand-lg navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
                <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                    <i class="ti ti-menu-2"></i>
                </a>
            </li>
        </ul>
        <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                <li class="nav-item dropdown">
                    <a class="nav-link" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <img src="{{ asset('assets/images/user-1.jpg') }}" alt="User Profile" width="35"
                            height="35" class="rounded-circle" />

                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                        <div class="message-body">
                            <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                <i class="ti ti-user fs-6"></i>
                                <p class="mb-0">My Profile</p>
                            </a>
                            <form id="logoutForm" action="{{ route('Admin.Logout') }}" method="GET">
                                @csrf
                                <button type="submit" id="logoutButton" class="btn btn-outline-primary w-100">
                                    <span class="spinner-border spinner-border-sm d-none" role="status"
                                        aria-hidden="true"></span>
                                    <span class="button-text">Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>

<script>
    document.getElementById('logoutForm').addEventListener('submit', function(event) {
        var logoutButton = document.getElementById('logoutButton');
        var spinner = logoutButton.querySelector('.spinner-border');
        var buttonText = logoutButton.querySelector('.button-text');

        spinner.classList.remove('d-none');
        buttonText.textContent = 'Logging out...';

        logoutButton.disabled = true;
    });
</script>
