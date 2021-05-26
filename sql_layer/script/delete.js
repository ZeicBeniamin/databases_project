/**
 * TODO: Add documentation
 */
function checkAndSubmit() {
    let checked = [...document.getElementsByClassName('id_button')].some(c => c.checked);
    if (checked) {
        document.getElementById("delete_form").submit();
    } else if (!document.getElementById("warning")) {
        let textField = document.createElement("h6");
        textField.setAttribute("class", "error");
        // textField.setAttribute("text");
        textField.innerText = "Please select one of the rows."
        textField.setAttribute("id", "warning");
        document.body.appendChild(textField)
    }

}