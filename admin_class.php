<?php
session_start();
ini_set('display_errors', 1);
class Action
{
    private $db;

    public function __construct()
    {
        ob_start();
        include 'db_connect.php';

        $this->db = $conn;
    }
    function __destruct()
    {
        $this->db->close();
        ob_end_flush();
    }

    public function login()
    {
        extract($_POST);

        // Debug inputs
        error_log("Email: $email, Password: $password");

        $email = $this->db->real_escape_string($email);
        $password = md5($password); // Hash password to match the stored hash

        // Debug hashed password
        error_log("Hashed Password: $password");

        $qry = $this->db->query("SELECT * FROM tbl_users WHERE user_email = '$email' AND user_password = '$password'");

        if (!$qry) {
            // Log database error
            error_log("Query Error: " . $this->db->error);
            return json_encode(['status' => 'error', 'message' => 'Database error']);
        }

        if ($qry->num_rows > 0) {
            $user = $qry->fetch_assoc();
            foreach ($user as $key => $value) {
                if ($key != 'user_password' && !is_numeric($key)) {
                    $_SESSION['login_' . $key] = $value;
                }
            }
            $_SESSION['login_id'] = $user['user_id'];

            // Debug session
            error_log("Session Data: " . print_r($_SESSION, true));

            return json_encode(['status' => 'success', 'message' => 'Login successful']);
        } else {
            error_log("Invalid Credentials for Email: $email");
            return json_encode(['status' => 'error', 'message' => 'Invalid email or password']);
        }
    }



    function logout()
    {
        session_destroy();
        foreach ($_SESSION as $key => $value) {
            unset($_SESSION[$key]);
        }
        header("location:login.php");
    }

    function save_user()
    {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('user_id', 'cpass')) && !is_numeric($k)) {
                if ($k == 'user_password') {
                    $v = md5($v);
                }
                if (empty($data)) {
                    $data .= " $k='$v' ";
                } else {
                    $data .= ", $k='$v' ";
                }
            }
        }

        if (empty($user_id)) {
            $save = $this->db->query("INSERT INTO tbl_users SET $data");
        } else {
            $save = $this->db->query("UPDATE tbl_users SET $data WHERE user_id = $user_id");
        }

        if ($save) {
            return 1;
        }
    }

    function update_user()
    {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('user_id', 'cpass', 'table')) && !is_numeric($k)) {
                if ($k == 'user_password')
                    $v = md5($v);
                if (empty($data)) {
                    $data .= " $k='$v' ";
                } else {
                    $data .= ", $k='$v' ";
                }
            }
        }
        $check = $this->db->query("SELECT * FROM tbl_users where user_mail ='$email' " . (!empty($id) ? " and user_id != {$user_id} " : ''))->num_rows;
        if ($check > 0) {
            return 2;
            exit;
        }
        if (empty($id)) {
            $save = $this->db->query("INSERT INTO tbl_users set $data");
        } else {
            $save = $this->db->query("UPDATE tbl_users set $data where user_id = $user_id");
        }

        if ($save) {
            foreach ($_POST as $key => $value) {
                if ($key != 'user_password' && !is_numeric($key))
                    $_SESSION['login_' . $key] = $value;
            }
            return 1;
        }
    }
    function delete_user()
    {
        extract($_POST);
        $delete = $this->db->query("DELETE FROM tbl_users where user_id = " . $user_id);
        if ($delete)
            return 1;
    }
    // function delete_message(){
    // 	extract($_POST);
    // 	$delete = $this->db->query("DELETE FROM contact where ID = ".$id);
    // 	if($delete)
    // 		return 1;
    // }
    // function delete_categories(){
    // 	extract($_POST);
    // 	$delete = $this->db->query("DELETE FROM categories where id = ".$id);
    // 	if($delete)
    // 		return 1;
    // }
    // function save_page_img(){
    // 	extract($_POST);
    // 	if($_FILES['img']['tmp_name'] != ''){
    // 			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
    // 			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
    // 			if($move){
    // 				$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';
    // 				$hostName = $_SERVER['HTTP_HOST'];
    // 					$path =explode('/',$_SERVER['PHP_SELF']);
    // 					$currentPath = '/'.$path[1]; 
    // 					 // $pathInfo = pathinfo($currentPath); 

    // 				return json_encode(array('link'=>$protocol.'://'.$hostName.$currentPath.'/admin/assets/uploads/'.$fname));

    // 			}
    // 	}
    // }
    // function save_categories(){
    // 	extract($_POST);
    // 	$data = "";
    // 	foreach($_POST as $k => $v){
    // 		if(!in_array($k, array('id')) && !is_numeric($k)){
    // 			if(empty($data)){
    // 				$data .= " $k='$v' ";
    // 			}else{
    // 				$data .= ", $k='$v' ";
    // 			}
    // 		}
    // 	}
    // 	if(empty($id)){
    // 		$save = $this->db->query("INSERT INTO categories set $data");
    // 	}else{
    // 		$save = $this->db->query("UPDATE categories set $data where id = $id");
    // 	}

    // 	if($save)
    // 		return 1;
    // }

    // function save_survey(){
    // 	extract($_POST);
    // 	$respondent_list = implode(',', $respondent);
    // 	$data = "respondent='$respondent_list', ";
    // 	foreach($_POST as $k => $v){
    // 	  if(!in_array($k, array('id', 'respondent')) && !is_numeric($k)){
    // 		$data .= "$k='$v', ";
    // 	  }
    // 	}
    // 	$data = rtrim($data, ', ');

    // 	if(empty($id)){
    // 	  $save = $this->db->query("INSERT INTO survey_set set $data");
    // 	} else {
    // 	  $save = $this->db->query("UPDATE survey_set set $data where id = $id");
    // 	}

    // 	if($save){
    // 	  return 1;
    // 	}
    //   }

    // function delete_survey(){
    // 	extract($_POST);
    // 	$delete = $this->db->query("DELETE FROM survey_set where id = ".$id);
    // 	if($delete){
    // 		return 1;
    // 	}
    // }

    // function save_question(){
    // 	extract($_POST);
    // 		$data = " survey_id=$sid ";
    // 		$data .= ", question='$question' ";
    // 		$data .= ", instruction='$instruction' ";
    // 		$data .= ", type='$type' ";
    // 		if($type != 'textfield_s'){
    // 			$arr = array();
    // 			foreach ($label as $k => $v) {
    // 				$i = 0 ;
    // 				while($i == 0){
    // 					$k = substr(str_shuffle(str_repeat($x='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(5/strlen($x)) )),1,5);
    // 					if(!isset($arr[$k]))
    // 						$i = 1;
    // 				}
    // 				$arr[$k] = $v;
    // 			}
    // 		$data .= ", frm_option='".json_encode($arr)."' ";
    // 		}else{
    // 		$data .= ", frm_option='' ";
    // 		}
    // 	if(empty($id)){
    // 		$save = $this->db->query("INSERT INTO questions set $data");
    // 	}else{
    // 		$save = $this->db->query("UPDATE questions set $data where id = $id");
    // 	}

    // 	if($save)
    // 		return 1;
    // }
    // function delete_question(){
    // 	extract($_POST);
    // 	$delete = $this->db->query("DELETE FROM questions where id = ".$id);
    // 	if($delete){
    // 		return 1;
    // 	}
    // }
    // function action_update_qsort(){
    // 	extract($_POST);
    // 	$i = 0;
    // 	foreach($qid as $k => $v){
    // 		$i++;
    // 		$update[] = $this->db->query("UPDATE questions set order_by = $i where id = $v");
    // 	}
    // 	if(isset($update))
    // 		return 1;
    // }
    // function save_answer(){
    // 	extract($_POST);
    // 		foreach($qid as $k => $v){
    // 			$data = " survey_id=$survey_id ";
    // 			$data .= ", question_id='$qid[$k]' ";
    // 			$data .= ", user_id='{$_SESSION['login_id']}' ";
    // 			if($type[$k] == 'check_opt'){
    // 				$data .= ", answer='[".implode("],[",$answer[$k])."]' ";
    // 			}else{
    // 				$data .= ", answer='$answer[$k]' ";
    // 			}
    // 			$save[] = $this->db->query("INSERT INTO answers set $data");
    // 		}


    // 	if(isset($save))
    // 		return 1;
    // }
    // function delete_comment(){
    // 	extract($_POST);
    // 	$delete = $this->db->query("DELETE FROM comments where id = ".$id);
    // 	if($delete){
    // 		return 1;
    // 	}
    // }
}
