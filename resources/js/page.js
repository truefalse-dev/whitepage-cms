const form = document.querySelector("form");
if (form) {
    form.addEventListener("submit", (e) => {
        e.preventDefault();
        axios
            .post(form.action, new FormData(form), {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            })
            .then((res) => {

                if (res.data.redirect_url) {
                    window.location.href = res.data.redirect_url;
                } else {
                    form.innerHTML = res.data;
                }

            })
            .catch((err) => {
                if (err.response && err.response.status === 422) {
                    // Обробка помилок валідації
                    const errors = err.response.data.errors;
                    // Наприклад, вивести повідомлення
                    Object.values(errors).forEach(messages => {
                        messages.forEach(msg => alert(msg));
                    });
                } else {
                    // Інші помилки
                    alert(err.message);
                }
            });
    });
}

let params = {};
const tagControl = document.querySelector('input[name="table-control"]');

if (tagControl !== null) {
    params = JSON.parse(tagControl.value);
}

let initTableControls = () => document.querySelectorAll('.table-control').forEach(el => {
    if (el.tagName === 'SELECT') {
        el.addEventListener('change', function() {
            const name = this.name,
                value = this.value;

            params[name] = value;
            params.page = "1";
            document.dispatchEvent(new CustomEvent('tableChanged', {detail: params}));
        });
    }

    if (el.tagName === 'INPUT') {
        el.addEventListener('change', function() {

            const name = this.name,
                value = this.value;

            console.log('name: ' + name, 'value: ' + value);

            params[name] = value;
            params.page = "1";
            document.dispatchEvent(new CustomEvent('tableChanged', {detail: params}));
        });
    }

    if (el.tagName === 'A') {
        el.addEventListener('click', function() {

            let nameAfterData = null;
            for (const attr of el.attributes) {
                if (attr.name.startsWith('data-')) {
                    nameAfterData = attr.name.substring(5);
                }
            }

            const name = nameAfterData,
                value = el.dataset[nameAfterData];

            params[name] = value;
            document.dispatchEvent(new CustomEvent('tableChanged', {detail: params}));
        });
    }
});

document.addEventListener('tableChanged', function (e) {
    form.style.opacity = 0.5;
    getData(e);
});

const getData = async (e) => {
    const path = form.getAttribute('action');
    await axios
        .post(path, e.detail, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        .then((res) => {

            form.style.opacity = 1;

            if (res.data.redirect_url) {
                window.location.href = res.data.redirect_url;
            } else {
                form.innerHTML = res.data;
                initTableControls();
            }

        })
        .catch((err) => {
            if (err.response && err.response.status === 422) {
                // Обробка помилок валідації
                const errors = err.response.data.errors;
                // Наприклад, вивести повідомлення
                Object.values(errors).forEach(messages => {
                    messages.forEach(msg => alert(msg));
                });
            } else {
                // Інші помилки
                alert(err.message);
            }
        });
}

document.addEventListener('DOMContentLoaded',
    () => {
        Object.keys(params).length !== 0
            ? document.dispatchEvent(new CustomEvent('tableChanged', {detail: params}))
            : initTableControls()
    }
);

