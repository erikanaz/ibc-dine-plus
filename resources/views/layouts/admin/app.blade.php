<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - IBC Dine+</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0ea5e9',
                        secondary: '#f97316',
                        dark: '#0f172a',
                        success: '#22c55e',
                        warning: '#f59e0b',
                        danger: '#ef4444'
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar {
            transition: all 0.3s ease;
        }
        .sidebar-item {
            transition: all 0.2s ease;
        }
        .sidebar-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 3px solid #0ea5e9;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .reservation-card {
            transition: all 0.3s ease;
        }
        .reservation-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-color: #0ea5e9;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Layout Utama -->
    <div class="flex min-h-screen">
        @include('layouts.admin.navigation')
        
        <div class="ml-64 flex-1 flex flex-col h-screen">
            @include('layouts.admin.header') <!-- Include header di sini -->
            
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
            
            @include('layouts.admin.footer')
        </div>
    </div>
    <script src="//unpkg.com/alpinejs" defer></script>
    
</body>
</html>