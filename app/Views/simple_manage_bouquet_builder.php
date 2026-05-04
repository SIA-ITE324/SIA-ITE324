<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-purple-800">
                        <i class="fas fa-spa text-pink-500"></i> Fleur Admin
                    </h1>
                </div>
                
                <div class="flex items-center space-x-6">
                    <a href="app.php?action=admin" class="text-gray-700 hover:text-purple-600">
                        <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                    </a>
                    <a href="app.php?action=orders" class="text-gray-700 hover:text-purple-600">
                        <i class="fas fa-shopping-bag mr-1"></i>Orders
                    </a>
                    <a href="app.php?action=products" class="text-gray-700 hover:text-purple-600">
                        <i class="fas fa-box mr-1"></i>Products
                    </a>
                    <div class="relative group">
                        <button class="flex items-center text-gray-700 hover:text-purple-600">
                            <i class="fas fa-user mr-2"></i>
                            <?= $_SESSION['user_name'] ?>
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <a href="app.php?action=profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                            <a href="app.php?action=logout" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 border-t">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Bouquet Builder Settings</h1>
            <p class="text-gray-600 text-lg">Manage all components of the custom bouquet builder</p>
            <div class="mt-4 flex justify-center space-x-4">
                <a href="app.php?action=admin_bouquet_builder" class="text-purple-600 hover:text-purple-800 text-sm">
                    <i class="fas fa-magic mr-1"></i>Test Bouquet Builder
                </a>
                <a href="app.php?action=bouquet_builder" class="text-purple-600 hover:text-purple-800 text-sm">
                    <i class="fas fa-external-link-alt mr-1"></i>View Customer Version
                </a>
                <a href="app.php?action=admin" class="text-gray-600 hover:text-gray-800 text-sm">
                    <i class="fas fa-arrow-left mr-1"></i>Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-check-circle mr-2"></i>
                <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Flowers Management -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">
                    <i class="fas fa-spa text-purple-600 mr-2"></i>Available Flowers
                </h2>
                <a href="app.php?action=add_product" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                    <i class="fas fa-plus mr-2"></i>Add New Flower
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Flower</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($flowers as $flower): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-leaf text-purple-600"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900"><?= $flower['name'] ?></div>
                                            <div class="text-sm text-gray-500"><?= $flower['sku'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">₱<?= number_format($flower['price'], 2) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= $flower['stock_quantity'] ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?= $flower['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= ucfirst($flower['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="app.php?action=edit_product&id=<?= $flower['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="text-red-600 hover:text-red-900" onclick="deleteFlower(<?= $flower['id'] ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bouquet Sizes Management -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                <i class="fas fa-ruler-combined text-purple-600 mr-2"></i>Bouquet Sizes
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <?php foreach ($bouquet_sizes as $size_key => $size): ?>
                    <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-purple-500 transition-colors">
                        <div class="text-center mb-4">
                            <div class="text-3xl mb-2">
                                <i class="fas <?= $size['icon'] ?> text-purple-600"></i>
                            </div>
                            <h3 class="font-semibold text-lg"><?= $size['name'] ?></h3>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Base Price:</span>
                                <span class="font-medium">₱<?= number_format($size['base_price'], 2) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Max Flowers:</span>
                                <span class="font-medium"><?= $size['max_flowers'] ?></span>
                            </div>
                        </div>
                        <div class="mt-4 flex space-x-2">
                            <button onclick="editSize('<?= $size_key ?>', '<?= addslashes($size['name']) ?>', <?= $size['base_price'] ?>, <?= $size['max_flowers'] ?>, '<?= addslashes($size['icon']) ?>')" class="flex-1 bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="mt-6">
                <button onclick="addNewSize()" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                    <i class="fas fa-plus mr-2"></i>Add New Size
                </button>
            </div>
        </div>

        <!-- Bouquet Styles Management -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                <i class="fas fa-palette text-purple-600 mr-2"></i>Bouquet Styles
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php foreach ($bouquet_styles as $style_key => $style): ?>
                    <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-purple-500 transition-colors">
                        <div class="flex items-center mb-3">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas <?= $style['icon'] ?> text-purple-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg"><?= $style['name'] ?></h3>
                                <p class="text-gray-600 text-sm"><?= $style['description'] ?></p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="editStyle('<?= $style_key ?>', '<?= addslashes($style['name']) ?>', '<?= addslashes($style['description']) ?>', '<?= addslashes($style['icon']) ?>')" class="flex-1 bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="mt-6">
                <button onclick="addNewStyle()" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                    <i class="fas fa-plus mr-2"></i>Add New Style
                </button>
            </div>
        </div>

        <!-- Color Themes Management -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                <i class="fas fa-paint-brush text-purple-600 mr-2"></i>Color Themes
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($color_themes as $theme_key => $theme): ?>
                    <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-purple-500 transition-colors">
                        <div class="text-center mb-3">
                            <div class="flex justify-center space-x-2 mb-3">
                                <?php foreach ($theme['colors'] as $color): ?>
                                    <div class="w-8 h-8 rounded-full" style="background-color: <?= $color ?>"></div>
                                <?php endforeach; ?>
                            </div>
                            <h3 class="font-semibold"><?= $theme['name'] ?></h3>
                            <p class="text-gray-600 text-sm"><?= $theme['description'] ?></p>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="editTheme('<?= $theme_key ?>', '<?= addslashes($theme['name']) ?>', '<?= addslashes($theme['description']) ?>', '<?= addslashes(implode(',', $theme['colors'])) ?>')" class="flex-1 bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="mt-6">
                <button onclick="addNewTheme()" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                    <i class="fas fa-plus mr-2"></i>Add New Theme
                </button>
            </div>
        </div>

        <!-- Fillers Management -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                <i class="fas fa-spray-can text-green-600 mr-2"></i>Fillers
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php 
                $fillers = [
                    'babys_breath' => [
                        'name' => 'Baby\'s Breath',
                        'price' => 50,
                        'description' => 'Delicate white clusters',
                        'icon' => 'fa-cloud'
                    ],
                    'ferns' => [
                        'name' => 'Ferns',
                        'price' => 40,
                        'description' => 'Green foliage accent',
                        'icon' => 'fa-leaf'
                    ],
                    'eucalyptus' => [
                        'name' => 'Eucalyptus',
                        'price' => 60,
                        'description' => 'Aromatic silver-green',
                        'icon' => 'fa-spa'
                    ],
                    'wax_flower' => [
                        'name' => 'Wax Flower',
                        'price' => 55,
                        'description' => 'Tiny pink clusters',
                        'icon' => 'fa-circle'
                    ],
                    'statice' => [
                        'name' => 'Statice',
                        'price' => 45,
                        'description' => 'Colorful paper-like flowers',
                        'icon' => 'fa-star'
                    ],
                    'italian_ruscus' => [
                        'name' => 'Italian Ruscus',
                        'price' => 35,
                        'description' => 'Elegant green leaves',
                        'icon' => 'fa-feather'
                    ]
                ];
                ?>
                <?php foreach ($fillers as $filler_key => $filler): ?>
                    <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-green-500 transition-colors">
                        <div class="flex items-center mb-3">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas <?= $filler['icon'] ?> text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold"><?= $filler['name'] ?></h4>
                                <p class="text-green-600 font-bold">₱<?= number_format($filler['price'], 2) ?></p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mb-3"><?= $filler['description'] ?></p>
                        <div class="flex space-x-2">
                            <button onclick="editFiller('<?= $filler_key ?>', '<?= addslashes($filler['name']) ?>', <?= $filler['price'] ?>, '<?= addslashes($filler['description']) ?>', '<?= addslashes($filler['icon']) ?>')" class="flex-1 bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="mt-6">
                <button onclick="testEditFiller()" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 mr-2">
                    <i class="fas fa-bug mr-2"></i>Test Edit
                </button>
                <button onclick="addNewFiller()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    <i class="fas fa-plus mr-2"></i>Add New Filler
                </button>
            </div>
        </div>

        <!-- Add-ons Management -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                <i class="fas fa-gift text-pink-500 mr-2"></i>Add-ons
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php foreach ($add_ons as $addon_key => $addon): ?>
                    <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-purple-500 transition-colors">
                        <div class="flex items-center mb-3">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas <?= $addon['icon'] ?> text-purple-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold"><?= $addon['name'] ?></h4>
                                <p class="text-purple-600 font-bold">+₱<?= number_format($addon['price'], 2) ?></p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mb-3"><?= $addon['description'] ?></p>
                        <div class="flex space-x-2">
                            <button onclick="editAddon('<?= $addon_key ?>', '<?= addslashes($addon['name']) ?>', <?= $addon['price'] ?>, '<?= addslashes($addon['description']) ?>', '<?= addslashes($addon['icon']) ?>')" class="flex-1 bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="mt-6">
                <button onclick="addNewAddon()" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                    <i class="fas fa-plus mr-2"></i>Add New Add-on
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Modals -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modalTitle">Edit Item</h3>
                <form id="editForm">
                    <div id="modalContent" class="space-y-4">
                        <!-- Dynamic content will be inserted here -->
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                            Cancel
                        </button>
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentEditType = '';
        let currentEditKey = '';
        
        // Load data from session storage and update page
        window.addEventListener('load', function() {
            console.log('Bouquet Builder Settings page loaded');
            
            // Load and apply session storage data
            loadFromSessionStorage();
            
            // Test if modal elements exist
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');
            console.log('Modal element:', modal);
            console.log('Form element:', form);
            
            if (!modal) {
                console.error('Modal element not found!');
            }
            if (!form) {
                console.error('Form element not found!');
            }
        });
        
        // Function to load data from session storage and update page
        function loadFromSessionStorage() {
            const storageKey = 'bouquetBuilderData';
            const storedData = sessionStorage.getItem(storageKey);
            
            if (storedData) {
                const bouquetData = JSON.parse(storedData);
                console.log('Loading data from session storage:', bouquetData);
                
                // Update fillers if they exist in session storage
                if (bouquetData.filler) {
                    updateFillerDisplay(bouquetData.filler);
                }
                
                // Update other sections as needed
                if (bouquetData.size) {
                    updateSizeDisplay(bouquetData.size);
                }
                if (bouquetData.style) {
                    updateStyleDisplay(bouquetData.style);
                }
                if (bouquetData.theme) {
                    updateThemeDisplay(bouquetData.theme);
                }
                if (bouquetData.addon) {
                    updateAddonDisplay(bouquetData.addon);
                }
            }
        }
        
        // Function to update filler display
        function updateFillerDisplay(fillerData) {
            Object.keys(fillerData).forEach(key => {
                const data = fillerData[key];
                
                // Find the filler card by looking for the edit button with this key
                const editButtons = document.querySelectorAll('button[onclick*="editFiller"]');
                editButtons.forEach(button => {
                    if (button.getAttribute('onclick').includes(`'${key}'`)) {
                        const card = button.closest('.border-2');
                        if (card) {
                            // Update the card content
                            const nameElement = card.querySelector('h4');
                            const priceElement = card.querySelector('.text-green-600');
                            const descElement = card.querySelector('.text-gray-600');
                            const iconElement = card.querySelector('.fas');
                            
                            if (nameElement) nameElement.textContent = data.name;
                            if (priceElement) priceElement.textContent = '₱' + parseFloat(data.price).toFixed(2);
                            if (descElement) descElement.textContent = data.description;
                            if (iconElement) {
                                // Update icon class
                                iconElement.className = `fas ${data.icon} text-green-600 text-xl`;
                            }
                            
                            // Update the edit button onclick
                            const newOnClick = `editFiller('${key}', '${data.name.replace(/'/g, "\\'")}', ${data.price}, '${data.description.replace(/'/g, "\\'")}', '${data.icon}')`;
                            button.setAttribute('onclick', newOnClick);
                        }
                    }
                });
            });
        }
        
        // Similar functions for other sections can be added here
        function updateSizeDisplay(sizeData) {
            // Implementation for sizes
        }
        
        function updateStyleDisplay(styleData) {
            // Implementation for styles
        }
        
        function updateThemeDisplay(themeData) {
            // Implementation for themes
        }
        
        function updateAddonDisplay(addonData) {
            // Implementation for add-ons
        }

        function editSize(key, name, basePrice, maxFlowers, icon) {
            currentEditType = 'size';
            currentEditKey = key;
            
            document.getElementById('modalTitle').textContent = 'Edit Size: ' + name;
            document.getElementById('modalContent').innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="${name}" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Base Price (₱)</label>
                    <input type="number" name="base_price" value="${basePrice}" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Max Flowers</label>
                    <input type="number" name="max_flowers" value="${maxFlowers}" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon (FontAwesome class)</label>
                    <input type="text" name="icon" value="${icon}" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
            `;
            
            showModal();
        }

        function editStyle(key, name, description, icon) {
            currentEditType = 'style';
            currentEditKey = key;
            
            document.getElementById('modalTitle').textContent = 'Edit Style: ' + name;
            document.getElementById('modalContent').innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="${name}" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>${description}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon (FontAwesome class)</label>
                    <input type="text" name="icon" value="${icon}" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
            `;
            
            showModal();
        }

        function editTheme(key, name, description, colors) {
            currentEditType = 'theme';
            currentEditKey = key;
            
            document.getElementById('modalTitle').textContent = 'Edit Theme: ' + name;
            document.getElementById('modalContent').innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="${name}" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>${description}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Colors (comma-separated hex codes)</label>
                    <input type="text" name="colors" value="${colors}" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
            `;
            
            showModal();
        }

        function editAddon(key, name, price, description, icon) {
            currentEditType = 'addon';
            currentEditKey = key;
            
            document.getElementById('modalTitle').textContent = 'Edit Add-on: ' + name;
            document.getElementById('modalContent').innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="${name}" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price (₱)</label>
                    <input type="number" name="price" value="${price}" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>${description}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon (FontAwesome class)</label>
                    <input type="text" name="icon" value="${icon}" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
            `;
            
            showModal();
        }

        function addNewSize() {
            currentEditType = 'size';
            currentEditKey = '';
            
            document.getElementById('modalTitle').textContent = 'Add New Size';
            document.getElementById('modalContent').innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Key (unique identifier)</label>
                    <input type="text" name="key" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Base Price (₱)</label>
                    <input type="number" name="base_price" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Max Flowers</label>
                    <input type="number" name="max_flowers" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon (FontAwesome class)</label>
                    <input type="text" name="icon" value="fa-seedling" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
            `;
            
            showModal();
        }

        function addNewStyle() {
            currentEditType = 'style';
            currentEditKey = '';
            
            document.getElementById('modalTitle').textContent = 'Add New Style';
            document.getElementById('modalContent').innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Key (unique identifier)</label>
                    <input type="text" name="key" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md" required></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon (FontAwesome class)</label>
                    <input type="text" name="icon" value="fa-grip-lines" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
            `;
            
            showModal();
        }

        function addNewTheme() {
            currentEditType = 'theme';
            currentEditKey = '';
            
            document.getElementById('modalTitle').textContent = 'Add New Theme';
            document.getElementById('modalContent').innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Key (unique identifier)</label>
                    <input type="text" name="key" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md" required></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Colors (comma-separated hex codes)</label>
                    <input type="text" name="colors" placeholder="#ff6b9d,#feca57,#ff9ff3" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
            `;
            
            showModal();
        }

        function addNewAddon() {
            currentEditType = 'addon';
            currentEditKey = '';
            
            document.getElementById('modalTitle').textContent = 'Add New Add-on';
            document.getElementById('modalContent').innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Key (unique identifier)</label>
                    <input type="text" name="key" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price (₱)</label>
                    <input type="number" name="price" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md" required></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon (FontAwesome class)</label>
                    <input type="text" name="icon" value="fa-gift" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
            `;
            
            showModal();
        }

        function editFiller(key, name, price, description, icon) {
            console.log('editFiller called with:', key, name, price, description, icon);
            
            currentEditType = 'filler';
            currentEditKey = key;
            
            console.log('Setting modal title and content');
            document.getElementById('modalTitle').textContent = 'Edit Filler: ' + name;
            document.getElementById('modalContent').innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="${name}" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price (₱)</label>
                    <input type="number" name="price" value="${price}" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>${description}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon (FontAwesome class)</label>
                    <input type="text" name="icon" value="${icon}" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
            `;
            
            console.log('Calling showModal');
            showModal();
        }

        function addNewFiller() {
            currentEditType = 'filler';
            currentEditKey = '';
            
            document.getElementById('modalTitle').textContent = 'Add New Filler';
            document.getElementById('modalContent').innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Key (unique identifier)</label>
                    <input type="text" name="key" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price (₱)</label>
                    <input type="number" name="price" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md" required></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon (FontAwesome class)</label>
                    <input type="text" name="icon" value="fa-leaf" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
            `;
            
            showModal();
        }

        function testEditFiller() {
            console.log('Test edit filler button clicked');
            editFiller('test_key', 'Test Filler', 99, 'Test description', 'fa-test');
        }

        function showModal() {
            console.log('showModal called');
            const modal = document.getElementById('editModal');
            console.log('Modal element:', modal);
            if (modal) {
                modal.classList.remove('hidden');
                console.log('Modal should now be visible');
            } else {
                console.error('Modal element not found in showModal!');
            }
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function deleteFlower(id) {
            if (confirm('Are you sure you want to delete this flower?')) {
                window.location.href = 'app.php?action=delete_product&id=' + id;
            }
        }

        // Handle form submission
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Debug: Check if form is working
            console.log('Form submitted');
            console.log('Edit type:', currentEditType);
            console.log('Edit key:', currentEditKey);
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            console.log('Form data:', data);
            
            // Validate required fields
            if (!data.name || data.name.trim() === '') {
                alert('Name is required!');
                return;
            }
            
            // Process based on edit type
            let successMessage = '';
            
            switch(currentEditType) {
                case 'size':
                    successMessage = `Size "${data.name}" updated successfully!`;
                    break;
                case 'style':
                    successMessage = `Style "${data.name}" updated successfully!`;
                    break;
                case 'theme':
                    successMessage = `Theme "${data.name}" updated successfully!`;
                    break;
                case 'filler':
                    successMessage = `Filler "${data.name}" updated successfully!`;
                    break;
                case 'addon':
                    successMessage = `Add-on "${data.name}" updated successfully!`;
                    break;
                default:
                    successMessage = 'Settings updated successfully!';
            }
            
            // Update the page content immediately
            updatePageContent(currentEditType, currentEditKey, data);
            
            // Show success message
            if (currentEditKey) {
                // Edit mode
                alert(successMessage + '\n\nChanges have been applied to the page.');
            } else {
                // Add mode
                alert(`${successMessage.replace('updated', 'added')}\n\nChanges have been applied to the page.`);
            }
            
            closeModal();
            
            // Function to update page content dynamically
            function updatePageContent(type, key, data) {
                console.log('Updating page content for:', type, key, data);
                
                if (type === 'filler') {
                    // Find the filler card by looking for the edit button with this key
                    const editButtons = document.querySelectorAll('button[onclick*="editFiller"]');
                    editButtons.forEach(button => {
                        if (button.getAttribute('onclick').includes(`'${key}'`)) {
                            const card = button.closest('.border-2');
                            if (card) {
                                // Update the card content
                                const nameElement = card.querySelector('h4');
                                const priceElement = card.querySelector('.text-green-600');
                                const descElement = card.querySelector('.text-gray-600');
                                const iconElement = card.querySelector('.fas');
                                
                                console.log('Found card elements:', {nameElement, priceElement, descElement, iconElement});
                                
                                if (nameElement) {
                                    nameElement.textContent = data.name;
                                    console.log('Updated name to:', data.name);
                                }
                                if (priceElement) {
                                    priceElement.textContent = '₱' + parseFloat(data.price).toFixed(2);
                                    console.log('Updated price to:', '₱' + parseFloat(data.price).toFixed(2));
                                }
                                if (descElement) {
                                    descElement.textContent = data.description;
                                    console.log('Updated description to:', data.description);
                                }
                                if (iconElement) {
                                    // Update icon class
                                    iconElement.className = `fas ${data.icon} text-green-600 text-xl`;
                                    console.log('Updated icon to:', data.icon);
                                }
                                
                                // Update the edit button onclick
                                const escapedName = data.name.replace(/'/g, "\\'");
                                const escapedDesc = data.description.replace(/'/g, "\\'");
                                const newOnClick = `editFiller('${key}', '${escapedName}', ${data.price}, '${escapedDesc}', '${data.icon}')`;
                                button.setAttribute('onclick', newOnClick);
                                console.log('Updated edit button onclick');
                            }
                        }
                    });
                }
                
                // Similar logic can be added for other types (size, style, theme, addon)
                if (type === 'size') {
                    updateSizeCard(key, data);
                }
                if (type === 'style') {
                    updateStyleCard(key, data);
                }
                if (type === 'theme') {
                    updateThemeCard(key, data);
                }
                if (type === 'addon') {
                    updateAddonCard(key, data);
                }
            }
            
            // Helper functions for other sections
            function updateSizeCard(key, data) {
                const editButtons = document.querySelectorAll('button[onclick*="editSize"]');
                editButtons.forEach(button => {
                    if (button.getAttribute('onclick').includes(`'${key}'`)) {
                        const card = button.closest('.border-2');
                        if (card) {
                            const nameElement = card.querySelector('h3');
                            const priceElement = card.querySelector('.text-purple-600');
                            const flowersElement = card.querySelector('.text-gray-600');
                            const iconElement = card.querySelector('.fas');
                            
                            if (nameElement) nameElement.textContent = data.name;
                            if (priceElement) priceElement.textContent = '₱' + parseFloat(data.basePrice).toFixed(2);
                            if (flowersElement) flowersElement.textContent = 'Max Flowers: ' + data.maxFlowers;
                            if (iconElement) iconElement.className = `fas ${data.icon} text-purple-600 text-xl`;
                            
                            // Update edit button
                            const escapedName = data.name.replace(/'/g, "\\'");
                            const newOnClick = `editSize('${key}', '${escapedName}', ${data.basePrice}, ${data.maxFlowers}, '${data.icon}')`;
                            button.setAttribute('onclick', newOnClick);
                        }
                    }
                });
            }
            
            function updateStyleCard(key, data) {
                const editButtons = document.querySelectorAll('button[onclick*="editStyle"]');
                editButtons.forEach(button => {
                    if (button.getAttribute('onclick').includes(`'${key}'`)) {
                        const card = button.closest('.border-2');
                        if (card) {
                            const nameElement = card.querySelector('h3');
                            const descElement = card.querySelector('.text-gray-600');
                            const iconElement = card.querySelector('.fas');
                            
                            if (nameElement) nameElement.textContent = data.name;
                            if (descElement) descElement.textContent = data.description;
                            if (iconElement) iconElement.className = `fas ${data.icon} text-purple-600 text-xl`;
                            
                            // Update edit button
                            const escapedName = data.name.replace(/'/g, "\\'");
                            const escapedDesc = data.description.replace(/'/g, "\\'");
                            const newOnClick = `editStyle('${key}', '${escapedName}', '${escapedDesc}', '${data.icon}')`;
                            button.setAttribute('onclick', newOnClick);
                        }
                    }
                });
            }
            
            function updateThemeCard(key, data) {
                const editButtons = document.querySelectorAll('button[onclick*="editTheme"]');
                editButtons.forEach(button => {
                    if (button.getAttribute('onclick').includes(`'${key}'`)) {
                        const card = button.closest('.border-2');
                        if (card) {
                            const nameElement = card.querySelector('h3');
                            const descElement = card.querySelector('.text-gray-600');
                            
                            if (nameElement) nameElement.textContent = data.name;
                            if (descElement) descElement.textContent = data.description;
                            
                            // Update colors if needed
                            const colorDivs = card.querySelectorAll('.w-8.h-8');
                            const colors = data.colors.split(',');
                            colorDivs.forEach((div, index) => {
                                if (colors[index]) {
                                    div.style.backgroundColor = colors[index].trim();
                                }
                            });
                            
                            // Update edit button
                            const escapedName = data.name.replace(/'/g, "\\'");
                            const escapedDesc = data.description.replace(/'/g, "\\'");
                            const newOnClick = `editTheme('${key}', '${escapedName}', '${escapedDesc}', '${data.colors}')`;
                            button.setAttribute('onclick', newOnClick);
                        }
                    }
                });
            }
            
            function updateAddonCard(key, data) {
                const editButtons = document.querySelectorAll('button[onclick*="editAddon"]');
                editButtons.forEach(button => {
                    if (button.getAttribute('onclick').includes(`'${key}'`)) {
                        const card = button.closest('.border-2');
                        if (card) {
                            const nameElement = card.querySelector('h4');
                            const priceElement = card.querySelector('.text-purple-600');
                            const descElement = card.querySelector('.text-gray-600');
                            const iconElement = card.querySelector('.fas');
                            
                            if (nameElement) nameElement.textContent = data.name;
                            if (priceElement) priceElement.textContent = '+₱' + parseFloat(data.price).toFixed(2);
                            if (descElement) descElement.textContent = data.description;
                            if (iconElement) iconElement.className = `fas ${data.icon} text-purple-600 text-xl`;
                            
                            // Update edit button
                            const escapedName = data.name.replace(/'/g, "\\'");
                            const escapedDesc = data.description.replace(/'/g, "\\'");
                            const newOnClick = `editAddon('${key}', '${escapedName}', ${data.price}, '${escapedDesc}', '${data.icon}')`;
                            button.setAttribute('onclick', newOnClick);
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
