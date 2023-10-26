<section class="sidebar_section">
    <nav>
        <div class="header-logo">
            <a href="/pos_dashboard" class="gashapon_logo nav-title">
                <img src="{{ asset('img/logo.png') }}" alt="">
                <span class="nav-item">GASHAPON</span>
            </a>
        </div>
        <ul>
          
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
                <a class="nav-module {{ Request::Segment(1) == 'pos_swap_history' ? 'active' : '' }}" href="/pos_swap_history">
                    <i class="fa fa-window-restore {{ Request::Segment(1) == 'pos_swap_history' ? 'active_i' : '' }}"></i>
                    <span class="nav-item {{ Request::Segment(1) == 'pos_swap_history' ? 'active_span' : '' }}">Swap History</span>
                </a>
            </li>
            <li>
                <a class="nav-module {{ Request::Segment(1) == 'pos_float_history' ? 'active' : '' }}" href="/pos_float_history">
                    <i class="fa fa-money {{ Request::Segment(1) == 'pos_float_history' ? 'active_i' : '' }}"></i>
                    <span class="nav-item {{ Request::Segment(1) == 'pos_float_history' ? 'active_span' : '' }}">Float History</span>
                </a>
            </li>
            <li>
                <a class="nav-module {{ Request::Segment(1) == 'pos_settings' ? 'active' : '' }}" href="/pos_settings">
                    <i class="fa fa-gear {{ Request::Segment(1) == 'pos_settings' ? 'active_i' : '' }}"></i>
                    <span class="nav-item {{ Request::Segment(1) == 'pos_settings' ? 'active_span' : '' }}">Settings</span>
                </a>
            </li>
            <li>
                <a class="nav-module {{ Request::Segment(1) == 'pos_end_of_day' ? 'active' : '' }}" href="/pos_end_of_day">
                    <i class="fa-solid fa-calendar {{ Request::Segment(1) == 'pos_end_of_day' ? 'active_i' : '' }}"></i>
                    <span class="nav-item {{ Request::Segment(1) == 'pos_end_of_day' ? 'active_span' : '' }}">End of Day</span>
                </a>
            </li>
        </ul>
    </nav>
</section>