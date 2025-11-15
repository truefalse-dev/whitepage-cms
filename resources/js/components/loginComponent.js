export function loginComponent() {
    return {
        fields: [],
        formData: {},
        errors: {},
        init(){
            this.url = this.$el.getAttribute('data-url');
            this.backwardUrl = this.$el.getAttribute('backward-url');
        },
        async submitForm() {
            try {
                const response = await axios
                    .post(this.url, this.formData)
                    .then(response => {
                        window.location.href = this.backwardUrl;
                    })
                    .catch((err) => {
                        if (err.response && err.response.status === 422) {
                            // const errors = err.response.data.errors;
                            // Object.values(errors).forEach(messages => {
                            //     messages.forEach(msg => alert(msg));
                            // });
                            this.errors = err.response.data.errors;
                        } else {
                            // Інші помилки
                            alert(err.message);
                        }
                    });
                this.error = null;
            } catch (error) {
                console.error('Error fetching data:', error);
                this.error = '<p style="color:red;">Помилка завантаження вмісту</p>';
            }
        },
        showPassword() {
            return {
                showPassword: false,
                toggle() {
                    this.showPassword = !this.showPassword;
                }
            }
        }
    }
}
