<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="https://opengraph.org/schema/">
    <head>
        <title><?= BACKEND_TITLE ?></title>
        <base href="<?= WEB_HOST ?>" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">     
            <script type="text/javascript" src="<?= is_https ?>://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.js"></script>
            <script src="<?= WEB_HOST ?>js/jquery-ui-1.8.20.custom.min.js" type="text/javascript"></script>
            <script src="<?= WEB_HOST ?>js/jquery.bpopup-0.7.0.min.js" type="text/javascript"></script>			
            <link rel="stylesheet" type="text/css" href="<?= WEB_HOST ?>css/jquery-ui-1.8.20.custom.css" />
            <link rel="stylesheet" type="text/css" href="<?= WEB_HOST ?>css/tab.css" />					
            <script type='text/javascript'>
                function xxbPopupClose(div){
                    $('#'+div).bPopup().close();										
                }			
                function alertError(){
                    alert('系統忙碌中, 請稍後再試.');		
                }	
				
				var method = '<?=$this->router->method?>';
				function poploading(){				
					$('#element_to_pop_up').bPopup({				
						position: [($(window).width()-124)/2, 'auto'],
						positionStyle: 'fixed'
					});
				}
				function closeloading(){				
					$('#element_to_pop_up').bPopup().close();
				}				
				
            </script>
    </head>
    <body>      
    <?php $this->load->view('backend_menu');?>
        <div id="content">
            <?php print $content ?>
        </div>
		<div style='display:none;'>
			<div id='preview_dialog' style='background-color:white;text-align:center;width:500px;height:30px;'>預覽資料已經準備好了, 按下<input type='button' onclick='gopreview();$("#preview_dialog").bPopup().close();' value='　GO　' />另開視窗預覽.</div>
            <div id='element_to_pop_up'>
                <div style='text-align:center;width:124px;height:124px;'><img src='<?= WEB_HOST . 'images/loading.gif' ?>' /></div>				
            </div>			
		</div>
    </body>
</html>