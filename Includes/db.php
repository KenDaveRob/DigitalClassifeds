<?php

class VerticalDB extends mysqli {

    // single instance of self shared among all instances
    private static $instance = null;
    // db connection config vars
    
    private $user = "s14g03";
    private $pass = "TTG#3isA";
    private $dbName = "student_s14g03";
    private $dbHost = "sfsuswe.com";
    // */ 

    /*
      private $pass = "";
      private $dbName = "test";
      private $dbHost = "localhost:3306";
      private $user = "root";
      // */
    private $con = null;

    //This method must be static, and must return an instance of the object if the object
    //does not already exist.
    public static function getInstance() {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    // The clone and wakeup methods prevents external instantiation of copies of the Singleton class,
    // thus eliminating the possibility of duplicate objects.
    public function __clone() {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public function __wakeup() {
        trigger_error('Deserializing is not allowed.', E_USER_ERROR);
    }

    // private constructor
    private function __construct() {
        parent::__construct($this->dbHost, $this->user, $this->pass, $this->dbName);
        if (mysqli_connect_error()) {
            exit('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
        }
        parent::set_charset('utf-8');
    }

    public function get_item_by_description($itemDescription) {
        return $this->query("SELECT * FROM items WHERE description LIKE '%" . $itemDescription . "%' OR name LIKE '%" . $itemDescription . "%'");
    }
    
    public function get_item_by_description_and_category($itemDescription, $category) {
        return $this->query("SELECT * FROM items INNER JOIN categories ON "
                . "items.category =categories.id WHERE description LIKE "
                . "'%" . $itemDescription . "%' OR name LIKE '%" . $itemDescription . "%'"
                . " AND categories.name = '". $category . "'");
    }

    public function get_pending_items() {
        return $this->query("SELECT * FROM items WHERE is_approved=0 ORDER BY date DESC");
    }

    public function get_parent_and_child($name = null) {
        if ($name)
            return $this->query("SELECT cat1.id id, cat1.name parent, GROUP_CONCAT(cat2.name) children FROM categories cat1, categories cat2 WHERE cat1.id = cat2.parent AND cat1.parent = '" . $name . "'GROUP BY cat1.id");
        return $this->query("SELECT cat1.id id, cat1.name parent, GROUP_CONCAT(cat2.name) children FROM categories cat1, categories cat2 WHERE cat1.id = cat2.parent GROUP BY cat1.id");
    }

    public function get_categories_list() {
        return $this->query("SELECT * FROM categories");
    }

    public function get_category_by_name($name) {
        return $this->query("SELECT * FROM categories WHERE name = '" . $name);
    }

    function format_date_for_sql($date) {
        if ($date == "")
            return null;
        else {
            $dateParts = date_parse($date);
            return $dateParts['year'] * 10000 + $dateParts['month'] * 100 + $dateParts['day'];
        }
    }

    public function get_items_by_category($categoryName, $is_sold = null) {

        if ($is_sold)
            return $this->query("SELECT items.id, items.name, items.description, "
                            . "items.price, items.image_uri, items.location, items.date, "
                            . "categories.name as category FROM items INNER JOIN categories "
                            . "ON items.category=categories.id WHERE is_sold = 1 AND is_approved=1 AND "
                            . "categories.name = '" . $categoryName . "' ORDER BY date DESC");
        else {
            switch ($categoryName) {
                case "furniture":
                    $categoryName = "'" . $categoryName . "','antiques'";
                    break;
                case "automotive":
                    $categoryName = "'" . $categoryName . "','rvs+camp','atv/utv/snow','auto parts','cars+trucks', 'motorcycles', 'boats'";
                    break;
                case "household":
                    $categoryName = "'" . $categoryName . "','appliances'";
                    break;
                case "electronics":
                    $categoryName = "'" . $categoryName . "','computer','cell phones','video gaming', 'tv'";
                    break;
                default:
                    $categoryName = "'" . $categoryName . "'";
            }
            $query = "SELECT items.id, items.name, items.description, "
                    . "items.price, items.image_uri, items.location, items.date, "
                    . "categories.name as category FROM items INNER JOIN categories "
                    . "ON items.category=categories.id WHERE is_sold <>1 AND is_approved=1 AND "
                    . "categories.name IN (" . $categoryName . ") ORDER BY date DESC";
            return $this->query($query);
        }
    }

    public function get_item_by_item_id($itemID, $is_sold = null) {
        if ($is_sold)
            return $this->query("SELECT * FROM items WHERE is_sold = 1 AND id = " . $itemID);
        else
            return $this->query("SELECT * FROM items WHERE is_sold <> 1 AND  id = " . $itemID);
    }

    public function get_user_by_user_id($userID) {
        return $this->query("SELECT * FROM user WHERE id = " . $userID);
    }

    public function get_user_by_email($email) {
        return $this->query("SELECT * FROM user WHERE email = '" . $email . "'");
    }

    public function login($email, $password) {
        $email = $this->real_escape_string($email);
        $password = $this->real_escape_string($password);
        $hash = mysqli_fetch_array($this->query("SELECT password FROM user WHERE email='" . $email . "'"));
        if ($hash[0] == crypt($password, $hash[0])){
            $id =  mysqli_fetch_array($this->query("SELECT id FROM user WHERE email='" . $email . "'"));
            return (int) $id['id'];
        }
        return false;
    }
    public function reset_password($email, $secretquestion, $secretanswer, $password) {
        $email      =   $this->real_escape_string($email);
        $password   =   $this->real_escape_string($password);
        $secret     =   $this->real_escape_string($secretquestion).': '
                .$this->real_escape_string($secretanswer);
    
        $secrethash = mysqli_fetch_array($this->query("SELECT secret FROM user WHERE email='" . $email . "'"));
        if ($secrethash[0] !== crypt($secret, $secrethash[0]))
            return false;
        
        $password   =   crypt($this->real_escape_string($password)); //php function to encrypt password
        $this->query("UPDATE user SET password  =  '".$password."' WHERE email='" . $email . "'");
        
        return true;
    }

    public function save_user($first_name, $last_name, $username, $password, $email, $location, $secretquestion, $secretanswer) {
        $first_name =   $this->real_escape_string($first_name);
        $last_name  =   $this->real_escape_string($last_name);
        $email      =   $this->real_escape_string($email);
        $username   =   $this->real_escape_string($username);
        $location   =   $this->real_escape_string($location);
        $password   =   crypt($this->real_escape_string($password)); //php function to encrypt password
        $secret     =   crypt($secretquestion.': '.$secretanswer);
        $this->query("INSERT INTO user (first_name, last_name, password, email, username, location, secret) "
                . "VALUES ('" . $first_name . "','" . $last_name . "','" . $password . "',"
                . "'" . $email . "','" . $username . "','" . $location . "','" . $secret . "')");
        
        return $this->insert_id;
    }

    public function get_items_by_user_id($userID, $issold = null) {
        if ($issold == 1) //show those sold
            return $this->query("SELECT * FROM items WHERE is_sold=1 AND user_id = " . $userID);
        else //show those not sold
            return $this->query("SELECT * FROM items WHERE is_sold<>1 AND user_id = " . $userID);
    }

    public function show_all_items($limit_top = null, $limit_bottom = null) {
        if ($limit_top)
            if ($limit_bottom)
                return $this->query("SELECT * FROM items WHERE is_approved=1 AND is_sold=0 ORDER BY date DESC LIMIT " . $limit_bottom . " , " . $limit_top);
            else
                return $this->query("SELECT * FROM items  WHERE is_approved=1 AND is_sold=0 ORDER BY date DESC LIMIT 0, " . $limit_top);
        else
            return $this->query("SELECT * FROM items WHERE is_approved=1 AND is_sold=0 ORDER BY date DESC");
    }

    public function create_item($name, $user, $category, $description = null, $price = null, $image_filename = null) {
        $name = $this->real_escape_string($name);
        $description = $this->real_escape_string($description);
        $price = $this->real_escape_string($price);
        $image_uri = $this->real_escape_string($image_filename);
        //$user = mysqli_fetch_array($this->query("SELECT id FROM user WHERE email='" . $user . "'"));
        $category = mysqli_fetch_array($this->query("SELECT id FROM categories WHERE name='" . $category . "'"));
        $this->query("INSERT INTO items (name, description, price, image_uri, user_id, category) VALUES ('" . $name
                . "', '" . $description . "', '" . $price . "', '" . $image_uri . "', '" . $user . "', '" . $category['id'] . "')");
        $id = $this->insert_id;
        return $id;
    }

    public function update_item($name = null, $category = null, $description = null, $price = null, $image_filenames = null) {
          $name = $this->real_escape_string($name);
          $description = $this->real_escape_string($description);
          $price = $this->real_escape_string($price);
          $image_uris = $this->real_escape_string($image_filenames);
          $category = mysqli_fetch_array($this->query("SELECT id FROM categories WHERE name='" . $category . "'"));
          $values = array("name"=>$name, "description"=> $description
            ,"price"=> $price, "image_uri"=> $image_uris,"category"=> $category['id']);
          $q_keys = "";
          $q_values = "";
          foreach($values as $key=>$value){
              if($value != null){
                  $q_keys .= ($q_keys == "") ? $key : ",$key";
                  $q_values .= ($q_values == "") ? "'$value'" : ",'$value'";
              }
          }
          $this->query("INSERT INTO items ($q_keys) VALUES ($q_values)");
         
    }

    public function buy_item($itemID, $buyerID, $sellerID) {
        $this->query("INSERT INTO transaction (`buyer_id`, `seller_id`, `item_id`) VALUES (" . $buyerID
                . ", " . $sellerID . ", " . $itemID . ")");
        $this->query("UPDATE items SET is_sold = '1' WHERE id = " . $itemID);
    }

    public function approve_post($id) {
        $this->query("UPDATE items SET is_approved = 1 where id=" . $id);
    }

    public function deny_post($id) {
        $this->query("UPDATE items SET is_approved = -1 where id=" . $id);
    }

    public function message_admin($message){
        
        $to = 's14g03list@sfsuswe.com';
        $subject = "A message from a user of Digital Classifieds.";
        $headers = 'From: s14g03@sfsuswe.com' . "\r\n" .
                'Reply-To: s14g03@sfsuswe.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

        return mail($to, $subject, $message, $headers);
    }
    public function send_mail($fromID, $toID, $type, $message) {

        $to = 's14g03@sfsuswe.com';
        $subject = $fromID . "-" . $toID . "-" . $type;
        $headers = 'From: s14g03@sfsuswe.com' . "\r\n" .
                'Reply-To: s14g03@sfsuswe.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

        return mail($to, $subject, $message, $headers);
    }

    public function display_mail($userID, $messageID = null) {
        $mailbox = imap_open("{sfsuswe.com:143/novalidate-cert}INBOX", "s14g03", "TTG#3isA");
        $count = imap_num_msg($mailbox);
        $messages = array();
        if ($messageID != null) { //then we are reading a specific message
            $body = imap_body($mailbox, $messageID);
            $headers = imap_fetchheader($mailbox, $messageID);
            $subject = ''; //logic for if the message belongs to the sender
            preg_match_all('/^Subject: (.*)/m', $headers, $subject);
            $subject = split('-', $subject[1][0]); //email subjects are sent in the form FROMID-TOID-TYPE_OF_MESSAGE
            if (count($subject) > 1) {
                $to = $subject[1];
                $from = $subject[0];
                $type = $subject[2];
                $messages[] = array(
                    'from' => $from,
                    'subject' => $type,
                    'body' => $body
                );
            }
        } else { // we want all the messages for this user
            for ($i = 1; $i < $count+1; $i++) {
                $headers = imap_fetchheader($mailbox, $i);
                $subject = ''; //logic for if the message belongs to the sender
                preg_match_all('/^Subject: (.*)/m', $headers, $subject);
                $subject = split('-', $subject[1][0]); //email subjects are sent in the form FROMID-TOID-TYPE_OF_MESSAGE
                if (count($subject) > 1) {
                    $to = (int) $subject[1];
                    $from = (int) $subject[0];
                    $type = (int)$subject[2];
                    if ($to == $userID) { //if the userid matches, this message is intended for this user
                        $body = imap_body($mailbox, $i);
                        $messageid = '';
                        preg_match_all('/^Message-ID: (.*)/m', $headers, $messageid);
                
                        $messages[] = array(
                            'from' => $from,
                            'subject' => $type,
                            'body' => $body,
                            'message_id' => (isset($messageid[1][0]) ? $messageid[1][0] : $i)
                        );
                    }
                } else {
                    $messages = null;
                }
            }
        }
        
        return $messages;
    }

}

?>