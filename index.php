<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Shopper System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/index.css">
    <style>
        .dropdown {
            display: none;
        }
        .dropdown.active {
            display: block;
        }
    </style>
</head>
<body class="bg-gray-100">
    <nav class="bg-gradient-to-r from-blue-900 to-blue-800 text-white shadow-lg">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="text-xl font-bold">Personal Shopper System</div>
                <div class="hidden md:flex space-x-8">
                    <?php if($is_logged_in): ?>
                        <a href="dashboard.php" class="hover:text-blue-200 transition">Shop</a>
                    <?php endif; ?>
                    <a href="index.php" class="hover:text-blue-200 transition">Home</a>
                    <a href="#" class="hover:text-blue-200 transition">About</a>
                </div>
                <?php if($is_logged_in): ?>
                    <a href="dashboard.php" class="bg-blue-500 hover:bg-blue-600 px-6 py-2 rounded-full transition">Go to Dashboard</a>
                <?php else: ?>
                    <a href="auth/login.php" class="bg-blue-500 hover:bg-blue-600 px-6 py-2 rounded-full transition">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="hero-section min-h-screen flex items-center relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/20 to-blue-600/20"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="text-white">
                    <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                        Shop online, Pick up ready.
                    </h1>
                    <p class="text-xl mb-8 text-blue-100">
                        Skip the aisles and the lines your groceries are ready for pickup.
                    </p>
                    <?php if($is_logged_in): ?>
                        <a href="dashboard.php" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold px-8 py-4 rounded-lg text-lg transition transform hover:scale-105 shadow-lg">
                            Shop Now
                        </a>
                    <?php else: ?>
                        <a href="auth/register.php" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold px-8 py-4 rounded-lg text-lg transition transform hover:scale-105 shadow-lg">
                            Shop Now
                        </a>
                    <?php endif; ?>
                </div>
                
                <div class="hidden md:block">
                    <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?w=800" alt="Shopping" class="rounded-2xl shadow-lg">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">How It Works</h2>
                <p class="text-gray-600 text-lg">Simple steps to get your groceries</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center p-8 rounded-xl hover:shadow-xl transition">
                    <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Browse & Shop</h3>
                    <p class="text-gray-600">Select your favorite products from our wide range</p>
                </div>
                
                <div class="text-center p-8 rounded-xl hover:shadow-xl transition">
                    <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Place Order</h3>
                    <p class="text-gray-600">Complete your order with secure checkout</p>
                </div>
                
                <div class="text-center p-8 rounded-xl hover:shadow-xl transition">
                    <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Pick Up</h3>
                    <p class="text-gray-600">Collect your ready groceries at your convenience</p>
                </div>
            </div>
        </div>
    </section>
</body>
</html>