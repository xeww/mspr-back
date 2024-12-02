import "./styles/app.css";
import "./styles/list.css";
import "./styles/home.css";
import "./styles/account.css";
import "./styles/profile.css";
import "./styles/security.css";
import "./styles/contact.css";
import handleList from "./list.js";
import handleAccount from "./account.js";
import handleContact from "./contact.js";

document.addEventListener("DOMContentLoaded", () => {
    handleList();
    handleAccount();
    handleContact();
});
