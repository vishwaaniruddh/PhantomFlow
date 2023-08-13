<?php
use Firebase\JWT\JWT;


class UsersController{

    // public function index(){
    //     $pdo = Connection::make();
    //     $query = new QueryBuilder($pdo);
    //     $sql = "select * from users";
        
    //     $result = $pdo->prepare($sql);

    //     $result->execute();
    //     $result = $result->fetchAll(PDO::FETCH_OBJ);
    //     var_dump($result);
    //     return view('users',compact('result'));
    // }
    public function index(){
        $pdo = Connection::make();
        $query = new QueryBuilder($pdo);

        // Get all users
        $allUsers = $query->selectAll('users');
        
        // Calculate the current page based on the "page" query parameter
        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

        // Calculate the number of users to display per page
        $usersPerPage = 10;
        $totalUsers = count($allUsers);
        $offset = ($currentPage - 1) * $usersPerPage;

        // Get the users for the current page
        $result = array_slice($allUsers, $offset, $usersPerPage);

        return view('users', compact('result', 'totalUsers', 'currentPage', 'usersPerPage'));
    }

    public function login(){
        return view('login');
    }
    
    public function login_process(){
        $username  = $_POST['username'];
        $password  = $_POST['password'];

        if($username && $password){

            $pdo = Connection::make();

            $sql = "select * from vendorUsers where uname = '".$username."' and password='".$password."' and user_status=1 and level=3" ;
            $statement = $pdo->prepare($sql);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            $datetime = date('Y-m-d H:i:s');
            
            if(count($result)>0){
                
                $_SESSION['SERVICE_auth']= true;
                $_SESSION['SERVICE_isProjectPortal'] = 1 ; 
                $_SESSION['SERVICE_username']=$result[0]['name'];
                $_SESSION['SERVICE_userid'] = $result[0]['id'];
                $_SESSION['SERVICE_level'] = $result[0]['level'];
                $_SESSION['SERVICE_RailTailVendorID'] = $result[0]['vendorId'];
                $_SESSION['SERVICE_branch'] = $result[0]['branch'];
                $_SESSION['SERVICE_zone'] = $result[0]['zone'];

                $userid = $result[0]['id'];
                $secret_key = "RailTailService";
                $issuedat_claim = time(); // issued at
                $notbefore_claim = $issuedat_claim + 10; //not before in seconds
                $expire_claim = $issuedat_claim + 60; // expire time in seconds
                $fname = $result[0]['name'];
                $email = $result[0]['uname'];
                
                $token = array(
                    "nbf" => $notbefore_claim,
                    "exp" => $expire_claim,
                    "data" => array(
                    "id" => $userid,
                    "fullname" => $fname,
                    "email" => $email,
                ));
                $jwt = JWT::encode($token, $secret_key,"HS256");
                $token_sql = "update vendorUsers set token='".$jwt."' , updated_at = '".$datetime."' where id='".$userid."'";
                $updateToken = $pdo->prepare($token_sql);
                $updateToken->execute();

                $_SESSION['isServicePortalToken'] = $jwt ;

                if(isset($_SESSION['SERVICE_username']) && !empty($_SESSION['SERVICE_username'])){
                    return redirect('index');                            
                }

            }

        }

    }
    
    public function logout(){
        session_destroy();
        return redirect('login');
    }

    public function store(){
        
        $pdo = Connection::make();
        $query = new QueryBuilder($pdo);
        
        $query->insert('users',[
            'name'=>$_POST['name']
        ]);
        return redirect('users');
    }
}