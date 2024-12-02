import "./styles/app.css";
import "./styles/list.css";
import "./styles/home.css";
import "./styles/account.css";
import "./styles/profile.css";
import "./styles/security.css";
import handleList from "./list.js";
import handleAccount from "./account.js";

document.addEventListener("DOMContentLoaded", () => {
    handleList();
    handleAccount();
});
