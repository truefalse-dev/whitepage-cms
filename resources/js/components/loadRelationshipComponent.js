export function loadRelationshipComponent() {
    return {
        content: '',
        url: null,
        init() {
            this.url = this.$el.getAttribute('data-url');
            if (this.url) this.load();
        },
        load(){
            axios.get(this.url)
                .then(response => {
                    this.content = response.data; // вставляємо отриманий HTML
                })
                .catch(() => {
                    this.content = '<p style="color:red;">Помилка завантаження вмісту</p>';
                });
        },
    }
}
