<?php
class Donation{
  
    // database connection and table name
    private $conn;
    private $table_name = "donation";
    private $table_name_doner = "doner";
    private $table_name_staff = "staff";
    private $table_name_beggar = "beggar";
    private $table_name_volunteer = "volunteer";
    
//New data entry
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

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
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

}
?>

