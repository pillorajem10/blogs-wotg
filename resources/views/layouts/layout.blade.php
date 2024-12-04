<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Welcome to Word On The Go, your online destination for uplifting church blogs. Discover inspiring articles, spiritual insights, and community stories that enrich your faith and connect you with our church family. Join us as we explore faith, hope, and love together!">
    <meta name="keywords" content="church blogs, faith, spirituality, community stories, religious articles, inspiration, hope, love, god, jesus, motivation">
    <title>@yield('title') | Word On The Go</title>
    <link rel="icon" href="{{ asset('images/wotg-icon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bungee&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
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
            left: -100%; /* Initially hidden */
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

        /* Mobile view adjustments */
        @media (max-width: 768px) {
            .hamburger-menu {
                display: flex;
            }

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
    
        <!-- Hamburger Menu -->
        <button id="hamburger" class="hamburger-menu" onclick="toggleDrawer()">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>
    </nav>
    
    <aside id="sidebar" class="sidebar">
        <ul>
            @if(auth()->check()) <!-- If user is authenticated -->
                <li><a href="#">Watch Messages (Coming soon)</a></li>
                <li><a href="#">Community (Coming soon)</a></li>
                <li><a href="#">Video Meeting (Coming soon)</a></li>
                <li><a href="#">Messages (Coming soon)</a></li>
                <li><a href="/d-group">D-Group</a></li>
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
        function toggleDrawer() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');

            // Get the current screen width
            const screenWidth = window.innerWidth;

            // Adjust the sidebar position based on screen width
            if (screenWidth <= 500) {
                if (sidebar.classList.contains('active')) {
                    sidebar.style.left = '0'; // Sidebar takes full width on smaller screens
                } else {
                    sidebar.style.left = '-100%'; // Sidebar moves off-screen
                }
            } else {
                // For larger screens, the sidebar behaves as before
                if (sidebar.classList.contains('active')) {
                    sidebar.style.left = '0'; // Sidebar stays visible
                } else {
                    sidebar.style.left = '-250px'; // Sidebar is hidden off-screen
                }
            }
        }
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
