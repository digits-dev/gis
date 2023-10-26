<script>

    // const userIcon = document.getElementById('user-icon');
    // const userIconDropdown = document.getElementById('user-icon-dropdown');


    // userIcon.addEventListener('click', function(event){
    //     userIconDropdown.classList.toggle('show');  
    //     event.stopPropagation();
    // });

    // document.addEventListener('click', function(event) {
    //     // Check if the click target is not inside the userIconDropdown
    //     if (!userIconDropdown.contains(event.target) && !userIcon.contains(event.target)) {
    //         userIconDropdown.classList.remove('show');
    //     }
    // });

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
            returnFocus: false,
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('logout') }}";
            }
        });
    });

</script>