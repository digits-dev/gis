<section class="topbar_section">
    <div class="topbar-box">
        <div class="topbar-content">
            <div class="topbar-title fs-20">
                <p>Dashboard</p>
            </div>
            <div>
                <div class="user_profile">
                    <img src="{{ asset('img/logo.png') }}" id="user-icon" alt="">
                    <div class="user_profile_info t-center">
                        <p>Patrick Lester Punzalan</p>
                        <p class="title-color fs-13">Superadmin</p>
                    </div>
                    <div class="hide" id="user-icon-dropdown">
                        <div class="d-flex-jcev">
                            <a href="" id="user-icon-my-profile"><i class="fa-solid fa-user"></i> Profile</a>
                            <a href="{{ route('logout') }}" id="user-icon-logout"><i class="fa-solid fa-power-off"></i> Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>