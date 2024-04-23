<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Toggler button for the sidebar -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- Right-aligned items in the navbar -->
    <ul class="navbar-nav ml-auto">
        <!-- Fullscreen button -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        <!-- User profile dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                {{ Auth::user()->nama }}
                <i class="fas fa-caret-down"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <!-- Profile link -->
                <a href="{{ route('profile.show') }}" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> Profile
                </a>

                <!-- Logout link -->
                <a href="javascript:;" class="dropdown-item" onclick="document.getElementById('form-logout').submit();">
                    <i class="fas fa-sign-out-alt mr-2"></i> Sign Out
                </a>
            </div>
        </li>
    </ul>
</nav>

<!-- Logout form -->
<form id="form-logout" action="{{ route('logout') }}" method="POST" style="display: none">
    @csrf
</form>
