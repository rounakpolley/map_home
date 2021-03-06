<!-- un-comment 95 and comment line 94 to enable markers on the map-->

<?php session_start();
    $login_err = false;
    require_once('dbconnection.php');
    if(isset($_SESSION['id'])){
        if($_SESSION['id'] == -1){
            echo "<script>alert('Invalid username or password');</script>";
            $_SESSION['id'] = 0;
            header("Refresh:0");
        }   
    }
    //Code for Registration 
    if(isset($_POST['registration-form-submit'])){
        //echo "<script>alert('Registering');</script>";
        $username       =$_POST['register-name'];
        $email          =$_POST['register-email'];
        $password       =$_POST['register-password'];
        $location       =$_POST['register-location'];
        $mobile         =$_POST['register-mobile'];
        $dob            =$_POST['register-dob'];
        $blood_group    =$_POST['register-bloodgroup'];
        $weight         =$_POST['register-weight'];
        $last_donation  =$_POST['register-last_donation'];
        
        $enc_password   =md5($password);
        
        $msg = mysqli_query($con,"insert into users(username,email,password,location,mobile,dob,blood_group,weight,last_donation) values('$username','$email','$enc_password','$location','$mobile','$dob','$blood_group','$weight','$last_donation')");
        
        
        if($msg){
            //echo "<script>alert('Register successfully');</script>";
            $extra              ="dashboard.php";
            $_SESSION['login']  =$_POST['register-email'];
            //$_SESSION['id']     =$num['id'];
            $host=$_SERVER['HTTP_HOST'];
            $uri=rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
            header("location:http://$host$uri/$extra");
            exit();
        }
    }

    // Code for login
    if(isset($_POST['login-form-submit'])){
        
        $useremail      =$_POST['login-email'];
        $password       =$_POST['login-password'];
        $dec_password   =md5($password);
        $ret = mysqli_query($con,"SELECT * FROM users WHERE email='$useremail' and password='$dec_password'");
        $num = mysqli_fetch_array($ret);
        
        if($num>0){
            
            $extra="dashboard.php";
            $_SESSION['login']  =$_POST['login-email'];
            $_SESSION['id']     =$num['id'];
            $host=$_SERVER['HTTP_HOST'];
            $uri=rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
            header("location:http://$host$uri/$extra");
            exit();
        }
        else{
            $extra="index.php";
            $host  = $_SERVER['HTTP_HOST'];
            $uri  = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
            $_SESSION['id'] = -1;
            header("location:http://$host$uri/$extra");                
            exit();
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Raqtdaan</title>
    <link href="favicon.ico" rel="shortcut icon" type="image/x-icon">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js'></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/home.css" type="text/css">
    <link rel="stylesheet" href="styles/responsive.css" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Slab:300|Oswald|Rokkitt|Ubuntu" rel="stylesheet">
    <link rel="stylesheet" href="templated-intensify/assets/css/font-awesome.min.css" />
    <link rel="stylesheet" href="/templated-intensify/assets/css/main.css" />
    
    <script src="scripts/home.js" type="text/javascript"></script>
    <script src="scripts/map.js" type="text/javascript"></script>
    <!--<script src="scripts/map_markers.js" type="text/javascript"></script>-->
    <script async defer 
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCGAuXcfGc1ye7Tk85s1uLXi7Op4ak2gl8&callback=initMap"
        type="text/javascript">
    </script>
</head>
<body>
    
    <div id="background-map"></div>
    
    <nav class="navbar navbar-inverse" data-spy="affix" data-offset-top="49">
        <div class="container-fluid">
            <div class="navbar-header"><!-- always present in nav-->
                <button type="button" class="navbar-toggle" data-toggle="slide-collapse" data-target="#startup_nav">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>                        
                </button>
                <a class="navbar-brand" href="#">
                    <span class="name"><img id="logo" src="logo.png"></span>
                </a>
            </div>
            <div id="nav-toggle-list"><!-- navbar part that gets hidden -->
                <div class="collapse navbar-collapse navbar-right" id="startup_nav">
                    <ul class="nav navbar-nav">
                        <li id="nav-register">
                            <a href="#"><button type="button" class="btn btn-danger">Be a Donor</button></a>
                        </li>
                        <li id="nav-team">
                            <a href="#"><button type="button" class="btn btn-success">Team</button></a>
                        </li>
                        <li id="nav-faq">
                            <a href="#"><button type="button" class="btn btn-info">FAQ</button></a>
                        </li>
                        <li id="nav-user">
                            <a href="#"><button type="button" class="btn btn-warning">User</button></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>    

    <div id="sidetray" class="sidenav">
        <button id="donors"             type="button" class="btn btn-warning sidenav-content">Donors</button>
         <div id="donors-contents" class="btn-group btn-group-lg sidetray-contents hidden" role="group"><!--hidden-->
          <button type="button" class="btn btn-primary">O-</button>
          <button type="button" class="btn btn-primary">O+</button>
          <button type="button" class="btn btn-primary">A-</button>
          <button type="button" class="btn btn-primary">A+</button>
          <button type="button" class="btn btn-primary">B-</button>
          <button type="button" class="btn btn-primary">B+</button>
          <button type="button" class="btn btn-primary">AB-</button>
          <button type="button" class="btn btn-primary">AB+</button>
        </div>

        <button id="blood_banks"        type="button" class="btn btn-success sidenav-content">Blood Banks</button>
        <div id="blood_banks-contents" class="btn-group btn-group-lg sidetray-contents hidden" role="group">
          <button type="button" class="btn btn-primary">Link to blood banks</button>
        </div>

        <button id="camps"              type="button" class="btn btn-info sidenav-content">Camps</button>
        <div id="camps-contents" class="btn-group btn-group-lg sidetray-contents hidden" role="group">
          <button type="button" class="btn btn-primary">Location of blood donation camps</button>
        </div>
        
        <button id="request_for_blood"  type="button" class="btn btn-danger sidenav-content">Existing<br/>requests</button>
        <div id="request_for_blood-contents" class="btn-group btn-group-lg sidetray-contents hidden" role="group">
          <button type="button" class="btn btn-primary">Requests for blood shown here but how?</button>
        </div>
    </div>
    
<!-- add scroll -->
    <div id="donor-register" class="modal">
        <div class="modal-content">
            <form id="registration-form" method="post" action="" enctype="multipart/form-data">
                <header id="registration-header"> -- Register <small>to</small> Save Lives -- </header>

                    <label class="sr-only" for="register-name">Name : </label>
                <input type="text" name="register-name" id="register-name" placeholder="Name" required autofocus />

                    <label class="sr-only" for="register-email">Email : </label>
                 <input type="email" name="register-email" id="register-email" placeholder="Email" required />

                    <label class="sr-only" for="register-password">Password : </label>
                <input type="password" name="register-password" id="register-password" placeholder="Password" required />

                    <label class="sr-only" for="register-location">Location : </label>
                <input type="text" name="register-location" id="register-location" placeholder="Location" required />
                    
                    <label class="sr-only" for="register-mobile">Mobile : </label>
                <input type="tel" name="register-mobile" id="register-mobile" placeholder="Mobile" required />

                    <label class="sr-only" for="register-dob">D.O.B : </label>
                <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" name="register-dob" 
                        id="register-dob" placeholder="D.O.B" required />

                    <label class="sr-only" for="register-bloodgroup">Blood Group : </label>
                <select id="register-bloodgroup"  name="register-bloodgroup" class="classic btn btn-default">
                    <option value="" disabled>Blood Group</option>
                    <option value="O−">O−</option>
                    <option value="O+">O+</option>
                    <option value="A−">A−</option>
                    <option value="A+">A+</option>
                    <option value="B−">B−</option>
                    <option value="B+">B+</option>
                    <option value="AB−">AB−</option>
                    <option value="AB+">AB+</option>
                </select>

                    <label class="sr-only" for="register-weight">Weight : </label>
                <input type="number" name="register-weight" id="register-weight" placeholder="Weight" required />

                    <label class="sr-only" for="register-last_donation">Last Donation : </label>
                <input name="register-last_donation" id="register-last_donation" placeholder="Last Donation"  required
                        type="text" onfocus="(this.type='date')" onblur="(this.type='text')" name="register-last_donation" id="register-last_donation"  />

                <button id="registration-form-submit" type="submit" name="registration-form-submit" value="registration-form-submit">Signup</button>
            </form>
        </div>
    </div>
    <div id="user-login" class="modal">
        <div class="modal-content">
            <form id="login-form" method="post" action="" enctype="multipart/form-data">
                <header id="login-header"> -- Login -- </header>

                    <label class="sr-only" for="login-email">Email : </label>
                 <input type="email" name="login-email" id="login-email" placeholder="Email" required />

                    <label class="sr-only" for="login-password">Password : </label>
                <input type="password" name="login-password" id="login-password" placeholder="Password" required />

                <button id="login-form-submit" type="submit" name="login-form-submit" 
                        value="login-form-submit">Login</button>
            </form>
        </div>
    </div>
    <!-- Country State City Blood Group Units Send Request -->
    <footer class="footer container-fluid">
        <h4>You Need Blood ...  Search Here ...</h4>
        <hr />
        <form id="blood-request">
            <div id="request-state" class="btn-group dropup">
                <button type="button" class="btn btn-success">State</button>
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Select State</span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#">West Bengal</a></li>
                    <li><a href="#">Orissa</a></li>
                </ul>
            </div>
            <div id="request-city" class="btn-group dropup">
                <button type="button" class="btn btn-success">City</button>
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Select City</span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#">Kolkata</a></li>
                    <li><a href="#">Mumbai</a></li>
                </ul>
            </div>
            <div id="request-group" class="btn-group dropup">
                <button type="button" class="btn btn-success">Blood Group</button>
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Select Blood Group</span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#">O-</a></li>
                    <li><a href="#">O+</a></li>
                    <li><a href="#">A-</a></li>
                    <li><a href="#">A+</a></li>
                    <li><a href="#">B-</a></li>
                    <li><a href="#">B+</a></li>
                    <li><a href="#">AB-</a></li>
                    <li><a href="#">AB+</a></li>
                </ul>
            </div>
            <div id="request-units" class="btn-group dropup">
                <button type="button" class="btn btn-success">Units</button>
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Select Units</span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#">10</a></li>
                    <li><a href="#">20</a></li>
                </ul>
            </div>
            <button type="submit" class="btn btn-danger">Send Request</button>
            <ul>
            <li>            
                <a href="#" class="button fit">Small</a>
            </li>
            </ul>
            <!-- &nbsp;  &nbsp; Send Request &nbsp;  &nbsp; -->
        </form>
    </footer>

</body>
</html>