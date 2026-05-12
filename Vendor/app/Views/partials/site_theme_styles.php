        :root {
            --slate-50: #f8fafc;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-300: #cbd5e1;
            --slate-400: #94a3b8;
            --slate-500: #64748b;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-800: #1e293b;
            --slate-900: #0f172a;
            --blue-400: #60a5fa;
            --blue-600: #2563eb;
            --blue-800: #1e40af;
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        #home, #about, #services, #technology, #products, #values, #contact {
            scroll-margin-top: 4.5rem;
        }
        body {
            margin: 0;
            font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 16px;
            line-height: 1.6;
            color: var(--slate-700);
            background: var(--slate-50);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .site-main {
            flex: 1 1 auto;
            width: 100%;
            display: flex;
            flex-direction: column;
        }
        a { color: var(--blue-600); }
        a:hover { text-decoration: underline; }
        .wrap { max-width: 80rem; margin: 0 auto; padding-left: 1rem; padding-right: 1rem; }
        @media (min-width: 640px) { .wrap { padding-left: 1.5rem; padding-right: 1.5rem; } }

        /* Top bar — fixed overlay above full-viewport hero */
        .site-topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            background: var(--slate-900);
            color: var(--slate-300);
            border-bottom: 1px solid rgba(148, 163, 184, 0.12);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.25), 0 4px 6px -4px rgba(0, 0, 0, 0.2);
        }
        .site-topbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem 1.5rem;
            flex-wrap: wrap;
            padding-top: 0.875rem;
            padding-bottom: 0.875rem;
            min-height: 3.25rem;
        }
        .site-topbar-brand {
            font-weight: 700;
            font-size: 1.25rem; /* text-xl — matches SPA nav brand */
            letter-spacing: -0.02em;
            color: #fff;
            text-decoration: none;
        }
        .site-topbar-brand:hover {
            color: var(--slate-200);
            text-decoration: none;
        }
        .site-topbar-nav {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.35rem 1.25rem;
            font-size: 0.875rem; /* text-sm */
            line-height: 1.25rem;
        }
        .site-topbar-nav a {
            color: var(--slate-300);
            text-decoration: none;
            font-weight: 500;
        }
        .site-topbar-nav a:hover {
            color: #fff;
            text-decoration: none;
        }
        .site-topbar-account {
            display: inline-flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.35rem 1.25rem;
            padding-left: 1rem;
            margin-left: 0.35rem;
            border-left: 1px solid rgba(148, 163, 184, 0.25);
        }

        /* Hero — SPA $g(): relative min-h-screen flex items-center justify-center bg-slate-900 */
        .hero {
            min-height: 100vh;
            min-height: 100svh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--slate-900);
            color: #fff;
            text-align: center;
            padding: 3rem 1rem;
            padding-top: max(3rem, calc(env(safe-area-inset-top, 0px) + 5rem));
            padding-bottom: max(3rem, env(safe-area-inset-bottom, 0px));
            box-sizing: border-box;
            overflow: hidden;
        }
        /* max-w-7xl inner + text-5xl / sm:text-6xl / lg:text-7xl (Tailwind-scale titles) */
        .hero-inner {
            max-width: 80rem;
            width: 100%;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
        }
        @media (min-width: 640px) {
            .hero-inner {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
        }
        .hero h1 {
            margin: 0;
            font-size: 3rem; /* text-5xl */
            font-weight: 700;
            letter-spacing: -0.025em; /* tracking-tight */
            line-height: 1;
        }
        @media (min-width: 640px) {
            .hero h1 { font-size: 3.75rem; } /* text-6xl */
        }
        @media (min-width: 1024px) {
            .hero h1 { font-size: 4.5rem; } /* text-7xl */
        }
        .hero .tagline {
            margin: 0 auto;
            max-width: 48rem; /* max-w-3xl */
            font-size: 1.125rem; /* text-lg */
            line-height: 1.75rem;
            color: var(--slate-300);
        }
        @media (min-width: 640px) {
            .hero .tagline { font-size: 1.25rem; line-height: 1.75rem; } /* sm:text-xl */
        }

        /* Values — Qg(): gradient band on white rail */
        .values-band {
            padding: 4rem 0;
            background: #fff;
        }
        .values-card {
            background: linear-gradient(135deg, var(--blue-600), var(--blue-800));
            border-radius: 1rem;
            padding: 2rem 1.5rem;
            text-align: center;
            color: #fff;
            box-sizing: border-box;
        }
        @media (min-width: 768px) {
            .values-card { padding: 3rem; }
        }
        .values-card h2 {
            margin: 0 0 3rem;
            font-size: 1.875rem; /* text-3xl — band title */
            line-height: 2.25rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.025em;
        }
        .values-grid {
            display: grid;
            gap: 2rem;
            grid-template-columns: 1fr;
            max-width: 64rem;
            margin: 0 auto;
        }
        @media (min-width: 768px) {
            .values-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 2rem;
            }
        }
        .values-item .values-emoji {
            font-size: 3rem;
            line-height: 1;
            margin-bottom: 1rem;
        }
        .values-item h3 {
            margin: 0 0 0.75rem;
            font-size: 1.125rem; /* text-lg */
            font-weight: 600;
            line-height: 1.75rem;
            color: #fff;
        }
        .values-item p {
            margin: 0;
            font-size: 1rem; /* text-base — text-blue-100 tone */
            line-height: 1.5rem;
            color: rgba(219, 234, 254, 0.98);
        }

        section.block { padding: 5rem 0; }
        section.block.white { background: #fff; }
        section.block.muted { background: var(--slate-50); }
        section.block.dark {
            background: var(--slate-900);
            color: #fff;
        }
        /* Separate consecutive dark bands (technology → products) */
        #products.block.dark {
            border-top: 1px solid rgba(148, 163, 184, 0.1);
        }
        .sec-head { text-align: center; margin-bottom: 4rem; } /* mb-16 — wf / kf / Sf */
        #products .sec-head { margin-bottom: 2rem; } /* jf header mb-8 */
        .sec-head h2 {
            margin: 0 0 1rem;
            font-size: 1.875rem; /* text-3xl — section titles */
            line-height: 2.25rem;
            font-weight: 700;
            letter-spacing: -0.025em;
            color: var(--slate-900);
        }
        section.block.dark .sec-head h2 { color: #fff; }
        #products .sec-head h2 { margin-bottom: 0.75rem; }
        .sec-head p {
            margin: 0 auto;
            max-width: 42rem; /* max-w-2xl */
            color: var(--slate-600);
            font-size: 1rem; /* text-base */
            line-height: 1.5rem;
        }
        section.block.dark .sec-head p { color: var(--slate-400); }

        /* About — wf() */
        .about-grid {
            display: grid;
            gap: 3rem;
            align-items: start;
            margin-bottom: 3rem;
        }
        @media (min-width: 1024px) {
            .about-grid { grid-template-columns: 1fr 1fr; }
        }
        .about-intro h3 {
            margin: 0 0 1rem;
            font-size: 1.25rem; /* text-xl */
            line-height: 1.75rem;
            font-weight: 600;
            color: var(--slate-900);
        }
        .about-intro > p {
            margin: 0;
            font-size: 1rem;
            line-height: 1.625rem;
            color: var(--slate-600);
        }
        .badges {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-top: 1.5rem;
        }
        .badge {
            text-align: center;
            padding: 1rem;
            background: var(--slate-50);
            border-radius: 0.5rem;
            font-weight: 600;
            color: var(--slate-700);
            font-size: 1rem; /* text-base */
            line-height: 1.5rem;
        }
        .badge svg {
            display: block;
            width: 2rem;
            height: 2rem;
            margin: 0 auto 0.5rem;
            color: var(--blue-600);
        }
        .company-stack { display: flex; flex-direction: column; gap: 1rem; }
        .company-card {
            border-left: 4px solid var(--slate-600);
            background: var(--slate-50);
            padding: 1.25rem 1.5rem;
            border-radius: 0 0.75rem 0.75rem 0;
        }
        .company-card.blue { border-left-color: var(--blue-600); }
        .company-card.amber { border-left-color: #f59e0b; }
        .company-card h4 {
            margin: 0 0 0.35rem;
            font-size: 1.125rem; /* text-lg */
            line-height: 1.75rem;
            font-weight: 600;
            color: var(--slate-900);
        }
        .company-card p { margin: 0; color: var(--slate-600); font-size: 0.875rem; line-height: 1.25rem; } /* text-sm */
        .company-card .small { font-size: 0.875rem; color: var(--slate-500); margin-top: 0.35rem; line-height: 1.25rem; }
        .company-card ul { margin: 0.75rem 0 0; padding-left: 1.2rem; color: var(--slate-600); font-size: 0.875rem; line-height: 1.25rem; }
        .divider-h {
            height: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .divider-h span {
            width: 1px;
            height: 3rem;
            background: var(--slate-300);
        }

        /* Services — kf() / Wg */
        .svc-grid {
            display: grid;
            gap: 2rem;
            grid-template-columns: 1fr;
        }
        @media (min-width: 768px) { .svc-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1024px) { .svc-grid { grid-template-columns: repeat(3, 1fr); } }
        .svc-card {
            background: #fff;
            border: 1px solid var(--slate-200);
            border-radius: 0.75rem;
            overflow: hidden;
            transition: transform 0.25s, box-shadow 0.25s;
        }
        .svc-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.12);
        }
        .svc-card-visual {
            position: relative;
            height: 12rem;
            overflow: hidden;
        }
        .svc-card-visual img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .svc-card-visual::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(15, 23, 42, 0.82), transparent 55%);
            pointer-events: none;
        }
        .svc-card-float-icon {
            position: absolute;
            bottom: 1rem;
            left: 1rem;
            z-index: 1;
            color: var(--blue-400);
        }
        .svc-card-float-icon svg {
            width: 2.5rem;
            height: 2.5rem;
            display: block;
            filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.35));
        }
        .svc-card .svc-body { padding: 1.25rem 1.25rem 1.5rem; }
        /* CardTitle: text-2xl font-semibold leading-none tracking-tight */
        .svc-card h3 {
            margin: 0 0 0.5rem;
            font-size: 1.5rem;
            line-height: 1;
            font-weight: 600;
            letter-spacing: -0.025em;
            color: var(--slate-900);
        }
        .svc-card .desc { margin: 0 0 1rem; color: var(--slate-600); font-size: 0.875rem; line-height: 1.25rem; } /* CardDescription text-sm */
        .svc-card ul { margin: 0; padding-left: 1rem; color: var(--slate-700); font-size: 0.875rem; line-height: 1.25rem; }
        .svc-card li { margin-bottom: 0.35rem; }

        /* Products — jf() */
        .prod-grid {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: 1fr;
        }
        @media (min-width: 768px) { .prod-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1100px) { .prod-grid { grid-template-columns: repeat(4, 1fr); } }
        .prod-card {
            display: block;
            border-radius: 0.75rem;
            padding: 1.35rem;
            text-decoration: none;
            color: inherit;
            transition: transform 0.25s, box-shadow 0.25s;
            min-height: 100%;
        }
        .prod-card:hover { transform: scale(1.03); box-shadow: 0 16px 40px rgba(0,0,0,0.35); }
        /* Product titles — icon + label inline (Lucide-style, no tile) */
        .prod-card-head {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }
        .prod-card-head svg {
            width: 1.5rem;
            height: 1.5rem;
            flex-shrink: 0;
            opacity: 0.95;
        }
        .prod-card h3 {
            margin: 0;
            font-size: 1.5rem; /* CardTitle text-2xl */
            line-height: 1;
            font-weight: 600;
            letter-spacing: -0.025em;
            flex: 1;
            min-width: 0;
        }
        .prod-card p { margin: 0 0 0.75rem; font-size: 1rem; line-height: 1.5rem; opacity: 0.95; } /* body text on gradient cards */
        .prod-card .sub { font-size: 0.875rem; line-height: 1.25rem; opacity: 0.8; } /* text-sm */
        .grad-blue { background: linear-gradient(135deg, var(--blue-600), var(--blue-800)); color: #fff; }
        .grad-cyan { background: linear-gradient(135deg, #0891b2, #155e75); color: #fff; }
        .grad-emerald { background: linear-gradient(135deg, #059669, #065f46); color: #fff; }
        .grad-purple { background: linear-gradient(135deg, #9333ea, #6b21a8); color: #fff; }
        .prod-card ul { margin: 0 0 0.5rem; padding-left: 1rem; font-size: 0.875rem; line-height: 1.25rem; opacity: 0.9; }

        /* Tech stack — dark section, slate cards, accent vendor names */
        #technology.tech-stack-section {
            background: var(--slate-900);
            color: #fff;
            border-top: 1px solid rgba(148, 163, 184, 0.1);
        }
        #technology.tech-stack-section .sec-head h2 { color: #fff; }
        #technology.tech-stack-section .sec-head p { color: var(--slate-400); }
        .tech-grid {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: 1fr;
        }
        @media (min-width: 640px) { .tech-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1024px) { .tech-grid { grid-template-columns: repeat(4, 1fr); } }
        #technology .tech-item {
            background: var(--slate-800);
            border: 1px solid var(--slate-700);
            border-radius: 0.75rem;
            padding: 0;
            min-height: 100%;
            transition: transform 0.25s, background 0.25s, box-shadow 0.25s, border-color 0.25s;
        }
        #technology .tech-item:hover {
            transform: scale(1.05);
            background: #263146;
            border-color: #475569;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.35);
        }
        #technology .tech-item a {
            display: block;
            text-decoration: none;
            color: inherit;
            padding: 1.25rem 1.35rem;
            height: 100%;
            border-radius: 0.75rem;
        }
        #technology .tech-item a:focus-visible {
            outline: 2px solid var(--blue-400);
            outline-offset: 2px;
        }
        #technology .tech-item .tech-item-body {
            display: block;
            padding: 1.25rem 1.35rem;
            height: 100%;
            border-radius: 0.75rem;
        }
        .tech-item-top {
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
        }
        .tech-item-top > div:last-child {
            flex: 1;
            min-width: 0;
        }
        .tech-item-icon {
            flex-shrink: 0;
            width: 2.15rem;
            height: 2.15rem;
        }
        .tech-item-icon svg {
            width: 100%;
            height: 100%;
            display: block;
        }
        /* CardTitle row uses text-lg on tech stack */
        #technology .tech-item-top div strong {
            display: block;
            color: #fff;
            font-size: 1.125rem;
            line-height: 1.75rem;
            font-weight: 600;
            letter-spacing: -0.025em;
        }
        #technology .tech-item span.tech-name {
            display: block;
            margin-top: 0.2rem;
            font-size: 1rem;
            line-height: 1.5rem;
            font-weight: 600;
        }
        #technology .tech-item p {
            margin: 0.65rem 0 0;
            font-size: 0.875rem;
            line-height: 1.25rem;
            color: var(--slate-400);
        }

        /* Footer — Ug() — full width band; inner uses wider max than body .wrap */
        footer.site {
            flex-shrink: 0;
            width: 100%;
            background: var(--slate-900);
            color: var(--slate-300);
            padding: 0;
            margin-top: auto;
        }
        footer.site .foot-wide {
            max-width: min(100%, 96rem);
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
            width: 100%;
            box-sizing: border-box;
        }
        @media (min-width: 640px) {
            footer.site .foot-wide {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
        }
        footer.site .foot-grid {
            display: grid;
            gap: 2rem 3rem;
            padding: 4rem 0 3rem;
            grid-template-columns: 1fr;
            align-items: start;
            min-height: min(22rem, 42vh);
        }
        @media (min-width: 768px) {
            footer.site .foot-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }
        footer.site h3 {
            margin: 0 0 1rem;
            color: #fff;
            font-size: 1.125rem; /* text-lg */
            line-height: 1.75rem;
            font-weight: 600;
        }
        footer.site h4 {
            margin: 0 0 1rem;
            color: #fff;
            font-size: 1rem;
            line-height: 1.5rem;
            font-weight: 600;
        }
        footer.site p { margin: 0; font-size: 0.875rem; line-height: 1.25rem; color: var(--slate-400); }
        footer.site .foot-meta { font-size: 0.875rem; color: var(--slate-500); line-height: 1.25rem; }
        footer.site .border-top {
            border-top: 1px solid var(--slate-800);
            padding: 2rem 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 1rem;
            align-items: center;
            font-size: 0.875rem;
            line-height: 1.25rem;
            color: var(--slate-500);
        }

        /* Debug metrics — preserve CI placeholders */
        .debug-env {
            background: var(--slate-950, #020617);
            color: var(--slate-400);
            padding: 1rem;
            text-align: center;
            font-size: 0.75rem; /* text-xs */
            line-height: 1rem;
            font-family: ui-monospace, monospace;
        }

        /* Standalone About page — offset below fixed nav */
        .about-page-main .about-body-first {
            padding-top: max(4rem, calc(env(safe-area-inset-top, 0px) + 4.25rem));
        }
