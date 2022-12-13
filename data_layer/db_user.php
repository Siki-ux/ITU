<?php
/**
 * @author xpavel39@stud.fit.vutbr.cz
 * Functions which interact with database
 */
    include_once('db_setup.php');


    /***
     * Get the first user wth given email from database
     * @return dict representing user with given email
     */
    function get_user_by_email($email)
    {
        $pdo = get_pdo();

        $stmt = $pdo->prepare('SELECT * FROM PERSON WHERE email = ?;');

        if( ! $stmt->execute([$email]))
            # execute FAILED!
            return null;
        
        return $stmt->fetch();
    }

    /***
     * Insert user with given attrbutes to database
     */
    function insert_user($f_name, $l_name, $email , $phone, $pw_hash,$role)
    {
        $pdo = get_pdo();

        $stmt = $pdo->prepare('INSERT INTO PERSON (first_name,last_name,PW_HASH,email,phone,role) VALUES (:f_name,:l_name,:PW_HASH,:email,:phone,:role)');

        $stmt->execute(["f_name"=>$f_name, "l_name" => $l_name , "PW_HASH"=> $pw_hash , "email" => $email, 'phone' => $phone , 'role'=>$role]);
    }

    /***
     * Get all rows from the PERSON table
     * @return PDOStatement object
     */
    function get_all_users($col = 'id', $asc = 1, $filter = "", $role = -1)
    {
        $pdo = get_pdo();

        if($role == -1) // Select all roles
            $stmt = $pdo->prepare("SELECT id,first_name,last_name,email,phone,role FROM PERSON WHERE (first_name LIKE :filter) OR (last_name LIKE :filter) OR (email LIKE :filter) OR (phone LIKE :filter) OR (id LIKE :filter) ORDER BY $col ".(($asc == 1) ? 'ASC' : 'DESC').";");
        else    // Specific role
            $stmt = $pdo->prepare("SELECT id,first_name,last_name,email,phone,role FROM PERSON WHERE (role = :role) AND ((first_name LIKE :filter) OR (last_name LIKE :filter) OR (email LIKE :filter) OR (phone LIKE :filter) OR (id LIKE :filter)) ORDER BY $col ".(($asc == 1) ? 'ASC' : 'DESC').";");

        $stmt->execute(['filter' => '%'.$filter.'%', 'role' => $role]);

        return $stmt;
    }

    /***
     * Get PERSON table with $role_num role
     * @return PDOStatement object
     */
    function get_users_by_role($role_num)
    {
        $pdo = get_pdo();

        $stmt = $pdo->query("SELECT id,first_name,last_name,email,phone,role FROM PERSON WHERE role = $role_num;");
 
        return $stmt;
    }

    /***
     * Try to remove user with given 'id'
     */
    function remove_user($id)
    {
        $pdo = get_pdo();

        try{    
            $stmt = $pdo->prepare('DELETE FROM PERSON WHERE id = ?;');                
            $stmt->execute([$id]);
        }catch(Exception $e) {}
    }

    /***
     * Determine if user with given 'id' has any submitted tickets
     */
    function user_has_tickets($id)
    {
        $pdo = get_pdo();

        $stmt = $pdo->prepare('SELECT * FROM TICKET WHERE submitted_by = ?;');
        $stmt->execute([$id]);

        if($stmt->fetch())
            return true;
        return false;
    }

    /***
     * Determine if user with given 'id' has any active service requests
     */
    function user_has_service_requests($id)
    {
        $pdo = get_pdo();

        $stmt = $pdo->prepare('SELECT * FROM SERVICE_REQUEST WHERE worker_id = ?;');
        $stmt->execute([$id]);

        if($stmt->fetch())
            return true;
        return false;
    }

    /***
     * Update row with 'id'. Change the value in 'col' to 'new_val'
     */
    function update_user($id,$col,$new_val)
    {
        $pdo = get_pdo();

        $stmt = $pdo->prepare('UPDATE PERSON SET '.$col.' = :new_val WHERE id = :id');
        $stmt->execute(['new_val' => $new_val , 'id' => $id]);
    }


?>