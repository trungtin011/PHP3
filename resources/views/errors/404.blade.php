@extends('layouts.user')
@section('content')

    <title>Error 404</title>
    <style>
      
        .error-container {
            text-align: center;
            padding: 20px;
        }
        .error-code {
            font-family: 'Montserrat', sans-serif;
            font-size: 10rem; /* Tăng kích thước font */
            font-weight: 700;
            color: #dc3545;
            text-shadow: 4px 4px 10px rgba(220, 53, 69, 0.3); /* Thêm bóng đổ */
            letter-spacing: 5px; /* Giãn chữ */
            animation: fadeIn 1s ease-in-out;
        }
        .error-message {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            font-weight: 400;
            color: #6c757d;
            margin-top: -10px;
            animation: fadeIn 1.5s ease-in-out;
        }
        .btn-home {
            font-family: 'Poppins', sans-serif;
            margin-top: 25px;
            padding: 12px 30px;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 50px; /* Bo tròn nút */
            background: linear-gradient(45deg, #007bff, #00c4ff); /* Gradient cho nút */
            color: white;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        }
        .btn-home:hover {
            background: linear-gradient(45deg, #0056b3, #0099cc);
            transform: translateY(-3px); /* Nâng nút khi hover */
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.5);
        }
        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<section class="py-3 py-md-5 min-vh-100 d-flex justify-content-center align-items-center">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="text-center">
          <h2 class="d-flex justify-content-center align-items-center gap-2 mb-4">
            <span class="display-1 fw-bold">4</span>
            <span class="display-1 fw-bold">0</span>
            <span class="display-1 fw-bold bsb-flip-h">4</span>
          </h2>
          <h3 class="h2 mb-2">Oops! You're lost.</h3>
          <p class="mb-5">The page you are looking for was not found.</p>
          <a class="btn bsb-btn-5xl btn-dark rounded-pill px-5 fs-6 m-0" href="/" role="button">Về Trang Chủ</a>
        </div>
      </div>
    </div>
  </div>
</section>
</html>
@endsection