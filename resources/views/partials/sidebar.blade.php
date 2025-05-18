<div class="sidebar">
    <div class="sidebar-header">
        <h3><i class="bi bi-graph-up"></i> Sales Dashboard</h3>
    </div>
    <ul class="sidebar-menu">
        <li
            class="sidebar-item {{ request()->is('dashboard/analytics') || request()->is('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard', ['page' => 'analytics']) }}" class="sidebar-link">
                <i class="bi bi-speedometer2"></i>
                <span>Analytics</span>
            </a>
        </li>
        <li class="sidebar-item {{ request()->is('dashboard/recommendations') ? 'active' : '' }}">
            <a href="{{ route('dashboard', ['page' => 'recommendations']) }}" class="sidebar-link">
                <i class="bi bi-lightbulb"></i>
                <span>Recommendations</span>
            </a>
        </li>
        <li class="sidebar-item {{ request()->is('dashboard/pricing') ? 'active' : '' }}">
            <a href="{{ route('dashboard', ['page' => 'pricing']) }}" class="sidebar-link">
                <i class="bi bi-tags"></i>
                <span>Dynamic Pricing</span>
            </a>
        </li>
        <li class="sidebar-item {{ request()->is('dashboard/add-order')? 'active' : '' }}">
            <a href="{{ route('dashboard', ['page' => 'add-order']) }}" class="sidebar-link">
                <i class="bi bi-cart"></i>
                <span>Add Order</span>
            </a>
        </li>
    </ul>
</div>