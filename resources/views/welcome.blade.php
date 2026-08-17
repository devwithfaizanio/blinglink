<html lang="en"><head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <style>
        @layer base {
            html, body { margin: 0; padding: 0; }
            body { overscroll-behavior: none; }
            main > :first-child { margin-top: 0 !important; }
            main > :last-child { margin-bottom: 0 !important; }
        }
        ::-webkit-scrollbar { display: none; }

        /* Metallic Gradient for Text */
        .metallic-gold-text {
            background: linear-gradient(
                to bottom,
                #EAD68D 0%,
                #C8B068 25%,
                #A28446 50%,
                #D1B96F 75%,
                #E6D288 100%
            );
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            color: transparent;
        }

        /* Metallic Gradient for Button */
        .metallic-gold-bg {
            background: linear-gradient(
                135deg,
                #EAD68D 0%,
                #C8B068 25%,
                #A28446 50%,
                #D1B96F 75%,
                #E6D288 100%
            );
            box-shadow: 0 4px 15px rgba(162, 132, 70, 0.3);
        }

        .metallic-gold-bg:hover {
            background: linear-gradient(
                135deg,
                #E6D288 0%,
                #D1B96F 25%,
                #C8B068 50%,
                #A28446 75%,
                #C8B068 100%
            );
        }
    </style>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#C8B068", // Champagne Gold
                        "primary-light": "#EAD68D",
                        "primary-dark": "#A28446",
                        "on-surface-variant": "#99907c",
                        "surface": "#0a0a0a", // Deep Obsidian Black
                        "on-surface": "#e5e2e1",
                        "outline-variant": "#4d4635",
                        "background": "#0a0a0a",
                    },
                    borderRadius: {
                        DEFAULT: "0.25rem",
                        lg: "0.5rem",
                        xl: "0.75rem",
                        full: "9999px"
                    },
                    spacing: {
                        "container-max": "1200px",
                        gutter: "32px",
                        "margin-desktop": "64px",
                        unit: "8px",
                        "section-gap": "128px",
                        "margin-mobile": "24px"
                    },
                    fontFamily: {
                        "body-lg": ["Montserrat"],
                        "body-md": ["Montserrat"],
                        "display-lg": ["Playfair Display"],
                        "label-sm": ["Montserrat"],
                        "headline-md": ["Playfair Display"],
                        "display-lg-mobile": ["Playfair Display"],
                        "headline-lg": ["Playfair Display"]
                    },
                    fontSize: {
                        "body-lg": ["18px", { lineHeight: "28px", letterSpacing: "0.01em", fontWeight: "400" }],
                        "body-md": ["16px", { lineHeight: "24px", fontWeight: "400" }],
                        "display-lg": ["64px", { lineHeight: "72px", letterSpacing: "-0.02em", fontWeight: "700" }],
                        "label-sm": ["12px", { lineHeight: "16px", letterSpacing: "0.1em", fontWeight: "600" }],
                        "headline-md": ["24px", { lineHeight: "32px", fontWeight: "600" }],
                        "display-lg-mobile": ["40px", { lineHeight: "48px", letterSpacing: "-0.01em", fontWeight: "700" }],
                        "headline-lg": ["32px", { lineHeight: "40px", fontWeight: "600" }]
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&amp;family=Playfair+Display:wght@100..900&amp;display=swap" rel="stylesheet"/>
</head>
<body class="bg-surface font-body-md text-on-surface">
<header class="fixed top-0 w-full z-50 bg-transparent">
    <div class="h-20 max-w-container-max mx-auto px-margin-mobile lg:px-margin-desktop flex items-center justify-between">
        <div class="flex items-center gap-4">
            <img alt="Blinglink Gold Logo" class="h-8 w-auto object-contain" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCEUriaXEWXQWT6kyulkD7z6u1JqlTV-o3eIml0OKSB_tEUZJo0BV5U1X5f9zSlkoOGCZaYTIetIzFlfm4oG_ghQAWLjE1V4LIpZfwOQYSiyC1faMI1Z6iLhuAB1ojQMuaWd8rXrLDDDoBFYud8ZTadXAQeQzipefHvrRBTLC_Xd3SMhFJB9LO3FU06QqUCdKltGGdhzK-L0BwNFvB-2t-D5bDY_IUQlqVxxT7pRHTFGHL1eqlCCmqzPBpik_3nhktIZN64801VeCU"/>
            <span class="font-display-lg text-[20px] tracking-widest uppercase text-on-surface">Blinglink</span>
        </div>
        <nav class="flex items-center gap-8">
            <a aria-current="page" class="font-label-sm transition-colors tracking-[0.2em] uppercase text-primary border-b border-primary" href="#">Home</a>
            <a aria-current="page" class="font-label-sm transition-colors tracking-[0.2em] uppercase text-primary" href="{{url('privacy-policy')}}">Privacy Policy</a>
            <a aria-current="page" class="font-label-sm transition-colors tracking-[0.2em] uppercase text-primary" href="{{url('support')}}">Support</a>
        </nav>
    </div>
</header>
<main class="w-full pt-20">
    <div class="flex flex-col w-full">
        <!-- Immersive Welcome Screen -->
        <section class="relative w-full h-[calc(100vh-80px)] flex items-center justify-center overflow-hidden bg-surface">
            <!-- Ambient Background Elements -->
            <div class="absolute inset-0 pointer-events-none">
                <!-- Soft Radial Champagne Glow -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-primary/5 rounded-full blur-[120px] opacity-40"></div>
                <!-- Subtle Animated Particles -->
                <svg class="absolute inset-0 w-full h-full opacity-10" id="particle-svg" xmlns="http://www.w3.org/2000/svg">
                    <g id="particles"></g>
                </svg>
            </div>
            <!-- Central Branding Stack -->
            <div class="relative z-10 flex flex-col items-center text-center px-margin-mobile lg:px-margin-desktop">
                <!-- High-End Logo with Refined Reveal -->
                <div class="relative mb-8 group">
                    <div class="absolute inset-0 bg-primary/10 blur-3xl rounded-full scale-75 group-hover:scale-110 transition-transform duration-1000"></div>
                    <img alt="Blinglink Luxury Logo" class="relative w-32 h-32 lg:w-48 lg:h-48 object-contain" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCEUriaXEWXQWT6kyulkD7z6u1JqlTV-o3eIml0OKSB_tEUZJo0BV5U1X5f9zSlkoOGCZaYTIetIzFlfm4oG_ghQAWLjE1V4LIpZfwOQYSiyC1faMI1Z6iLhuAB1ojQMuaWd8rXrLDDDoBFYud8ZTadXAQeQzipefHvrRBTLC_Xd3SMhFJB9LO3FU06QqUCdKltGGdhzK-L0BwNFvB-2t-D5bDY_IUQlqVxxT7pRHTFGHL1eqlCCmqzPBpik_3nhktIZN64801VeCU" style="animation: fadeInScale 1.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;"/>
                </div>
                <!-- Brand Name with Metallic Gradient -->
                <h1 class="font-display-lg text-display-lg-mobile lg:text-display-lg tracking-tight mb-4 opacity-0 metallic-gold-text" style="animation: fadeInUp 1.2s cubic-bezier(0.22, 1, 0.36, 1) 0.4s forwards;">
                    Blinglink
                </h1>
                <!-- Elegant Tagline -->
                <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-[0.4em] mb-12 opacity-0" style="animation: fadeInUp 1.2s cubic-bezier(0.22, 1, 0.36, 1) 0.8s forwards;">
                    The Gold Standard of Connection
                </p>
                <!-- Minimalist Call to Action with Metallic Gradient -->
                <div class="opacity-0" style="animation: fadeInUp 1.2s cubic-bezier(0.22, 1, 0.36, 1) 1.2s forwards;">
                    <button class="metallic-gold-bg group relative px-12 py-4 text-surface font-label-sm text-label-sm uppercase tracking-[0.2em] overflow-hidden transition-all duration-500 hover:pr-16 active:scale-95">
                        <span class="relative z-10">Enter the World</span>
                        <!-- Shimmer Effect -->
                        <div class="absolute inset-0 w-1/2 h-full bg-white/30 skew-x-[-25deg] -translate-x-[150%] group-hover:translate-x-[250%] transition-transform duration-1000 ease-in-out"></div>
                        <!-- Arrow Icon -->
                        <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-all duration-300">
                chevron_right
              </span>
                    </button>
                </div>
            </div>
            <!-- Corner Accents -->
            <div class="absolute bottom-margin-desktop left-margin-desktop flex items-center gap-4 opacity-20">
                <div class="w-12 h-[1px] bg-primary"></div>
                <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Est. MMXXIV</span>
            </div>
            <div class="absolute bottom-margin-desktop right-margin-desktop flex items-center gap-4 opacity-20">
                <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Curated Excellence</span>
                <div class="w-12 h-[1px] bg-primary"></div>
            </div>
        </section>
        <style>
            @keyframes fadeInScale {
                0% { opacity: 0; transform: scale(0.9) translateY(20px); filter: blur(8px); }
                100% { opacity: 1; transform: scale(1) translateY(0); filter: blur(0); }
            }

            @keyframes fadeInUp {
                0% { opacity: 0; transform: translateY(30px); }
                100% { opacity: 1; transform: translateY(0); }
            }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const particleContainer = document.getElementById('particles');
                const particleCount = 20;

                for (let i = 0; i < particleCount; i++) {
                    const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                    const x = Math.random() * 100;
                    const y = Math.random() * 100;
                    const r = Math.random() * 1.2;
                    const duration = 15 + Math.random() * 25;
                    const delay = Math.random() * -25;

                    circle.setAttribute('cx', `${x}%`);
                    circle.setAttribute('cy', `${y}%`);
                    circle.setAttribute('r', r);
                    circle.setAttribute('fill', '#C8B068');
                    circle.setAttribute('opacity', Math.random() * 0.3);

                    const animate = document.createElementNS('http://www.w3.org/2000/svg', 'animate');
                    animate.setAttribute('attributeName', 'cy');
                    animate.setAttribute('from', '110%');
                    animate.setAttribute('to', '-10%');
                    animate.setAttribute('dur', `${duration}s`);
                    animate.setAttribute('begin', `${delay}s`);
                    animate.setAttribute('repeatCount', 'indefinite');

                    circle.appendChild(animate);
                    particleContainer.appendChild(circle);
                }
            });
        </script>
    </div>
</main>
</body></html>
