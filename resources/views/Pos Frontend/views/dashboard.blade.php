<!DOCTYPE html>
<html lang="en">
<head>
    @include('Pos Frontend.plugins.pos_frontend_plugin')
    <title>Dashboard</title>
</head>
<body>
    <section class="dashboard_section">
        @include('Pos Frontend.assets.sidebar')
        @include('Pos Frontend.assets.content')
    </section>

    <script>

        const userIcon = document.getElementById('user-icon');
        const userIconDropdown = document.getElementById('user-icon-dropdown');


        userIcon.addEventListener('click', function(event){
            userIconDropdown.classList.toggle('show');  
            event.stopPropagation();
        });

        document.addEventListener('click', function(event) {
            // Check if the click target is not inside the userIconDropdown
            if (!userIconDropdown.contains(event.target) && !userIcon.contains(event.target)) {
                userIconDropdown.classList.remove('show');
            }
        });

    </script>
</body>
</html>