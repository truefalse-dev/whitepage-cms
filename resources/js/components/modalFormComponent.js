export function modalFormComponent() {
    return {
        isOpen: false,
        title: '',
        content: '',
        formHtml: '',
        openForm(formUrl) {
            this.formHtml = 'Завантаження...';
            this.title = 'Заголовок Модалки';

            axios.get(formUrl)
                .then(response => {
                    this.formHtml = response.data;

                    Alpine.nextTick(() => {
                        // Тепер елементи форми точно в DOM
                        const form = this.$refs.formContainer.querySelector('form');
                        if (form) {
                            form.addEventListener('submit', (e) => {
                                e.preventDefault();
                                // Логіка сабміта
                                axios.post(form.action, new FormData(form), {
                                    headers: {
                                        "Content-Type": "multipart/form-data",
                                    },
                                })
                                    .then(res => {
                                        console.log('Форма відправлена', res.data);

                                        this.closeForm();
                                    })
                                    .catch(console.error);
                            });
                        }
                    });
                })
                .catch(error => {
                    console.error('Помилка завантаження форми:', error);
                    this.formHtml = '<p class="text-red-600">Не вдалося завантажити форму.</p>';
                })
                .finally(() => {
                    this.loading = false;
                });

            this.isOpen = true;
        },
        closeForm() {

            document.querySelectorAll('.reloadable-component').forEach(el => {
                Alpine.$data(el).load();
            });

            this.isOpen = false;
            this.formHtml = '';
        }
    }
}
