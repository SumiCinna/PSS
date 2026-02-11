<?php
session_start();
require_once '../config/config.php';

if(isset($_SESSION['user_id'])){
    header('Location: ../index.php');
    exit();
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $firstname = trim($_POST['firstname']);
    $middlename = trim($_POST['middlename']);
    $surname = trim($_POST['surname']);
    $suffix = trim($_POST['suffix']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $region = trim($_POST['region']);
    $province = trim($_POST['province']);
    $city = trim($_POST['city']);
    $barangay = trim($_POST['barangay']);
    $block_no = trim($_POST['block_no']);
    $lot_no = trim($_POST['lot_no']);
    $postal_code = trim($_POST['postal_code']);
    $mobile = trim($_POST['mobile']);
    $email = trim($_POST['email']);
    $terms_agreed = isset($_POST['terms_agreed']) ? 1 : 0;
    
    if(empty($firstname) || empty($surname) || empty($username) || empty($password) || empty($email) || empty($mobile)){
        $error = 'Please fill in all required fields';
    } elseif($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif(strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } elseif(!$terms_agreed) {
        $error = 'You must agree to the Terms & Privacy Policy';
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0){
            $error = 'Email or username already exists';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $conn->prepare("INSERT INTO users (firstname, middlename, surname, suffix, username, password, email, mobile, region, province, city, barangay, block_no, lot_no, postal_code, terms_agreed, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("sssssssssssssssi", $firstname, $middlename, $surname, $suffix, $username, $hashed_password, $email, $mobile, $region, $province, $city, $barangay, $block_no, $lot_no, $postal_code, $terms_agreed);
            
            if($stmt->execute()){
                $success = 'Registration successful! Redirecting to login...';
                header("refresh:2;url=login.php");
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Personal Shopper System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body class="min-h-screen flex items-center justify-center auth-background py-12">
    <div class="absolute inset-0 bg-gradient-to-br from-blue-900/40 to-blue-600/40"></div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-2xl mx-auto">
            <div class="text-center mb-8">
                <a href="../index.php" class="text-white text-2xl font-bold hover:text-blue-200 transition">Personal Shopper System</a>
            </div>
            
            <div class="bg-white rounded-2xl shadow-2xl p-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Sign Up Now</h2>
                
                <?php if($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <?php if($success): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="h-2 bg-blue-600 rounded-full transition-all step-indicator" id="step1"></div>
                        </div>
                        <div class="flex-1 mx-2">
                            <div class="h-2 bg-gray-200 rounded-full transition-all step-indicator" id="step2"></div>
                        </div>
                        <div class="flex-1 mx-2">
                            <div class="h-2 bg-gray-200 rounded-full transition-all step-indicator" id="step3"></div>
                        </div>
                        <div class="flex-1">
                            <div class="h-2 bg-gray-200 rounded-full transition-all step-indicator" id="step4"></div>
                        </div>
                    </div>
                    <div class="flex justify-between mt-2 text-xs text-gray-600">
                        <span>Name</span>
                        <span>Account</span>
                        <span>Address</span>
                        <span>Contact</span>
                    </div>
                </div>
                
                <form method="POST" action="" id="registerForm">
                    <div class="form-step active" id="formStep1">
                        <h3 class="text-xl font-semibold mb-4 text-gray-700">Personal Information</h3>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-gray-700 font-semibold mb-2">First Name*</label>
                                <input type="text" name="firstname" id="firstname" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-gray-700 font-semibold mb-2">Middle Name</label>
                                <input type="text" name="middlename" id="middlename" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-gray-700 font-semibold mb-2">Surname*</label>
                                <input type="text" name="surname" id="surname" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-gray-700 font-semibold mb-2">Suffix</label>
                                <select name="suffix" id="suffix" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Select</option>
                                    <option value="Jr.">Jr.</option>
                                    <option value="Sr.">Sr.</option>
                                    <option value="II">II</option>
                                    <option value="III">III</option>
                                    <option value="IV">IV</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" onclick="nextStep(1)" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg transition">
                                Next
                            </button>
                        </div>
                    </div>

                    <div class="form-step" id="formStep2">
                        <h3 class="text-xl font-semibold mb-4 text-gray-700">Account Details</h3>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Username*</label>
                            <input type="text" name="username" id="username" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Email*</label>
                            <input type="email" name="email" id="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Password*</label>
                            <div class="relative">
                                <input type="password" name="password" id="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-3 text-gray-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Confirm Password*</label>
                            <div class="relative">
                                <input type="password" name="confirm_password" id="confirm_password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <button type="button" onclick="togglePassword('confirm_password')" class="absolute right-3 top-3 text-gray-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-sm text-gray-500 mt-1" id="passwordMatch"></p>
                        </div>
                        <div class="flex justify-between">
                            <button type="button" onclick="prevStep(2)" class="bg-gray-400 hover:bg-gray-500 text-white font-semibold px-8 py-3 rounded-lg transition">
                                Back
                            </button>
                            <button type="button" onclick="nextStep(2)" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg transition">
                                Next
                            </button>
                        </div>
                    </div>

                    <div class="form-step" id="formStep3">
                        <h3 class="text-xl font-semibold mb-4 text-gray-700">Address Information</h3>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-gray-700 font-semibold mb-2">Region*</label>
                                <select name="region" id="region" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    <option value="">Select Region</option>
                                    <option value="NCR">NCR</option>
                                    <option value="Region I">Region I</option>
                                    <option value="Region II">Region II</option>
                                    <option value="Region III">Region III</option>
                                    <option value="Region IV-A">Region IV-A</option>
                                    <option value="Region IV-B">Region IV-B</option>
                                    <option value="Region V">Region V</option>
                                    <option value="Region VI">Region VI</option>
                                    <option value="Region VII">Region VII</option>
                                    <option value="Region VIII">Region VIII</option>
                                    <option value="Region IX">Region IX</option>
                                    <option value="Region X">Region X</option>
                                    <option value="Region XI">Region XI</option>
                                    <option value="Region XII">Region XII</option>
                                    <option value="Region XIII">Region XIII</option>
                                    <option value="CAR">CAR</option>
                                    <option value="BARMM">BARMM</option>
                                </select>
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-gray-700 font-semibold mb-2">Province*</label>
                                <input type="text" name="province" id="province" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-gray-700 font-semibold mb-2">City/Municipality*</label>
                                <input type="text" name="city" id="city" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-gray-700 font-semibold mb-2">Barangay*</label>
                                <input type="text" name="barangay" id="barangay" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Block No.</label>
                                <input type="text" name="block_no" id="block_no" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Lot No.</label>
                                <input type="text" name="lot_no" id="lot_no" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Postal Code*</label>
                                <input type="text" name="postal_code" id="postal_code" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <button type="button" onclick="prevStep(3)" class="bg-gray-400 hover:bg-gray-500 text-white font-semibold px-8 py-3 rounded-lg transition">
                                Back
                            </button>
                            <button type="button" onclick="nextStep(3)" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg transition">
                                Next
                            </button>
                        </div>
                    </div>

                    <div class="form-step" id="formStep4">
                        <h3 class="text-xl font-semibold mb-4 text-gray-700">Contact Information</h3>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Mobile Number*</label>
                            <input type="tel" name="mobile" id="mobile" placeholder="+63 XXX XXX XXXX" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        
                        <div class="mb-6">
                            <div class="flex items-start">
                                <input type="checkbox" name="terms_agreed" id="terms_agreed" class="mt-1 mr-3" required>
                                <label for="terms_agreed" class="text-sm text-gray-700">
                                    I agree to the <button type="button" onclick="showTerms()" class="text-blue-600 hover:underline">Terms & Privacy Policy</button>
                                </label>
                            </div>
                        </div>
                        
                        <div class="flex justify-between">
                            <button type="button" onclick="prevStep(4)" class="bg-gray-400 hover:bg-gray-500 text-white font-semibold px-8 py-3 rounded-lg transition">
                                Back
                            </button>
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-3 rounded-lg transition">
                                Register
                            </button>
                        </div>
                    </div>
                </form>
                
                <div class="mt-6 text-center">
                    <p class="text-gray-600">Already have an account? <a href="login.php" class="text-blue-600 hover:text-blue-800 font-semibold">Login</a></p>
                </div>
            </div>
        </div>
    </div>

    <div id="termsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl max-w-3xl max-h-[80vh] overflow-y-auto p-8">
            <h2 class="text-2xl font-bold mb-4">Terms & Agreement – Data Privacy & Account Registration</h2>
            
            <div class="space-y-4 text-gray-700">
                <div>
                    <h3 class="font-bold text-lg mb-2">1. Acceptance of Terms</h3>
                    <p>By creating an account and clicking "Sign Up", you confirm that you have read, understood, and agreed to these Terms & Agreement and our Data Privacy Policy.</p>
                </div>
                
                <div>
                    <h3 class="font-bold text-lg mb-2">2. Data We Collect</h3>
                    <p>Upon registration, we may collect the following personal information: First Name, Middle Name, Surname, Email Address, Contact Number, Address Details (Region, Province, City, Barangay, Street, Block, Lot, Postal Code), Account credentials (username and encrypted password). Additional information may be requested to improve service delivery or comply with legal requirements.</p>
                </div>
                
                <div>
                    <h3 class="font-bold text-lg mb-2">3. Purpose of Data Collection</h3>
                    <p>Your personal data is collected and processed for the following purposes: Account creation and authentication, Communication and notifications, Identity verification and security, System functionality and service improvement, Compliance with applicable laws and regulations.</p>
                </div>
                
                <div>
                    <h3 class="font-bold text-lg mb-2">4. Data Protection & Security</h3>
                    <p>We are committed to protecting your personal data. Reasonable administrative, technical, and organizational measures are implemented to safeguard your information against unauthorized access, alteration, disclosure, or loss. Passwords are encrypted and are not stored in plain text.</p>
                </div>
                
                <div>
                    <h3 class="font-bold text-lg mb-2">5. Data Sharing & Disclosure</h3>
                    <p>Your personal data will not be sold or shared with third parties, except in the following cases: When required by law or government authorities, When necessary to provide system functionality, With your explicit consent. All third-party services, if any, are required to comply with data protection standards.</p>
                </div>
                
                <div>
                    <h3 class="font-bold text-lg mb-2">6. User Rights</h3>
                    <p>In accordance with the Data Privacy Act of 2012 (RA 10173), you have the right to: Access your personal data, Request correction or updating of inaccurate information, Request deletion or deactivation of your account, Withdraw consent for data processing, subject to legal and operational limitations.</p>
                </div>
                
                <div>
                    <h3 class="font-bold text-lg mb-2">7. Account Responsibility</h3>
                    <p>You are responsible for maintaining the confidentiality of your account credentials and for all activities conducted under your account.</p>
                </div>
                
                <div>
                    <h3 class="font-bold text-lg mb-2">8. Changes to Terms</h3>
                    <p>We reserve the right to update or modify these Terms at any time. Users will be notified of significant changes through the platform or via email.</p>
                </div>
                
                <div>
                    <h3 class="font-bold text-lg mb-2">9. Consent</h3>
                    <p>By proceeding with registration, you voluntarily give consent to the collection, use, processing, and storage of your personal data as described above.</p>
                </div>
            </div>
            
            <button type="button" onclick="closeTerms()" class="mt-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg">
                Close
            </button>
        </div>
    </div>

    <script>
        let currentStep = 1;

        function nextStep(step) {
            if(validateStep(step)) {
                document.getElementById('formStep' + step).classList.remove('active');
                document.getElementById('step' + step).classList.remove('bg-blue-600');
                document.getElementById('step' + step).classList.add('bg-gray-200');
                
                currentStep = step + 1;
                document.getElementById('formStep' + currentStep).classList.add('active');
                document.getElementById('step' + currentStep).classList.remove('bg-gray-200');
                document.getElementById('step' + currentStep).classList.add('bg-blue-600');
            }
        }

        function prevStep(step) {
            document.getElementById('formStep' + step).classList.remove('active');
            document.getElementById('step' + step).classList.remove('bg-blue-600');
            document.getElementById('step' + step).classList.add('bg-gray-200');
            
            currentStep = step - 1;
            document.getElementById('formStep' + currentStep).classList.add('active');
            document.getElementById('step' + currentStep).classList.remove('bg-gray-200');
            document.getElementById('step' + currentStep).classList.add('bg-blue-600');
        }

        function validateStep(step) {
            if(step === 1) {
                const firstname = document.getElementById('firstname').value.trim();
                const surname = document.getElementById('surname').value.trim();
                if(!firstname || !surname) {
                    alert('Please fill in required fields: First Name and Surname');
                    return false;
                }
            } else if(step === 2) {
                const username = document.getElementById('username').value.trim();
                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value;
                const confirm = document.getElementById('confirm_password').value;
                
                if(!username || !email || !password || !confirm) {
                    alert('Please fill in all account fields');
                    return false;
                }
                if(password !== confirm) {
                    alert('Passwords do not match');
                    return false;
                }
                if(password.length < 6) {
                    alert('Password must be at least 6 characters');
                    return false;
                }
            } else if(step === 3) {
                const region = document.getElementById('region').value;
                const province = document.getElementById('province').value.trim();
                const city = document.getElementById('city').value.trim();
                const barangay = document.getElementById('barangay').value.trim();
                const postal = document.getElementById('postal_code').value.trim();
                
                if(!region || !province || !city || !barangay || !postal) {
                    alert('Please fill in all required address fields');
                    return false;
                }
            }
            return true;
        }

        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        function showTerms() {
            document.getElementById('termsModal').classList.remove('hidden');
        }

        function closeTerms() {
            document.getElementById('termsModal').classList.add('hidden');
        }

        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirm = this.value;
            const matchText = document.getElementById('passwordMatch');
            
            if(confirm === '') {
                matchText.textContent = '';
            } else if(password === confirm) {
                matchText.textContent = 'Passwords match';
                matchText.className = 'text-sm text-green-600 mt-1';
            } else {
                matchText.textContent = 'Passwords do not match';
                matchText.className = 'text-sm text-red-600 mt-1';
            }
        });

        document.getElementById('registerForm').addEventListener('submit', function(e) {
            if(!document.getElementById('terms_agreed').checked) {
                e.preventDefault();
                alert('You must agree to the Terms & Privacy Policy');
            }
        });
    </script>
</body>
</html>