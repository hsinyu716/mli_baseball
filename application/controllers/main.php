<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Main extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('gd_creater');
		$this->gd_creater->delimg();
	}

	public function setask() {
		$_SESSION['have_ask'] = '1';
		redirect('/main/index', 'refresh');
	}

	public function delete_ask() {
		unset($_SESSION['have_ask']);
	}

	public function ask() {
		$this->load->view('ask_view');
	}

	private function _getBaseData(){
		$fansInfoArray = $this->getFansInfo();
		$data = array(
				'fb_title' => $this->APPTITLE(),
				'page_id' => $fansInfoArray['page_id'],
				'page_url' => $fansInfoArray['page_url'],
				'images' => $this->preload_images()
		);
		return $data;
	}

	public function index() {
		// 		var_dump($_GET['t']);exit;
		$data = $this->_getBaseData();
		// 		$friends = $this->facebook_model->get_friends_list();
		// 		$fids = array();
		// 		foreach($friends as $fk=>$fv){
		// 			$fids[] = $fv['uid'];
		// 		}
		// 		echo (implode(',',$fids));

		$this->init_model->apply_template_with_ga($this->router->method . '_view', $data);
	}
	
	public function mobile(){
		$data = $this->_getBaseData();
		$this->init_model->apply_template_with_ga($this->router->method . '_view', $data);
	}
	
	public function check_user(){
		$fbid = $this->facebook->getUser();
		$table = 'user_info';
		$params = array(
				'fbid' => $fbid,
				'is_join' => 'Y'
		);
		$rs = $this->db_md->getCount($table,$params);
		$success = false;
		if($rs==1){
			$success = true;
		}
		$json = array(
				'success' => $success
				);
		echo json_encode($json);
	}

	public function select_b(){
		$data = $this->_getBaseData();

		

		$this->init_model->apply_template_with_ga($this->router->method . '_view', $data);
	}

	public function select() {
		$data = $this->_getBaseData();

		// 		$position = array('補手-智慧無敵鐵金剛','一壘手-人高馬大鐵漢子','二壘手-神秘內野魔術師','三壘手-沉著穩當鐵布衫','左外野手-成熟穩健大好人',
		// 				'右外野手-侵略積極飛毛腿','啦啦隊-活力充沛好身手','總教練-領導眾人扛勝負','吉祥物-蹦蹦跳跳開心果');

		$position = array('補手-catch9','一壘手-sacker7','二壘手-sacker6','三壘手-sacker5','左外野手-sacker2',
				'右外野手-sacker4','啦啦隊-lala','總教練-coach','吉祥物-mascot3');

		$posi_title = array();
		$posi_class = array();
		foreach($position as $pk=>$pv):
		$tmp = explode('-',$pv);
		$posi_title[] = $tmp[0];
		$posi_class[] = $tmp[1];
		endforeach;

		$data['position'] = json_encode($position);
		$data['posi_title'] = json_encode($posi_title);
		$data['posi_class'] = json_encode($posi_class);

		$friends = $this->facebook_model->get_friends_list();
		$data['friends'] = $friends;
		$fbid = $this->facebook->getUser();
		$data['fbid'] = $fbid;

		$this->init_model->apply_template_with_ga($this->router->method . '_view', $data);
	}

	public function test(){
		$t = array(
				'q' => '若有塊地是養老用的房子,你會蓋在哪？',
				'op' => array(
						array(
								'title' => 'Ａ. 靠近湖邊',
								'score' => '8'
						),
						array(
								'title' => 'Ｂ. 靠近河邊',
								'score' => '15'
						),
						array(
								'title' => 'Ｃ. 深山內',
								'score' => '6'
						),
						array(
								'title' => 'Ｄ. 森林',
								'score' => '10'
						)
				)
		);
		echo json_encode($t);
	}

	public function result() {
			
		$fbid = $this->facebook->getUser();
		$data = $this->_getBaseData();
		$data['fbid'] = $fbid;

// 		$cates = $this->cate_model->get();
		
		if(!empty($_POST['frie']) && $_POST['frie'][0]==0):
			//抓資料
			$st = strtotime('2014-01-01');
			$fql = "select post_id ,comment_info.comment_count from stream where source_id = me() and created_time > ".$st."  and app_id < 0 limit 50";
			$param = array('method' => 'fql.query','query' => $fql,'callback' => '');
			$streams = $this->facebook->api($param);
			// var_dump($streams);
			$fbids = array();
			foreach($streams as $sk=>$sv):
				if($sv['comment_info']['comment_count']>0):
					$fql = "select fromid from comment where post_id = '".$sv['post_id']."'";
					$param = array('method' => 'fql.query','query' => $fql,'callback' => '');
					$comments = $this->facebook->api($param);
					foreach($comments as $ck=>$cv):
						if($cv['fromid']!=$fbid):
							if(empty($fbids[$cv['fromid'].'_'])):
								$fbids[$cv['fromid'].'_'] = 1;
							else:
								$fbids[$cv['fromid'].'_']++;
							endif;
						endif;
					endforeach;
				endif;
			endforeach;
			arsort($fbids);
	// 				var_dump($fbids);exit;
	
			$result_fbid = array_slice($fbids, 0, 9);
			$result_fbids = array();
			foreach($result_fbid as $rk=>$rv):
			$result_fbids[] = str_replace('_','',$rk);
			endforeach;
			
	// 		var_dump($result_fbid);exit;
			$fids = array();
				
			foreach($result_fbids as $rk=>$rv):
			$fids[] = $rv;
			endforeach;
				
			if(sizeof($fids)<9){
				$friends = $this->facebook_model->get_friends_list();
				foreach($friends as $fk=>$fv):
				if(sizeof($fids)<9 && !in_array($fv['uid'],$fids)){
					$fids[] = $fv['uid'];
				}
				endforeach;
			}
	// 		var_dump($fids);exit;
			$fusers = array();
			foreach($fids as $fk=>$fv):
			$tu = $this->facebook_model->getUser($fv);
			$fusers[] = $tu;
			endforeach;
		elseif(!empty($_POST['frie'])):
			$fids = $_POST['frie'];
			foreach($fids as $fk=>$fv):
			$tu = $this->facebook_model->getUser($fv);
			$fusers[] = $tu;
			endforeach;
		elseif(empty($_POST['frie'])):
			$table = 'tag_record';
			$params = array(
					'fbid' => $fbid
			);
			$fids = $this->db_md->getData($table,$params);
			$fusers = array();
			foreach($fids as $fk=>$fv):
			$tu = $this->facebook_model->getUser($fv['tofbid']);
			$tu['message'] = $fv['message'];
			$fusers[] = $tu;
			endforeach;
		endif;

		// 		var_dump($data);exit;

		// 		$paste_e = array(
		// 				array(142,68),
		// 				array(233,68),
		// 				array(322,80)
		// 		);

		$user = $this->facebook_model->getUser($fbid);
		$data['user'] = $user;
		$filename = 'tmp/'.$fbid.'.jpg';
		copy($user['pic_big'],$filename);
		$img_array = array();
		$text_array = array();
		$img_array[] = array(
				'filename' => $filename,
				'imgw' => 64,
				'imgh' => 64,
				'imgx' => 242,
				'imgy' => 252,
				'makep_color' => '255,255,255',
				'resize' => false, //png要設為false
				'border' => 0, //png不可有border
				'borderw' => 0,
				// 					'del' => 'N'
		);

		$text_array[] = array(
				'text' => $user['name'],
				'font_type' => 'font/msjhbd.ttf',
				'fontsize' => 14,
				'fontcolor' => '0,0,0',
				'tx' => 10,
				'ty' => 50,
				'isp' => 4,
				'tx1' => 160,
				'ty1' => 0,
				'br' => 'N',
				'brcnt' => 20
		);
		
		$posi = array(
				array(155,410), //補手
				array(444,174), //一壘
				array(233,96), //二壘
				array(20,200), //三壘
				array(131,123), //左外
				array(337,123), //右外
				array(325,410), //lala
				array(72,350), //教練
				array(436,345), //吉祥物
		);
		foreach($fusers as $fk=>$fv){
			$filename = 'tmp/'.$fv['uid'].'.jpg';
			copy($fv['pic_big'],$filename);

			$img_array[] = array(
					'filename' => $filename,
					'imgw' => 64,
					'imgh' => 64,
					'imgx' => $posi[$fk][0],
					'imgy' => $posi[$fk][1],
					'makep_color' => '255,255,255',
					'resize' => false, //png要設為false
					'border' => 0, //png不可有border
					'borderw' => 0,
					// 					'del' => 'N'
			);
		}

		$filename = 'img/wall.png';
		$img_array[] = array(
				'filename' => $filename,
				'imgw' => 520,
				'imgh' => 520,
				'imgx' => 0,
				'imgy' => 0,
				'makep_color' => '255,255,255',
				'resize' => false, //png要設為false
				'border' => 0, //png不可有border
				'borderw' => 0,
				'del' => 'N'
		);

		$merge_data = array(
				'fbid' => $fbid,
				'bg' => 'img/wall.png',
				'bw' => 520,
				'bh' => 520,
				'img_array' => $img_array,
				'text_array' => $text_array,
				'output' => MERGE_PATH.$fbid.'_wall.jpg'
		);
		$this->gd_creater->merge($merge_data);
		// 		echo '<img src="'.MERGE_PATH.$fbid.'_wall.jpg"/>';

		$table = 'user_info';
		$params = array(
				'fbid' => $fbid,
				'is_join' => 'Y'
		);
		$rs = $this->db_md->getCount($table,$params);
		$data['count'] = $rs;

		###文案
		$position = array('補手-catch9','一壘手-sacker7','二壘手-sacker6','三壘手-sacker5','左外野手-sacker2',
		'右外野手-sacker4','啦啦隊-lala','總教練-coach','吉祥物-mascot3');

		$posi_title = array();
		$posi_class = array();
		foreach($position as $pk=>$pv):
		$tmp = explode('-',$pv);
		$posi_title[] = $tmp[0];
		$posi_class[] = $tmp[1];
		$fusers[$pk]['class'] = $tmp[1];
		if(empty($fusers[$pk]['message'])):
		$fusers[$pk]['message'] = '';
		endif;
		endforeach;

		foreach($fusers as $fk=>$fv){
			$img_array = array();
			$text_array = array();
			$filename = 'tmp/'.$fv['uid'].'.jpg';
			copy('https://graph.facebook.com/'.$fv['uid'].'/picture?width=159&height=159',$filename);

			$img_array[] = array(
					'filename' => $filename,
					'imgw' => 159,
					'imgh' => 159,
					'imgx' => 152,
					'imgy' => 202,
					'makep_color' => '255,255,255',
					'resize' => false, //png要設為false
					'border' => 0, //png不可有border
					'borderw' => 0,
					// 					'del' => 'N'
			);

			$text_array[] = array(
					'text' => $fv['name'],
					'font_type' => 'font/msjhbd.ttf',
					'fontsize' => 14,
					'fontcolor' => '0,0,0',
					'tx' => 310,
					'ty' => 230,
					'isp' => 4,
					'tx1' => 160,
					'ty1' => 0,
					'br' => 'N',
					'brcnt' => 20
			);

			$merge_data = array(
					'fbid' => $fbid,
					'bg' => 'img/wall-'.$fv['class'].'.jpg',
					'bw' => 600,
					'bh' => 600,
					'img_array' => $img_array,
					'text_array' => $text_array,
					'output' => MERGE_PATH.$fbid.$fv['uid'].'_wall.jpg'
			);
			$this->gd_creater->merge($merge_data);
			// 			echo '<img src="'.WEB_HOST.MERGE_PATH.$fbid.$fv['uid'].'_wall.jpg"/>';
		}

		$_SESSION[$fbid.'fuser'] = $fusers;
		$data['fuser'] = $fusers;
		$data['fids'] = $fids;

		$this->init_model->apply_template_with_ga($this->router->method . '_view', $data);
	}
	
	public function result2($fbid) {
			
		$data = $this->_getBaseData();
		$data['fbid'] = $fbid;
		$user = $this->facebook_model->getUser($fbid);
		$data['user'] = $user;
		
		$table = 'tag_record';
		$params = array(
				'fbid' => $fbid
		);
		$fids = $this->db_md->getData($table,$params);
		$fusers = array();
		foreach($fids as $fk=>$fv):
		$tu = $this->facebook_model->getUser($fv['tofbid']);
		$tu['message'] = $fv['message'];
		$fusers[] = $tu;
		endforeach;
		###文案
		$position = array('補手-catch9','一壘手-sacker7','二壘手-sacker6','三壘手-sacker5','左外野手-sacker2',
		'右外野手-sacker4','啦啦隊-lala','總教練-coach','吉祥物-mascot3');
;
		$posi_title = array();
		$posi_class = array();
		foreach($position as $pk=>$pv):
		$tmp = explode('-',$pv);
		$posi_title[] = $tmp[0];
		$posi_class[] = $tmp[1];
		$fusers[$pk]['class'] = $tmp[1];
		if(empty($fusers[$pk]['message'])):
		$fusers[$pk]['message'] = '';
		endif;
		endforeach;
	
		$_SESSION[$fbid.'fuser'] = $fusers;
		$data['fuser'] = $fusers;
		$data['fids'] = $fids;
	
		$this->init_model->apply_template_with_ga($this->router->method . '_view', $data);
	}

	public function share(){
		$position = array(
				'catch9'=>'智慧無敵鐵金剛
				{user}就像捕手一樣，是你的最好搭檔，始終陪伴在你身邊。擁有敏銳的觀察力，指引著你的方向，異於常人的靈活頭腦及反應，為你解決一道道難題',
				'sacker7'=>'人高馬大鐵漢子
				{user}就像一壘手一樣，通常是眾人中最顯眼的那一個。負責有擔當的他，常為你阻擋突如其來的危險，守住重要的第一道防線。有他在身旁守護，總是能帶給你一股溫暖的安全感。',
				'sacker6'=>'神秘內野魔術師
				{user}就像二壘手一樣，身手敏捷，總能在你身旁施展神秘魔法，帶給你意想不到的驚喜。在人生的道路上若是遇到困難，他的小聰明，絕對會助你一臂之力！',
				'sacker5'=>'沉著穩當鐵布衫
				{user}就像三壘手一樣，在內野中如一堵牆矗立著，成為你人生中最穩固的依靠。難過、不安時，就躲進這個你專屬的避風港吧！',
				'sacker2'=>'成熟穩健大好人
				{user}就像左外野手一樣，再遠的距離也為你盡全力狂奔，直到接住那顆落下的球，替你解決問題。他是你最得力的幫手，讓你盡情發揮！',
				'sacker4'=>'侵略積極飛毛腿
				{user}就像右外野手一樣，擁有積極的行動力，無所畏懼，永遠是第一個向前衝的人！在你失意時，總會給你適時的鼓勵，是你生活中重要的動力來源。',
				'lala'=>'活力充沛好身手
				{user}就像啦啦隊一樣，永遠是你最忠實的粉絲，帶給你滿滿的活力與能量，陪你朝更高的目標邁進！',
				'coach'=>'領導眾人扛勝負
				{user}就像球隊總教練一樣，指揮所有的作戰細節，為你規劃戰鬥布局，讓你盡情展現自我。更替所有人承擔成敗責任，是一個十分有擔當的前輩！',
				'mascot3'=>'蹦蹦跳跳開心果
				{user}就像球隊吉祥物一樣，帶給所有的人歡樂！臉上總掛著笑容，擁有無止盡的點子，跟他在一起，你絕對不會感到無聊，因為他總是能讓你哈哈大笑！');

		$fb_title = $this->APPTITLE();
		$fbid = $this->facebook->getUser();

		$user = $this->facebook_model->getUser($fbid);
		$file = WEB_HOST.MERGE_PATH.$fbid.$_POST['uid'].'_wall.jpg';
		$file = str_replace('https','http',$file);
		$this->load->library('bitly');
		$url = site_url('main/redirect').'/'.$fbid;
		$bitly = '';
		$bitly = $this->bitly->shorten($url);
		$bitly = $bitly->$url->shortUrl;
		
		$url = site_url('main/redirect');
		$bitly2 = $this->bitly->shorten($url);
		$bitly2 = $bitly2->$url->shortUrl;

		$message = str_replace('{user}',$_POST['uname'],$position[$_POST['class']]).'>>>'.$bitly;

		$params = array(
				'pic' => $file,
				'album_name' => $fb_title.' photos',
				'album_description' => $fb_title.' photos',
				'picture_description' => $message,
		);
		$pid = $this->facebook_model->album($params);
		// 		foreach($fuser as $fk=>$fv):
		// 			$params = array(
		// 					'upload_photo_id' => $pid['id'],
		// 					'x' => 5,
		// 					'y' => 5,
		// 					'uid' => $fv['uid']
		// 			);
		// 			$this->facebook_model->tag($params);
		// 		endforeach;
		$json = array(
				'success' => true
		);
		echo json_encode($json);
	}

	public function message($fbid=0){
		$data = $this->_getBaseData();
		$data['fbid'] = $fbid;
		$user = $this->facebook_model->getUser($fbid);
		$data['user'] = $user;

		$this->init_model->apply_template_with_ga($this->router->method . '_view', $data);
	}

	public function check_msg(){
		$table = 'tag_record';
		$rs = $this->db_md->get_one($table,$_POST);
		$json = array(
				'data' => $rs,
				'cnt' => sizeof($rs)
		);
		echo json_encode($json);
	}

	public function setMsg(){
		$table = 'tag_record';
		$params = array(
				'message' => $_POST['message']
		);
		unset($_POST['message']);
		$where = $_POST;
		$this->db->update($table,$params,$where);
		$json = array(
				'success' => true
		);
		echo json_encode($json);
	}

	public function redirect($fbid=0){
		if($fbid==0):
		echo '<script>window.open("'.APP_HOST.'","_top");</script>';
		else:
		echo '<script>window.open("'.APP_HOST.'main/message/'.$fbid.'","_top");</script>';
		endif;
		exit;
	}

	public function setData(){
		$table = 'user_info';
		$fbid = $this->facebook->getUser();

		$params = array(
				'fbid' => $fbid
		);
		$rs = $this->db_md->getCount($table,$params);
		$params = array(
				'username' => str_replace('%0D','',$_POST['username']),
				'email' => str_replace('%0D','',$_POST['email']),
				'tel' => str_replace('%0D','',$_POST['tel'])
		);
		$user = $this->facebook_model->getUser($fbid);
		$params['fbid'] = $fbid;
		$params['fbname'] = $user['name'];
		$params['is_join'] = 'Y';
		$where = array(
				'fbid' => $fbid
		);
		$this->db->update($table,$params,$where);
		$json = array(
				'success' => 1
		);
		echo json_encode($json);
	}

	public function po_wall() {
		$fb_title = $this->APPTITLE();
		$fbid = $this->facebook->getUser();

		$fuser = $_SESSION[$fbid.'fuser'];

		$table = 'user_info';
		$params = array(
				'fbid' => $fbid
		);
		$rs = $this->db_md->getCount($table,$params);
		if($rs==0){
			$params['is_join'] = 'N';
			$this->db->insert($table,$params);
			foreach($fuser as $fk=>$fv):
			$table = 'tag_record';
			$params = array(
					'fbid' => $fbid,
					'tofbid' => $fv['uid']
			);
			$this->db->insert($table,$params);
			endforeach;
		}
		$uname = array();
		foreach($fuser as $fk=>$fv):
		$uname[] = $fv['name'];
		endforeach;

		$user = $this->facebook_model->getUser($fbid);
		$file = WEB_HOST.MERGE_PATH.$fbid.'_wall.jpg';
		$file = str_replace('https','http',$file);
		$this->load->library('bitly');
		$url = site_url('main/redirect').'/'.$fbid;
		// 		$bitly = '';
		$bitly = $this->bitly->shorten($url);
		$bitly = $bitly->$url->shortUrl;

		$url = site_url('main/redirect');
		// 		$bitly = '';
		$bitly2 = $this->bitly->shorten($url);
		$bitly2 = $bitly2->$url->shortUrl;

		$message = implode(',',$uname).'是我的超完美強棒應援團，朋友們快來留言，讓我們一起擊出一支無人能擋的HOME RUN吧>>>'.$bitly.'

				在家靠父母，出外靠朋友，是誰在你人生中的重要時刻為你撐腰，成為你的最佳戰友？'.$bitly2;

		$params = array(
				'pic' => $file,
				'album_name' => $fb_title.' photos',
				'album_description' => $fb_title.' photos',
				'picture_description' => $message,
		);
		$pid = $this->facebook_model->album($params);
		// 		foreach($fuser as $fk=>$fv):
		// 			$params = array(
		// 					'upload_photo_id' => $pid['id'],
		// 					'x' => 5,
		// 					'y' => 5,
		// 					'uid' => $fv['uid']
		// 			);
		// 			$this->facebook_model->tag($params);
		// 		endforeach;
		$json = array(
				'success' => true
		);
		echo json_encode($json);
	}

	public function ajaxrecord(){
		$table = $_POST['table'];
		$fbid = $this->facebook->getUser();
		$params = array(
				'fbid' => $fbid
		);
		$success = $this->db->insert($table,$params);
		$json = array(
				'success' => $success
		);
		echo json_encode($json);
	}

	private function preload_images(){
		foreach (glob(IMAGE_PATH."/*") as $f) $images[]=  "'$f'";
		return implode(',',$images);
	}

	public function add_tab()
	{
		$url = "http://www.facebook.com/dialog/pagetab?app_id=" . FBAPP_ID . "&next=" . APP_HOST;
		echo "<a href='$url'>$url</a>";
		exit;
	}

	private function getFansInfo() {
		$rows['page_id'] = fans_page_id;
		$rows['page_url'] = fans_page;

		return $rows;
	}

	public function ajaxtouch(){
	}

	private function APPTITLE(){
		$fbapp_title = $this->facebook_model->getAPPTitle();
		return $fbapp_title[0]['display_name'];
	}
}

?>
