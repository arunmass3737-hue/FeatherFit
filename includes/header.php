<?php
if (!isset($pageTitle)) {
    $pageTitle = "Feather Fits — Confidence Fits You | Premium Apparel & Graphic T-Shirts";
}
if (!isset($pageDescription)) {
    $pageDescription = "Feather Fits luxury streetwear, pre-shrunk 240 GSM graphic tees, vintage cinema prints, and premium oversized fits.";
}
if (!isset($currentDept)) {
    $currentDept = $_GET['department'] ?? 'all';
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS v3 via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            dark: '#08080c',
                            card: '#111118',
                            surface: '#181824',
                            border: 'rgba(255, 255, 255, 0.08)',
                            borderHover: 'rgba(168, 85, 247, 0.4)',
                            purple: '#a855f7',
                            lightPurple: '#c084fc',
                            deepPurple: '#581c87',
                            gold: '#fbbf24',
                            emerald: '#10b981'
                        }
                    },
                    fontFamily: {
                        cinzel: ['Cinzel', 'serif'],
                        sans: ['Plus Jakarta Sans', 'sans-serif']
                    },
                    boxShadow: {
                        'glow-purple': '0 0 35px -5px rgba(168, 85, 247, 0.35)',
                        'glow-sm': '0 0 15px -3px rgba(168, 85, 247, 0.25)',
                        'glass': '0 8px 32px 0 rgba(0, 0, 0, 0.37)'
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Swiper.js v11 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- AOS (Animate On Scroll) CSS -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />

    <!-- Custom Utility & Swiper Customization -->
    <style>
        body {
            background-color: #08080c;
            color: #f3f4f6;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
        }

        /* Glassmorphism Classes */
        .glass-panel {
            background: rgba(17, 17, 24, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glass-card {
            background: rgba(24, 24, 36, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .glass-card:hover {
            border-color: rgba(168, 85, 247, 0.4);
            transform: translateY(-4px);
            box-shadow: 0 12px 30px -10px rgba(168, 85, 247, 0.25);
        }

        /* Swiper Customizations */
        .swiper-button-next, .swiper-button-prev {
            width: 44px !important;
            height: 44px !important;
            background: rgba(17, 17, 24, 0.85);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            color: #fff !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
            transition: all 0.2s ease;
        }
        .swiper-button-next:hover, .swiper-button-prev:hover {
            background: #a855f7;
            border-color: #a855f7;
            transform: scale(1.08);
        }
        .swiper-button-next::after, .swiper-button-prev::after {
            font-size: 16px !important;
            font-weight: 800;
        }
        .swiper-pagination-bullet {
            background: rgba(255, 255, 255, 0.4) !important;
            opacity: 1 !important;
            width: 8px !important;
            height: 8px !important;
            transition: all 0.3s ease !important;
        }
        .swiper-pagination-bullet-active {
            background: #a855f7 !important;
            width: 26px !important;
            border-radius: 999px !important;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #08080c;
        }
        ::-webkit-scrollbar-thumb {
            background: #2a2a3c;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #a855f7;
        }
    </style>
</head>
<body class="bg-brand-dark text-gray-100 min-h-screen flex flex-col selection:bg-purple-500/30 selection:text-purple-200">
