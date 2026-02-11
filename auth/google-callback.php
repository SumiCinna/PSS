<?php
session_start();
require_once '../config/config.php';
require_once '../config/google-config.php';

if(!isset($_GET['code'])) {
    header('Location: login.php');
    exit();
}

$code = $_GET['code'];

$token_url = 'https://oauth2.googleapis.com/token';
$token_data = array(
    'code' => $code,
    'client_id' => GOOGLE_CLIENT_ID,
    'client_secret' => GOOGLE_CLIENT_SECRET,
    'redirect_uri' => GOOGLE_REDIRECT_URI,
    'grant_type' => 'authorization_code'
);

$ch = curl_init($token_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($token_data));
$token_response = curl_exec($ch);
curl_close($ch);

$token_info = json_decode($token_response, true);

if(isset($token_info['access_token'])) {
    $access_token = $token_info['access_token'];
    
    $user_info_url = 'https://www.googleapis.com/oauth2/v2/userinfo';
    $ch = curl_init($user_info_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer ' . $access_token
    ));
    $user_info_response = curl_exec($ch);
    curl_close($ch);
    
    $user_info = json_decode($user_info_response, true);
    
    if(isset($user_info['email'])) {
        $email = $user_info['email'];
        $google_id = $user_info['id'];
        $given_name = isset($user_info['given_name']) ? $user_info['given_name'] : '';
        $family_name = isset($user_info['family_name']) ? $user_info['family_name'] : '';
        $picture = isset($user_info['picture']) ? $user_info['picture'] : '';
        
        $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            $update_stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $update_stmt->bind_param("i", $user['id']);
            $update_stmt->execute();
            $update_stmt->close();
            
            header('Location: ../dashboard.php');
            exit();
        } else {
            $username = strtolower(str_replace(' ', '', $given_name . $family_name)) . rand(100, 999);
            $random_password = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
            
            $insert_stmt = $conn->prepare("INSERT INTO users (firstname, surname, username, password, email, mobile, region, province, city, barangay, postal_code, terms_agreed, created_at, last_login) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW(), NOW())");
            
            $default_mobile = '';
            $default_region = 'NCR';
            $default_province = 'Metro Manila';
            $default_city = 'Manila';
            $default_barangay = 'N/A';
            $default_postal = '1000';
            
            $insert_stmt->bind_param("sssssssssss", 
                $given_name, 
                $family_name, 
                $username, 
                $random_password, 
                $email, 
                $default_mobile,
                $default_region,
                $default_province,
                $default_city,
                $default_barangay,
                $default_postal
            );
            
            if($insert_stmt->execute()) {
                $new_user_id = $insert_stmt->insert_id;
                $_SESSION['user_id'] = $new_user_id;
                $_SESSION['username'] = $username;
                
                header('Location: ../dashboard.php');
                exit();
            } else {
                $_SESSION['error'] = 'Registration failed. Please try again.';
                header('Location: login.php');
                exit();
            }
            $insert_stmt->close();
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = 'Failed to get user information from Google.';
        header('Location: login.php');
        exit();
    }
} else {
    $_SESSION['error'] = 'Failed to authenticate with Google.';
    header('Location: login.php');
    exit();
}

$conn->close();
?>