import axios from 'axios';
import Alpine from 'alpinejs';
import { modalFormComponent } from './components/modalFormComponent.js';
import { loadRelationshipComponent } from './components/loadRelationshipComponent.js';
import { tableComponent } from './components/tableComponent.js';
import { formComponent } from './components/formComponent.js';
import { loginComponent } from './components/loginComponent.js';

window.axios = axios;
window.Alpine = Alpine;

window.modalFormComponent = modalFormComponent;
window.loadRelationshipComponent = loadRelationshipComponent;
window.tableComponent = tableComponent;
window.formComponent = formComponent;
window.loginComponent = loginComponent;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
Alpine.start();
