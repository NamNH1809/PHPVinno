<?php

//Ham goi phuong thuc HTTP_POST tao request truyen gia tri ve endpoint
function dicom_service_testpostCurl($url, $params=array(), $header= array()) 
{    
    if (is_array($header))
    { 
      if (!(in_array('Content-Type: application/json', $header)))
      {
        $header[] = 'Content-Type: application/json';
      } 
    }
    else
      $header = array('Content-Type: application/json');
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    $data = curl_exec($ch);
    if ($data) {
        curl_close($ch);
        return $data;
    } else {
        $error = curl_error($ch);
        curl_close($ch);
        throw new Exception($error, 0);
    }
}

// Them equipment log
function dicom_service_runtest_add_equipment_log()
{
	//Thiet lap endpoint
  $url_token = 'http://27.72.146.18:8080/dicom_intranet/dicom-equipment-api/user/token.json';
  $url_login = 'http://27.72.146.18:8080/dicom_intranet/dicom-equipment-api/user/login.json';
  $url_create = 'http://27.72.146.18:8080/dicom_intranet/dicom-equipment-api/equipmentlog';

  $user_name = '';
  $password = '';
  
  //Lay token de ket noi
  $json_token = dicom_service_testpostCurl($url_token);
  $obj_token = json_decode($json_token);
  if ($obj_token && isset($obj_token->token))
  {
    //Dang nhap bang tai khoan co quyen su dung API
    $token = $obj_token->token;

    //Tao data chua thong tin dang nhap tai khoan
    $data_login = array('username' => $user_name, 'password' => $password);
    $json_data_login = json_encode($data_login);
    
    //Tao header
    $header_login = array();
    $header_login[] = 'Content-Type: application/json';
    $header_login[] = 'X-CSRF-Token: '.$token;
    
    $result_login = dicom_service_testpostCurl($url_login,$json_data_login,$header_login);  
    $obj_cookie = json_decode($result_login); 
   
   if ($obj_cookie && isset($obj_cookie->sessid) && isset($obj_cookie->session_name) && isset($obj_cookie->token))
    {
      //Tao data chua tham so tao log thiet bi
      $input_data = array(        
        'equipment_ip' => '192.168.1.168',
        'error_type' => 'HIGH_TEMP', 
        'status' => TRUE,
        'cpu' => '1.4',
        'temperature' => 12,
        'uptime' => 123,
        'downloadspeed' =>5421,
        'uploadspeed' => 4321,
        'totalsize' => 2247,
        'freespace' => 7792,
        'mac_address' => '192.168.1.254',
      );
      $json_data_input = json_encode($input_data);

      //Tao header
      $header_input = array();
      $header_input[] = 'Content-Type: application/json';
      $header_input[] = 'X-CSRF-Token:'.$obj_cookie->token;
      $header_input[] = 'Cookie: '.$obj_cookie->session_name.'='.$obj_cookie->sessid;

      $result_input = dicom_service_testpostCurl($url_create,$json_data_input,$header_input);
      $arr_result = json_decode($result_input);
      echo $arr_result[0];
    }
  }  
}

// Lay danh sach thiet bi
function dicom_service_runtest_list_equipment()
{
  $//Thiet lap endpoint
  $url_token = 'http://27.72.146.18:8080/dicom_intranet/dicom-equipment-api/user/token.json';
  $url_login = 'http://27.72.146.18:8080/dicom_intranet/dicom-equipment-api/user/login.json';
  $url_create = 'http://27.72.146.18:8080/dicom_intranet/dicom-equipment-api/equipmentlog';

  $user_name = '';
  $password = '';
  
  //Lay token de ket noi
  $json_token = dicom_service_testpostCurl($url_token);
  $obj_token = json_decode($json_token);
  if ($obj_token && isset($obj_token->token))
  {
    //Dang nhap bang tai khoan co quyen su dung API
    $token = $obj_token->token;

    //Tao data chua thong tin dang nhap tai khoan
    $data_login = array('username' => $user_name, 'password' => $password);
    $json_data_login = json_encode($data_login);
    
    //Tao header
    $header_login = array();
    $header_login[] = 'Content-Type: application/json';
    $header_login[] = 'X-CSRF-Token: '.$token;
    
    $result_login = dicom_service_testpostCurl($url_login,$json_data_login,$header_login);  
    $obj_cookie = json_decode($result_login); 
   
   if ($obj_cookie && isset($obj_cookie->sessid) && isset($obj_cookie->session_name) && isset($obj_cookie->token))
    {
      //Tao data chua tham so tao log thiet bi
      // Neu lay tat ca thiet bi thi array khong co value nao
      // Neu tim thiet bi theo type thi them 'type' => 'CWP', 
      $input_data = array( 
        //'type' => 'CWP', 
      );
      $json_data_input = json_encode($input_data);

      //Tao header
      $header_input = array();
      $header_input[] = 'Content-Type: application/json';
      $header_input[] = 'X-CSRF-Token:'.$obj_cookie->token;
      $header_input[] = 'Cookie: '.$obj_cookie->session_name.'='.$obj_cookie->sessid;

      $result_input = dicom_service_testpostCurl($url_create,$json_data_input,$header_input);
      $arr_result = json_decode($result_input);
      print_r($arr_result);
    }
  }  
}
?>
