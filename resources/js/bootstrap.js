import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// uncomment this when the BE has deploy.
// axios.defaults.withXSRFToken = true;
// axios.defaults.withCrendentials = true;
