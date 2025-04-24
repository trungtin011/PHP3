<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MagicShop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary: #D4AF37;
            --primary-hover: #E6C774;
            --dark-bg: #1A1A1A;
            --dark-secondary: #2D2D2D;
            --text-light: #F3F4F6;
            --shopee-orange: #ee4d2d;
        }

        .bg-dark-gradient {
            background: linear-gradient(120deg, var(--dark-bg) 0%, var(--dark-secondary) 100%);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.3);
        }

        .text-gradient {
            background: linear-gradient(45deg, var(--primary), var(--primary-hover));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-weight: 700;
        }

        .nav-link {
            position: relative;
            padding-bottom: 4px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-link:hover {
            color: var(--primary);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background: var(--primary);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .btn {
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--dark-bg);
            border: none;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
        }

        .btn-outline {
            border: 1px solid var(--primary);
            color: var(--text-light);
            background: transparent;
        }

        .btn-outline:hover {
            background: var(--primary);
            color: var(--dark-bg);
            border-color: var(--primary);
        }

        .search-bar {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 20px;
            padding: 8px 16px;
            width: 350px;
            color: var(--text-light);
            transition: all 0.3s ease;
            margin-right: 70px;
        }

        .search-bar:focus {
            outline: none;
            width: 300px;
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.2);
        }

        .dropdown-toggle {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dropdown-toggle:hover {
            color: var(--primary);
        }

        .dropdown-menu {
            background: var(--dark-secondary);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            padding: 10px;
            min-width: 180px;
            animation: dropdownFade 0.25s ease-out;
            transform-origin: top right;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            color: var(--text-light);
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .dropdown-item:hover {
            background: var(--primary);
            color: var(--dark-bg);
            transform: translateX(4px);
        }

        .dropdown-item i {
            width: 16px;
            text-align: center;
        }

        .location-btn {
            background: rgba(255, 255, 255, 0.1);
            padding: 6px 12px;
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        .location-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Search Results Dropdown */
        #search-results {
            background: var(--dark-secondary);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            animation: dropdownFade 0.25s ease-out;
            max-height: 400px;
            overflow-y: auto;
        }

        #search-results .result-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            color: var(--text-light);
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(212, 175, 55, 0.1);
        }

        #search-results .result-item:hover {
            background: var(--primary);
            color: var(--dark-bg);
        }

        #search-results .result-item img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 8px;
        }

        #search-results .result-item div {
            flex: 1;
        }

        #search-results .result-item .title {
            font-size: 14px;
            font-weight: 500;
        }

        #search-results .result-item .price {
            font-size: 12px;
            color: var(--primary);
        }

        #search-results .no-results {
            padding: 10px 16px;
            color: var(--text-light);
            font-size: 14px;
            text-align: center;
        }

        #search-results::-webkit-scrollbar {
            width: 5px;
        }

        #search-results::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 3px;
        }

        @keyframes dropdownFade {
            from { opacity: 0; transform: scale(0.95) translateY(-10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        /* MagicShop Chat Box Styles */
        #ms-chat-box {
            transform: scale(0);
            transition: all 0.3s ease-in-out;
            transform-origin: bottom right;
        }

        #ms-chat-box.ms-show {
            transform: scale(1);
        }

        .ms-chat-container {
            width: 360px;
            background: var(--dark-secondary);
            border-radius: 20px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        #ms-chat-header {
            background: var(--shopee-orange);
            color: #fff;
            padding: 14px 20px;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #ms-chat-messages {
            height: 450px;
            padding: 16px;
            background: var(--dark-bg);
            overflow-y: auto;
        }

        #ms-chat-messages::-webkit-scrollbar {
            width: 6px;
        }

        #ms-chat-messages::-webkit-scrollbar-thumb {
            background: var(--primary-hover);
            border-radius: 3px;
        }

        .ms-chat-message {
            display: flex;
            margin-bottom: 16px;
            align-items: flex-end;
        }

        .ms-user-message {
            flex-direction: row-reverse;
        }

        .ms-ai-message {
            flex-direction: row;
        }

        .ms-chat-bubble {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 16px;
            font-size: 15px;
            line-height: 1.6;
            animation: ms-slideIn 0.3s ease-out;
        }

        .ms-user-message .ms-chat-bubble {
            background: var(--shopee-orange);
            color: #fff;
            border-bottom-right-radius: 4px;
        }

        .ms-ai-message .ms-chat-bubble {
            background: var(--text-light);
            color: var(--dark-bg);
            border-bottom-left-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        }

        .ms-chat-message img.ms-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            margin: 0 12px;
        }

        .ms-ai-message .ms-product-item {
            display: flex;
            align-items: center;
            margin-top: 12px;
        }

        .ms-ai-message .ms-product-item img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 14px;
        }

        .ms-ai-message .ms-product-item div {
            font-size: 10px;
        }

        #ms-chat-form {
            padding: 14px;
            background: var(--dark-secondary);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        #ms-chat-input {
            border: none;
            border-radius: 24px;
            padding: 12px 16px;
            font-size: 15px;
            flex: 1;
            outline: none;
            background: var(--dark-bg);
            color: var(--text-light);
            transition: background 0.3s;
        }

        #ms-chat-input:focus {
            background: rgba(255, 255, 255, 0.1);
        }

        #ms-chat-form button {
            background: var(--shopee-orange);
            color: #fff;
            padding: 10px;
            border-radius: 50%;
            border: none;
            font-size: 18px;
            cursor: pointer;
            transition: background 0.3s;
        }

        #ms-chat-form button:hover {
            background: #f53d2d;
        }

        #ms-open-chat {
            background: var(--shopee-orange);
            padding: 16px;
            border-radius: 50%;
            box-shadow: 0 6px 16px rgba(238, 77, 45, 0.4);
            transition: all 0.3s ease;
        }

        #ms-open-chat:hover {
            background: #f53d2d;
            transform: scale(1.15);
        }

        @keyframes ms-slideIn {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 640px) {
            .ms-chat-container {
                width: 100%;
                max-width: 320px;
            }
            #ms-chat-messages {
                height: 380px;
            }
            .search-bar {
                width: 150px;
            }
            .search-bar:focus {
                width: 200px;
            }
        }
    </style>
</head>
<body>
<header class="bg-dark-gradient text-white py-4 sticky-top">
    <div class="container mx-auto px-4 flex items-center justify-between">
        <!-- Left Section -->
        <div class="flex items-center space-x-6">
            <a href="/" class="flex items-center space-x-2">
                <i class="fa-solid fa-wand-magic-sparkles text-gradient text-2xl"></i>
                <span class="text-2xl font-bold"><span class="text-gradient">Magic</span>Shop</span>
            </a>
            <div class="location-btn flex items-center space-x-2 text-base">
                <i class="fa-solid fa-map-marker-alt"></i>
                <span>HCM</span>
                <i class="fa-solid fa-chevron-down text-sm"></i>
            </div>
        </div>

        <!-- Right Section -->
        <div class="flex items-center space-x-6">
            <!-- Search Form -->
            <div class="relative flex items-center">
                <form action="#" method="GET" class="flex items-center" id="search-form">
                    <input type="text" name="query" class="search-bar" placeholder="Tìm kiếm sản phẩm..." id="search-input" autocomplete="off">
                    <button type="submit" class="ml-2 text-white"></button>
                </form>
                <!-- Search Results Dropdown -->
                <div id="search-results" class="absolute top-full left-0 w-full bg-dark-secondary border border-primary/20 rounded-lg mt-2 hidden z-50">
                    <div id="search-results-content"></div>
                </div>
            </div>
            <a href="#" class="nav-link text-base hidden lg:block">
                <i class="fa-solid fa-phone-alt mr-1"></i>1800.2097
            </a>
            <a href="/cart" class="nav-link text-base">
                <i class="fa-solid fa-shopping-bag mr-1"></i>Giỏ hàng
            </a>
            <nav class="hidden md:flex space-x-6">
                <a href="/products" class="nav-link text-base">
                    <i class="fa-solid fa-box-open mr-1"></i>Sản phẩm
                </a>
                <a href="/about" class="nav-link text-base">
                    <i class="fa-solid fa-info-circle mr-1"></i>Giới thiệu
                </a>
            </nav>
            @auth
                <div class="relative group">
                    <div class="nav-link text-base dropdown-toggle flex items-center gap-2">
                        @if(Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover">
                        @else
                            <i class="fa-solid fa-user-circle text-2xl"></i>
                        @endif
                        <span>{{ Auth::user()->name }}</span>
                        <i class="fa-solid fa-chevron-down text-sm ml-1"></i>
                    </div>
                    <div class="absolute right-0 hidden group-hover:block dropdown-menu">
                        @if (Auth::user()->role === 'admin')
                            <a href="{{ route('dashboard') }}" class="dropdown-item">
                                <i class="fa-solid fa-tachometer-alt"></i> Trang quản trị
                            </a>
                        @else
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <i class="fa-solid fa-user-gear"></i> Profile
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item w-full text-left">
                                <i class="fa-solid fa-sign-out-alt"></i> Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline text-base">Đăng nhập</a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn btn-primary text-base">Đăng ký</a>
            @endif
            @endauth
        </div>
    </div>

    <!-- Chat Box -->
    <div class="fixed bottom-5 right-5 z-50">
        <div id="ms-chat-box" class="hidden">
            <div class="ms-chat-container">
                <div id="ms-chat-header">
                    <div class="flex items-center space-x-2">
                        <img src="https://media.istockphoto.com/id/1010001882/vector/%C3%B0%C3%B0%C2%B5%C3%B1%C3%B0%C3%B1%C3%B1.jpg?s=612x612&w=0&k=20&c=1jeAr9KSx3sG7SKxUPR_j8WPSZq_NIKL0P-MA4F1xRw=" alt="Lravel Logo" class="w-8 h-8 rounded-full">
                        <span>MagicShop</span>
                    </div>
                    <button id="ms-close-chat" class="text-white hover:text-gray-200 text-xl">×</button>
                </div>
                <div id="ms-chat-messages">
                    @auth
                        @foreach ($chatHistory ?? [] as $chat)
                            <div class="ms-chat-message ms-user-message">
                                <img src="{{ Auth::user()->avatar ?? 'https://via.placeholder.com/32' }}" alt="User Avatar" class="ms-avatar">
                                <div class="ms-chat-bubble">{{ $chat->message }}</div>
                            </div>
                            <div class="ms-chat-message ms-ai-message">
                                <img src="https://media.istockphoto.com/id/1010001882/vector/%C3%B0%C3%B0%C2%B5%C3%B1%C3%B0%C3%B1%C3%B1.jpg?s=612x612&w=0&k=20&c=1jeAr9KSx3sG7SKxUPR_j8WPSZq_NIKL0P-MA4F1xRw=" alt="AI Avatar" class="ms-avatar">
                                <div class="ms-chat-bubble">{{ $chat->reply }}</div>
                            </div>
                        @endforeach
                    @endauth
                </div>
                <form id="ms-chat-form">
                    <input type="text" id="ms-chat-input" placeholder="Nhập tin nhắn..." required>
                    <button type="submit"><i class="fas fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
        <button id="ms-open-chat" class="text-white"><i class="fas fa-comments"></i></button>
    </div>
</header>

<script>
    // Search Functionality
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    const searchResultsContent = document.getElementById('search-results-content');
    let debounceTimeout;

    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimeout);
        const query = searchInput.value.trim();

        if (query.length < 2) {
            searchResults.classList.add('hidden');
            return;
        }

        debounceTimeout = setTimeout(async () => {
            searchResultsContent.innerHTML = '<div class="no-results">Đang tìm kiếm...</div>';
            searchResults.classList.remove('hidden');

            try {
                const response = await fetch(`{{ route('search') }}?query=${encodeURIComponent(query)}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const data = await response.json();
                searchResultsContent.innerHTML = data.html;
                searchResults.classList.remove('hidden');
            } catch (error) {
                console.error('Search error:', error);
                searchResultsContent.innerHTML = '<div class="no-results">Có lỗi xảy ra, vui lòng thử lại.</div>';
                searchResults.classList.remove('hidden');
            }
        }, 300); // Debounce 300ms
    });

    // Ẩn dropdown khi click ra ngoài
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.add('hidden');
        }
    });

    // Ẩn dropdown khi nhấn Enter
    document.getElementById('search-form').addEventListener('submit', (e) => {
        e.preventDefault();
        searchResults.classList.add('hidden');
    });

    // Chat Functionality
    const userAvatar = "{{ Auth::user()->avatar ?? 'https://via.placeholder.com/32' }}";
    const openChatBtn = document.getElementById('ms-open-chat');
    const closeChatBtn = document.getElementById('ms-close-chat');
    const chatBox = document.getElementById('ms-chat-box');
    const messages = document.getElementById('ms-chat-messages');

    openChatBtn.addEventListener('click', async () => {
        chatBox.classList.remove('hidden');
        setTimeout(() => chatBox.classList.add('ms-show'), 10);

        // Load chat history dynamically
        if (!messages.dataset.loaded) {
            try {
                const response = await fetch('{{ route('chat.history') }}', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const data = await response.json();
                messages.innerHTML = ''; // Clear existing messages
                data.history.forEach(chat => {
                    const userDiv = `
                        <div class="ms-chat-message ms-user-message">
                            <img src="${userAvatar}" alt="User Avatar" class="ms-avatar">
                            <div class="ms-chat-bubble">${chat.message}</div>
                        </div>`;
                    const aiDiv = `
                        <div class="ms-chat-message ms-ai-message">
                            <img src="https://media.istockphoto.com/id/1010001882/vector/%C3%B0%C3%B0%C2%B5%C3%B1%C3%B0%C3%B1%C3%B1.jpg?s=612x612&w=0&k=20&c=1jeAr9KSx3sG7SKxUPR_j8WPSZq_NIKL0P-MA4F1xRw=" alt="AI Avatar" class="ms-avatar">
                            <div class="ms-chat-bubble">${chat.reply}</div>
                        </div>`;
                    messages.innerHTML += userDiv + aiDiv;
                });
                messages.dataset.loaded = true; // Mark as loaded
                messages.scrollTop = messages.scrollHeight; // Scroll to the latest message
            } catch (error) {
                console.error('Error loading chat history:', error.message);
            }
        }
    });

    closeChatBtn.addEventListener('click', () => {
        chatBox.classList.remove('ms-show');
        setTimeout(() => chatBox.classList.add('hidden'), 300);
    });

    document.getElementById('ms-chat-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const input = document.getElementById('ms-chat-input');
        const userMessage = input.value.trim();

        if (!userMessage) return;

        // User message
        const userDiv = document.createElement('div');
        userDiv.className = 'ms-chat-message ms-user-message';
        userDiv.innerHTML = `
            <img src="{{ Auth::user()->avatar ?? 'https://via.placeholder.com/32' }}" alt="User Avatar" class="ms-avatar">
            <div class="ms-chat-bubble">${userMessage}</div>
        `;
        messages.appendChild(userDiv);

        input.value = '';
        messages.scrollTop = messages.scrollHeight;

        try {
            const response = await fetch('{{ route('chat.send') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: userMessage })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.json();
            const reply = data.reply || "Xin lỗi, mình không thể trả lời câu hỏi này.";

            // AI message
            const aiDiv = document.createElement('div');
            aiDiv.className = 'ms-chat-message ms-ai-message';
            aiDiv.innerHTML = `
                <img src="https://media.istockphoto.com/id/1010001882/vector/%C3%B0%C3%B0%C2%B5%C3%B1%C3%B0%C3%B1%C3%B1.jpg?s=612x612&w=0&k=20&c=1jeAr9KSx3sG7SKxUPR_j8WPSZq_NIKL0P-MA4F1xRw=" alt="AI Avatar" class="ms-avatar">
                <div class="ms-chat-bubble">${reply}</div>
            `;
            messages.appendChild(aiDiv);
            messages.scrollTop = messages.scrollHeight;
        } catch (error) {
            console.error('Chat error:', error.message);

            const errorDiv = document.createElement('div');
            errorDiv.className = 'ms-chat-message ms-ai-message';
            errorDiv.innerHTML = `
                <img src="https://media.istockphoto.com/id/1010001882/vector/%C3%B0%C3%B0%C2%B5%C3%B1%C3%B0%C3%B1%C3%B1.jpg?s=612x612&w=0&k=20&c=1jeAr9KSx3sG7SKxUPR_j8WPSZq_NIKL0P-MA4F1xRw=" alt="AI Avatar" class="ms-avatar">
                <div class="ms-chat-bubble">Có lỗi xảy ra, vui lòng thử lại.</div>
            `;
            messages.appendChild(errorDiv);
            messages.scrollTop = messages.scrollHeight;
        }
    });
</script>
</body>
</html>