<?php
session_start();
require_once 'config/config.php';

if(!isset($_SESSION['user_id'])){
    header('Location: auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT firstname, surname FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
$stmt->close();

$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM products WHERE status = 'active'";
if($category_filter) {
    $sql .= " AND category = ?";
}
if($search_query) {
    $sql .= " AND (name LIKE ? OR description LIKE ?)";
}

$stmt = $conn->prepare($sql);
if($category_filter && $search_query) {
    $search_term = "%$search_query%";
    $stmt->bind_param("sss", $category_filter, $search_term, $search_term);
} elseif($category_filter) {
    $stmt->bind_param("s", $category_filter);
} elseif($search_query) {
    $search_term = "%$search_query%";
    $stmt->bind_param("ss", $search_term, $search_term);
}
$stmt->execute();
$products = $stmt->get_result();
$stmt->close();

$categories_query = "SELECT DISTINCT category FROM products WHERE status = 'active'";
$categories_result = $conn->query($categories_query);

$wishlist_query = "SELECT product_id FROM wishlist WHERE user_id = ?";
$wishlist_stmt = $conn->prepare($wishlist_query);
$wishlist_stmt->bind_param("i", $user_id);
$wishlist_stmt->execute();
$wishlist_result = $wishlist_stmt->get_result();
$wishlist_items = [];
while($row = $wishlist_result->fetch_assoc()) {
    $wishlist_items[] = $row['product_id'];
}
$wishlist_stmt->close();

$cart_count_query = "SELECT COUNT(*) as count FROM cart WHERE user_id = ?";
$cart_stmt = $conn->prepare($cart_count_query);
$cart_stmt->bind_param("i", $user_id);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();
$cart_count = $cart_result->fetch_assoc()['count'];
$cart_stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Personal Shopper System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/dashboard.css">
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
                    <a href="dashboard.php" class="hover:text-blue-200 transition">Shop</a>
                    <a href="index.php" class="hover:text-blue-200 transition">Home</a>
                    <a href="#" class="hover:text-blue-200 transition">About</a>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button onclick="toggleDropdown()" class="flex items-center space-x-2">
                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold"><?php echo strtoupper(substr($user['firstname'], 0, 1)); ?></span>
                            </div>
                            <span class="hidden md:block"><?php echo htmlspecialchars($user['firstname']); ?></span>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <div id="userDropdown" class="dropdown absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 z-50">
                            <a href="auth/logout.php" class="block px-4 py-2 text-gray-800 hover:bg-blue-50 transition">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="bg-blue-700 py-6">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <button onclick="toggleCategories()" class="flex items-center space-x-2 bg-white text-blue-900 px-4 py-2 rounded-lg hover:bg-blue-50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <span class="font-semibold">Categories</span>
                </button>
                
                <form method="GET" class="flex-1 max-w-2xl">
                    <div class="relative">
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Type Your Products ..." class="w-full px-6 py-3 pr-12 rounded-full text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <button type="submit" class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
                
                <div class="flex items-center space-x-4">
                    <a href="wishlist.php" class="relative">
                        <svg class="w-6 h-6 text-white hover:text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </a>
                    <a href="cart.php" class="relative">
                        <svg class="w-6 h-6 text-white hover:text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <?php if($cart_count > 0): ?>
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"><?php echo $cart_count; ?></span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div id="categoriesPanel" class="hidden bg-white shadow-lg">
        <div class="container mx-auto px-6 py-8">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Categories</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <?php while($cat = $categories_result->fetch_assoc()): ?>
                    <a href="?category=<?php echo urlencode($cat['category']); ?>" class="category-card bg-white border-2 border-gray-200 rounded-xl p-4 text-center hover:border-blue-500 hover:shadow-lg transition <?php echo $category_filter == $cat['category'] ? 'border-blue-500' : ''; ?>">
                        <div class="w-20 h-20 mx-auto mb-3 bg-gradient-to-br from-red-400 to-red-600 rounded-lg flex items-center justify-center">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-800"><?php echo htmlspecialchars($cat['category']); ?></h4>
                    </a>
                <?php endwhile; ?>
            </div>
            <?php if($category_filter): ?>
                <div class="mt-4">
                    <a href="dashboard.php" class="text-blue-600 hover:text-blue-800">Clear Filter</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="container mx-auto px-6 py-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            <?php echo $category_filter ? htmlspecialchars($category_filter) : 'All Products'; ?>
        </h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
            <?php while($product = $products->fetch_assoc()): ?>
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition overflow-hidden flex flex-col">
                    <div class="relative">
                        <button onclick="toggleWishlist(<?php echo $product['id']; ?>)" class="absolute top-3 right-3 z-10 wishlist-btn <?php echo in_array($product['id'], $wishlist_items) ? 'active' : ''; ?>" data-product-id="<?php echo $product['id']; ?>">
                            <svg class="w-6 h-6" fill="<?php echo in_array($product['id'], $wishlist_items) ? 'currentColor' : 'none'; ?>" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-48 object-cover">
                    </div>
                    <div class="p-4 flex flex-col flex-grow">
                        <h3 class="font-semibold text-gray-800 mb-2 text-sm min-h-[40px]"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="text-xs text-gray-500 mb-2"><?php echo htmlspecialchars($product['weight']); ?></p>
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <span class="text-lg font-bold text-blue-900">₱<?php echo number_format($product['price'], 2); ?></span>
                            </div>
                            <span class="text-xs <?php echo $product['stock'] > 0 ? 'text-green-600' : 'text-red-600'; ?> font-semibold">
                                <?php echo $product['stock'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
                            </span>
                        </div>
                        <button onclick="addToCart(<?php echo $product['id']; ?>)" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition flex items-center justify-center mt-auto <?php echo $product['stock'] <= 0 ? 'opacity-50 cursor-not-allowed' : ''; ?>" <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add to Cart
                        </button>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div id="notification" class="hidden fixed top-20 right-6 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <p id="notificationText"></p>
    </div>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('active');
        }

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const button = event.target.closest('button[onclick="toggleDropdown()"]');
            if (!button && !dropdown.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });

        function toggleCategories() {
            const panel = document.getElementById('categoriesPanel');
            panel.classList.toggle('hidden');
        }

        function toggleWishlist(productId) {
            fetch('ajax/toggle_wishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    const btn = document.querySelector(`button[data-product-id="${productId}"]`);
                    const svg = btn.querySelector('svg');
                    if(data.action === 'added') {
                        btn.classList.add('active');
                        svg.setAttribute('fill', 'currentColor');
                    } else {
                        btn.classList.remove('active');
                        svg.setAttribute('fill', 'none');
                    }
                    showNotification(data.message);
                }
            });
        }

        function addToCart(productId) {
            fetch('ajax/add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ product_id: productId, quantity: 1 })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    showNotification(data.message);
                    location.reload();
                }
            });
        }

        function showNotification(message) {
            const notification = document.getElementById('notification');
            const notificationText = document.getElementById('notificationText');
            notificationText.textContent = message;
            notification.classList.remove('hidden');
            setTimeout(() => {
                notification.classList.add('hidden');
            }, 3000);
        }
    </script>
</body>
</html>