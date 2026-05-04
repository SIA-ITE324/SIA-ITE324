<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'My Profile' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-purple-800">
                        <i class="fas fa-spa text-pink-500"></i> Fleur
                    </h1>
                </div>
                
                <div class="flex items-center space-x-6">
                    <a href="app.php?action=home" class="text-gray-700 hover:text-purple-600">Home</a>
                    <a href="app.php?action=orders" class="text-gray-700 hover:text-purple-600">My Orders</a>
                    <div class="relative group">
                        <button class="flex items-center text-gray-700 hover:text-purple-600">
                            <i class="fas fa-user mr-2"></i>
                            <?= $_SESSION['user_name'] ?>
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                <a href="app.php?action=admin" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Admin Dashboard</a>
                            <?php endif; ?>
                            <a href="app.php?action=profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Profile</a>
                            <a href="app.php?action=logout" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 border-t">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <!-- Flash Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-check-circle mr-2"></i>
                <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <!-- Profile Header -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">My Profile</h2>
                <button class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                    <i class="fas fa-edit mr-2"></i>Edit Profile
                </button>
            </div>
            
            <div class="flex items-center space-x-6">
                <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-purple-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-800"><?= $user['first_name'] . ' ' . $user['last_name'] ?></h3>
                    <p class="text-gray-600"><?= $user['email'] ?></p>
                    <span class="inline-block px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800 mt-1">
                        <?= ucfirst($user['role']) ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Profile Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Personal Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-600">First Name</label>
                        <p class="font-medium text-gray-800"><?= $user['first_name'] ?></p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Last Name</label>
                        <p class="font-medium text-gray-800"><?= $user['last_name'] ?></p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Email Address</label>
                        <p class="font-medium text-gray-800"><?= $user['email'] ?></p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Phone Number</label>
                        <p class="font-medium text-gray-800"><?= $user['phone'] ?: 'Not provided' ?></p>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Address Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-600">Address</label>
                        <p class="font-medium text-gray-800"><?= $user['address'] ?: 'Not provided' ?></p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">City</label>
                        <p class="font-medium text-gray-800"><?= $user['city'] ?: 'Not provided' ?></p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">State</label>
                        <p class="font-medium text-gray-800"><?= $user['state'] ?: 'Not provided' ?></p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Postal Code</label>
                        <p class="font-medium text-gray-800"><?= $user['postal_code'] ?: 'Not provided' ?></p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Country</label>
                        <p class="font-medium text-gray-800"><?= $user['country'] ?: 'Not provided' ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Information -->
        <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Account Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-600">Account Status</label>
                        <p class="font-medium text-gray-800">
                            <span class="inline-block px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                <?= ucfirst($user['status']) ?>
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Member Since</label>
                        <p class="font-medium text-gray-800"><?= date('M d, Y', strtotime($user['created_at'])) ?></p>
                    </div>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-600">Last Login</label>
                        <p class="font-medium text-gray-800"><?= $user['last_login'] ? date('M d, Y H:i', strtotime($user['last_login'])) : 'Never' ?></p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Email Verified</label>
                        <p class="font-medium text-gray-800">
                            <span class="inline-block px-2 py-1 text-xs rounded-full bg-<?= $user['email_verified'] ? 'green' : 'yellow' ?>-100 text-<?= $user['email_verified'] ? 'green' : 'yellow' ?>-800">
                                <?= $user['email_verified'] ? 'Verified' : 'Not Verified' ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                    <i class="fas fa-key mr-2"></i>Change Password
                </button>
                <a href="app.php?action=orders" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-center block">
                    <i class="fas fa-shopping-cart mr-2"></i>View Orders
                </a>
                <button class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    <i class="fas fa-download mr-2"></i>Download Data
                </button>
            </div>
        </div>
    </div>
</body>
</html>
