<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Welcome to Word On The Go, your online destination for uplifting church blogs. Discover inspiring articles, spiritual insights, and community stories that enrich your faith and connect you with our church family. Join us as we explore faith, hope, and love together!">
    <meta name="keywords" content="church blogs, faith, spirituality, community stories, religious articles, inspiration, hope, love, god, jesus, motivation">
    <title>@yield('title') | Word On The Go</title>
    
    <meta property="og:title" content="Word On The Go" />
    <meta property="og:image" content="{{ asset('images/wotg-logo-with-bg.jpeg') }}">
    <meta property="og:image:alt" content="Open Graph Image Description">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:type" content="website">
    <meta property="og:description" content="Welcome to Word On The Go, your online destination for uplifting church blogs. Discover inspiring articles, spiritual insights, and community stories that enrich your faith and connect you with our church family. Join us as we explore faith, hope, and love together!">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Word On The Go">
    <meta name="twitter:description" content="Welcome to Word On The Go, your online destination for uplifting church blogs.">
    <meta name="twitter:image" content="{{ asset('images/wotg-logo-with-bg.jpeg') }}">
    
    <link rel="icon" href="{{ asset('images/wotg-icon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bungee&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}',
            authUserId: {{ auth()->id() ?? 'null' }} // Include the authenticated user's ID, or `null` if not logged in
        };
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Ensure body takes full height */
        }

        .navbar {
            background-color: #c0392b;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            /*height: 3rem;*/
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        .main-content {
            flex: 1;

            /*
            padding: 20px;
            margin-top: 4rem; 
            */
        }

        footer {
            background-color: #c0392b;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px 0;
            width: 100%;
        }

        .header-title {
            font-size: 1.5rem;
        }

        header {
            background-color: #e74c3c;
            color: white;
            padding: 10px;
            border-radius: 5px;
            font-size: 1rem;
        }

        .cards {
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            margin-bottom: 10px;
        }

        .card p {
            font-size: 24px;
            color: #c0392b;
        }

        /* Sidebar styles */
        .sidebar {
            position: fixed;
            top: 1rem;
            left: -250px;  
            width: 250px;
            height: 100%;
            background-color: darkred;
            color: white;
            padding-top: 70px; /* To accommodate for navbar */
            transition: left 0.3s ease;
            z-index: 999;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 10px;
            text-align: center;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
            font-size: 14px;
        }

        .sidebar ul li a:hover {
            color: gray;
        }

        /* Hamburger menu styles */
        .hamburger-menu {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 30px;
            height: 25px;
            background: transparent;
            border: none;
            cursor: pointer;
            z-index: 1001; /* Ensure it's above the navbar */
        }

        .hamburger-menu .bar {
            background-color: white;
            height: 4px;
            width: 100%;
            border-radius: 2px;
        }

        .user-profile {
            display: flex;
            align-items: center;
        }

        .profile-img-nav {
            width: 35px;
            height: 35px;
            cursor: pointer;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-circle-nav {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: white;
            display: flex;
            cursor: pointer;
            justify-content: center;
            align-items: center;
            color: #c0392b;
            font-size: 1.2rem; /* Adjust font size as needed */
            font-weight: bold;
        }

        .right-side-nav {
            display: flex;
            gap: 10px;
            align-items: center
        }

        .profile-dropdown {
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            right: 0;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        }

        .profile-dropdown a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .profile-dropdown a:hover {background-color: #f1f1f1}


        /* Mobile view adjustments */
        @media (max-width: 768px) {
            .navbar {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
        }


        @media (max-width: 600px) {
            footer {
                font-size: .8rem;
            }
        }

        @media (max-width: 500px) {
            .hamburger-menu {
                display: flex;
            }
        }


        /* For screens with max-width of 500px */
        @media (max-width: 500px) {
            .sidebar {
                left: -100%; /* Make the sidebar visible */
                width: 100%; /* Set the sidebar to 100% width */
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar">
        <a href="/" class="logo-link">
            <img src="{{ asset('images/wotg-logo.png') }}" alt="WOTG Logo" style="width: 3rem;">
        </a>
    
        <div class="right-side-nav">
            <div class="user-profile">
                @auth
                    <div class="profile-clickable" data-user-id="{{ auth()->id() }}">
                        @if (Auth::user()->user_profile_picture)
                            <!-- Display Profile Picture -->
                            <img src="data:image/jpeg;base64,{{ base64_encode(Auth::user()->user_profile_picture) }}" alt="Profile Picture" class="profile-img-nav" onclick="toggleProfileDropdown(event)">
                        @else
                            <!-- Display Circle with First Letter of First Name -->
                            <div class="profile-circle-nav" onclick="toggleProfileDropdown(event)">
                                <span>{{ strtoupper(substr(Auth::user()->user_fname, 0, 1)) }}</span>
                            </div>
                        @endif
                        <!-- Dropdown Menu -->
                        <div class="profile-dropdown" id="profileDropdownMenu" style="display: none;">
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">Edit Profile</a>
                            <a href="{{ route('profile.view', ['userId' => Auth::id()]) }}" class="dropdown-item">View Profile</a>
                        </div>
                    </div>
                @endauth
            </div>
            
            <button id="hamburger" class="hamburger-menu" onclick="toggleDrawer()">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
        </div>              
    </nav>
    
    <aside id="sidebar" class="sidebar">
        <ul>
            <li><a href="/blogs">Devotion</a></li>
            @if(auth()->check()) <!-- If user is authenticated -->
                <li><a href="#">Watch Messages (Coming soon)</a></li>
                {{--<li><a href="/community">Community</a></li>--}}
                <li><a href="#">Video Meeting (Coming soon)</a></li>
                <li><a href="#">Messages (Coming soon)</a></li>
                <li><a href="/d-group">My D-Group</a></li>
                <li><a href="/faq">FAQs</a></li>
                <li><a href="{{ route('auth.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
            @else <!-- If user is not authenticated -->
                <li><a href="/login">Login</a></li>
                <li><a href="/signup">Register</a></li>
            @endif
        </ul>
    </aside>
    
    <!-- Logout form (hidden) -->
    <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <div class="main-content">
        <div class="cards">
            @yield('content')
        </div>
    </div>

    <footer>
        <p>&copy; {{ date('Y') }} Word On The Go. All rights reserved.</p>
    </footer>

    <script>
        function toggleProfileDropdown(event) {
            event.stopPropagation();
            var dropdown = document.getElementById('profileDropdownMenu');
            if (dropdown.style.display === "none") {
                dropdown.style.display = "block";
            } else {
                dropdown.style.display = "none";
            }
        }

        // Close dropdown when clicking outside of it
        document.addEventListener('click', function(event) {
            var dropdown = document.getElementById('profileDropdownMenu');
            if (!event.target.closest('.profile-clickable')) {
                dropdown.style.display = "none";
            }
        });

        function toggleDrawer() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            // const screenWidth = window.innerWidth;

            sidebar.classList.toggle('active');
            if (sidebar.classList.contains('active')) {
                sidebar.style.left = '0'; // Sidebar visible
            } else {
                sidebar.style.left = '-100%'; // Sidebar hidden
                mainContent.style.marginLeft = '0'; // Reset content margin
            }
        }


        /*
        function adjustSidebarAndContent() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            const screenWidth = window.innerWidth;

            if (screenWidth > 500) {
                // Sidebar is always visible on larger screens
                sidebar.classList.add('active');
                sidebar.style.left = '0';
                mainContent.style.marginLeft = '250px'; // Squeeze content
            } else {
                // On smaller screens, hide sidebar by default
                if (!sidebar.classList.contains('active')) {
                    sidebar.style.left = '-100%'; // Sidebar off-screen
                    mainContent.style.marginLeft = '0'; // Reset content margin
                }
            }
        }
    
        // Ensure the sidebar and content are adjusted on window resize
        window.addEventListener('resize', adjustSidebarAndContent);
    
        // Adjust the sidebar and content on page load
        document.addEventListener('DOMContentLoaded', adjustSidebarAndContent);
        */
    </script>    
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
