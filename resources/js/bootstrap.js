import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// don't uncomment this when the BE has deploy (optional). Because has already created by server.
// axios.defaults.withXSRFToken = true;
// axios.defaults.withCrendentials = true;
