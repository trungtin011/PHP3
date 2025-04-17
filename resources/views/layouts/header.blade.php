<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MagicShop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
    <style>
        :root {
            --primary: #D4AF37;
            --primary-hover: #E6C774;
            --dark-bg: #1A1A1A;
            --dark-secondary: #2D2D2D;
            --text-light: #F3F4F6;
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
            width: 250px;
            color: var(--text-light);
            transition: all 0.3s ease;
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

        @keyframes dropdownFade {
            from { opacity: 0; transform: scale(0.95) translateY(-10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
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
            <input type="text" class="search-bar" placeholder="Tìm kiếm sản phẩm...">
            <a href="#" class="nav-link text-base hidden lg:block">
                <i class="fa-solid fa-phone-alt mr-1"></i>1800.2097
            </a>
            <a href="#" class="nav-link text-base hidden lg:block">
                <i class="fa-solid fa-store mr-1"></i>Cửa hàng
            </a>
            <a href="#" class="nav-link text-base">
                <i class="fa-solid fa-truck mr-1"></i>Đơn hàng
            </a>
            <a href="/cart" class="nav-link text-base">
                <i class="fa-solid fa-shopping-bag mr-1"></i>Giỏ hàng
            </a>
            <nav class="hidden md:flex space-x-6">
                <a href="/products" class="nav-link text-base">
                    <i class="fa-solid fa-box-open mr-1"></i>Products
                </a>
                <a href="/index" class="nav-link text-base">
                    <i class="fa-solid fa-info-circle mr-1"></i>About
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
    <div id="chat-box" class="hidden">
        <div class="chat-container">
            <div id="chat-header">
                <div class="flex items-center space-x-2">
                    <img src="https://play-lh.googleusercontent.com/Xw_z0F46QdKAvxcOoeiAMYG5yG2etRVdl_Fe77kRcfY558mpZrYOl_wvxk4i1J02gAo=w240-h480-rw" alt="AI Avatar" class="w-8 h-8 rounded-full">
                    <span>Magic Shop</span>
                </div>
                <button id="close-chat" class="text-white hover:text-gray-200 text-xl">×</button>
            </div>
            <div id="chat-messages"></div>
            <form id="chat-form">
                <input type="text" id="chat-input" placeholder="Type a message..." required>
                <button type="submit"><i class="fas fa-paper-plane"></i></button>
            </form>
        </div>
    </div>
    <button id="open-chat" class="text-white"><i class="fas fa-comments"></i></button>
</div>

<!-- Updated Styles -->
<style>
    #chat-box {
        transform: scale(0);
        transition: all 0.3s ease-in-out;
        transform-origin: bottom right;
    }
    #chat-box.show {
        transform: scale(1);
    }
    .chat-container {
        width: 340px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }
    #chat-header {
        background: #ee4d2d;
        color: white;
        padding: 12px 16px;
        font-weight: 500;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    #chat-messages {
        height: 420px;
        padding: 12px;
        background: #e5e5ea;
        overflow-y: auto;
    }
    #chat-messages::-webkit-scrollbar {
        width: 5px;
    }
    #chat-messages::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 3px;
    }
    .chat-message {
        display: flex;
        margin-bottom: 12px;
        align-items: flex-end;
    }
    .user-message {
        flex-direction: row-reverse;
    }
    .ai-message {
        flex-direction: row;
    }
    .chat-bubble {
        max-width: 75%;
        padding: 10px 14px;
        border-radius: 20px;
        font-size: 14px;
        line-height: 1.5;
        animation: slideIn 0.2s ease-out;
    }
    .user-message .chat-bubble {
        background: #ee4d2d;
        color: white;
        border-bottom-right-radius: 4px;
    }
    .ai-message .chat-bubble {
        background: #fff;
        color: #333;
        border-bottom-left-radius: 4px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }
    .chat-message img.avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        margin: 0 10px;
    }
    .ai-message .product-item {
        display: flex;
        align-items: center;
        margin-top: 10px;
    }
    .ai-message .product-item img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 12px;
    }
    .ai-message .product-item div {
        font-size: 14px;
    }
    #chat-form {
        padding: 12px;
        background: #fff;
        border-top: 1px solid #ddd;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    #chat-input {
        border: 1px solid #ddd;
        border-radius: 20px;
        padding: 10px 14px;
        font-size: 14px;
        flex: 1;
        outline: none;
        transition: border-color 0.2s;
    }
    #chat-input:focus {
        border-color: #ee4d2d;
    }
    #chat-form button {
        background: none;
        color: #ee4d2d;
        padding: 8px;
        border: none;
        font-size: 16px;
        cursor: pointer;
    }
    #chat-form button:hover {
        color: #005bb5;
    }
    #open-chat {
        background: #ee4d2d;
        padding: 14px;
        border-radius: 50%;
        box-shadow: 0 4px 12px rgba(0, 132, 255, 0.3);
        transition: all 0.3s ease;
    }
    #open-chat:hover {
        background: #ee4d2d;
        transform: scale(1.1);
    }
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    @media (max-width: 640px) {
        .chat-container {
            width: 100%;
            max-width: 300px;
        }
        #chat-messages {
            height: 350px;
        }
    }
</style>

<script>
    const userAvatar = "{{ Auth::user()->avatar ?? 'https://via.placeholder.com/32' }}";


    const openChatBtn = document.getElementById('open-chat');
    const closeChatBtn = document.getElementById('close-chat');
    const chatBox = document.getElementById('chat-box');

    openChatBtn.addEventListener('click', () => {
        chatBox.classList.remove('hidden');
        setTimeout(() => chatBox.classList.add('show'), 10);
    });

    closeChatBtn.addEventListener('click', () => {
        chatBox.classList.remove('show');
        setTimeout(() => chatBox.classList.add('hidden'), 300);
    });

    document.getElementById('chat-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const input = document.getElementById('chat-input');
        const messages = document.getElementById('chat-messages');
        const userMessage = input.value.trim();

        if (!userMessage) return;

        // User message
        const userDiv = document.createElement('div');
        userDiv.className = 'chat-message user-message';
        userDiv.innerHTML = `
            <img src="${userAvatar}" alt="User Avatar" class="avatar">
            <div class="chat-bubble">${userMessage}</div>
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
                throw new Error(`HTTP error! Status: ${response.status}, Text: ${await response.text()}`);
            }

            const data = await response.json();
            if (!data.reply) {
                throw new Error('No reply in response');
            }

            const reply = data.reply;

            // AI message
            const aiDiv = document.createElement('div');
            aiDiv.className = 'chat-message ai-message';
            const aiBubble = document.createElement('div');
            aiBubble.className = 'chat-bubble';

            if (reply.includes('<div') || reply.includes('<img')) {
                try {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(reply, 'text/html');
                    const items = doc.body.children;

                    if (items[0]?.textContent.trim()) {
                        const introDiv = document.createElement('div');
                        introDiv.textContent = items[0].textContent.trim();
                        aiBubble.appendChild(introDiv);
                    }

                    for (let i = 1; i < items.length; i++) {
                        const item = items[i];
                        const productDiv = document.createElement('div');
                        productDiv.className = 'product-item';

                        const img = item.querySelector('img');
                        const textDiv = item.querySelector('div');

                        if (img) productDiv.appendChild(img);
                        if (textDiv) productDiv.appendChild(textDiv);

                        aiBubble.appendChild(productDiv);
                    }
                } catch (parseError) {
                    console.error('HTML parse error:', parseError, 'Reply:', reply);
                    aiBubble.textContent = 'Không thể hiển thị sản phẩm, vui lòng thử lại.';
                }
            } else {
                aiBubble.textContent = reply;
            }

            aiDiv.innerHTML = `
                <img src="https://play-lh.googleusercontent.com/Xw_z0F46QdKAvxcOoeiAMYG5yG2etRVdl_Fe77kRcfY558mpZrYOl_wvxk4i1J02gAo=w240-h480-rw" alt="AI Avatar" class="avatar">
                <div class="chat-bubble">${aiBubble.innerHTML}</div>
            `;
            messages.appendChild(aiDiv);
            messages.scrollTop = messages.scrollHeight;
        } catch (error) {
            console.error('Chat error:', error.message, 'Response:', error);
            const errorDiv = document.createElement('div');
            errorDiv.className = 'chat-message ai-message';
            errorDiv.innerHTML = `
                <img src="https://play-lh.googleusercontent.com/Xw_z0F46QdKAvxcOoeiAMYG5yG2etRVdl_Fe77kRcfY558mpZrYOl_wvxk4i1J02gAo=w240-h480-rw" alt="AI Avatar" class="avatar">
                <div class="chat-bubble">Có lỗi xảy ra, vui lòng thử lại.</div>
            `;
            messages.appendChild(errorDiv);
            messages.scrollTop = messages.scrollHeight;
        }
    });
</script>
    

</header>
</body>
</html>
