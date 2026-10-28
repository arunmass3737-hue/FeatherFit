// Feather Fits Product Catalog & Department Architecture
// Departments:
// 1. Home (All Fits)
// 2. Men's Fashion (T-Shirts & Anime, Casual & Linen Shirts, Cargos & Bottoms)
// 3. Women's Fashion (T-Shirts & Crop Tops, Relaxed Shirts & Blouses, Blazers & Co-Ords)
// 4. Kids' Fashion (Anime Graphic Tees, Casual Cotton Overshirts, Play Sets & Joggers)

window.FEATHER_DEPARTMENTS = [
  { id: "all", name: "Home", slug: "all", icon: "✨" },
  { id: "men", name: "Men", slug: "men", icon: "👔" },
  { id: "women", name: "Women", slug: "women", icon: "👗" },
  { id: "kids", name: "Kids", slug: "kids", icon: "🧒" }
];

window.FEATHER_DEPARTMENT_BANNERS = {
  all: {
    badge: "FEATHER FITS • CONFIDENCE FITS YOU",
    title: "Confidence Fits You. <span>Express Every Vibe.</span>",
    subtitle: "Elevate your daily rotation with heavyweight combed cotton tees, iconic anime art, cinematic vintage graphics, and modern street tailoring across Men, Women & Kids.",
    image: "assets/images/hero-banner.jpg",
    primaryCta: { label: "Explore Men's Drops", dept: "men" },
    secondaryCta: { label: "Explore Women's Fits", dept: "women" }
  },
  men: {
    badge: "MEN'S APPAREL • STREETWEAR & ESSENTIALS",
    title: "Men's Streetwear <span>& Modern Tailoring.</span>",
    subtitle: "Engineered for pure style and heavy-cotton durability. Browse oversized anime prints, casual linen overshirts, and tailored utility cargos.",
    image: "assets/images/mens-apparel.jpg",
    primaryCta: { label: "Shop Men's T-Shirts", subcat: "men-tshirts" },
    secondaryCta: { label: "Shop Men's Shirts", subcat: "men-shirts" }
  },
  women: {
    badge: "WOMEN'S COLLECTION • CONTEMPORARY ELEGANCE",
    title: "Women's Luxury <span>Street & Relaxed Fits.</span>",
    subtitle: "Chic structured blazers, breezy linen overshirts, cropped organic cotton tees, and matching coordinates tailored to perfection.",
    image: "assets/images/womens-apparel.jpg",
    primaryCta: { label: "Shop Women's Tops", subcat: "women-tshirts" },
    secondaryCta: { label: "Shop Women's Shirts", subcat: "women-shirts" }
  },
  kids: {
    badge: "KIDS' STREETWEAR • PLAYFUL & DURABLE",
    title: "Kids' Anime Drops <span>& Urban Playwear.</span>",
    subtitle: "Ultra-soft 100% hypoallergenic combed cotton tees, authentic anime designs, light breathable overshirts, and comfortable play sets.",
    image: "assets/images/kids-hero-banner.jpg",
    primaryCta: { label: "Shop Kids' Anime Tees", subcat: "kids-tshirts" },
    secondaryCta: { label: "Shop Kids' Sets", subcat: "kids-sets" }
  }
};

window.FEATHER_DEPARTMENT_POSTERS = {
  // Home showcase cards linking to departments
  all: [
    {
      id: "poster-home-men",
      targetDept: "men",
      targetSubcat: "all",
      badge: "Trending Collection",
      title: "Men's Fashion",
      subtitle: "Anime Tees, Linen Overshirts & Utility Cargos",
      image: "assets/images/mens-apparel.jpg",
      actionText: "Shop Men →"
    },
    {
      id: "poster-home-women",
      targetDept: "women",
      targetSubcat: "all",
      badge: "Editor's Choice",
      title: "Women's Fashion",
      subtitle: "Relaxed Shirts, Crop Tops & Chic Blazers",
      image: "assets/images/womens-apparel.jpg",
      actionText: "Shop Women →"
    },
    {
      id: "poster-home-kids",
      targetDept: "kids",
      targetSubcat: "all",
      badge: "New Kids Drop",
      title: "Kids' Streetwear",
      subtitle: "Junior Anime Tees, Overshirts & Play Sets",
      image: "assets/images/kids-hero-banner.jpg",
      actionText: "Shop Kids →"
    }
  ],
  // Men's Category Posters
  men: [
    {
      id: "poster-men-tshirts",
      targetDept: "men",
      targetSubcat: "men-tshirts",
      badge: "240 GSM Combed Cotton",
      title: "T-Shirts & Anime Tees",
      subtitle: "Heavyweight Boxy Fits, Tanjiro Anime & Movie Graphics",
      image: "assets/images/anime-tee-model-front.jpg",
      actionText: "View T-Shirts →"
    },
    {
      id: "poster-men-shirts",
      targetDept: "men",
      targetSubcat: "men-shirts",
      badge: "Linen & Denim",
      title: "Casual & Linen Shirts",
      subtitle: "Relaxed Cuban Collars, Denim Layers & Street Overshirts",
      image: "assets/images/men-shirts-poster.jpg",
      actionText: "View Shirts →"
    },
    {
      id: "poster-men-bottoms",
      targetDept: "men",
      targetSubcat: "men-bottoms",
      badge: "Street Tailoring",
      title: "Cargos & Streetwear Bottoms",
      subtitle: "Multi-Pocket Ripstop Twill & Relaxed Fit Pants",
      image: "assets/images/mens-apparel.jpg",
      actionText: "View Bottoms →"
    }
  ],
  // Women's Category Posters
  women: [
    {
      id: "poster-women-tshirts",
      targetDept: "women",
      targetSubcat: "women-tshirts",
      badge: "Soft Pima Cotton",
      title: "T-Shirts & Crop Tops",
      subtitle: "Flattering Cropped Silhouettes & Minimalist Heavy Tops",
      image: "assets/images/womens-apparel.jpg",
      actionText: "View Tops →"
    },
    {
      id: "poster-women-shirts",
      targetDept: "women",
      targetSubcat: "women-shirts",
      badge: "Pastel & Breathable",
      title: "Oversized Shirts & Blouses",
      subtitle: "Relaxed Poplin, Resort Collars & Soft Cotton Drapes",
      image: "assets/images/women-shirts-poster.jpg",
      actionText: "View Shirts →"
    },
    {
      id: "poster-women-bottoms",
      targetDept: "women",
      targetSubcat: "women-bottoms",
      badge: "Modern Power Fits",
      title: "Blazers & Co-Ord Sets",
      subtitle: "Metropolitan Tailored Blazers & High-Waist Trousers",
      image: "assets/images/womens-apparel.jpg",
      actionText: "View Co-Ords →"
    }
  ],
  // Kids' Category Posters (Professional Fashion Photoshoots)
  kids: [
    {
      id: "poster-kids-tshirts",
      targetDept: "kids",
      targetSubcat: "kids-tshirts",
      badge: "Hypoallergenic 100% Cotton",
      title: "Kids Anime & Graphic Tees",
      subtitle: "Vibrant Manga Art, Cyberpunk & Playful Character Prints",
      image: "assets/images/kids-anime-poster.jpg",
      actionText: "View Anime Tees →"
    },
    {
      id: "poster-kids-shirts",
      targetDept: "kids",
      targetSubcat: "kids-shirts",
      badge: "Smart & Comfy",
      title: "Kids Casual Overshirts",
      subtitle: "Dual-Pocket Linen Cotton Twill & Button-Down Day Shirts",
      image: "assets/images/kids-shirt-poster.jpg",
      actionText: "View Overshirts →"
    },
    {
      id: "poster-kids-sets",
      targetDept: "kids",
      targetSubcat: "kids-sets",
      badge: "Active Comfort",
      title: "Play Sets & Jogger Combos",
      subtitle: "Matching Graphic Tee + Elastic Drawstring Joggers",
      image: "assets/images/kids-hero-banner.jpg",
      actionText: "View Play Sets →"
    }
  ]
};

window.FEATHER_SUBCATEGORIES = {
  all: [
    { id: "all", name: "All Fits" },
    { id: "men-tshirts", name: "Men's T-Shirts" },
    { id: "men-shirts", name: "Men's Shirts" },
    { id: "women-tshirts", name: "Women's Tops" },
    { id: "women-shirts", name: "Women's Shirts" },
    { id: "kids-tshirts", name: "Kids' Anime Tees" },
    { id: "kids-shirts", name: "Kids' Shirts" }
  ],
  men: [
    { id: "all", name: "All Men's Fits" },
    { id: "men-tshirts", name: "T-Shirts & Anime" },
    { id: "men-shirts", name: "Casual & Linen Shirts" },
    { id: "men-bottoms", name: "Cargos & Bottoms" }
  ],
  women: [
    { id: "all", name: "All Women's Fits" },
    { id: "women-tshirts", name: "T-Shirts & Crop Tops" },
    { id: "women-shirts", name: "Oversized Shirts" },
    { id: "women-bottoms", name: "Blazers & Co-Ords" }
  ],
  kids: [
    { id: "all", name: "All Kids' Fits" },
    { id: "kids-tshirts", name: "Anime Graphic Tees" },
    { id: "kids-shirts", name: "Cotton Overshirts" },
    { id: "kids-sets", name: "Play Sets & Joggers" }
  ]
};

window.FEATHER_PRODUCTS = [
  // -------------------------------------------------------------
  // MEN: T-SHIRTS & ANIME TEES
  // -------------------------------------------------------------
  {
    id: "ff-anime-01",
    department: "men",
    category: "men-tshirts",
    categoryName: "Men's Anime Design T-shirt",
    name: "Demon Slayer Tanjiro Oversized Graphic Tee",
    basePrice: 1299,
    originalPrice: 1699,
    rating: 4.9,
    reviewsCount: 142,
    badge: "Bestseller",
    description: "Premium heavy-weight 240 GSM combed cotton. Features front-chest 'Metsu' insignia print and a dynamic full-back Tanjiro Kamado water & flame breathing anime graphic with traditional calligraphy.",
    images: [
      "assets/images/anime-tee-model-front.jpg",
      "assets/images/anime-tee-model-back.jpg",
      "assets/images/anime-tee-back.jpg",
      "assets/images/anime-tee-front.jpg"
    ],
    colors: [
      { name: "Obsidian Black", hex: "#121214" },
      { name: "Neon Cyber Violet", hex: "#8E1658" },
      { name: "Washed Vintage Grey", hex: "#3D3A40" }
    ],
    sizes: ["S", "M", "L", "XL", "XXL"],
    stock: {
      "Obsidian Black-S": 12, "Obsidian Black-M": 24, "Obsidian Black-L": 18, "Obsidian Black-XL": 15, "Obsidian Black-XXL": 5,
      "Neon Cyber Violet-S": 8, "Neon Cyber Violet-M": 14, "Neon Cyber Violet-L": 10, "Neon Cyber Violet-XL": 6, "Neon Cyber Violet-XXL": 0,
      "Washed Vintage Grey-S": 15, "Washed Vintage Grey-M": 20, "Washed Vintage Grey-L": 12, "Washed Vintage Grey-XL": 8, "Washed Vintage Grey-XXL": 4
    }
  },
  {
    id: "ff-anime-02",
    department: "men",
    category: "men-tshirts",
    categoryName: "Men's Anime Design T-shirt",
    name: "Spirit Guardian Kitsune Streetwear Tee",
    basePrice: 1199,
    originalPrice: 1499,
    rating: 4.8,
    reviewsCount: 98,
    badge: "Trending",
    description: "Intricately detailed Japanese Kitsune spirit mask artwork blended with celestial cherry blossom typography across the back.",
    images: [
      "assets/images/anime-tee.jpg"
    ],
    colors: [
      { name: "Mulberry Wine", hex: "#63103D" },
      { name: "Midnight Black", hex: "#1A1A1A" },
      { name: "Eggshell White", hex: "#F3EDE8" }
    ],
    sizes: ["XS", "S", "M", "L", "XL"],
    stock: {
      "Mulberry Wine-S": 10, "Mulberry Wine-M": 15, "Mulberry Wine-L": 8, "Mulberry Wine-XL": 4,
      "Midnight Black-S": 14, "Midnight Black-M": 22, "Midnight Black-L": 16, "Midnight Black-XL": 9,
      "Eggshell White-S": 6, "Eggshell White-M": 11, "Eggshell White-L": 5, "Eggshell White-XL": 2
    }
  },
  {
    id: "ff-movie-01",
    department: "men",
    category: "men-tshirts",
    categoryName: "Men's Vintage Graphic T-shirt",
    name: "Galactic Starship 1982 Vintage Movie Tee",
    basePrice: 1399,
    originalPrice: 1799,
    rating: 5.0,
    reviewsCount: 88,
    badge: "Collector's Pick",
    description: "Authentic mineral-washed vintage look inspired by early 80s sci-fi cinema adventures. Silkscreen distressed print on ultra-soft organic cotton.",
    images: [
      "assets/images/movie-tee.jpg",
      "assets/images/hero-banner.jpg"
    ],
    colors: [
      { name: "Mineral Washed Charcoal", hex: "#323336" },
      { name: "Deep Plum", hex: "#2D0B34" },
      { name: "Desert Sand", hex: "#B8A793" }
    ],
    sizes: ["S", "M", "L", "XL", "XXL"],
    stock: {
      "Mineral Washed Charcoal-S": 10, "Mineral Washed Charcoal-M": 19, "Mineral Washed Charcoal-L": 15, "Mineral Washed Charcoal-XL": 7, "Mineral Washed Charcoal-XXL": 3,
      "Deep Plum-S": 12, "Deep Plum-M": 18, "Deep Plum-L": 14, "Deep Plum-XL": 8, "Deep Plum-XXL": 4,
      "Desert Sand-S": 5, "Desert Sand-M": 9, "Desert Sand-L": 6, "Desert Sand-XL": 3, "Desert Sand-XXL": 0
    }
  },
  {
    id: "ff-movie-02",
    department: "men",
    category: "men-tshirts",
    categoryName: "Men's Vintage Graphic T-shirt",
    name: "Neo Noir Cinema Club Graphic T-Shirt",
    basePrice: 1249,
    originalPrice: 1599,
    rating: 4.7,
    reviewsCount: 64,
    badge: "New",
    description: "Minimalist silhouette framing homage to iconic crime noir cinema with high-density typographic typography along the spine.",
    images: [
      "assets/images/movie-tee.jpg"
    ],
    colors: [
      { name: "Pure Midnight", hex: "#0E0E10" },
      { name: "Heather Slate", hex: "#565860" }
    ],
    sizes: ["XS", "S", "M", "L", "XL"],
    stock: {
      "Pure Midnight-S": 11, "Pure Midnight-M": 20, "Pure Midnight-L": 14, "Pure Midnight-XL": 9,
      "Heather Slate-S": 8, "Heather Slate-M": 14, "Heather Slate-L": 10, "Heather Slate-XL": 5
    }
  },
  {
    id: "ff-plain-01",
    department: "men",
    category: "men-tshirts",
    categoryName: "Men's Minimalist T-shirt",
    name: "Feather Signature Heavyweight Plain Tee",
    basePrice: 899,
    originalPrice: 1199,
    rating: 4.9,
    reviewsCount: 310,
    badge: "Wardrobe Essential",
    description: "The quintessential minimalist T-shirt. Crafted from 100% Peruvian Pima cotton with double-stitched reinforced collar and tailored relaxed drape.",
    images: [
      "assets/images/plain-tee.jpg",
      "assets/images/hero-banner.jpg"
    ],
    colors: [
      { name: "Burgundy Wine", hex: "#681D39" },
      { name: "Deep Plum", hex: "#2D0B34" },
      { name: "Crisp Chalk White", hex: "#FDFDFD" },
      { name: "Midnight Onyx", hex: "#161618" }
    ],
    sizes: ["XS", "S", "M", "L", "XL", "XXL"],
    stock: {
      "Burgundy Wine-S": 25, "Burgundy Wine-M": 40, "Burgundy Wine-L": 35, "Burgundy Wine-XL": 20, "Burgundy Wine-XXL": 12,
      "Deep Plum-S": 18, "Deep Plum-M": 32, "Deep Plum-L": 28, "Deep Plum-XL": 14, "Deep Plum-XXL": 8,
      "Crisp Chalk White-S": 30, "Crisp Chalk White-M": 50, "Crisp Chalk White-L": 45, "Crisp Chalk White-XL": 25, "Crisp Chalk White-XXL": 15,
      "Midnight Onyx-S": 28, "Midnight Onyx-M": 48, "Midnight Onyx-L": 40, "Midnight Onyx-XL": 22, "Midnight Onyx-XXL": 10
    }
  },
  {
    id: "ff-plain-02",
    department: "men",
    category: "men-tshirts",
    categoryName: "Men's Minimalist T-shirt",
    name: "Supima Cotton Minimalist Drop-Shoulder Tee",
    basePrice: 999,
    originalPrice: 1299,
    rating: 4.8,
    reviewsCount: 175,
    badge: "Popular",
    description: "Effortless boxy cut featuring raw seam finishing and pre-shrunk organic jersey fabric engineered for daily luxury comfort.",
    images: [
      "assets/images/plain-tee.jpg"
    ],
    colors: [
      { name: "Soft Coral Rose", hex: "#D62568" },
      { name: "Charcoal Heather", hex: "#3A383E" },
      { name: "Sage Mist", hex: "#7E8D85" }
    ],
    sizes: ["S", "M", "L", "XL"],
    stock: {
      "Soft Coral Rose-S": 12, "Soft Coral Rose-M": 20, "Soft Coral Rose-L": 15, "Soft Coral Rose-XL": 8,
      "Charcoal Heather-S": 16, "Charcoal Heather-M": 28, "Charcoal Heather-L": 20, "Charcoal Heather-XL": 10,
      "Sage Mist-S": 10, "Sage Mist-M": 14, "Sage Mist-L": 9, "Sage Mist-XL": 4
    }
  },

  // -------------------------------------------------------------
  // MEN: CASUAL & LINEN SHIRTS
  // -------------------------------------------------------------
  {
    id: "ff-men-shirt-01",
    department: "men",
    category: "men-shirts",
    categoryName: "Men's Casual Shirt",
    name: "Men's Relaxed Linen Blend Streetwear Overshirt",
    basePrice: 1699,
    originalPrice: 2299,
    rating: 4.9,
    reviewsCount: 92,
    badge: "Hot Poster Pick",
    description: "Tailored from a breathable French flax linen & combed cotton blend. Features a modern cuban collar, tortoiseshell buttons, and double chest utility pockets perfect for layering over graphic tees.",
    images: [
      "assets/images/men-shirts-poster.jpg",
      "assets/images/mens-apparel.jpg"
    ],
    colors: [
      { name: "Natural Flax Linen", hex: "#D8C7B5" },
      { name: "Midnight Navy", hex: "#1B2430" },
      { name: "Olive Moss", hex: "#4E5340" }
    ],
    sizes: ["S", "M", "L", "XL", "XXL"],
    stock: {
      "Natural Flax Linen-S": 10, "Natural Flax Linen-M": 22, "Natural Flax Linen-L": 18, "Natural Flax Linen-XL": 9, "Natural Flax Linen-XXL": 4,
      "Midnight Navy-S": 8, "Midnight Navy-M": 16, "Midnight Navy-L": 12, "Midnight Navy-XL": 6, "Midnight Navy-XXL": 2,
      "Olive Moss-S": 6, "Olive Moss-M": 14, "Olive Moss-L": 10, "Olive Moss-XL": 5, "Olive Moss-XXL": 1
    }
  },
  {
    id: "ff-men-01",
    department: "men",
    category: "men-shirts",
    categoryName: "Men's Casual Shirt",
    name: "Tailored Indigo Denim Overshirt & Tee Set",
    basePrice: 2499,
    originalPrice: 3299,
    rating: 4.9,
    reviewsCount: 119,
    badge: "Trending",
    description: "Rugged yet refined raw indigo denim layer paired seamlessly with our signature graphic plum crewneck for effortlessly elevated streetwear.",
    images: [
      "assets/images/mens-apparel.jpg",
      "assets/images/hero-banner.jpg"
    ],
    colors: [
      { name: "Raw Indigo & Plum", hex: "#1E2A4A" },
      { name: "Washed Black & Wine", hex: "#2B1B26" }
    ],
    sizes: ["S", "M", "L", "XL", "XXL"],
    stock: {
      "Raw Indigo & Plum-S": 8, "Raw Indigo & Plum-M": 18, "Raw Indigo & Plum-L": 14, "Raw Indigo & Plum-XL": 7, "Raw Indigo & Plum-XXL": 3,
      "Washed Black & Wine-S": 6, "Washed Black & Wine-M": 12, "Washed Black & Wine-L": 10, "Washed Black & Wine-XL": 5, "Washed Black & Wine-XXL": 2
    }
  },

  // -------------------------------------------------------------
  // MEN: CARGOS & BOTTOMS
  // -------------------------------------------------------------
  {
    id: "ff-men-02",
    department: "men",
    category: "men-bottoms",
    categoryName: "Men's Bottomwear",
    name: "Urban Explorer Cargo Pants & Fitted Graphic Tee",
    basePrice: 1899,
    originalPrice: 2499,
    rating: 4.7,
    reviewsCount: 84,
    badge: "New Arrival",
    description: "Constructed with ripstop cotton twill and tailored silhouette for all-day comfort, multi-pocket utility, and bold street aesthetics.",
    images: [
      "assets/images/mens-apparel.jpg"
    ],
    colors: [
      { name: "Midnight Plum", hex: "#2D0B34" },
      { name: "Dark Carbon", hex: "#202124" }
    ],
    sizes: ["S", "M", "L", "XL"],
    stock: {
      "Midnight Plum-S": 10, "Midnight Plum-M": 15, "Midnight Plum-L": 12, "Midnight Plum-XL": 6,
      "Dark Carbon-S": 14, "Dark Carbon-M": 20, "Dark Carbon-L": 18, "Dark Carbon-XL": 8
    }
  },

  // -------------------------------------------------------------
  // WOMEN: T-SHIRTS & CROPPED TOPS
  // -------------------------------------------------------------
  {
    id: "ff-women-02",
    department: "women",
    category: "women-tshirts",
    categoryName: "Women's Graphic Top",
    name: "Confidence Cropped Luxe Cotton T-Shirt",
    basePrice: 1099,
    originalPrice: 1499,
    rating: 4.8,
    reviewsCount: 104,
    badge: "Trending",
    description: "Boxy cropped silhouette flattering every shape with subtle embroidered feather insignia on the cuff and luxurious softness.",
    images: [
      "assets/images/womens-apparel.jpg"
    ],
    colors: [
      { name: "Mulberry Wine", hex: "#8E1658" },
      { name: "Pure White", hex: "#FFFFFF" },
      { name: "Charcoal Noir", hex: "#1C1B1F" }
    ],
    sizes: ["XS", "S", "M", "L"],
    stock: {
      "Mulberry Wine-XS": 12, "Mulberry Wine-S": 22, "Mulberry Wine-M": 18, "Mulberry Wine-L": 8,
      "Pure White-XS": 15, "Pure White-S": 30, "Pure White-M": 24, "Pure White-L": 10,
      "Charcoal Noir-XS": 10, "Charcoal Noir-S": 19, "Charcoal Noir-M": 15, "Charcoal Noir-L": 6
    }
  },

  // -------------------------------------------------------------
  // WOMEN: SHIRTS & BLOUSES
  // -------------------------------------------------------------
  {
    id: "ff-women-shirt-01",
    department: "women",
    category: "women-shirts",
    categoryName: "Women's Shirt",
    name: "Women's Relaxed Pastel Poplin Oversized Shirt",
    basePrice: 1599,
    originalPrice: 2199,
    rating: 4.9,
    reviewsCount: 76,
    badge: "Poster Spotlight",
    description: "Feather-light woven cotton poplin shirt with a modern dropped shoulder, mother-of-pearl buttons, and airy breezy silhouette. Effortlessly styled tucked or unbuttoned over a bralette.",
    images: [
      "assets/images/women-shirts-poster.jpg",
      "assets/images/womens-apparel.jpg"
    ],
    colors: [
      { name: "Dusty Rose Pink", hex: "#D49B9E" },
      { name: "Crisp Pure White", hex: "#FFFFFF" },
      { name: "Sky Blue Breeze", hex: "#9EC2E8" }
    ],
    sizes: ["XS", "S", "M", "L", "XL"],
    stock: {
      "Dusty Rose Pink-XS": 8, "Dusty Rose Pink-S": 18, "Dusty Rose Pink-M": 20, "Dusty Rose Pink-L": 10, "Dusty Rose Pink-XL": 4,
      "Crisp Pure White-XS": 12, "Crisp Pure White-S": 25, "Crisp Pure White-M": 28, "Crisp Pure White-L": 14, "Crisp Pure White-XL": 6,
      "Sky Blue Breeze-XS": 6, "Sky Blue Breeze-S": 15, "Sky Blue Breeze-M": 16, "Sky Blue Breeze-L": 8, "Sky Blue Breeze-XL": 3
    }
  },
  {
    id: "ff-women-shirt-02",
    department: "women",
    category: "women-shirts",
    categoryName: "Women's Shirt",
    name: "Women's Vintage Striped Boxy Resort Shirt",
    basePrice: 1449,
    originalPrice: 1999,
    rating: 4.7,
    reviewsCount: 52,
    badge: "Summer Chic",
    description: "Breathable rayon weave featuring vertical vintage resort stripes, relaxed notched collar, and easy vacation drape.",
    images: [
      "assets/images/women-shirts-poster.jpg"
    ],
    colors: [
      { name: "Sand & Plum Stripe", hex: "#BCA37F" },
      { name: "Noir Monochrome", hex: "#222222" }
    ],
    sizes: ["XS", "S", "M", "L"],
    stock: {
      "Sand & Plum Stripe-XS": 7, "Sand & Plum Stripe-S": 14, "Sand & Plum Stripe-M": 16, "Sand & Plum Stripe-L": 8,
      "Noir Monochrome-XS": 9, "Noir Monochrome-S": 15, "Noir Monochrome-M": 18, "Noir Monochrome-L": 7
    }
  },

  // -------------------------------------------------------------
  // WOMEN: BLAZERS & CO-ORDS
  // -------------------------------------------------------------
  {
    id: "ff-women-01",
    department: "women",
    category: "women-bottoms",
    categoryName: "Women's Blazer & Set",
    name: "Metropolitan Fuchsia Blazer & Fitted Modal Tee",
    basePrice: 2799,
    originalPrice: 3599,
    rating: 5.0,
    reviewsCount: 167,
    badge: "Editor's Choice",
    description: "Sharp contemporary tailoring meeting fluid luxury comfort. Deep fuchsia unstructured blazer with matching silk-blend modal inner tee.",
    images: [
      "assets/images/womens-apparel.jpg",
      "assets/images/hero-banner.jpg"
    ],
    colors: [
      { name: "Vivid Magenta", hex: "#D62568" },
      { name: "Deep Royal Plum", hex: "#2D0B34" },
      { name: "Blush Champagne", hex: "#E8C2CA" }
    ],
    sizes: ["XS", "S", "M", "L", "XL"],
    stock: {
      "Vivid Magenta-XS": 9, "Vivid Magenta-S": 18, "Vivid Magenta-M": 22, "Vivid Magenta-L": 12, "Vivid Magenta-XL": 4,
      "Deep Royal Plum-XS": 7, "Deep Royal Plum-S": 14, "Deep Royal Plum-M": 19, "Deep Royal Plum-L": 9, "Deep Royal Plum-XL": 3,
      "Blush Champagne-XS": 5, "Blush Champagne-S": 10, "Blush Champagne-M": 12, "Blush Champagne-L": 6, "Blush Champagne-XL": 2
    }
  },

  // -------------------------------------------------------------
  // KIDS: ANIME GRAPHIC TEES
  // -------------------------------------------------------------
  {
    id: "ff-kids-01",
    department: "kids",
    category: "kids-tshirts",
    categoryName: "Kids' Anime Graphic Tee",
    name: "Kids' Naruto Spirit Anime Streetwear Tee",
    basePrice: 799,
    originalPrice: 1099,
    rating: 4.9,
    reviewsCount: 88,
    badge: "Kids Bestseller",
    description: "100% hypoallergenic organic cotton for young anime fans. Vibrant screen-printed shinobi artwork with skin-safe dyes and tear-away tags for total comfort.",
    images: [
      "assets/images/kids-anime-poster.jpg",
      "assets/images/kids-hero-banner.jpg"
    ],
    colors: [
      { name: "Ninja Black", hex: "#111113" },
      { name: "Fire Orange", hex: "#E05A1C" },
      { name: "Cloud White", hex: "#FAFAFA" }
    ],
    sizes: ["4-5Y", "6-7Y", "8-9Y", "10-11Y", "12-13Y"],
    stock: {
      "Ninja Black-4-5Y": 15, "Ninja Black-6-7Y": 25, "Ninja Black-8-9Y": 20, "Ninja Black-10-11Y": 15, "Ninja Black-12-13Y": 10,
      "Fire Orange-4-5Y": 10, "Fire Orange-6-7Y": 18, "Fire Orange-8-9Y": 15, "Fire Orange-10-11Y": 10, "Fire Orange-12-13Y": 5,
      "Cloud White-4-5Y": 12, "Cloud White-6-7Y": 20, "Cloud White-8-9Y": 18, "Cloud White-10-11Y": 12, "Cloud White-12-13Y": 8
    }
  },

  // -------------------------------------------------------------
  // KIDS: CASUAL COTTON OVERSHIRTS
  // -------------------------------------------------------------
  {
    id: "ff-kids-02",
    department: "kids",
    category: "kids-shirts",
    categoryName: "Kids' Casual Shirt",
    name: "Kids' Urban Explorer Cotton Twill Overshirt",
    basePrice: 1199,
    originalPrice: 1599,
    rating: 4.8,
    reviewsCount: 45,
    badge: "Smart Street",
    description: "Durable and soft brushed cotton twill overshirt with chest flap pockets, sturdy snap buttons, and relaxed layering cut for school or weekend fun.",
    images: [
      "assets/images/kids-shirt-poster.jpg",
      "assets/images/kids-hero-banner.jpg"
    ],
    colors: [
      { name: "Olive Green", hex: "#526046" },
      { name: "Mustard Gold", hex: "#CF9B28" },
      { name: "Navy Blue", hex: "#1D2838" }
    ],
    sizes: ["4-5Y", "6-7Y", "8-9Y", "10-11Y", "12-13Y"],
    stock: {
      "Olive Green-4-5Y": 8, "Olive Green-6-7Y": 15, "Olive Green-8-9Y": 12, "Olive Green-10-11Y": 8, "Olive Green-12-13Y": 4,
      "Mustard Gold-4-5Y": 6, "Mustard Gold-6-7Y": 12, "Mustard Gold-8-9Y": 10, "Mustard Gold-10-11Y": 6, "Mustard Gold-12-13Y": 3,
      "Navy Blue-4-5Y": 10, "Navy Blue-6-7Y": 18, "Navy Blue-8-9Y": 14, "Navy Blue-10-11Y": 9, "Navy Blue-12-13Y": 5
    }
  },
  {
    id: "ff-kids-04",
    department: "kids",
    category: "kids-shirts",
    categoryName: "Kids' Casual Shirt",
    name: "Kids' Tokyo Kanji Button-Down Oxford Shirt",
    basePrice: 899,
    originalPrice: 1299,
    rating: 4.7,
    reviewsCount: 38,
    badge: "New Arrival",
    description: "Crisp breathable oxford weave featuring subtle kanji graphic embroidery on the pocket and soft curved hem.",
    images: [
      "assets/images/kids-shirt-poster.jpg"
    ],
    colors: [
      { name: "Chalk White", hex: "#FFFFFF" },
      { name: "Sky Pastel Blue", hex: "#A8C7EC" }
    ],
    sizes: ["4-5Y", "6-7Y", "8-9Y", "10-11Y"],
    stock: {
      "Chalk White-4-5Y": 10, "Chalk White-6-7Y": 16, "Chalk White-8-9Y": 12, "Chalk White-10-11Y": 7,
      "Sky Pastel Blue-4-5Y": 8, "Sky Pastel Blue-6-7Y": 14, "Sky Pastel Blue-8-9Y": 10, "Sky Pastel Blue-10-11Y": 5
    }
  },

  // -------------------------------------------------------------
  // KIDS: PLAY SETS & JOGGERS
  // -------------------------------------------------------------
  {
    id: "ff-kids-03",
    department: "kids",
    category: "kids-sets",
    categoryName: "Kids' Play Set",
    name: "Kids' Kanji Streetwear Graphic Tee & Jogger Set",
    basePrice: 1399,
    originalPrice: 1899,
    rating: 5.0,
    reviewsCount: 62,
    badge: "2-Piece Set",
    description: "Complete 2-piece streetwear combo. Includes an oversized anime graphic tee plus comfortable fleece-lined drawstring joggers with deep side pockets.",
    images: [
      "assets/images/kids-hero-banner.jpg",
      "assets/images/kids-anime-poster.jpg"
    ],
    colors: [
      { name: "Cyber Plum & Black", hex: "#2D0B34" },
      { name: "Heather Grey & Teal", hex: "#5D6268" }
    ],
    sizes: ["4-5Y", "6-7Y", "8-9Y", "10-11Y", "12-13Y"],
    stock: {
      "Cyber Plum & Black-4-5Y": 10, "Cyber Plum & Black-6-7Y": 20, "Cyber Plum & Black-8-9Y": 18, "Cyber Plum & Black-10-11Y": 12, "Cyber Plum & Black-12-13Y": 6,
      "Heather Grey & Teal-4-5Y": 8, "Heather Grey & Teal-6-7Y": 16, "Heather Grey & Teal-8-9Y": 14, "Heather Grey & Teal-10-11Y": 9, "Heather Grey & Teal-12-13Y": 4
    }
  }
];

// Backward-compatible category list for filters and count
window.FEATHER_CATEGORIES = [
  { id: "all", name: "All Collections", count: window.FEATHER_PRODUCTS.length },
  { id: "men-tshirts", name: "Men's T-Shirts & Anime", count: 6 },
  { id: "men-shirts", name: "Men's Casual Shirts", count: 2 },
  { id: "men-bottoms", name: "Men's Bottoms", count: 1 },
  { id: "women-tshirts", name: "Women's Tops", count: 1 },
  { id: "women-shirts", name: "Women's Shirts", count: 2 },
  { id: "women-bottoms", name: "Women's Blazers & Sets", count: 1 },
  { id: "kids-tshirts", name: "Kids' Anime Tees", count: 1 },
  { id: "kids-shirts", name: "Kids' Overshirts", count: 2 },
  { id: "kids-sets", name: "Kids' Play Sets", count: 1 }
];

window.FEATHER_SIZE_GUIDE = {
  inches: [
    { size: "XS", chest: "34-36", length: "26.5", shoulder: "16.5" },
    { size: "S", chest: "36-38", length: "27.5", shoulder: "17.5" },
    { size: "M", chest: "39-41", length: "28.5", shoulder: "18.5" },
    { size: "L", chest: "42-44", length: "29.5", shoulder: "19.5" },
    { size: "XL", chest: "45-47", length: "30.5", shoulder: "20.5" },
    { size: "XXL", chest: "48-50", length: "31.5", shoulder: "21.5" },
    { size: "4-5Y", chest: "23-24", length: "17.5", shoulder: "11.0" },
    { size: "6-7Y", chest: "25-26", length: "19.0", shoulder: "12.0" },
    { size: "8-9Y", chest: "27-28", length: "21.0", shoulder: "13.0" },
    { size: "10-11Y", chest: "29-30", length: "23.0", shoulder: "14.0" },
    { size: "12-13Y", chest: "31-32", length: "25.0", shoulder: "15.0" }
  ],
  cm: [
    { size: "XS", chest: "86-91", length: "67", shoulder: "42" },
    { size: "S", chest: "91-96", length: "70", shoulder: "44" },
    { size: "M", chest: "99-104", length: "72", shoulder: "47" },
    { size: "L", chest: "107-112", length: "75", shoulder: "49" },
    { size: "XL", chest: "114-119", length: "77", shoulder: "52" },
    { size: "XXL", chest: "122-127", length: "80", shoulder: "54" },
    { size: "4-5Y", chest: "58-61", length: "44", shoulder: "28" },
    { size: "6-7Y", chest: "63-66", length: "48", shoulder: "30" },
    { size: "8-9Y", chest: "68-71", length: "53", shoulder: "33" },
    { size: "10-11Y", chest: "73-76", length: "58", shoulder: "35" },
    { size: "12-13Y", chest: "78-81", length: "63", shoulder: "38" }
  ]
};
