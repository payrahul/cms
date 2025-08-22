<nav class="bg-dark text-white p-3" style="width: 250px;">
    <h5 class="mb-4">Admin Panel</h5>
    <ul class="nav flex-column">
        <li class="nav-item mb-2">
            <a class="nav-link text-white" href="{{ route('dashboard') }}">Dashboard</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-white" href="{{ route('users') }}">Users</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-white" href="#">Settings</a>
        </li>
    </ul>
</nav>