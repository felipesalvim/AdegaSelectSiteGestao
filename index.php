<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adega Select | Vinhos Premium & Blockchain</title>
    <meta name="description" content="Compre vinhos premium com origem garantida por tecnologia Blockchain na Adega Select.">

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="manifest" href="manifest.json">

    <style>
        /* Melhorias rápidas de layout injetadas diretamente para garantir o visual */
        .wine-card {
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .wine-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-bottom: 3px solid var(--gold);
        }

        .wine-info {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .blockchain-badge-front {
            background: #f4f4f4;
            padding: 8px;
            border-radius: 4px;
            border-left: 3px solid var(--gold);
            font-size: 0.8rem;
            margin: 15px 0;
            color: #555;
            word-break: break-all;
        }

        /* O Segredo do Hero Section Premium */
        .hero-section {
            text-align: center;
            padding: 100px 20px;
            background: linear-gradient(rgba(44, 8, 8, 0.85), rgba(44, 8, 8, 0.85)), url('https://images.unsplash.com/photo-1506377247377-2a5b3b417ebb?auto=format&fit=crop&q=80') center/cover no-repeat;
            color: white;
            box-shadow: inset 0 -10px 20px rgba(0, 0, 0, 0.5);
        }

        footer {
            background: var(--wine-dark);
            color: white;
            text-align: center;
            padding: 30px 20px;
            margin-top: 60px;
            font-size: 0.9rem;
        }

        .app-section {
            background: #ffffff;
            padding: 60px 5%;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 50px;
            border-bottom: 1px solid #f3f4f6;
        }

        .app-card {
            background: #f9fafb;
            padding: 30px;
            border-radius: 25px;
            border: 1px solid #e5e7eb;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03);
        }

        .btn-apk {
            background: #000000;
            color: #ffffff;
            padding: 16px 32px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            transition: 0.3s;
            margin-top: 20px;
        }

        .btn-apk:hover {
            background: #333333;
            transform: translateY(-3px);
        }

        .app-text-content {
            max-width: 550px;
        }

        .app-text-content h2 {
            font-size: 2rem;
            color: #2c0808;
            margin-bottom: 15px;
        }

        .app-text-content p {
            color: #4b5563;
            line-height: 1.6;
            font-size: 1.1rem;
        }

        /* Ajuste para Telemóveis */
        @media (max-width: 768px) {
            .app-section {
                flex-direction: column;
                text-align: center;
                gap: 30px;
            }

            .app-text-content h2 {
                font-size: 1.6rem;
            }
        }

        /* Ajustes de Layout do Header */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 5%;
            background: #fff;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        nav {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* Esconde o botão sanduíche no desktop */
        .menu-toggle {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--wine-dark);
        }

        /* Regras exclusivas para Mobile (até 768px) */
        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }

            nav {
                display: none;
                /* Esconde o menu por padrão */
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: #fff;
                padding: 20px 0;
                gap: 15px;
                box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
                border-top: 1px solid #eee;
            }

            /* Classe que o JavaScript vai ativar */
            nav.active {
                display: flex;
            }

            #cart-icon {
                width: fit-content;
                margin: 0 auto;
            }
        }
    </style>
</head>

<body>

    <header>
        <div class="logo">
            <img src="img/adega-select.png" alt="Adega Select" style="height: 50px; width: auto;">
        </div>

        <div class="menu-toggle" id="mobile-menu" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </div>

        <nav id="nav-list">
            <a href="#vitrine" onclick="toggleMenu()">Catálogo</a>
            <a href="#suporte" onclick="toggleMenu()">Atendimento</a>
            <a href="login.php" onclick="toggleMenu()"><i class="fas fa-lock"></i> Restrito</a>
            <a href="checkout.php" id="cart-icon" title="Seu Carrinho" style="background: var(--wine-main); color: white; padding: 8px 15px; border-radius: 20px;">
                <i class="fas fa-shopping-cart"></i> <span id="cart-count">0</span>
            </a>
        </nav>
    </header>

    <main>
        <section class="hero-section">
            <h1 style="font-size: 3.5rem; margin-bottom: 15px; color: var(--gold); text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">A Experiência do Vinho Perfeito</h1>
            <p style="font-size: 1.3rem; margin-bottom: 30px; max-width: 600px; margin-left: auto; margin-right: auto; color: #eee;">
                Safras exclusivas com origem 100% validada por tecnologia Blockchain. Descubra a sua próxima harmonização com nossa Inteligência Artificial.
            </p>
            <a href="#vitrine" class="btn-buy" style="padding: 15px 35px; font-size: 1.1rem;"><i class="fas fa-arrow-down"></i> Explorar Catálogo</a>
        </section>

        <section id="app" class="app-section">
            <div class="app-card">
                <i class="fas fa-qrcode" style="font-size: 6rem; color: #111;"></i>
                <p style="font-size: 0.85rem; font-weight: 700; color: #6b7280; margin-top: 15px;">SCAN PARA BAIXAR</p>
            </div>

            <div class="app-text-content">
                <h2>Leve a Adega Select no seu Bolso</h2>
                <p>Tenha acesso a pré-lançamentos, rastreio em tempo real de suas garrafas via Blockchain e consulte nosso Sommelier IA a qualquer momento.</p>

                <a href="app/adegaselect.apk" class="btn-apk">
                    <i class="fab fa-android" style="font-size: 1.8rem;"></i>
                    <div style="text-align: left; line-height: 1.2;">
                        <small style="display: block; font-size: 0.7rem; opacity: 0.8;">Disponível para Android</small>
                        <span>Download APK Oficial</span>
                    </div>
                </a>
            </div>
        </section>

        <div class="container">
            <section id="vitrine" style="margin-top: 50px;">
                <div style="text-align: center; margin-bottom: 40px;">
                    <h2 style="color: var(--wine-dark); font-size: 2rem;">Nossa Coleção Exclusiva</h2>
                    <div style="width: 60px; height: 3px; background: var(--gold); margin: 10px auto;"></div>

                    <div class="filter-wrapper" style="margin-top: 25px; display: inline-block; text-align: left;">

                        <div class="filter-group" style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px; justify-content: center; flex-wrap: wrap;">
                            <span class="filter-label" style="font-weight: bold; color: var(--wine-dark);"><i class="fas fa-filter"></i> Tipo:</span>
                            <div class="filter-options scroll-mobile" style="display: flex; gap: 10px;">
                                <button class="btn-filter active" data-type="Todos" onclick="aplicarFiltros(this, 'tipo')">Todos</button>
                                <button class="btn-filter" data-type="Tinto" onclick="aplicarFiltros(this, 'tipo')">Tintos</button>
                                <button class="btn-filter" data-type="Branco" onclick="aplicarFiltros(this, 'tipo')">Brancos</button>
                                <button class="btn-filter" data-type="Rosé" onclick="aplicarFiltros(this, 'tipo')">Rosés</button>
                                <button class="btn-filter" data-type="Espumante" onclick="aplicarFiltros(this, 'tipo')">Espumantes</button>
                            </div>
                        </div>

                        <div class="filter-group" style="display: flex; align-items: center; gap: 15px; justify-content: center; flex-wrap: wrap;">
                            <span class="filter-label" style="font-weight: bold; color: var(--wine-dark);"><i class="fas fa-tag"></i> Preço:</span>
                            <div class="filter-options scroll-mobile" style="display: flex; gap: 10px;">
                                <button class="btn-filter active" data-price="Todos" onclick="aplicarFiltros(this, 'preco')">Todos</button>
                                <button class="btn-filter" data-price="0-100" onclick="aplicarFiltros(this, 'preco')">Até R$ 100</button>
                                <button class="btn-filter" data-price="100-200" onclick="aplicarFiltros(this, 'preco')">R$ 100 - 200</button>
                                <button class="btn-filter" data-price="200-9999" onclick="aplicarFiltros(this, 'preco')">Acima de R$ 200</button>
                            </div>
                        </div>

                    </div>
                </div>

                <div id="grid-vinhos" class="wine-grid">
                </div>
            </section>
        </div>
    </main>

    <section id="suporte" style="background: #fdfdfd; padding: 60px 20px; border-top: 1px solid #eee; margin-top: 40px;">
        <div class="container">
            <div style="text-align: center; margin-bottom: 40px;">
                <h2 style="color: var(--wine-dark); font-size: 2rem;">Central de Atendimento</h2>
                <div style="width: 60px; height: 3px; background: var(--gold); margin: 10px auto;"></div>
                <p style="color: #666; font-size: 1.1rem; max-width: 600px; margin: 0 auto;">
                    Como podemos ajudar? Escolha o melhor canal para falar com nossa equipe.
                </p>
            </div>

            <div style="display: flex; gap: 30px; justify-content: center; flex-wrap: wrap;">

                <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: center; flex: 1; min-width: 250px; max-width: 320px; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i class="fab fa-whatsapp" style="font-size: 3rem; color: #25D366; margin-bottom: 15px;"></i>
                    <h3 style="margin-bottom: 10px; color: #333;">WhatsApp</h3>
                    <p style="color: #666; font-size: 0.9rem; margin-bottom: 20px;">Atendimento rápido e direto com nossos especialistas.</p>
                    <a href="https://wa.me/5585998261414?text=Olá, preciso de ajuda com a Adega Select!" target="_blank" style="background: #25D366; color: white; border: none; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold; display: inline-block; transition: 0.2s;"><i class="fas fa-comment"></i> Chamar no Zap</a>
                </div>

                <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: center; flex: 1; min-width: 250px; max-width: 320px; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-robot" style="font-size: 3rem; color: var(--gold); margin-bottom: 15px;"></i>
                    <h3 style="margin-bottom: 10px; color: #333;">Sommelier IA</h3>
                    <p style="color: #666; font-size: 0.9rem; margin-bottom: 20px;">Dúvidas sobre harmonização? Fale com nossa Inteligência.</p>
                    <button onclick="toggleChat()" style="background: var(--wine-main); color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; cursor: pointer; transition: 0.2s;"><i class="fas fa-magic"></i> Abrir Chatbot</button>
                </div>

                <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: center; flex: 1; min-width: 250px; max-width: 320px; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-envelope" style="font-size: 3rem; color: #3b82f6; margin-bottom: 15px;"></i>
                    <h3 style="margin-bottom: 10px; color: #333;">E-mail</h3>
                    <p style="color: #666; font-size: 0.9rem; margin-bottom: 20px;">Para dúvidas de pedidos, fornecedores ou parcerias.</p>
                    <a href="mailto:fel.s.alvim@gmail.com" style="background: #3b82f6; color: white; border: none; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold; display: inline-block; transition: 0.2s;"><i class="fas fa-paper-plane"></i> Enviar E-mail</a>
                </div>

            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <i class="fas fa-wine-glass-alt" style="color: var(--gold); font-size: 2rem; margin-bottom: 15px;"></i>
            <p>&copy; 2026 Adega Select.</p>
            <p style="color: #aaa; font-size: 0.8rem; margin-top: 5px;">Rastreabilidade garantida por criptografia SHA-256.</p>
        </div>
    </footer>

    <template id="wine-card-template">
        <article class="wine-card">
            <img src="https://images.unsplash.com/photo-1584916201218-f4242ceb4809?auto=format&fit=crop&w=400&q=80" alt="Garrafa de Vinho" class="wine-image">

            <div class="wine-info">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <span class="wine-tag js-tipo"></span>
                    <span style="color: #888; font-size: 0.9rem;"><i class="fas fa-calendar-alt"></i> Safra: <strong class="js-safra"></strong></span>
                </div>

                <h3 class="js-nome" style="margin: 0 0 10px 0; color: #333; font-size: 1.3rem;"></h3>

                <p style="font-size: 1.6rem; color: var(--wine-dark); margin: 0; font-weight: bold;">
                    <strong class="js-preco"></strong>
                </p>

                <div class="blockchain-badge-front" title="Hash SHA-256 da Transação">
                    <i class="fas fa-link" style="color: var(--gold);"></i> Blockchain ID: <br>
                    <code class="js-hash" style="font-weight: bold; color: #333;"></code>
                </div>

                <div style="display: flex; gap: 10px; margin-top: auto;">
                    <a href="#" class="btn-buy js-link-rastrear" style="background: transparent; border: 2px solid var(--wine-main); color: var(--wine-main); flex: 1; display: flex; justify-content: center; align-items: center; gap: 5px;"><i class="fas fa-search"></i> Origem</a>
                    <button class="btn-buy js-btn-comprar" style="flex: 1; border: none; font-size: 1rem;"><i class="fas fa-cart-plus"></i> Add</button>
                </div>
            </div>
        </article>
    </template>

    <div id="chat-container" class="chat-minimized">
        <div class="chat-header" onclick="toggleChat()">
            <div class="header-content">
                <i class="fas fa-robot" style="color: var(--gold); margin-right: 8px;"></i>
                <span>Sommelier IA</span>
            </div>
            <i class="fas fa-comment-dots chat-main-icon"></i>
            <i class="fas fa-chevron-down chat-close-icon"></i>
        </div>

        <div id="chat-body">
            <div id="chat-msgs">
            </div>

            <div class="chat-footer">
                <input type="text" id="chat-input" placeholder="Digite aqui..." onkeypress="if(event.key==='Enter') enviarParaIA()">
                <button onclick="enviarParaIA()" id="btn-send"><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>
    </div>

    <script src="js/app.js"></script>

    <script>
        function toggleMenu() {
            const nav = document.getElementById('nav-list');
            nav.classList.toggle('active');
        }
    </script>

    <?php include 'vlibras.php'; ?>

</body>

</html>