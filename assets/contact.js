export default function handleContact() {
    const container = document.querySelector(".contact-messages-container");
    if(container) {
        document.querySelectorAll(".message-delete-button").forEach(button => {
            button.addEventListener("click", () => {
                const url = button.getAttribute("data-url");
                if(url) {
                    if(confirm("Voulez-vous vraiment supprimer ce message?")) {
                        window.location.href = url;
                    }
                }
            });
        });
    }
}