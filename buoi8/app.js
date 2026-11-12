const { createApp, ref, reactive, onMounted } = Vue;

createApp({
    setup() {
        const API_URL = 'api/products.php';

        const products = ref([]);
        const editingId = ref(null);
        const errorMessage = ref('');
        const successMessage = ref('');
        let successTimer = null;

        const form = reactive({
            name: '',
            price: '',
            stock: ''
        });

        const clearMessages = () => {
            if (successTimer) {
                clearTimeout(successTimer);
                successTimer = null;
            }
            errorMessage.value = '';
            successMessage.value = '';
        };

        const showSuccess = (message) => {
            clearMessages();
            successMessage.value = message;
            successTimer = setTimeout(() => {
                successMessage.value = '';
            }, 3000); 
        };

        const formatPrice = (value) => {
            return Number(value).toLocaleString('vi-VN');
        };

        const fetchProducts = async () => {
            try {
                const response = await fetch(API_URL);
                const result = await response.json();

                if (!result.success) {
                    errorMessage.value = result.message || 'Không thể tải danh sách sản phẩm.';
                    return;
                }

                products.value = Array.isArray(result.data) ? result.data : [];
            } catch (error) {
                errorMessage.value = 'Lỗi kết nối tới máy chủ API.';
            }
        };

        const resetForm = () => {
            editingId.value = null;
            form.name = '';
            form.price = '';
            form.stock = '';
        };

        const saveProduct = async () => {
            clearMessages();

            const payload = {
                name: form.name,
                price: form.price === '' ? null : Number(form.price),
                stock: form.stock === '' ? null : Number(form.stock)
            };

            const isEditing = editingId.value !== null;
            const url = isEditing ? `${API_URL}?id=${editingId.value}` : API_URL;
            const method = isEditing ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (!result.success) {
                    errorMessage.value = result.message || 'Thao tác thất bại.';
                    return;
                }

                showSuccess(result.message || 'Lưu sản phẩm thành công.');
                resetForm();
                await fetchProducts();
            } catch (error) {
                errorMessage.value = 'Không thể gửi yêu cầu tới máy chủ.';
            }
        };

        const startEdit = (product) => {
            clearMessages();
            editingId.value = product.id;
            form.name = product.name;
            form.price = product.price;
            form.stock = product.stock;
        };

        const deleteProduct = async (product) => {
            clearMessages();

            const confirmed = window.confirm(
                `Bạn có chắc chắn muốn xóa sản phẩm "${product.name}" không?`
            );
            if (!confirmed) {
                return;
            }

            try {
                const response = await fetch(`${API_URL}?id=${product.id}`, {
                    method: 'DELETE'
                });
                const result = await response.json();

                if (!result.success) {
                    errorMessage.value = result.message || 'Xóa sản phẩm thất bại.';
                    return;
                }

                if (editingId.value === product.id) {
                    resetForm();
                }

                showSuccess(result.message || 'Xóa sản phẩm thành công.');
                await fetchProducts();
            } catch (error) {
                errorMessage.value = 'Không thể gửi yêu cầu xóa tới máy chủ.';
            }
        };

        onMounted(() => {
            fetchProducts();
        });

        return {
            products,
            form,
            editingId,
            errorMessage,
            successMessage,
            formatPrice,
            saveProduct,
            startEdit,
            deleteProduct,
            resetForm
        };
    }
}).mount('#app');