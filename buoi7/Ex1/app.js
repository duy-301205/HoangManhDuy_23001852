import { createApp } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.js';

createApp({
    data() {
        return {
            pageStatus: "Đang tải...",
            searchQuery: "",
            searchMessage: "",
            selectedCategory: "",
            sortOrder: "none",
            newProduct: { name: "", price: null, category: "Điện thoại", stock: null },
            products: [
                { id: 1, name: "iPhone 14", price: 20000000, category: "Điện thoại", stock: 10 },
                { id: 2, name: "Samsung Galaxy S23", price: 18000000, category: "Điện thoại", stock: 0 },
                { id: 3, name: "MacBook Pro M2", price: 35000000, category: "Laptop", stock: 5 },
                { id: 4, name: "Dell XPS 15", price: 30000000, category: "Laptop", stock: 2 },
                { id: 5, name: "Chuột Logitech G102", price: 400000, category: "Phụ kiện", stock: 20 },
                { id: 6, name: "Bàn phím cơ Akko", price: 1200000, category: "Phụ kiện", stock: 0 }
            ]
        };
    },
    computed: {
        totalCount() { return this.products.length; },
        displayedCount() { return this.filteredProducts.length; },
        filteredProducts() {
            let result = [...this.products]; 
            if (this.searchQuery) {
                result = result.filter(p => p.name.toLowerCase().includes(this.searchQuery.toLowerCase()));
            }
            if (this.selectedCategory) {
                result = result.filter(p => p.category === this.selectedCategory);
            }
            if (this.sortOrder === "asc") result.sort((a, b) => a.price - b.price);
            else if (this.sortOrder === "desc") result.sort((a, b) => b.price - a.price);
            return result;
        },
        totalValue() {
            return this.filteredProducts.reduce((sum, p) => sum + (p.price * p.stock), 0);
        }
    },
    watch: {
        searchQuery(newVal) {
            this.searchMessage = newVal.length === 1 ? "Hãy nhập thêm ký tự để tìm kiếm chính xác hơn." : "";
        }
    },
    mounted() {
        this.pageStatus = "Sẵn sàng";
    },
    methods: {
        addProduct() {
            if (!this.newProduct.name.trim()) return alert("Tên không được rỗng!");
            if (this.newProduct.price <= 0) return alert("Giá phải lớn hơn 0!");
            if (!Number.isInteger(this.newProduct.stock) || this.newProduct.stock < 0) return alert("Tồn kho phải là số nguyên không âm!");

            const maxId = this.products.length > 0 ? Math.max(...this.products.map(p => p.id)) : 0;
            this.products.push({
                id: maxId + 1,
                name: this.newProduct.name.trim(),
                price: this.newProduct.price,
                category: this.newProduct.category,
                stock: this.newProduct.stock
            });
            this.newProduct = { name: "", price: null, category: "Điện thoại", stock: null };
        },
        deleteProduct(id) {
            if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này không?")) {
                this.products = this.products.filter(p => p.id !== id);
            }
        }
    }
}).mount('#app');