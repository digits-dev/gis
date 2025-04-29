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
                <a class="nav-module {{ Request::Segment(1) == 'item_pos' ? 'active' : '' }}" href="/item_pos">
                    <i class="fa-solid fa-cash-register {{ Request::Segment(1) == 'item_pos' ? 'active_i' : '' }}"></i>
                    <span class="nav-item {{ Request::Segment(1) == 'item_pos' ? 'active_span' : '' }}">Item POS</span>
                </a>
            </li>
            <li>
                <a data-item="token-dispense" class="nav-module {{ Request::Segment(1) == 'pos_token_dispense' ? 'active' : '' }}" href="/pos_token_dispense">
                    <i class="fa-solid fa-coins {{ Request::Segment(1) == 'pos_token_dispense' ? 'active_i_dispense' : '' }}"></i>
                    <span class="nav-item {{ Request::Segment(1) == 'pos_token_dispense' ? 'active_span_dispense' : '' }}">Token Dispense</span>
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

<script>
     $(document).ready(function() {
        const isTokenDispense = $('li a.active').attr('data-item');
        if(isTokenDispense === 'token-dispense'){
            $('.sidebar_section').css('background', 'linear-gradient(to top right, #00a65a, #00a65a)');
            
            $('.sidebar_section nav ul li a').hover(function() {
                $(this).find('i').css('color', '#00a65a');
                $(this).find('.nav-item').css('color', '#00a65a');
            }, function() {
                $(this).find('i').css('color', ''); // Reset to default
                $(this).find('.nav-item').css('color', ''); // Reset to default
            });
        }else{
            $('.sidebar_section').css('background', 'linear-gradient(to top right, #fe3e3e, #8a2121)');
        }
    });
</script>