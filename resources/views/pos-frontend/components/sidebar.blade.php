<section class="sidebar_section">
    <nav>
        <ul>
            <li>
                <a href="/pos_dashboard" class="gashapon_logo nav-title">
                    <img src="{{ asset('img/logo.png') }}" alt="">
                    <span class="nav-item">GASHAPON</span>
                </a>
            </li>
            <li>
                <a class="nav-module {{ Request::Segment(1) == 'pos_dashboard' ? 'active' : '' }}" href="/pos_dashboard">
                    <i class="fa fa-home {{ Request::Segment(1) == 'pos_dashboard' ? 'active_i' : '' }}"></i>
                    <span class="nav-item {{ Request::Segment(1) == 'pos_dashboard' ? 'active_span' : '' }}">Home</span>
                </a>
            </li>
            <li>
                <a class="nav-module {{ Request::Segment(1) == 'pos_token_swap' ? 'active' : '' }}" href="/pos_token_swap">
                    <i class="fa-solid fa-coins {{ Request::Segment(1) == 'pos_token_swap' ? 'active_i' : '' }}"></i>
                    <span class="nav-item {{ Request::Segment(1) == 'pos_token_swap' ? 'active_span' : '' }}">Token Swap</span>
                </a>
            </li>
            <li>
                <a class="nav-module" href="">
                    <i class="fa fa-window-restore"></i>
                    <span class="nav-item">Swap History</span>
                </a>
            </li>
            <li>
                <a class="nav-module" href="">
                    <i class="fa fa-money"></i>
                    <span class="nav-item">Float History</span>
                </a>
            </li>
            <li>
                <a class="nav-module" href="">
                    <i class="fa fa-gear"></i>
                    <span class="nav-item">Settings</span>
                </a>
            </li>
            <li>
                <a class="nav-module" href="">
                    <i class="fa-solid fa-calendar"></i>
                    <span class="nav-item">End of Day</span>
                </a>
            </li>
        </ul>
    </nav>
</section>