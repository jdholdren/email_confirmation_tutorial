<?php
 
/**
* Entity representing a user
**/
 
class User
{
    // Properties
    private $id, $email, $active;
 
    /**
    * Constructor for the object
    * @param key/value array of initial values for the User
    **/
    public function __construct($initials = array())
    {
        if (!empty($initials))
        {
            foreach ($initials as $key => $value)
            {
                $this->$key = $value;
            }
        }
    }
 
    /**
    * Retrieves a user model based on email
    * @param string the user's email
    * @return User object if such a record exists. False if not
    **/
    public static function getUser($email)
    {
        // Get the global db object
        global $db;
 
        // Prepare the SQL
        $sql = "SELECT * FROM users WHERE email='<>'";
 
 
 
 
        $result = $db->query($sql, $email);
         
        if ($result->num_rows == 1)
        {
            // A record was found, return an object with its data
            $result = $result->fetch_assoc();
            return new self($result);
        }
        else
        {
            // No result was found
            return false;
        }
    }
 
    /**
    * Creates a new user in the db and sends a confirmation email
    * @param string the user's email
    * @return user obj
    **/
    public static function createUser($email)
    {
        // Make the activation hash
        $hash = sha1(time());
 
        // Make a new user in the db
        global $db;
 
        $sql = "INSERT INTO users (email) VALUES ('<>')";
        $db->query($sql, $email);
 
        // Get the last insert id
        $id = $db->getLastInsertId();
 
        // Create a user obj
        $user = new self(array('id' => $id, 'email' => $email, 'active' => false));
 
        // Make an activation 
        $sql = "INSERT INTO activations (userId, hash) VALUES (<>, '<>')";
 
        // Execute the activation sql
        $db->query($sql, array($id, $hash));
 
        // Send the User a confirmation email
        User::sendConfirmation($email, $hash);
 
        return $user;
    }
 
    /**
    * Sends a confirmation email to a specified address
    * @param string email address
    * @param string hash
    * @return boolean
    **/
    public static function sendConfirmation($email, $hash)
    {
        $subject = 'Confirm your signup.';
        $message = 'To activate your account, click <a href="http://localhost/emailconfirm/activation.php?email=' . urlencode($email) . 'pass=' . $hash;
        return mail($email, $subject, $message);
    }

    /**
    * Sets the user's account to activated and deletes the activation token
    * @param string user's email
    * @param string the user's hash
    * @return boolean on successful activation
    **/
    public static function activate($email, $hash)
    {
        global $db;

        // Prep the SQL
        $sql = "UPDATE users INNER JOIN activations ON users.id=activations.userId SET users.active=1, activations.used=1 WHERE users.email='<>' AND activations.hash='<>' AND activations.used='0'";

        // Exec and if successful, delete the activation
        return $db->query($sql, array($email, $hash));
    }
}