<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class backend extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!isset($_SESSION)) {
            session_start();
        }
        if (isset($_POST) && isset($_POST['account']) && isset($_POST['pwd'])) {
        	if ($_POST['account'] == 'mlib' && $_POST['pwd'] == 'mlib') {
        		$_SESSION[FBAPP_ID . 'pwd'] = $_POST['pwd'];
        	}
        }
        if ($this->router->method != 'index' && !isset($_SESSION[FBAPP_ID . 'pwd'])) {
            echo "<div style='height:100px;'></div><div><center><form method='post'>帳號：<input type='text' name='account' />Password：<input type='password' name='pwd' /><input type='submit' value='Submit' /></form></center></div>";
            exit;
        }
    }
    
    public function admin() {
    	$msg = '';
    	if(isset($_REQUEST['FBAPP_ID'])){
    		$_REQUEST['id'] = 1;
    		$this->db->update('admin',$_REQUEST,array('id'=>1));
    		$msg = "Saved. ".date('Y-m-d H:i:s');
    	}
    	$admins = $this->db->get('admin')->result_array();
    	$data = $this->getFansInfo();
    	$data['admin'] = $admins[0];
    
    	$tab = "http://www.facebook.com/dialog/pagetab?app_id=".$admins[0]['FBAPP_ID']."&next=".WEB_HOST;
    	$data['tab'] = $tab;
    
    	$data['msg'] = $msg;
    	$this->template->set_template('backend');
    	$this->init_model->apply_template('backend_' . $this->router->method . '_view', $data);
    }

    private function getFansInfo() {
        $arr = array(
            'page_id' => fans_page_id,
            'page_url' => fans_page
        );
        return $arr;
    }

    public function index() {
        if (isset($_POST) && isset($_POST['account']) && isset($_POST['pwd'])) {
            if ($_POST['account'] == 'ardbeg' && $_POST['pwd'] == 'ardbeg') {
                $_SESSION[FBAPP_ID . 'pwd'] = $_POST['pwd'];
            }
        }
        if (!isset($_SESSION[FBAPP_ID . 'pwd'])) {
            echo "<div style='height:100px;'></div><div><center><form method='post'>帳號：<input type='text' name='account' />Password：<input type='password' name='pwd' /><input type='submit' value='Submit' /></form></center></div>";
            exit;
        }

        redirect("backend/user");exit;
    }
    
    public function user() {
        $data = $this->getFansInfo();
        $this->template->set_template('backend');
        $this->init_model->apply_template('backend_' . $this->router->method . '_view', $data);
    }

    public function getAjaxList(){
    	$table = $_POST['table'];
    	$params = array(
    			);
    		
    	$page = isset($_POST['page']) ? $_POST['page'] : 1;//頁數
    	$rp = isset($_POST['rp']) ? $_POST['rp'] : 10;//每頁顯示幾筆
    	$sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'name';//預設根據欄位作排列
    	$sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';//預設排列方法
    	$query = isset($_POST['query']) ? $_POST['query'] : false;//搜尋條件字串
    	$qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;//搜尋欄位
    
    	$rows = $this->db->get_where($table,$params)->result_array();
    	if($qtype && $query){
    		$query = strtolower(trim($query));
    		foreach($rows AS $key => $row){
    			if(strpos(strtolower($row[$qtype]),$query) === false){
    				unset($rows[$key]);
    			}
    		}
    	}
    		
        $sortArray = array();
        foreach($rows AS $key => $row){
            $sortArray[$key] = $row[$sortname];
        }

        $sortMethod = SORT_ASC;
        if($sortorder == 'desc'){
            $sortMethod = SORT_DESC;
        }
            
        array_multisort($sortArray, $sortMethod, $rows);
    		
    	$total = count($rows);
    	$rows = array_slice($rows,($page-1)*$rp,$rp);
    		
    	$jsonData = array('page'=>$page,'total'=>$total,'rows'=>array());
    	foreach($rows AS $row){
    			$entry = array('id'=>$row['serial_id'],
    					'cell'=>array(
    							'serial_id'=>$row['serial_id'],
    							'fbid' => $row['fbid'],
    							'fbname' => $row['fbname'],
    							'username' => $row['username'],
    							'tel' => $row['tel'],
    							'email' => $row['email'],
    					),
    			);
    
    		$jsonData['rows'][] = $entry;
    	}
    		
    	echo json_encode($jsonData);
    }

    public function logout() {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 1000000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        echo "<script type='text/javascript'>location.href='" . WEB_HOST . "index.php/backend';</script>";
    }
    
    private function APPTITLE(){
    	$fbapp_title = $this->facebook_model->getAPPTitle();
    	return $fbapp_title[0]['display_name'];
    }

    public function ajax_update($id) {
        $table = 'user_info';
        if($id==1){
	        $params = array(
	        		'exp' => $_POST['exp']
	        		);
        }else{
        	$params = array(
        			'is_publish' => $_POST['is_publish']
        	);
        }
        $where = array(
        		'serial_id' => $_POST['id']
        		);
        $success = $this->db->update($table,$params,$where);

        echo json_encode(array('success' => $success));
        exit;
    }

    public function ajax_update_index() {
        $preview = $this->uri->segment(3);

        $checkboxs = $_POST['checkboxs'];
        unset($_POST['checkboxs']);
        $answers = $_POST['answers'];
        unset($_POST['answers']);

        $_POST['id'] = $_SESSION[FBAPP_ID . 'admin']['id'];

        if ($preview != 'preview') {
            $success = $this->admin_model->update($_POST);
        }
        $success = $this->preview_admin_model->update($_POST);

        foreach ($checkboxs as $k => $c) {
            $insert_answer = array(
                'answer' => $answers[$k],
                'used' => $c
            );

            if ($preview != 'preview') {
                $success = $this->answer_model->_update($_SESSION[FBAPP_ID . 'admin']['id'], $k, $insert_answer);
            }
            $success = $this->preview_answer_model->_update($_SESSION[FBAPP_ID . 'admin']['id'], $k, $insert_answer);
        }

        echo json_encode(array('success' => $success));
        exit;
    }

    public function output() {        
        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename="user_' . date("Y_m_d_H_i_s") . '.xls"');		
		echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">'."\n";
		echo '<head><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"></head>'."\n";
		echo '<style>.xlString { mso-number-format:\\@; } </style>';		
		echo '<body>';		
		echo '<div>';
		echo '<table cellspacing="0" rules="all" border="1" id="gvReportDT" style="border-collapse:collapse;">';
		echo '<tr>';
		echo '<th scope="col">流水號id</th><th scope="col">臉書ID</th><th scope="col">臉書名字</th>';
		echo '<th scope="col">名字</th><th scope="col">電話</th>';
		echo '<th scope="col">email</th>';		
		echo '</tr>';	
        
        // $users_pre = $this->user_model->_get($_SESSION[FBAPP_ID . 'admin']['id']);

        $sql = "SELECT serial_id,fbid,fbname,username,tel,address,email from user_info";
        $users = $this->db->query($sql)->result_array();
        
        $data = '';
        foreach ($users as $k => $v) {
			$data .= "<tr>";
            $data .= "<td>" . $v['serial_id'] . "</td>";
            $data .= "<td>" . '`' . $v['fbid'] . '`' . "</td>";
            $data .= "<td>" . $v['fbname'] . "</td>";
            $data .= "<td>" . $v['username'] . "</td>";
            $data .= "<td>" . '`' . $v['tel'] . '`' . "</td>";			
            $data .= "<td>" . $v['email'] . "</td>";			
			$data .= "</tr>";			
        }
		echo $data;
		echo '</table>';
		echo '</div>';
		echo '</body>';
		echo '</html>';
		exit;
    }
    
    private function array_to_csv($fields, $delimiter = ',', $enclosure = '"') {
        $csv = '';
        foreach ($fields as $field) {
            $first_element = true;
            foreach ($field as $element) {
                // 除了第一個欄位外, 於 每個欄位 前面都需加上 欄位分隔符號
                if (!$first_element)
                    $csv .= $delimiter;

                $first_element = false;

                // CSV 遇到 $enclosure, 需要重複一次, ex: " => ""
                $element = str_replace($enclosure, $enclosure . $enclosure, $element);
                $csv .= $enclosure . $element . $enclosure;
            }
            $csv .= "\n";
        }
        return $csv;
    }

    public function ajax_delete_checked() {
        $checkboxs = $_POST['checkboxs'];
        foreach ($checkboxs as $k => $c) {
            $update = array(
                'id' => $c,
                'admin_id' => $_SESSION[FBAPP_ID . 'admin']['id'],
                'disabled' => 'Y'
            );
            $success = $this->user_model->update($update);
        }

        echo json_encode(array('success' => $success));
        exit;
    }

}

?>
