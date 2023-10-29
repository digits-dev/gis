<script>
    
    $(document).ready(function() {
        // Hide the statistic boxes initially
        // $(".statistic-box").hide();
    });
                
    // Time
    function getCurrentDateTime() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');

        return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    }

    function updateDateTime() {
        const elements = document.querySelectorAll('.currentDateTime');
        elements.forEach(function(element) {
            element.textContent = getCurrentDateTime();
        });
    }

    setInterval(updateDateTime, 1000);

    $(".cash-float").hide();
    $(".cash-float").fadeIn(500);

    // Logout
    $('#user-icon-logout').on('click', function() {
        Swal.fire({
            title: "Do you want to logout?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            reverseButtons: true,
            returnFocus: false,
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('logout') }}";
            }
        });
    });

    window.onpageshow = function(event) {
        if (event.persisted) {
            location.reload();
        }
    };

    // Menu
    let isSidebarShown = true; 
    let isScreenPhoneSize = false;

    $('.menu').on('click', function () {
        $('.sidebar_section').show();        
    });

    $(window).resize(function () {
        if ($(document).width() <= 470) {
            isSidebarShown = false;
            isScreenPhoneSize = true;
            $('.sidebar_section').hide();

        } else {
            isSidebarShown = true;
            isScreenPhoneSize = false;
            $('.sidebar_section').show();
        }
    });

    if($(document).width() <= 470){
        isScreenPhoneSize = true;
    }

    $(document).on('click', function (e) {
        if ($(e.target).closest('.sidebar_section').length === 0 && !$(e.target).is('.menu') && isScreenPhoneSize) {
            $('.sidebar_section').hide();
        }
    });

    // Animation


</script>