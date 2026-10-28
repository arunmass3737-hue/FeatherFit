<?php
$pageTitle = "Admin Command Dashboard | Feather Fits";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feather Fits — Admin Command | White, Black & Purple</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS v3 via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        adm: {
                            bg: '#F8F9FA',
                            card: '#FFFFFF',
                            text: '#0F172A',
                            muted: '#64748B',
                            border: '#E2E8F0',
                            purple: '#7E22CE',
                            purpleLight: '#FAF5FF',
                            purpleDark: '#581C87',
                            black: '#09090B'
                        }
                    },
                    fontFamily: {
                        cinzel: ['Cinzel', 'serif'],
                        sans: ['Plus Jakarta Sans', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#F8F9FA] text-[#0F172A] font-sans antialiased min-h-screen flex flex-col">

    <!-- Top Admin Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-40 px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="w-8 h-8 rounded-lg bg-[#7E22CE] text-white flex items-center justify-center font-black text-sm">FF</span>
            <div>
                <h1 class="font-cinzel font-black text-lg text-black tracking-tight leading-none">FEATHER FITS</h1>
                <span class="text-[10px] uppercase font-bold tracking-widest text-[#7E22CE]">Admin Command Center</span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="index.php" target="_blank" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-xs font-bold text-gray-700 flex items-center gap-2 transition">
                <i data-lucide="external-link" class="w-3.5 h-3.5 text-[#7E22CE]"></i>
                <span>Open Live Storefront</span>
            </a>
            <div class="flex items-center gap-2 pl-3 border-l border-gray-200">
                <div class="w-8 h-8 rounded-full bg-[#7E22CE] text-white text-xs font-bold flex items-center justify-center">
                    AD
                </div>
                <span class="text-xs font-bold text-gray-800 hidden sm:inline">Administrator</span>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8 flex-1 space-y-8">
        
        <!-- Metrics Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs uppercase font-bold text-gray-500">Total Store Revenue</span>
                    <h3 class="text-2xl font-black text-black mt-1">₹3,42,890</h3>
                    <span class="text-xs text-emerald-600 font-bold mt-1 inline-flex items-center gap-1">
                        <i data-lucide="trending-up" class="w-3.5 h-3.5"></i> +24.8% this drop
                    </span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-[#7E22CE] flex items-center justify-center">
                    <i data-lucide="indian-rupee" class="w-6 h-6"></i>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs uppercase font-bold text-gray-500">Live Orders</span>
                    <h3 class="text-2xl font-black text-black mt-1">142</h3>
                    <span class="text-xs text-purple-600 font-bold mt-1 inline-flex items-center gap-1">
                        <i data-lucide="clock" class="w-3.5 h-3.5"></i> 18 in dispatch queue
                    </span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-[#7E22CE] flex items-center justify-center">
                    <i data-lucide="package" class="w-6 h-6"></i>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs uppercase font-bold text-gray-500">Active Products</span>
                    <h3 class="text-2xl font-black text-black mt-1">17 Drops</h3>
                    <span class="text-xs text-blue-600 font-bold mt-1 inline-flex items-center gap-1">
                        <i data-lucide="tag" class="w-3.5 h-3.5"></i> 4 Departments
                    </span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-[#7E22CE] flex items-center justify-center">
                    <i data-lucide="layers" class="w-6 h-6"></i>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs uppercase font-bold text-gray-500">VIP Subscribers</span>
                    <h3 class="text-2xl font-black text-black mt-1" id="admin-subscriber-count">1,284</h3>
                    <span class="text-xs text-emerald-600 font-bold mt-1 inline-flex items-center gap-1">
                        <i data-lucide="mail" class="w-3.5 h-3.5"></i> Realtime newsletter
                    </span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-[#7E22CE] flex items-center justify-center">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <!-- Product Management Section -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-gray-200">
                <div>
                    <h2 class="text-lg font-bold font-cinzel text-black">Product Catalog Inventory</h2>
                    <p class="text-xs text-gray-500">Manage apparel listings, upload images, update prices, and delete drops.</p>
                </div>
                <button type="button" onclick="openProductModal()" class="px-5 py-2.5 bg-[#7E22CE] hover:bg-[#581C87] text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow flex items-center gap-2 transition">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Add New Apparel Drop</span>
                </button>
            </div>

            <!-- Table of Products -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-gray-200 text-gray-500 uppercase font-bold tracking-wider">
                            <th class="py-3 px-4">Product</th>
                            <th class="py-3 px-4">Department</th>
                            <th class="py-3 px-4">Price</th>
                            <th class="py-3 px-4">Stock</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="admin-product-table-body" class="divide-y divide-gray-100 font-medium">
                        <!-- Populated dynamically via JS -->
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Product Modal with Image Uploader Preview -->
    <div id="admin-prod-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeProductModal()"></div>
        <div class="relative max-w-xl w-full bg-white rounded-3xl p-6 sm:p-8 shadow-2xl z-10 border border-gray-200 space-y-5">
            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                <h3 id="modal-title" class="font-cinzel font-black text-lg text-black">Add New Streetwear Drop</h3>
                <button type="button" onclick="closeProductModal()" class="p-2 text-gray-400 hover:text-black">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form id="admin-prod-form" onsubmit="handleSaveProduct(event)" class="space-y-4">
                <input type="hidden" id="p-form-id" value="" />
                
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Product Title *</label>
                    <input type="text" id="p-form-name" required placeholder="e.g. Vintage Jujutsu Heavyweight Tee" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-2.5 text-xs text-black focus:outline-none focus:border-[#7E22CE]" />
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Department *</label>
                        <select id="p-form-dept" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2.5 text-xs text-black focus:outline-none focus:border-[#7E22CE]">
                            <option value="men">Men's Streetwear</option>
                            <option value="women">Women's Collection</option>
                            <option value="kids">Kids' Drops</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Selling Price (₹) *</label>
                        <input type="number" id="p-form-price" required placeholder="1299" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-2.5 text-xs text-black focus:outline-none focus:border-[#7E22CE]" />
                    </div>
                </div>

                <!-- Image Upload with Instant Preview -->
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Product Image (Upload or URL)</label>
                    <div class="flex items-center gap-3">
                        <input type="file" id="p-form-file" accept="image/*" onchange="previewUploadImage(event)" class="text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-[#7E22CE] hover:file:bg-purple-100" />
                        <input type="text" id="p-form-img-url" placeholder="or enter image path" class="flex-1 bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs text-black" />
                    </div>
                    <div id="img-preview-wrap" class="mt-3 hidden">
                        <img id="img-preview" src="" class="w-20 h-24 object-cover rounded-xl border border-gray-300 bg-gray-100" />
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" onclick="closeProductModal()" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-bold text-gray-700">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 bg-[#7E22CE] hover:bg-[#581C87] text-white rounded-xl text-xs font-bold uppercase shadow">Save Product</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script -->
    <script>
        let currentProducts = [
            { id: "p_anime_01", name: "Sukuna Domain Expansion Heavyweight Tee", dept: "men", price: 1299, image: "assets/images/anime-front-black.png", stock: 45 },
            { id: "p_anime_02", name: "Gojo Satoru Hollow Purple Boxy Fit Tee", dept: "men", price: 1349, image: "assets/images/movie-vintage-godfather.png", stock: 32 },
            { id: "p_plain_01", name: "Obsidian Essential 240 GSM Oversized Drop Tee", dept: "men", price: 999, image: "assets/images/plain-minimalist-mockup.png", stock: 68 },
            { id: "p_women_01", name: "Structured Minimalist Linen Crop Overshirt", dept: "women", price: 1499, image: "assets/images/womens-apparel.jpg", stock: 24 },
            { id: "p_kids_01", name: "Kids' Anime Graphic 100% Combed Cotton Tee", dept: "kids", price: 799, image: "assets/images/kids-hero-banner.jpg", stock: 50 }
        ];

        function renderAdminTable() {
            const body = document.getElementById('admin-product-table-body');
            if (!body) return;

            let html = '';
            currentProducts.forEach((p, idx) => {
                html += `
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-4 flex items-center gap-3">
                            <img src="${p.image}" class="w-10 h-12 object-cover rounded-lg bg-gray-100 border border-gray-200" />
                            <span class="font-bold text-gray-900">${p.name}</span>
                        </td>
                        <td class="py-3 px-4 capitalize text-gray-600">${p.dept}</td>
                        <td class="py-3 px-4 font-bold text-gray-900">₹${Number(p.price).toLocaleString('en-IN')}</td>
                        <td class="py-3 px-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold ${p.stock > 10 ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700'}">
                                ${p.stock} Units
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right space-x-2">
                            <button type="button" onclick="editProduct(${idx})" class="p-1.5 text-gray-500 hover:text-[#7E22CE] rounded-lg hover:bg-gray-100">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </button>
                            <button type="button" onclick="deleteProduct(${idx})" class="p-1.5 text-gray-500 hover:text-red-600 rounded-lg hover:bg-gray-100">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            body.innerHTML = html;
            if (window.lucide) lucide.createIcons();
        }

        function openProductModal(isEdit = false) {
            const modal = document.getElementById('admin-prod-modal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
            if (!isEdit) {
                document.getElementById('modal-title').innerText = 'Add New Streetwear Drop';
                document.getElementById('admin-prod-form').reset();
                document.getElementById('p-form-id').value = '';
                document.getElementById('img-preview-wrap').classList.add('hidden');
            }
        }

        function closeProductModal() {
            const modal = document.getElementById('admin-prod-modal');
            if (modal) {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }
        }

        function previewUploadImage(e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(evt) {
                document.getElementById('img-preview').src = evt.target.result;
                document.getElementById('p-form-img-url').value = evt.target.result;
                document.getElementById('img-preview-wrap').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }

        function handleSaveProduct(e) {
            e.preventDefault();
            const id = document.getElementById('p-form-id').value;
            const name = document.getElementById('p-form-name').value.trim();
            const dept = document.getElementById('p-form-dept').value;
            const price = Number(document.getElementById('p-form-price').value);
            const image = document.getElementById('p-form-img-url').value.trim() || 'assets/images/anime-front-black.png';

            if (id) {
                const idx = currentProducts.findIndex(p => p.id === id);
                if (idx !== -1) {
                    currentProducts[idx] = { ...currentProducts[idx], name, dept, price, image };
                }
            } else {
                currentProducts.unshift({
                    id: 'p_custom_' + Date.now(),
                    name,
                    dept,
                    price,
                    image,
                    stock: 50
                });
            }

            renderAdminTable();
            closeProductModal();
            alert('✓ Product saved successfully to inventory!');
        }

        function editProduct(idx) {
            const p = currentProducts[idx];
            if (!p) return;
            document.getElementById('p-form-id').value = p.id;
            document.getElementById('p-form-name').value = p.name;
            document.getElementById('p-form-dept').value = p.dept;
            document.getElementById('p-form-price').value = p.price;
            document.getElementById('p-form-img-url').value = p.image;
            document.getElementById('img-preview').src = p.image;
            document.getElementById('img-preview-wrap').classList.remove('hidden');
            document.getElementById('modal-title').innerText = 'Edit Product #' + p.id;
            openProductModal(true);
        }

        function deleteProduct(idx) {
            if (confirm('Are you sure you want to delete this product?')) {
                currentProducts.splice(idx, 1);
                renderAdminTable();
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderAdminTable();
            if (window.lucide) lucide.createIcons();
            
            // Sync subscriber count
            try {
                const subs = JSON.parse(localStorage.getItem('ff_newsletter_subscribers') || '[]');
                if (subs.length > 0) {
                    document.getElementById('admin-subscriber-count').innerText = (1284 + subs.length).toLocaleString('en-IN');
                }
            } catch(e){}
        });
    </script>
</body>
</html>
