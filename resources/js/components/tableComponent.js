export function tableComponent() {
    return {
        isLoading: false,
        isInitial: true,
        isDraggable: false,
        selectedItems: [],
        selectAll: false,
        formData: {
            category_id: '',
            user_id: '',
            page: 1,
            limit: 10,
            active: false
        },
        list: [],
        columns: [],
        filters: [],
        dragOverId: null,
        init(){
            this.url = this.$el.getAttribute('data-url');
            this.fetchData();
        },
        async fetchData() {
            this.isLoading = true;
            this.selectedItems = [];
            try {
                const response = await axios.post(this.url, this.formData);
                this.list = Array.isArray(response.data.list.data) ? response.data.list.data : [];
                this.isDraggable = response.data.draggable ?? false;
                this.columns = response.data.columns ?? [];
                this.filters = response.data.filters ?? [];
                this.totalItems = response.data.list.total || 0;
                this.error = null;
            } catch (error) {
                console.error('Error fetching data:', error);
                this.list = [];
                this.totalItems = 0;
                this.error = '<p style="color:red;">Помилка завантаження вмісту</p>';
            } finally {
                this.isLoading = false;
                this.isInitial = false;
            }
        },
        get items() {
            return this.list;
        },
        get columnsCount() {
            return Object.keys(this.columns).length;
        },
        updateSelectAll() {
            this.selectAll = this.items.length > 0 && this.selectedItems.length === this.items.length;
        },
        pageCount() {
            return Math.ceil(this.totalItems / this.formData.limit);
        },
        pages() {
            return Array.from({ length: this.pageCount() }, (_, i) => i + 1);
        },
        update(entity, value) {
            this.formData[entity] = value;
            this.fetchData();
        },
        deleteSelectedItems() {
            this.delete();
        },

        dragStart(event, id) {
            //event.target.classList.add('opacity-50');
            event.dataTransfer.setData('text/plain', id);
        },
        dragOver(event, id) {
            this.dragOverId = id;
        },
        dragEnd(event) {
            event.target.classList.remove('opacity-30');
            this.dragOverId = null;
        },
        drop(event, id) {
            event.preventDefault();
            const draggedId = event.dataTransfer.getData('text/plain');

            if (draggedId !== id) {
                let draggedIndex = this.list.findIndex(item => item.id === parseInt(draggedId));
                let targetIndex = this.items.findIndex(item => item.id === id);

                targetIndex = draggedIndex < targetIndex ? targetIndex - 1 : targetIndex;

                const draggedItem = this.items.splice(draggedIndex, 1)[0];
                this.items.splice(targetIndex, 0, draggedItem);
            }

            this.dragOverId = null;

            this.reorder();
        },
        async reorder() {
            const offset = (this.formData.page - 1) * this.formData.limit;
            const orderData = this.items.map((item, idx) => ({
                id: item.id,
                sort_order: offset + idx + 1
            }));

            try {
                await axios.post(this.url, {reorder: orderData});
                this.error = null;
            } catch (error) {
                console.error('Error fetching data:', error);
                this.error = '<p style="color:red;">Помилка завантаження вмісту</p>';
            }
        },

        async delete() {
            try {
                await axios.post(this.url, {delete: this.selectedItems});
                this.error = null;
            } catch (error) {
                console.error('Error fetching data:', error);
                this.error = '<p style="color:red;">Помилка завантаження вмісту</p>';
            } finally {
                this.fetchData();
            }
        }
    }
}
