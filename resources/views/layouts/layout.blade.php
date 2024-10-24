<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Welcome to Word On The Go, your online destination for uplifting church blogs. Discover inspiring articles, spiritual insights, and community stories that enrich your faith and connect you with our church family. Join us as we explore faith, hope, and love together!">
    <meta name="keywords" content="church blogs, faith, spirituality, community stories, religious articles, inspiration, hope, love, god, jesus, motivation">
    <title>@yield('title') || Word On The Go</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
            height: 3.8rem;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            margin-top: 4rem; 
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

        @media (max-width: 600px) {
            .sidebar {
                width: 200px; /* Adjust width for smaller screens */
            }

            .sidebar.active {
                left: 0; /* Keep it at 0 when active */
                width: 100%; /* Full width on mobile */
            }

            .main-content {
                margin-left: 0; /* Reset margin for mobile */
            }

            .sidebar.active + .main-content {
                margin-left: 0; /* No shift when sidebar is active on mobile */
            }

            footer {
                font-size: .8rem;

            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar">
        <a href="/">
            <img src="{{ asset('images/wotg-logo.png') }}" alt="WOTG Logo" style="width: 3.8rem;">
        </a>
    </nav>

    <div class="main-content">
        <header>
            <h1 class="header-title">@yield('title', 'Dashboard')</h1>
        </header>
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
        }
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
