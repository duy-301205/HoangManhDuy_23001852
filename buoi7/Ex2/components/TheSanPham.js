export default {
    props: {
        ten: { type: String, required: true },
        gia: { type: Number, required: true },
        anh: { type: String, default: "https://via.placeholder.com/150" },
        moTa: { type: String, default: "Chưa có mô tả" },
        tonKho: { type: Number, required: true }
    },
    data() {
        return {
            showDesc: false
        };
    },
    template: `
        <div style="border: 1px solid #ddd; padding: 15px; margin: 10px; width: 200px; border-radius: 8px;">
            <img :src="anh" alt="Ảnh sản phẩm" style="width: 100%; max-height: 150px; object-fit: cover; border-radius: 4px;">
            <h3>{{ ten }}</h3>
            <p style="color: red; font-weight: bold; margin: 5px 0;">{{ gia.toLocaleString() }} VNĐ</p>
            
            <p v-if="tonKho === 0" style="background: red; color: white; display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 0.9em;">Hết hàng</p>
            <p v-else style="margin: 5px 0;">Tồn kho: {{ tonKho }}</p>

            <button @click="showDesc = !showDesc" style="margin-top: 10px; cursor: pointer;">
                {{ showDesc ? 'Ẩn mô tả' : 'Xem mô tả' }}
            </button>
            <p v-if="showDesc" style="font-style: italic; font-size: 0.9em; margin-top: 10px; color: #555;">{{ moTa }}</p>
            
            <div style="margin-top: 15px;">
                <button :disabled="tonKho === 0" style="width: 100%; padding: 8px; cursor: pointer;">Mua</button>
            </div>
        </div>
    `
};