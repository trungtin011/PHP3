composer install

cp .env.example .env

php artisan migrate
bash
php artisan make:model TenModel

php artisan make:migration create_tenbang_table
bash
php artisan make:controller Admin/CourseController

php artisan key:generate


php artisan storage:link

php artisan serve



php artisan serve  # Khởi chạy server Laravel nội bộ
php artisan make:model TenModel -m  # Tạo model mới kèm file migration
php artisan make:controller TenController  # Tạo controller mới
php artisan make:migration create_table_name  # Tạo file migration để chỉnh sửa database
php artisan migrate  # Chạy tất cả migration để tạo bảng trong database
php artisan migrate:rollback  # Quay lại migration trước đó (undo)
php artisan db:seed  # Chạy seeder để đổ dữ liệu mẫu vào database
php artisan route:list  # Liệt kê tất cả route của ứng dụng
php artisan cache:clear  # Xóa cache ứng dụng
php artisan config:clear  # Xóa cache cấu hình
php artisan view:clear  # Xóa cache view (blade template)
php artisan key:generate  # Tạo khóa mã hóa APP_KEY trong .env
php artisan storage:link  # Tạo symbolic link để truy cập thư mục storage từ public