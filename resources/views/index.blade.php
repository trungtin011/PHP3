<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV - {{ $info['name'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <style>
        body { font-family: 'Roboto', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 p-6 flex justify-center">
    <div class="w-full max-w-5xl bg-white p-8 rounded-lg shadow-lg flex flex-col md:flex-row">
        <!-- Cột trái: Avatar, Thông tin cá nhân, Kỹ năng -->
        <div class="md:w-1/3 pr-6 border-r border-gray-300">
            <div class="flex flex-col items-center md:items-start">
                <img src="{{ $info['avatar'] }}" alt="Profile picture" class="rounded-lg mb-4 w-32 h-32 object-cover shadow-md"/>
                <div class="text-left">
                    <p class="font-bold text-lg">Name: <span class="font-normal">{{ $info['name'] }}</span></p>
                    <p class="font-bold text-lg">Profile: <span class="font-normal">{{ $info['profile'] }}</span></p>
                    <p class="font-bold text-lg">Email: <span class="font-normal">{{ $info['email'] }}</span></p>
                    <p class="font-bold text-lg">Phone: <span class="font-normal">{{ $info['phone'] }}</span></p>
                </div>
            </div>

            <!-- Kỹ năng -->
            <div class="mt-6">
                <h2 class="text-xl font-bold mb-4">Skill</h2>
                @foreach ($info['skills'] as $skill => $percentage)
                    <p class="font-bold">{{ $skill }}</p>
                    <div class="w-full bg-gray-300 rounded-full h-3 mb-2">
                        <!-- <div class="bg-blue-500 h-3 rounded-full" style="width: {{ $percentage }}%"></div> -->
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Cột phải: About Me -->
        <div class="md:w-2/3 pl-6">
            <h2 class="text-2xl font-bold mb-4 border-b-4 border-blue-500 inline-block">About me</h2>

            <!-- Phần 1: Giới thiệu bản thân -->
            <div class="mb-6">
                <h3 class="text-lg font-bold text-blue-600 mb-2">Giới thiệu bản thân</h3>
                <p class="text-gray-700 leading-relaxed">
                    {{ $info['about']['intro'] }}
                </p>
            </div>

            <!-- Phần 2: Kinh nghiệm làm việc -->
            <div class="mb-6">
                <h3 class="text-lg font-bold text-blue-600 mb-2">Kinh nghiệm làm việc</h3>
                <p class="text-gray-700 leading-relaxed">
                    {{ $info['about']['experience'] }}
                </p>
            </div>

            <!-- Phần 3: Mục tiêu nghề nghiệp -->
            <div class="mb-6">
                <h3 class="text-lg font-bold text-blue-600 mb-2">Mục tiêu nghề nghiệp</h3>
                <p class="text-gray-700 leading-relaxed">
                    {{ $info['about']['goal'] }}
                </p>
            </div>
        </div>
    </div>
</body>
</html>
