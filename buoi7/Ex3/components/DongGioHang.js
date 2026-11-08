export default {
    props: ['item'],
    emits: ['doi-so-luong', 'yeu-cau-xoa'],
    template: `
        <div class="cart-item">
            <div style="flex: 2;">
                <strong>{{ item.name }}</strong> <br>
                <small>Đơn giá: {{ item.price.toLocaleString() }}đ</small>
            </div>
            <div style="flex: 1; text-align: center;">
                <button @click="decrease" :disabled="item.quantity <= 1">-</button>
                <span style="display: inline-block; width: 30px;">{{ item.quantity }}</span>
                <button @click="increase" :disabled="item.quantity >= 99">+</button>
            </div>
            <div style="flex: 1; text-align: right; font-weight: bold;">
                {{ (item.price * item.quantity).toLocaleString() }}đ
            </div>
            <div style="flex: 0.5; text-align: right;">
                <button style="color: white; background: red; border: none; border-radius: 3px;" @click="remove">Xóa</button>
            </div>
        </div>
    `,
    methods: {
        increase() {
            this.$emit('doi-so-luong', this.item.id, this.item.quantity + 1);
        },
        decrease() {
            this.$emit('doi-so-luong', this.item.id, this.item.quantity - 1);
        },
        remove() {
            this.$emit('yeu-cau-xoa', this.item.id);
        }
    }
};