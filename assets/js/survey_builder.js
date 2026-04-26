// ===============================
// OPEN ADD QUESTION (MODAL)
// ===============================
function openAddQuestion() {
    fetch("add_question.php")
        .then(res => res.text())
        .then(html => {
            document.getElementById("ajaxModalContent").innerHTML = html;
            document.getElementById("ajaxModal").style.display = "block";
        });
}

// ===============================
// CLOSE MODAL ON BACKDROP CLICK
// ===============================
document.addEventListener("click", function (e) {
    const modal = document.getElementById("ajaxModal");
    if (e.target === modal) {
        closeModal();
    }
});
