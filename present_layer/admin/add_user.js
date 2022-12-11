/***
 * @author Martin Pavella xpavel39
 */


/**
 * Handle form submit. Try to add new user or display error
 */
function submit(){
    // Check email is valid
    e_email = document.getElementById("email");
    if(email == null)
        return;
    email = e_email.value;
    if( ! check_email(email))
    {
        bad_email();
        return;
    }

    // Check password is valid
    if( ! check_pwd())
    {
        bad_pwd();
        return;
    }

    // Try to add new user
    add_user(email);
}

function send_form()
{
    clean_up();
}

/***
 * Clear form inputs and error messages
 */
function clean_up()
{
    clear_value_by_id("f_name");
    clear_value_by_id("l_name");
    clear_value_by_id("email");
    clear_value_by_id("password");
    clear_value_by_id("phone");
    show_succ();
}

/***
 * Clear the value of object with given id
 */
function clear_value_by_id(id)
{
    elt = document.getElementById(id);
    if(elt == null)
        return;

    elt.value = "";
}

/***
 * Functions that print error messages
 */
function email_exists()
{
    show_err("Zadaný e-mail je už používaný!");
}
function bad_email()
{
    show_err("Skontrolujte formát e-mailu!");
}
function bad_pwd()
{
    show_err("Heslo musí mať aspoň 8 znakov!");
}


/***
 * Display error message under form
 */
function show_err(err)
{
    err_msg = document.getElementById("err-msg");
    if(err_msg == null)
        return

    err_msg.innerHTML = err;
    err_msg.classList.remove("hidden");
    err_msg.classList.add("err-msg");
    err_msg.classList.remove("succ-msg");

}

/***
 * Display success message
 */
function show_succ()
{
    err_msg = document.getElementById("err-msg");
    if(err_msg == null)
        return;
    
    err_msg.classList.remove("hidden");
    err_msg.classList.remove("err-msg");
    err_msg.classList.add("succ-msg");

    err_msg.innerHTML = "Užívateľ úspešne pridaný";
}

/***
 * Add new user if email is not used yet
 */
function add_user(email)
{
    req = new XMLHttpRequest();
    req.open("POST","../../bussiness_layer/admin/email_exists.php");
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    req.onreadystatechange = function() {
        if(this.readyState === 4 && this.status === 200) {
            if(this.responseText == false)
                // Email is not used
                send_form();
            else
                // Email is already used
                email_exists();
        }
    };

    req.send("email_to_check=" + email);
}

/**
 * Check email is in a valid format
 */
function check_email(email) 
{
    return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{1,3})+$/.test(email);
}

/**
 * Check password is valid
 */
function check_pwd()
{
    e_pwd = document.getElementById("password");
    if(e_pwd == null)
        return
    pwd = e_pwd.value;
    return pwd.length >= 8;
}
