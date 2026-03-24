<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>AquaPink Store</title>
    <style>
        :root {
            --pink-primary: #e6007e;
            --bg-app: #f4f4f7;
            --white: #ffffff;
            --text-dark: #333333;
            --text-muted: #666666;
            --cat-1: #ffe5ec; --cat-2: #e0f2fe; --cat-3: #fef3c7; --cat-4: #dcfce7; --cat-5: #ede9fe;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; -webkit-tap-highlight-color: transparent; }
        body { background-color: var(--bg-app); color: var(--text-dark); padding-bottom: 100px; overflow-x: hidden; }

        /* --- SPLASH SCREEN --- */
        #splash-screen {
            position: fixed; inset: 0; background: var(--white);
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            z-index: 9999; transition: opacity 0.8s ease, visibility 0.8s;
        }
        .splash-logo { font-size: 40px; font-weight: 900; color: var(--pink-primary); animation: pulse 1.5s infinite; }
        @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } }

        /* --- HEADER --- */
        header {
            background: var(--white); padding: calc(env(safe-area-inset-top) + 15px) 15px 10px;
            position: sticky; top: 0; z-index: 100; border-bottom: 1px solid #eee;
        }
        
        /* BUSCADOR OCULTO POR DEFECTO */
        .search-wrapper {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), margin 0.3s;
            opacity: 0;
        }
        .search-wrapper.visible {
            max-height: 60px;
            margin-top: 10px;
            opacity: 1;
        }

        .search-container {
            background: #f0f0f2; border-radius: 12px; display: flex; padding: 12px; align-items: center;
            border: 2px solid transparent;
        }
        .search-container input { border: none; background: transparent; width: 100%; margin-left: 10px; outline: none; font-size: 16px; }

        /* --- CATEGORÍAS --- */
        .categories-container { display: flex; overflow-x: auto; padding: 20px 15px; gap: 18px; scrollbar-width: none; }
        .categories-container::-webkit-scrollbar { display: none; }
        .cat-item { display: flex; flex-direction: column; align-items: center; min-width: 65px; cursor: pointer; transition: 0.3s; }
        .cat-icon { width: 58px; height: 58px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 26px; margin-bottom: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.06); background: var(--white); transition: 0.2s; overflow: hidden; }
        .cat-item.selected .cat-icon { background: var(--pink-primary) !important; color: white; transform: scale(1.1); box-shadow: 0 6px 15px rgba(230,0,126,0.3); }
        .cat-item span { font-size: 11px; font-weight: 700; color: var(--text-muted); }

        /* --- GRID PRODUCTOS --- */
        .main-content { padding: 0 15px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 10px; }
        .product-card { background: var(--white); border-radius: 16px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.03); animation: fadeIn 0.4s ease forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        .img-box { height: 160px; width: 100%; overflow: hidden; background: #eee; }
        .img-box img { width: 100%; height: 100%; object-fit: cover; }
        
        .info { padding: 12px; }
        .price { font-size: 1.1rem; font-weight: 800; }
        .title { font-size: 13px; color: var(--text-muted); margin-top: 4px; height: 34px; overflow: hidden; }

        /* --- NAV INFERIOR --- */
        .nav-bottom {
            position: fixed; bottom: 0; width: 100%; background: var(--white);
            display: flex; justify-content: space-around; padding: 12px 0 30px;
            border-top: 1px solid #eee; z-index: 1000;
        }
        .nav-link { text-align: center; color: #bbb; text-decoration: none; font-size: 12px; font-weight: 700; flex: 1; position: relative; }
        .nav-link.active { color: var(--pink-primary); }
        .badge { position: absolute; top: -8px; right: 28%; background: var(--pink-primary); color: white; font-size: 10px; border-radius: 10px; padding: 2px 7px; border: 2px solid white; }

        /* --- VISTAS --- */
        .full-view { position: fixed; top: 0; right: -100%; width: 100%; height: 100%; background: var(--white); z-index: 2000; transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1); display: flex; flex-direction: column; }
        .full-view.active { right: 0; }
        .view-header { padding: 50px 20px 20px; border-bottom: 1px solid #eee; display: flex; align-items: center; justify-content: space-between; font-size: 18px; font-weight: bold; }
        .btn-primary { background: var(--pink-primary); color: white; border: none; width: 100%; padding: 16px; border-radius: 12px; font-weight: bold; font-size: 16px; }
        
        #notification { position: fixed; bottom: 110px; left: 50%; transform: translateX(-50%); background: rgba(0,0,0,0.85); color: white; padding: 12px 25px; border-radius: 30px; opacity:0; z-index: 5000; pointer-events: none; transition: 0.3s; }
    </style>
</head>
<body onload="hideSplash()">

    <div id="splash-screen"><div class="splash-logo">AquaPink</div></div>
    <div id="notification"></div>

    <header>
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h1 style="color:var(--pink-primary); font-size:24px; font-weight:900;">AquaPink</h1>
            <span style="font-size: 20px;">💖</span>
        </div>
        
        <div class="search-wrapper" id="searchWrapper">
            <div class="search-container">
                <span>🔍</span>
                <input type="text" id="searchInput" placeholder="Filtrar en esta categoría..." oninput="filtrarTodo()">
            </div>
        </div>
    </header>

    <section class="categories-container" id="catBar">
        <div class="cat-item selected" onclick="cambiarCategoria('all', this)"><div class="cat-icon" style="background:var(--cat-1)">✨</div><span>Todo</span></div>
        <div class="cat-item" onclick="cambiarCategoria('Calzado', this)"><div class="cat-icon" style="background:var(--cat-2)">👟</div><span>Calzado</span></div>
        <div class="cat-item" onclick="cambiarCategoria('Relojes', this)"><div class="cat-icon" style="background:var(--cat-4)">⌚</div><span>Relojes</span></div>
        <div class="cat-item" onclick="cambiarCategoria('Bolsos', this)"><div class="cat-icon" style="background:var(--cat-5)">👜</div><span>Bolsos</span></div>
        <div class="cat-item" onclick="cambiarCategoria('Belleza', this)"><div class="cat-icon" style="background:#ffe4d6">💄</div><span>Belleza</span></div>
    </section>

    <main class="main-content">
        <p id="labelResultados" style="font-size:14px; font-weight:bold; color:#999; margin-bottom:10px; text-transform: uppercase;">Para ti</p>
        <div class="grid" id="productGrid"></div>
    </main>

    <div id="checkoutView" class="full-view">
        <div class="view-header">
            <button onclick="closeCheckout()" style="border:none; background:none; font-size:26px;">✕</button>
            <span>Mi Bolsa</span>
            <button onclick="vaciarBolsa()" style="border:none; background:none; color:#ff4d4d; font-weight:bold;">Vaciar</button>
        </div>
        <div style="padding:20px; overflow-y:auto; flex-grow:1;">
            <div id="cartItemsList"></div>
            <div id="checkoutForm" style="display:none; margin-top:25px;">
                <input type="text" id="custName" style="width:100%; padding:14px; border:1px solid #ddd; border-radius:10px; margin-bottom:10px;" placeholder="Tu nombre">
                <input type="text" id="custAddr" style="width:100%; padding:14px; border:1px solid #ddd; border-radius:10px; margin-bottom:10px;" placeholder="Dirección de entrega">
                <select id="payMethod" style="width:100%; padding:14px; border:1px solid #ddd; border-radius:10px;">
                    <option>Efectivo al recibir</option>
                    <option>Transferencia</option>
                </select>
                <div id="checkoutSummary" style="margin-top:20px; padding:15px; background:#f9f9f9; border-radius:10px; font-weight:bold;"></div>
            </div>
        </div>
        <div style="padding:20px;" id="cartFooter"><button class="btn-primary" onclick="sendWhatsApp()">Confirmar Pedido</button></div>
    </div>

    <div id="detailView" class="full-view">
        <div class="view-header"><button onclick="closeDetail()" style="border:none; background:none; font-size:26px;">✕</button>Detalle</div>
        <div id="detailContent" style="padding:20px; overflow-y:auto; flex-grow:1;"></div>
        <div style="padding:20px; border-top:1px solid #eee;"><button class="btn-primary" id="addFromDetail">Añadir a mi bolsa</button></div>
    </div>

    <nav class="nav-bottom">
        <div class="nav-link active" onclick="location.reload()">🏠<br>Inicio</div>
        <div class="nav-link" onclick="openCheckout()">
            <span class="badge" id="cartBadge" style="display:none">0</span>
            🛍️<br>Bolsa
        </div>
    </nav>

    <script>
        const miTelefono = "5219991234567"; 
        let currentCategory = 'all';

        const products = [
            { id: 1, category: 'Calzado', title: 'Tenis Sport Pro', price: 1299, image: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400' },
            { id: 2, category: 'Relojes', title: 'Smart Watch Pink', price: 2100, image: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400' },
            { id: 3, category: 'Bolsos', title: 'Bolso Elegance', price: 950, image: 'https://images.unsplash.com/photo-1584917033904-493bb3c39d8b?w=400' },
            { id: 4, category: 'Belleza', title: 'Perfume Mist', price: 1500, image: 'https://images.unsplash.com/photo-1541643600914-78b084683601?w=400' },
            { id: 5, category: 'Calzado', title: 'Sandalias Sky', price: 450, image: 'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=400' },
            { id: 6, category: 'Relojes', title: 'Classic Gold', price: 3200, image: 'https://images.unsplash.com/photo-1524592091214-8c97afdfc367?w=400' },
            { id: 7, category: 'Bolsos', title: 'Cartera Mini', price: 350, image: 'https://images.unsplash.com/photo-1627123424574-724758594e93?w=400' },
            { id: 8, category: 'Belleza', title: 'Set Maquillaje', price: 890, image: 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=400' },
            { id: 9, category: 'Calzado', title: 'Botas Urban', price: 1850, image: 'https://images.unsplash.com/photo-1542272454315-4c01d7abdf4a?w=400' },
            { id: 10, category: 'Bolsos', title: 'Mochila Travel', price: 1100, image: 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400' }
        ];

        let cart = [];

        function hideSplash() { setTimeout(() => { document.getElementById('splash-screen').style.opacity = '0'; setTimeout(() => document.getElementById('splash-screen').style.visibility = 'hidden', 800); }, 1500); }

        function cambiarCategoria(cat, el) {
            currentCategory = cat;
            
            // Quitar clase seleccionada de todos
            document.querySelectorAll('.cat-item').forEach(i => i.classList.remove('selected'));
            el.classList.add('selected');

            // MOSTRAR/OCULTAR BUSCADOR SEGÚN LA CATEGORÍA
            const searchWrap = document.getElementById('searchWrapper');
            if(cat === 'all') {
                searchWrap.classList.remove('visible');
                document.getElementById('searchInput').value = ""; // Limpiar búsqueda
            } else {
                searchWrap.classList.add('visible');
            }

            filtrarTodo();
        }

        function filtrarTodo() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const label = document.getElementById('labelResultados');

            const filtrados = products.filter(p => {
                const coincideBusqueda = p.title.toLowerCase().includes(query);
                const coincideCategoria = currentCategory === 'all' || p.category === currentCategory;
                return coincideBusqueda && coincideCategoria;
            });

            label.innerText = currentCategory === 'all' ? "Para ti" : `Filtro en ${currentCategory}`;
            renderProducts(filtrados);
        }

        function renderProducts(items) {
            const grid = document.getElementById('productGrid');
            grid.innerHTML = items.map(p => `
                <div class="product-card" onclick="openDetail(${p.id})">
                    <div class="img-box"><img src="${p.image}"></div>
                    <div class="info">
                        <p class="price">$${p.price.toLocaleString()}</p>
                        <p class="title">${p.title}</p>
                    </div>
                </div>
            `).join('');
        }

        function openDetail(id) {
            const p = products.find(prod => prod.id === id);
            document.getElementById('detailContent').innerHTML = `
                <img src="${p.image}" style="width:100%; height:300px; object-fit:cover; border-radius:20px; margin-bottom:15px;">
                <h2 style="font-size:32px; font-weight:900; color:var(--pink-primary);">$${p.price.toLocaleString()}</h2>
                <h3>${p.title}</h3>
                <p style="color:#888; margin-top:10px;">Producto de alta calidad AquaPink.</p>
            `;
            document.getElementById('addFromDetail').onclick = () => { cart.push({...p, cartId: Date.now()}); updateBadge(); closeDetail(); showToast("Añadido!"); };
            document.getElementById('detailView').classList.add('active');
        }

        function closeDetail() { document.getElementById('detailView').classList.remove('active'); }
        function openCheckout() { renderCart(); document.getElementById('checkoutView').classList.add('active'); }
        function closeCheckout() { document.getElementById('checkoutView').classList.remove('active'); }

        function renderCart() {
            const container = document.getElementById('cartItemsList');
            if(!cart.length) {
                container.innerHTML = `<div style="text-align:center; padding:50px; color:#999;">Bolsa vacía</div>`;
                document.getElementById('checkoutForm').style.display = 'none';
                document.getElementById('cartFooter').style.display = 'none';
                return;
            }
            document.getElementById('checkoutForm').style.display = 'block';
            document.getElementById('cartFooter').style.display = 'block';
            container.innerHTML = cart.map(item => `
                <div class="cart-item">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <img src="${item.image}" class="cart-item-img">
                        <div><p style="font-weight:bold; font-size:14px;">${item.title}</p><p style="color:var(--pink-primary);">$${item.price}</p></div>
                    </div>
                    <button onclick="eliminarDelCarrito(${item.cartId})" style="border:none; background:#eee; padding:8px 12px; border-radius:8px;">✕</button>
                </div>
            `).join('');
            const t = cart.reduce((s, i) => s + i.price, 0);
            document.getElementById('checkoutSummary').innerText = `TOTAL: $${t.toLocaleString()}`;
        }

        function eliminarDelCarrito(id) { cart = cart.filter(i => i.cartId !== id); updateBadge(); renderCart(); }
        function vaciarBolsa() { cart = []; updateBadge(); renderCart(); }
        function updateBadge() { const b = document.getElementById('cartBadge'); b.innerText = cart.length; b.style.display = cart.length ? 'block' : 'none'; }
        function showToast(m) { const t = document.getElementById('notification'); t.innerText = m; t.style.opacity = '1'; setTimeout(() => t.style.opacity = '0', 2000); }

        function sendWhatsApp() {
            const n = document.getElementById('custName').value;
            const d = document.getElementById('custAddr').value;
            if(!n || !d) return alert("Datos incompletos");
            const t = cart.reduce((s, i) => s + i.price, 0);
            let msg = `*AQUAPINK ORDER*%0A👤 *Cliente:* ${n}%0A📍 *Dirección:* ${d}%0A📦 *Items:* ${cart.length}%0A*TOTAL: $${t}*`;
            window.open(`https://wa.me/${miTelefono}?text=${msg}`, '_blank');
        }

        renderProducts(products);
    </script>
</body>
</html>