export default function handleAccount() {
    const container = document.getElementById("accounts-container");
    if(container) {
        document.querySelectorAll(".account-delete-button")
            .forEach(button => {
                button.addEventListener("click", () => {
                    const url = button.getAttribute("data-url");
                    if(url) {
                        if(confirm("Voulez-vous vraiment supprimer ce compte?")) {
                            window.location.href = url;
                        }
                    }
                })
            });

        document.querySelectorAll(".account-edit-button")
            .forEach(button => {
                button.addEventListener("click", () => {
                    const url = button.getAttribute("data-url");
                    if(url) window.location.href = url;
                })
            });

        const createButton = document.getElementById("account-create-button");
        if(createButton) {
            createButton.addEventListener("click", () => {
                const url = createButton.getAttribute("data-url");
                if(url) {
                    window.location.href = url;
                }
            })
        }
    }
}