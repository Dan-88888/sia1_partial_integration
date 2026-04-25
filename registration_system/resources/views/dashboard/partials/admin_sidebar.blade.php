<div class="sidebar-section">
    <div class="sidebar-label">Main Menu</div>
    <a class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
        <i class="fas fa-th-large"></i> Dashboard
    </a>
    <a class="sidebar-link {{ request()->routeIs('admin.registration.records') ? 'active' : '' }}" href="{{ route('admin.registration.records') }}">
        <i class="fas fa-history"></i> Registration Records
    </a>
</div>

<div class="sidebar-section">
    <div class="sidebar-label">People</div>
    <a class="sidebar-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}" href="{{ route('admin.students.index') }}">
        <i class="fas fa-user-graduate"></i> Students
    </a>
    <a class="sidebar-link {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}" href="{{ route('admin.teachers.index') }}">
        <i class="fas fa-chalkboard-teacher"></i> Teachers
    </a>
    <a class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
        <i class="fas fa-user-shield"></i> Admin Users
    </a>
</div>

<div class="sidebar-section">
    <div class="sidebar-label">Academics</div>
    <a class="sidebar-link {{ request()->routeIs('departments.index') ? 'active' : '' }}" href="{{ route('departments.index') }}">
        <i class="fas fa-university"></i> Departments
    </a>
    <a class="sidebar-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}" href="{{ route('admin.courses.index') }}">
        <i class="fas fa-graduation-cap"></i> Courses
    </a>
    <a class="sidebar-link {{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}" href="{{ route('admin.subjects.index') }}">
        <i class="fas fa-book"></i> Subjects
    </a>
    <a class="sidebar-link {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}" href="{{ route('admin.rooms.index') }}">
        <i class="fas fa-door-open"></i> Rooms
    </a>
    <a class="sidebar-link {{ request()->routeIs('admin.sections.*') ? 'active' : '' }}" href="{{ route('admin.sections.index') }}">
        <i class="fas fa-layer-group"></i> Sections
    </a>
</div>

<div class="sidebar-section">
    <div class="sidebar-label">System</div>
    <a class="sidebar-link {{ request()->routeIs('admin.finance.*') ? 'active' : '' }}" href="{{ route('admin.finance.index') }}">
        <i class="fas fa-file-invoice-dollar"></i> Finance & Billing
    </a>
    <a class="sidebar-link {{ request()->routeIs('admin.audit.*') ? 'active' : '' }}" href="{{ route('admin.audit.index') }}">
        <i class="fas fa-clipboard-list"></i> Audit Logs
    </a>
    <a class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
        <i class="fas fa-cogs"></i> System Config
    </a>
</div>
