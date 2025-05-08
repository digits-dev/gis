<section class="content-section">
    <section class="topbar_section">
        <div class="topbar-box">
            <div class="topbar-content">
                <div class="fa fa-bars menu"></div>
                <div class="title-color fs-20">
                    @if(Request::Segment(1) == 'pos_dashboard')
                    <p>Dashboard</p>
                    @elseif(Request::Segment(1) == 'pos_token_swap')
                    <p>Token Swap</p>
                    @elseif(Request::Segment(1) == 'pos_token_dispense')
                    <p>Token Dispense</p>
                    @elseif(Request::Segment(1) == 'item_pos')
                    <p>Item POS</p>
                    @elseif(Request::Segment(1) == 'item_pos_transactions')
                    <p>Item POS Transactions</p>
                    @elseif(Request::Segment(1) == 'pos_swap_history')
                    <p>Swap History</p>
                    @elseif(Request::Segment(1) == 'pos_float_history')
                    <p>Float History</p>
                    @elseif(Request::Segment(1) == 'pos_settings')
                    <p>Settings</p>
                    @elseif(Request::Segment(1) == 'pos_end_of_day')
                    <p>End of day</p>
                    @endif
                </div>
                <div>
                    <div class="user_profile">
                        {{-- <img src="{{ asset('img/logo.png') }}" id="user-icon" alt=""> --}}
                        <div class="user_profile_info t-center">
                            <p>{{ auth()->user()->customData()->name }} - {{ auth()->user()->customData()->location_name }}</p>
                            <p class="title-color fs-13 currentDateTime">Loading Time...</p>
                        </div>
                        {{-- <div class="notif d-flex-jcev">
                            <i class="fa-regular fa-bell" style="font-size: 20px;"></i>
                        </div> --}}
                        <div class="hide" id="user-icon-dropdown">
                            <div class="d-flex-jcev">
                                <i class="fa-regular fa-bell" id="notif" style="font-size: 16px;"></i>
                                {{-- <a href="" id="user-icon-my-profile"><i class="fa-solid fa-user"></i></a> --}}
                                <a id="user-icon-logout"><i class="fa-solid fa-power-off"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content_wrapper">
            @yield('content') {{-- Display content from child views here --}}
        </div>
    </section>
</section>