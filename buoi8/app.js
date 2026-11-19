const { createApp, ref, reactive, onMounted } = Vue;

createApp({
    setup() {
        const API_URL = 'api/products.php';

        const products = ref([]);
        const pagination = ref({ page: 1, limit: 5, total: 0, total_pages: 1 });
        const busy = ref(false);
        const loading = ref(false);
        const fileInput = ref(null);
        const selectedImage = ref(null);
        const imagePreview = ref('');
        const currentImage = ref('');
        const imageError = ref('');
        const pendingUpload = ref(null);
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

        const fetchProducts = async (page = pagination.value.page) => {
            loading.value = true;
            try {
                const response = await fetch(`${API_URL}?page=${page}&limit=5`);
                const result = await response.json();

                if (!result.success) {
                    errorMessage.value = result.message || 'Không thể tải danh sách sản phẩm.';
                    return;
                }

                products.value = result.data;
                pagination.value = result.pagination;
            } catch (error) {
                errorMessage.value = 'Lỗi kết nối tới máy chủ API.';
            } finally {
                loading.value = false;
            }
        };

        const clearImageSelection = (resetInput = true) => {
            if (imagePreview.value) {
                URL.revokeObjectURL(imagePreview.value);
            }
            imagePreview.value = '';
            selectedImage.value = null;
            imageError.value = '';
            if (resetInput && fileInput.value) {
                fileInput.value.value = '';
            }
        };

        const selectImage = (event) => {
            const file = event.target.files[0];
            clearImageSelection(false);
            if (!file) {
                return;
            }

            const extension = file.name.match(/\.(jpe?g|png)$/i)?.[1].toLowerCase();
            const expectedMime = { jpg: 'image/jpeg', jpeg: 'image/jpeg', png: 'image/png' }[extension];
            if (!expectedMime || (file.type && file.type !== expectedMime)) {
                imageError.value = 'Chỉ chấp nhận ảnh JPG, JPEG hoặc PNG.';
                return;
            }
            if (file.size === 0 || file.size > 2 * 1024 * 1024) {
                imageError.value = 'Ảnh phải có dung lượng tối đa 2 MB và không được rỗng.';
                return;
            }

            selectedImage.value = file;
            imagePreview.value = URL.createObjectURL(file);
        };

        const resetForm = () => {
            clearImageSelection();
            currentImage.value = '';
            editingId.value = null;
            form.name = '';
            form.price = '';
            form.stock = '';
        };

        const uploadImage = async () => {
            const upload = pendingUpload.value;
            const body = new FormData();
            body.append('product_id', upload.id);
            body.append('image', upload.file);

            try {
                const response = await fetch('api/upload.php', { method: 'POST', body });
                const result = await response.json();
                if (!response.ok || !result.success) {
                    throw new Error(result.message || 'Không thể tải ảnh lên.');
                }

                pendingUpload.value = null;
                showSuccess(result.message || 'Tải ảnh lên thành công.');
            } catch (error) {
                upload.error = error instanceof SyntaxError
                    ? 'Máy chủ không trả về kết quả upload hợp lệ.'
                    : (error.message || 'Không thể tải ảnh lên.');
            }
        };

        const retryUpload = async () => {
            if (busy.value || loading.value || !pendingUpload.value) {
                return;
            }
            busy.value = true;
            pendingUpload.value.error = '';
            try {
                await uploadImage();
                await fetchProducts();
            } finally {
                busy.value = false;
            }
        };

        const saveProduct = async () => {
            if (busy.value || loading.value || imageError.value || pendingUpload.value) {
                return;
            }
            clearMessages();
            busy.value = true;

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

                if (selectedImage.value) {
                    pendingUpload.value = {
                        id: isEditing ? editingId.value : result.data.id,
                        file: selectedImage.value,
                        error: ''
                    };
                    await uploadImage();
                } else {
                    showSuccess(result.message || 'Lưu sản phẩm thành công.');
                }
                resetForm();
                await fetchProducts(isEditing ? pagination.value.page : 1);
            } catch (error) {
                errorMessage.value = 'Không thể gửi yêu cầu tới máy chủ.';
            } finally {
                busy.value = false;
            }
        };

        const startEdit = (product) => {
            clearMessages();
            clearImageSelection();
            currentImage.value = product.image || '';
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

            busy.value = true;
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

                if (pendingUpload.value?.id === product.id) {
                    pendingUpload.value = null;
                }
                showSuccess(result.message || 'Xóa sản phẩm thành công.');
                await fetchProducts();
            } catch (error) {
                errorMessage.value = 'Không thể gửi yêu cầu xóa tới máy chủ.';
            } finally {
                busy.value = false;
            }
        };

        onMounted(() => {
            fetchProducts();
        });

        return {
            products,
            pagination,
            busy,
            loading,
            fileInput,
            imagePreview,
            currentImage,
            imageError,
            pendingUpload,
            selectImage,
            clearImageSelection,
            retryUpload,
            fetchProducts,
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
