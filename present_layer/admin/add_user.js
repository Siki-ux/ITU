/***
 * @author Martin Pavella xpavel39
 */

/// Bool indicating if a new user can be added ATM
can_add = true;


document.addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        submit();
    }
});


/**
 * Handle form submit. Try to add new user or display error
 */
function submit(){
    if( ! can_add ) // Cannot add new user right now
        return;
    can_add = false;

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

    // Check phone number is valid
    if( ! check_phone())
    {
        bad_phone();
        return;
    }

    // Try to add new user
    add_user(email);
}

/***
 * Send data from form directly to server using AJAX to create a new user
 */
function send_form()
{
    // Get data from form
    f_name = get_value_by_id("f_name");
    l_name = get_value_by_id("l_name");
    email = get_value_by_id("email");
    pwd = get_value_by_id("password");
    phone = get_value_by_id("phone");
    role = get_value_by_id("role");

    // Send
    req = new XMLHttpRequest();
    req.open("POST","../../bussiness_layer/admin/add_user.php");
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.onreadystatechange = function() {
        if(this.readyState === 4 && this.status === 200) {
            if(this.responseText == false)
                // Error occured
                show_err("Nepodarilo sa pridať užívateľa!");
            else
                // Everything OK
                show_succ();
        }

        if(this.readyState === 4)
            // Allow new user to be added
            can_add = true;
    };
    req.send("first_name=" + f_name + "&last_name=" + l_name + "&email=" + email + "&password=" + pwd + "&phone=" + phone + "&role=" + role);

    // Prepare for next use
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
    hide_err();
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
 * Get the value of the element with given id
 */
function get_value_by_id(id)
{
    elt = document.getElementById(id);
    if(elt == null)
        return "";

    return elt.value;
}

/***
 * Functions that print error messages
 */
function email_exists()
{
    show_err("Zadaný e-mail je už používaný!");
}function bad_email()
{
    show_err("Skontrolujte formát e-mailu!");
}function bad_pwd()
{
    show_err("Heslo musí mať aspoň 8 znakov!");
}function bad_phone()
{
    show_err("Skontrolujte formát telefónneho čísla!");
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
 * Hode the error message
 */
function hide_err()
{
    err_msg = document.getElementById("err-msg");
    if(err_msg == null)
        return

    err_msg.innerHTML = "";
    err_msg.classList.add("hidden");
    err_msg.classList.remove("err-msg");
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

/***
 * Check phone number format
 */
function check_phone()
{
    e_phone = document.getElementById("phone");
    if(e_phone == null)
        return
    phone = e_phone.value;

    return /^[+0-9 ]*$/.test(phone) && phone.length < 20;
}
