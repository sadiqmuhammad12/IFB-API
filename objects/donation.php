<?php

session_start();
// echo $_SESSION['user_id'];         
class Donation{
  
    // database connection and table name
    private $conn;

    private $table_name = "donation";
    private $table_name_doner = "doner";
    private $table_name_staff = "staff";
    private $table_name_beggar = "beggar";
    private $table_name_volunteer = "volunteer";
    private $table_name_contact_us = "contact_us";
    private $table_name_users = "users";    
    
  
    // object properties
    public $city;
    public $comments;
    
    // Properties of beggar table
    public $full_name;
    public $cnic;
    public $gender;
    public $address;
    public $phone_no;
    public $doner_name;
    public $description;
    public $donation_amount;
    public $img;
     
    // object properties
    public $id;
    public $name; // For image
    public $beggar_cnic;
    public $doner_id;
    public $amount;
    public $date_time;
    public $email;
    public $passwords;
    public $profile_img;
    public $addressess;
    public $beggar_full_name;
    // Properties of staff_table
    //public $id;
    public $staff_name;
    //public $email;
    //public $passwords;

    // Properties of beggar table
    public $fullname;
    //public $gender;
    // public $added_by;
    // public $approved_by;
  

    // Properties of volunteer table
    // public $fullname;
    // public $cnic;
    // public $address;
    public $username;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }


    // read donation
function read(){
  
    
    $query = " SELECT doner.id,doner.doner_name,doner.email,doner.phone_no,doner.passwords,
      doner.addressess, doner.profile_img,
      
      donation.beggar_cnic,donation.doner_id,donation.amount,donation.doner_name,
      donation.date_time
      FROM donation
      INNER JOIN doner
      ON donation.doner_id = doner.id";

    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // execute query
    $stmt->execute();
  
    return $stmt;
}



// create donation

function create_donation(){
    
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
                
            SET
                beggar_cnic=:beggar_cnic,amount=:amount, doner_name=:doner_name,
                phone_no=:phone_no,gender=:gender,description=:description,
                img=:img,beggar_full_name=:beggar_full_name,address=:address,doner_id=:doner_id
                ";
                
                // echo $_SESSION['user_id'];         

    // for testing
        // $query = "INSERT INTO donation(beggar_cnic,amount,doner_name,phone_no,gender,description,img,
        //          beggar_full_name,doner_id)
        //          values('7854783','2333','Naeem','48488','Male','edsxz','imag2.jpeg','kalim',
        //         (SELECT id FROM users WHERE username ='javid'))";

        // $query = "INSERT INTO `donation` (beggar_cnic,amount,doner_name,phone_no,gender,description,img,
        //          beggar_full_name,address,doner_id)
        //          values(?,?,?,?,?,?,?,?,?,
        //         (SELECT id FROM users WHERE username ='$username'))";
    

    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->beggar_cnic=htmlspecialchars(strip_tags($this->beggar_cnic));
    $this->amount=htmlspecialchars(strip_tags($this->amount));
    $this->doner_name=htmlspecialchars(strip_tags($this->doner_name));
    $this->phone_no=htmlspecialchars(strip_tags($this->phone_no));
    
    $this->gender=htmlspecialchars(strip_tags($this->gender));
    $this->address=htmlspecialchars(strip_tags($this->address));
    $this->description=htmlspecialchars(strip_tags($this->description));
    
    $this->img=htmlspecialchars(strip_tags($this->img));
    $this->beggar_full_name=htmlspecialchars(strip_tags($this->beggar_full_name));
    $this->doner_id=htmlspecialchars(strip_tags($this->doner_id));
  
    // bind values
    $stmt->bindParam(":beggar_cnic", $this->beggar_cnic);
    $stmt->bindParam(":amount", $this->amount);
    $stmt->bindParam(":doner_name", $this->doner_name);
    $stmt->bindParam(":phone_no", $this->phone_no);
    $stmt->bindParam(":gender", $this->gender);
    $stmt->bindParam(":img", $this->img);
    $stmt->bindParam(":address", $this->address);
    $stmt->bindParam(":description", $this->description);
    $stmt->bindParam(":beggar_full_name", $this->beggar_full_name);
    $stmt->bindParam(":doner_id", $this->doner_id);
  
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
      
}

// Fetch single element
function read_single_donation(){  
    // query to read single record
    $query = "SELECT * FROM  " . $this->table_name . " 
            WHERE beggar_cnic = ?
            LIMIT 0,1";
  
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
  
    // bind beggar_cnic of donation to be updated
    $stmt->bindParam(1, $this->beggar_cnic);
  
    // execute query
    $stmt->execute();
  
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
    // set values to object properties
    $this->id = $row['id'];
    $this->doner_id = $row['doner_id'];
    $this->amount = $row['amount'];
    $this->doner_name = $row['doner_name'];
    $this->phone_no = $row['phone_no'];
    $this->gender = $row['gender'];
    $this->address = $row['address'];
    $this->description = $row['description'];
    $this->beggar_full_name = $row['beggar_full_name'];
    $this->name = $row['name'];
}



// create doner
function create_doner(){
  
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name_doner . "
            SET
                doner_name=:doner_name, email=:email, phone_no=:phone_no, 	passwords=:passwords, profile_img=:profile_img,addressess=:addressess";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->doner_name=htmlspecialchars(strip_tags($this->doner_name));
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->phone_no=htmlspecialchars(strip_tags($this->phone_no));
    $this->passwords=htmlspecialchars(strip_tags($this->passwords));
    $this->profile_img=htmlspecialchars(strip_tags($this->profile_img));
    $this->addressess=htmlspecialchars(strip_tags($this->addressess));
  
    // bind values
    $stmt->bindParam(":doner_name", $this->doner_name);
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":phone_no", $this->phone_no);
    $stmt->bindParam(":passwords", $this->passwords);
    $stmt->bindParam(":profile_img", $this->profile_img);
    $stmt->bindParam(":addressess", $this->addressess);
  
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
      
}



// update the donation
function update_donation(){
  
    // update query
    $query = "UPDATE
                " . $this->table_name . "
            SET
                beggar_cnic = :beggar_cnic,
                amount = :amount,
                doner_name = :doner_name,
                phone_no = :phone_no,
                gender = :gender,
                img = :img,
                address = :address,
                beggar_full_name = :beggar_full_name,
                description = :description,
            WHERE
                id = :id";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    $this->beggar_cnic=htmlspecialchars(strip_tags($this->beggar_cnic));
    $this->amount=htmlspecialchars(strip_tags($this->amount));
    $this->doner_name=htmlspecialchars(strip_tags($this->doner_name));
    $this->phone_no=htmlspecialchars(strip_tags($this->phone_no));
    $this->gender=htmlspecialchars(strip_tags($this->gender));
    $this->address=htmlspecialchars(strip_tags($this->address));
    $this->description=htmlspecialchars(strip_tags($this->description));
    $this->img=htmlspecialchars(strip_tags($this->img));
    $this->beggar_full_name=htmlspecialchars(strip_tags($this->beggar_full_name));
    $this->id=htmlspecialchars(strip_tags($this->id));
  
    // bind values
    $stmt->bindParam(":beggar_cnic", $this->beggar_cnic);
    $stmt->bindParam(":amount", $this->amount);
    $stmt->bindParam(":doner_name", $this->doner_name);
    $stmt->bindParam(":phone_no",$this->phone_no);
    $stmt->bindParam(":gender", $this->gender);
    $stmt->bindParam(":address", $this->address);
    $stmt->bindParam(":description", $this->description);
    $stmt->bindParam(":img", $this->img);
    $stmt->bindParam(":beggar_full_name", $this->beggar_full_name);
    $stmt->bindParam(":id", $this->id);
  
    // execute the query
    if($stmt->execute()){
        return true;
    }
  
    return false;
}




// update the doner
function update_doner(){
  
    // update query
    $query = "UPDATE
                " . $this->table_name_doner . "
            SET
                doner_name = :doner_name,
                email = :email,
                phone_no = :phone_no,
                passwords = :passwords,
                profile_img = :profile_img,
                addressess = :addressess
            WHERE
                id = :id";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    $this->doner_name=htmlspecialchars(strip_tags($this->doner_name));
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->phone_no=htmlspecialchars(strip_tags($this->phone_no));
    $this->passwords=htmlspecialchars(strip_tags($this->passwords));
    $this->profile_img=htmlspecialchars(strip_tags($this->profile_img));
    $this->addressess=htmlspecialchars(strip_tags($this->addressess));
    $this->id=htmlspecialchars(strip_tags($this->id));
  
    // bind values
    $stmt->bindParam(":doner_name", $this->doner_name);
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":phone_no", $this->phone_no);
    $stmt->bindParam(":passwords", $this->passwords);
    $stmt->bindParam(":profile_img", $this->profile_img);
    $stmt->bindParam(":addressess", $this->addressess);
    $stmt->bindParam(":id", $this->id);
  
    // execute the query
    if($stmt->execute()){
        return true;
    }
  
    return false;
}

// read donation
function read_donation(){  
    $query = " SELECT * FROM donation";
    
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // execute query
    $stmt->execute();
    return $stmt;
}


// delete the donation
function delete_donation(){
  
    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
  
    // bind id of record to delete
    $stmt->bindParam(1, $this->id);
  
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
}


// delete the Doner
function delete_doner(){
  
    // delete query
    $query = "DELETE FROM " . $this->table_name_doner . " WHERE id = ?";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
  
    // bind id of record to delete
    $stmt->bindParam(1, $this->id);
  
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
}

// Everything below for Staff_Table
   // read staff
   function read_staff(){  
    $query = " SELECT * FROM staff";
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // execute query
    $stmt->execute();
    return $stmt;
}


// create staff
function create_staff(){
  
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name_staff . "
            SET
                full_name=:full_name, email=:email,passwords=:passwords";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->full_name=htmlspecialchars(strip_tags($this->full_name));
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->passwords=htmlspecialchars(strip_tags($this->passwords));
  
    // bind values
    $stmt->bindParam(":full_name", $this->full_name);
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":passwords", $this->passwords);
  
    // execute query
    if($stmt->execute()){
        return true;
    }
    return false;   
}

// update the staff
function update_staff(){
    // update query
    $query = "UPDATE
                " . $this->table_name_staff . "
            SET
                staff_name = :staff_name,
                email = :email,
                passwords = :passwords
            WHERE
                id = :id";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    $this->staff_name=htmlspecialchars(strip_tags($this->staff_name));
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->passwords=htmlspecialchars(strip_tags($this->passwords));
    $this->id=htmlspecialchars(strip_tags($this->id));
  
    // bind values
    $stmt->bindParam(":staff_name", $this->staff_name);
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":passwords", $this->passwords);
    $stmt->bindParam(":id", $this->id);
  
    // execute the query
    if($stmt->execute()){
        return true;
    }
  
    return false;
}



// delete the Staff
function delete_staff(){
  
    // delete query
    $query = "DELETE FROM " . $this->table_name_staff . " WHERE id = ?";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
  
    // bind id of record to delete
    $stmt->bindParam(1, $this->id);
  
    // execute query
    if($stmt->execute()){
        return true;
    }
    return false;
}

// Fetch single element
function read_single_staff(){  
    // query to read single record
    $query = "SELECT * FROM  " . $this->table_name_staff . " 
            WHERE full_name = ?
            LIMIT 0,1";
  
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
  
    // bind full_name of product to be updated
    $stmt->bindParam(1, $this->full_name);
  
    // execute query
    $stmt->execute();
  
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
    // set values to object properties
    $this->id = $row['id'];
    $this->email = $row['email'];
    $this->passwords = $row['passwords'];
}


//  Everything below for Beggar_Table
// create beggar
function create_beggar(){
  
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name_beggar . "
            SET
                full_name=:full_name, cnic=:cnic,gender=:gender,address=:address,
                phone_no=:phone_no,doner_name=:doner_name,description=:description,
                donation_amount=:donation_amount,img=:img";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->full_name=htmlspecialchars(strip_tags($this->full_name));
    $this->cnic=htmlspecialchars(strip_tags($this->cnic));
    $this->gender=htmlspecialchars(strip_tags($this->gender));
    $this->address=htmlspecialchars(strip_tags($this->address));
    $this->phone_no=htmlspecialchars(strip_tags($this->phone_no));
    $this->doner_name=htmlspecialchars(strip_tags($this->doner_name));
    $this->description=htmlspecialchars(strip_tags($this->description));
    $this->donation_amount=htmlspecialchars(strip_tags($this->donation_amount));
    $this->img=htmlspecialchars(strip_tags($this->img));
  
    // bind values
    $stmt->bindParam(":full_name", $this->full_name);
    $stmt->bindParam(":cnic", $this->cnic);
    $stmt->bindParam(":gender", $this->gender);
    $stmt->bindParam(":address", $this->address);
    $stmt->bindParam(":phone_no", $this->phone_no);
    $stmt->bindParam(":doner_name", $this->doner_name);
    $stmt->bindParam(":description", $this->description);
    $stmt->bindParam(":donation_amount", $this->donation_amount);
    $stmt->bindParam(":img", $this->img);
  
    // execute query
    if($stmt->execute()){
        return true;
    }
    return false;   
}



// delete the beggar
function delete_beggar(){
  
    // delete query
    $query = "DELETE FROM " . $this->table_name_beggar . " WHERE id = ?";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
  
    // bind id of record to delete
    $stmt->bindParam(1, $this->id);
  
    // execute query
    if($stmt->execute()){
        return true;
    }
    return false;
}

// Fetch single element
function read_single_beggar(){  
    // query to read single record
    $query = "SELECT * FROM  " . $this->table_name_beggar . " 
            WHERE id = ?
            LIMIT 0,1";
  
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
  
    // bind id of product to be updated
    $stmt->bindParam(1, $this->id);
  
    // execute query
    $stmt->execute();
  
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
    // set values to object properties
    $this->full_name = $row['full_name'];
    $this->cnic = $row['cnic'];
    $this->gender = $row['gender'];
    $this->address = $row['address'];
    $this->added_by = $row['added_by'];
    $this->approved_by = $row['approved_by'];
}

// read beggar
   function read_beggar(){  
    $query = " SELECT * FROM beggar";
    
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // execute query
    $stmt->execute();
    return $stmt;
}


// update the beggar
function update_beggar(){
    // update query
    $query = "UPDATE
                " . $this->table_name_beggar . "
            SET
                full_name = :full_name,
                cnic = :cnic,
                gender = :gender,
                address = :address,
                added_by = :added_by,
                approved_by = :approved_by
            WHERE
                id = :id";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    $this->full_name=htmlspecialchars(strip_tags($this->full_name));
    $this->cnic=htmlspecialchars(strip_tags($this->cnic));
    $this->gender=htmlspecialchars(strip_tags($this->gender));
    $this->address=htmlspecialchars(strip_tags($this->address));
    $this->added_by=htmlspecialchars(strip_tags($this->added_by));
    $this->approved_by=htmlspecialchars(strip_tags($this->approved_by));
    $this->id=htmlspecialchars(strip_tags($this->id));
  
    // bind values
    $stmt->bindParam(":full_name", $this->full_name);
    $stmt->bindParam(":cnic", $this->cnic);
    $stmt->bindParam(":gender", $this->gender);
    $stmt->bindParam(":address", $this->address);
    $stmt->bindParam(":added_by", $this->added_by);
    $stmt->bindParam(":approved_by", $this->approved_by);
    $stmt->bindParam(":id", $this->id);
  
    // execute the query
    if($stmt->execute()){
        return true;
    }
  
    return false;
}

// Everything below for volunteer table
// create volunteer
function create_volunteer(){
  
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name_volunteer . "
            SET
                full_name=:full_name, cnic=:cnic,gender=:gender,address=:address";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->full_name=htmlspecialchars(strip_tags($this->full_name));
    $this->cnic=htmlspecialchars(strip_tags($this->cnic));
    $this->gender=htmlspecialchars(strip_tags($this->gender));
    $this->address=htmlspecialchars(strip_tags($this->address));
    
  
    // bind values
    $stmt->bindParam(":full_name", $this->full_name);
    $stmt->bindParam(":cnic", $this->cnic);
    $stmt->bindParam(":gender", $this->gender);
    $stmt->bindParam(":address", $this->address);
    
  
    // execute query
    if($stmt->execute()){
        return true;
    }
    return false;   
}



// delete the beggar
function delete_volunteer(){
  
    // delete query
    $query = "DELETE FROM " . $this->table_name_volunteer . " WHERE id = ?";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
  
    // bind id of record to delete
    $stmt->bindParam(1, $this->id);
  
    // execute query
    if($stmt->execute()){
        return true;
    }
    return false;
}

// Fetch single element
function read_single_volunteer(){  
    // query to read single record
    $query = "SELECT * FROM  " . $this->table_name_volunteer . " 
            WHERE cnic = ?
            LIMIT 0,1";
  
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
  
    // bind cnic of product to be updated
    $stmt->bindParam(1, $this->cnic);
  
    // execute query
    $stmt->execute();
  
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
    // set values to object properties
    $this->full_name = $row['full_name'];
    $this->id = $row['id'];
    $this->gender = $row['gender'];
    $this->address = $row['address'];

}


// read volunteer
function read_volunteer(){  
    $query = " SELECT * FROM volunteer";
    
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // execute query
    $stmt->execute();
    return $stmt;
}

// update the volunteer
function update_volunteer(){
    // update query
    $query = "UPDATE
                " . $this->table_name_volunteer . "
            SET
                full_name = :full_name,

                gender = :gender,
                address = :address
            WHERE
                cnic = :cnic";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    $this->full_name=htmlspecialchars(strip_tags($this->full_name));
    $this->cnic=htmlspecialchars(strip_tags($this->cnic));
    $this->gender=htmlspecialchars(strip_tags($this->gender));
    $this->address=htmlspecialchars(strip_tags($this->address));
    // $this->id=htmlspecialchars(strip_tags($this->id));
  
    // bind values
    $stmt->bindParam(":full_name", $this->full_name);
    $stmt->bindParam(":cnic", $this->cnic);
    $stmt->bindParam(":gender", $this->gender);
    $stmt->bindParam(":address", $this->address);
    // $stmt->bindParam(":id", $this->id);
  
    // execute the query
    if($stmt->execute()){
        return true;
    }
  
    return false;
}


// create contact
function create_contact(){
  
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name_contact_us . "
            SET
                full_name=:full_name, email=:email,city=:city,comments=:comments";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->full_name=htmlspecialchars(strip_tags($this->full_name));
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->city=htmlspecialchars(strip_tags($this->city));
    $this->comments=htmlspecialchars(strip_tags($this->comments));
  
    // bind values
    $stmt->bindParam(":full_name", $this->full_name);
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":city", $this->city);
    $stmt->bindParam(":comments", $this->comments);
    
  
    // execute query
    if($stmt->execute()){
        return true;
    }
    return false;   
}

// read donation
function read_single_user(){  
    // query to read single record
    $query = "SELECT * FROM  " . $this->table_name_users . " 
            WHERE id = ?
            LIMIT 0,1";
  
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
  
    // bind id of product to be updated
    $stmt->bindParam(1, $this->id);
  
    // execute query
    $stmt->execute();
  
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
    // set values to object properties
    $this->username = $row['username'];
    $this->id = $row['id'];
    $this->email = $row['email'];

}


}
?>

