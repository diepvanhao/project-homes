<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "class_ajax.php";

if (isset($_POST['email'])) {
    $email = $_POST['email'];
} elseif (isset($_GET['email'])) {
    $email = $_GET['email'];
} else {
    $email = "";
}

if (isset($_POST['username'])) {
    $username = $_POST['username'];
} elseif (isset($_GET['username'])) {
    $username = $_GET['username'];
} else {
    $username = "";
}

if (isset($_POST['password'])) {
    $password = $_POST['password'];
} elseif (isset($_GET['password'])) {
    $password = $_GET['password'];
} else {
    $password = "";
}

if (isset($_POST['confirm_password'])) {
    $confirm_password = $_POST['confirm_password'];
} elseif (isset($_GET['confirm_password'])) {
    $confirm_password = $_GET['confirm_password'];
} else {
    $confirm_password = "";
}

if (isset($_POST['action'])) {
    $action = $_POST['action'];
} elseif (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = "";
}

//init ajax
$ajax = new ajax();

if ($action == "check_email") {

    $error = $ajax->checkExists('email', array('email' => $email));
    echo $error;
} elseif ($action == "check_username") {

    $error = $ajax->checkExists('username', array('username' => $username));
    echo $error;
} elseif ($action == 'check_password') {

    $error = $ajax->checkExists('confirm_password', array('password' => $password, 'confirm_password' => $confirm_password));
    echo $error;
} elseif ($action == 'deleteAgent') {

    if (isset($_POST['agent_id'])) {
        $agent_id = $_POST['agent_id'];
    } elseif (isset($_GET['agent_id'])) {
        $agent_id = $_GET['agent_id'];
    } else {
        $agent_id = "";
    }

    $result = $ajax->deleteAgent($agent_id);
    echo $result;
} elseif ($action == 'deleteHouse') {

    if (isset($_POST['house_id'])) {
        $house_id = $_POST['house_id'];
    } elseif (isset($_GET['house_id'])) {
        $house_id = $_GET['house_id'];
    } else {
        $house_id = "";
    }
    $result = $ajax->deleteHouse($house_id);
    echo $result;
} elseif ($action == 'deleteBroker') {

    if (isset($_POST['broker_id'])) {
        $broker_id = $_POST['broker_id'];
    } elseif (isset($_GET['broker_id'])) {
        $broker_id = $_GET['broker_id'];
    } else {
        $broker_id = "";
    }
    $result = $ajax->deleteBroker($broker_id);
    echo $result;
} elseif ($action == 'deleteAccount') {

    if (isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];
    } elseif (isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];
    } else {
        $user_id = "";
    }
    $result = $ajax->deleteAccount($user_id);
    echo $result;
} elseif ($action == 'edit_profile') {
    if (isset($_POST['task'])) {
        $task = $_POST['task'];
    } elseif (isset($_GET['task'])) {
        $task = $_GET['task'];
    } else {
        $task = "";
    }
    if ($task == "editName") {
        if (isset($_POST['fname'])) {
            $fname = $_POST['fname'];
        } elseif (isset($_GET['fname'])) {
            $fname = $_GET['fname'];
        } else {
            $fname = "";
        }
        if (isset($_POST['lname'])) {
            $lname = $_POST['lname'];
        } elseif (isset($_GET['lname'])) {
            $lname = $_GET['lname'];
        } else {
            $lname = "";
        }
        if (isset($_POST['password'])) {
            $password = $_POST['password'];
        } elseif (isset($_GET['password'])) {
            $password = $_GET['password'];
        } else {
            $password = "";
        }

        $result = $ajax->editName($fname, $lname, $password);
        echo $result;
    }
    if ($task == "editUsername") {
        if (isset($_POST['username'])) {
            $username = $_POST['username'];
        } elseif (isset($_GET['username'])) {
            $username = $_GET['username'];
        } else {
            $username = "";
        }
        if (isset($_POST['password'])) {
            $password = $_POST['password'];
        } elseif (isset($_GET['password'])) {
            $password = $_GET['password'];
        } else {
            $password = "";
        }
        $result = $ajax->editUsername($username, $password);
        echo json_encode($result);
    }
    if ($task == 'editEmail') {
        if (isset($_POST['email'])) {
            $email = $_POST['email'];
        } elseif (isset($_GET['email'])) {
            $email = $_GET['email'];
        } else {
            $email = "";
        }
        if (isset($_POST['password'])) {
            $password = $_POST['password'];
        } elseif (isset($_GET['password'])) {
            $password = $_GET['password'];
        } else {
            $password = "";
        }
        $result = $ajax->editEmail($email, $password);
        echo json_encode($result);
    }
    if ($task == 'editPassword') {

        if (isset($_POST['current_password'])) {
            $current_password = $_POST['current_password'];
        } elseif (isset($_GET['current_password'])) {
            $current_password = $_GET['current_password'];
        } else {
            $current_password = "";
        }
        if (isset($_POST['new_password'])) {
            $new_password = $_POST['new_password'];
        } elseif (isset($_GET['new_password'])) {
            $new_password = $_GET['new_password'];
        } else {
            $new_password = "";
        }
        if (isset($_POST['re_new_password'])) {
            $re_new_password = $_POST['re_new_password'];
        } elseif (isset($_GET['re_new_password'])) {
            $re_new_password = $_GET['re_new_password'];
        } else {
            $re_new_password = "";
        }

        $result = $ajax->editPassword($current_password, $new_password, $re_new_password);
        echo json_encode($result);
    }
    if ($task == 'editPhoto') {

        if (isset($_POST['password'])) {
            $password = $_POST['password'];
        } elseif (isset($_GET['password'])) {
            $password = $_GET['password'];
        } else {
            $password = "";
        }
        if (isset($_POST['photoname'])) {
            $photoname = $_POST['photoname'];
        } elseif (isset($_GET['photoname'])) {
            $photoname = $_GET['photoname'];
        } else {
            $photoname = "";
        }
        $result = $ajax->editPhoto($photoname, $password);
        echo json_encode($result);
    }
} elseif ($action == 'create_order') {
    if (isset($_POST['task'])) {
        $task = $_POST['task'];
    } elseif (isset($_GET['task'])) {
        $task = $_GET['task'];
    } else {
        $task = "";
    }
    if ($task == "getHouseSearch") {
        if (isset($_POST['search'])) {
            $search = $_POST['search'];
        } elseif (isset($_GET['search'])) {
            $search = $_GET['search'];
        } else {
            $search = "";
        }
        $result = $ajax->getHouseByKey($search);
        if ($result) {
            for ($i = 0; $i < count($result); $i++) {
                echo "<option value='{$result[$i]['id']}'>{$result[$i]['house_name']}</option>";
            }
        }
    }
    if($task=='getContentHouse'){
        
         if (isset($_POST['house_id'])) {
            $house_id = $_POST['house_id'];
        } elseif (isset($_GET['house_id'])) {
            $house_id = $_GET['house_id'];
        } else {
            $house_id = "";
        }
         $result = $ajax->getHouseContent($house_id);
         echo $result['house_description'];
        
    }
    
}elseif ($action == 'deleteClient') {

    if (isset($_POST['id'])) {
        $id = $_POST['id'];
    } elseif (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        $id = "";
    }
    include 'class_client.php';
    $client = new Client();
    echo $client->delete($id);
} 


