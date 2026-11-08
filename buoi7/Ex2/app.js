import { createApp } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.js';
import TheSanPham from './components/TheSanPham.js';

const app = createApp({
    data() {
        return {
            dsSanPham: [
    { 
        id: 1, 
        ten: "Tai nghe nhét tai", 
        gia: 100000, 
        anh: "https://tse2.mm.bing.net/th/id/OIP.pBdFZraadpgxcfi23WHLUQHaHY?r=0&pid=Api&h=220&P=0", 
        moTa: "Tai nghe in-ear chống ồn thụ động với dải âm bass trầm ấm. Dây dài 1.2m bọc dù chống rối, tích hợp micro đàm thoại rõ nét. Rất phù hợp cho việc học online, gọi điện và nghe nhạc hàng ngày.", 
        tonKho: 10 
    },
    { 
        id: 2, 
        ten: "Sạc dự phòng", 
        gia: 250000, 
        anh: "https://tse1.mm.bing.net/th/id/OIP.onquSuVwYs_hkhyN2pdEtQHaFd?r=0&pid=Api&h=220&P=0", 
        moTa: "Pin sạc dự phòng dung lượng 10,000mAh, hỗ trợ sạc nhanh 18W. Thiết kế nguyên khối siêu mỏng, vỏ hợp kim nhôm chống xước. Tương thích an toàn với hầu hết các dòng điện thoại thông minh hiện nay.", 
        tonKho: 0 
    },
    { 
        id: 3, 
        ten: "Ốp lưng Silicon", 
        gia: 50000, 
        moTa: "Ốp lưng dẻo trong suốt cao cấp, chống ố vàng sau thời gian dài sử dụng. Cấu tạo viền 4 góc nhô cao tản lực giúp bảo vệ camera và màn hình khi rơi rớt. Cảm giác cầm nắm chắc chắn, không bám mồ hôi.", 
        tonKho: 5 
    },
    { 
        id: 4, 
        ten: "Bàn phím Bluetooth D", 
        gia: 300000, 
        anh: "https://tse1.mm.bing.net/th/id/OIP.xhgPi9XuhQy5JIMsEAQcQwHaHa?r=0&pid=Api&h=220&P=0", 
        moTa: "Bàn phím không dây mini với thiết kế mỏng nhẹ, phím bấm êm ái giảm thiểu tối đa tiếng ồn. Hỗ trợ kết nối mượt mà cùng lúc 3 thiết bị, tương thích hoàn hảo với cả máy tính Windows, macOS và iPad.", 
        tonKho: 2 
    }
]
        };
    }
});

app.component('the-san-pham', TheSanPham);
app.mount('#app');