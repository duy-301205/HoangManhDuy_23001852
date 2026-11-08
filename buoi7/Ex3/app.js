import { createApp } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.js';
import DongGioHang from './components/DongGioHang.js';

const app = createApp({
    data() {
        return {
            cart: [
                { id: 1, name: "Bút", price: 5000, quantity: 2 },
                { id: 2, name: "Quyển vở", price: 12000, quantity: 3 }
            ]
        };
    },
    computed: {
        totalQuantity() {
            return this.cart.reduce((sum, item) => sum + item.quantity, 0);
        },
        totalPrice() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        }
    },
    methods: {
        updateQuantity(id, newQty) {
            if (newQty >= 1 && newQty <= 99 && Number.isInteger(newQty)) {
                const target = this.cart.find(item => item.id === id);
                if (target) {
                    target.quantity = newQty;
                }
            }
        },
        removeItem(id) {
            this.cart = this.cart.filter(item => item.id !== id);
        }
    }
});

app.component('dong-gio-hang', DongGioHang);
app.mount('#app');