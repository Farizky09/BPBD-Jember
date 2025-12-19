import 'select2';
import 'select2/dist/css/select2.css';

$(document).ready(function() {
    $('.select2').select2();
});
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
