<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        // Sample static data
        $aboutData = [
            'title' => 'Về Chúng Tôi',
            'description' => 'Chúng tôi là một công ty thương mại điện tử hàng đầu, cam kết mang đến những sản phẩm chất lượng cao và dịch vụ tuyệt vời cho khách hàng.',
            'mission' => 'Cung cấp trải nghiệm mua sắm trực tuyến tiện lợi, an toàn và đáng tin cậy.',
            'vision' => 'Trở thành nền tảng thương mại điện tử số 1 tại Việt Nam, kết nối người bán và người mua một cách hiệu quả.',
        ];

        // Sample dynamic data
        $teamMembers = [
            ['name' => 'Nguyễn Văn A', 'role' => 'CEO', 'image' => 'storage/team/ceo.jpg'],
            ['name' => 'Trần Thị B', 'role' => 'CTO', 'image' => 'storage/team/cto.jpg'],
            ['name' => 'Lê Văn C', 'role' => 'Marketing Manager', 'image' => 'storage/team/marketing.jpg'],
        ];

        return view('user.about.about', compact('aboutData', 'teamMembers'));
    }
}
