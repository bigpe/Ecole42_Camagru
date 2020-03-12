let email_notify_status;
onload = function () {
    let email_notify = document.getElementById("email_notify");
    let email_notify_word;
    let r = new XMLHttpRequest();
    r.open("POST", "../check_email_notify.php");
    r.send();
    r.onload = function () {
        email_notify_status = parseInt(r.response);
        if (email_notify_status)
            email_notify_word = "Disable";
        else
            email_notify_word = "Enable";
        email_notify.innerHTML = "<i class=\"fas fa-envelope\"></i> " + email_notify_word + " Email Notify <i class=\"fas fa-bell\"></i>";
    };
    email_notify.onclick = function () {
        if (email_notify_status) {
            email_notify_status = 0;
            email_notify_word = "Enable";
        }
        else {
            email_notify_status = 1;
            email_notify_word = "Disable";
        }
        email_notify.innerHTML = "<i class=\"fas fa-envelope\"></i> " + email_notify_word + " Email Notify <i class=\"fas fa-bell\"></i>";
        email_notify_change(email_notify_status);
    }
};

function email_notify_change(email_notify_status) {
    let r = new XMLHttpRequest();
    r.open("POST", "../email_notify_change.php");
    r.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    r.send("email_notify_status=" + email_notify_status);
}