<?php

//Hàm gọi phương thức HTTP_POST tạo request truyền giá trị về endpoint
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

function dicom_service_runtest()
{
	//Thiết lập endpoint
  $url_token = 'http://vanphong.vtranet.com:8083/dicom_intranet/dicom-equipment-api/user/token.json';
  $url_login = 'http://vanphong.vtranet.com:8083/dicom_intranet/dicom-equipment-api/user/login.json';
  $url_create = 'http://vanphong.vtranet.com:8083/dicom_intranet/dicom-equipment-api/equipmentlog';
  $user_name = '';
  $password = '';
  
  //Lấy token để kết nối
  $json_token = dicom_service_testpostCurl($url_token);
  $obj_token = json_decode($json_token);
  if ($obj_token && isset($obj_token->token))
  {
  	//Đăng nhập bằng tài khoản có quyền tạo log và sử dụng API
    $token = $obj_token->token;

    //Tạo data chứa thông tin đăng nhập tài khoản
    $data_login = array('username' => $username, 'password' => $password);
    $json_data_login = json_encode($data_login);
    
    //Tạo header
    $header_login = array();
    $header_login[] = 'Content-Type: application/json';
    $header_login[] = 'X-CSRF-Token: '.$token;
    
    $result_login = dicom_service_testpostCurl($url_login,$json_data_login,$header_login);  
    $obj_cookie = json_decode($result_login); 
   
   if ($obj_cookie && isset($obj_cookie->sessid) && isset($obj_cookie->session_name) && isset($obj_cookie->token))
    {
    	//Tạo data chứa tham số tạo log thiết bị
      $input_data = array(
        'equipment_id' => 24652,
        'error_type' => 'HIGH_TEMP', 
        'status' => TRUE,
        'cpu' => 'CHAOS CORE 1.4',
        'temperature' => 12,
        'uptime' => 123,
        'downloadspeed' =>5421,
        'uploadspeed' => 4321,
        'totalsize' => 2247,
        'freespace' => 7792,
      );
      $json_data_input = json_encode($input_data);

      //Tạo header
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
?>