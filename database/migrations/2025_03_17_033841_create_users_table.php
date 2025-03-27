<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Khóa chính tự động tăng
            $table->string('name'); // Tên người dùng
            $table->string('email')->unique(); // Email (duy nhất)
            $table->string('password'); // Mật khẩu
            $table->string('phone')->nullable(); // Số điện thoại (có thể null)
            $table->string('avatar')->nullable(); // Ảnh đại diện   
            $table->enum('role', ['admin', 'user'])->default('user'); // Phân quyền
            $table->string('otp_code')->nullable(); // Mã OTP
            $table->timestamp('otp_expires_at')->nullable(); // Thời gian hết hạn OTP
            $table->timestamp('email_verified_at')->nullable(); // Xác nhận email
            $table->rememberToken(); // Token đăng nhập
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
