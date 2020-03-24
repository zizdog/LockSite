<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * typecho 整站加密插件，需要输入密码才能访问网站 
 * 
 * @package LockSite
 * @author zizdog
 * @version 0.0.1
 * @link http://blog.zizdog.com
 */
class LockSite_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('admin/menu.php')->navBar = array('LockSite_Plugin', 'render');


	
		Typecho_Plugin::factory('Widget_Archive')->headerOptions =array('LockSite_Plugin', 'main_fun');
	
	
	
    }

	public static function main_fun()
    {
		
		$Str_Msg_PSWERR="";
		//检查密码 处理 cookies
		if ( isset($_POST['index_passwd']) ){
			
			if ( trim($_POST['index_passwd'])==  Typecho_Widget::widget('Widget_Options')->plugin('LockSite')->str_Pword  ){
				
				setcookie("index_passwd",trim($_POST['index_passwd']),time()+3600*24*7);
	
				echo '<meta http-equiv="refresh" content="0;url='.$_SERVER["REQUEST_URI"].'"> ';
			}else{
				$Str_Msg_PSWERR="密码错误，请重新输入";
			}
		}
		
		if(empty($_COOKIE["index_passwd"])){
     ?>
	 <!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">
        <title><?php echo htmlspecialchars(Typecho_Widget::widget('Widget_Options')->plugin('LockSite')->str_word)?></title>
        <style>
            html {padding: 50px 10px;font-size: 16px;line-height: 1.4;color: #666;background: #F6F6F3;}
            html,input { font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; }
            body {max-width: 500px;_width: 500px;padding: 30px 20px;margin: 0 auto;background: #FFF;}

            ul {padding: 0 0 0 40px;}
            .container {max-width: 380px;_width: 380px;margin: 0 auto;}
            .passwd_form,p{text-align: center;}.logo1{width:200px;height: 200px;margin:auto;display: block;}
            .pass_input{padding:4px 5px;border:1px solid #bbb;border-radius:2px;}
            .pass_button{border:1px solid #bbb;padding:2px 7px;background: #532e17;color:#fff;border-radius: 3px;cursor: pointer;}
        </style>
    </head><body>
        <div class="container">
        <img class="logo1" src=" <?php echo htmlspecialchars(Typecho_Widget::widget('Widget_Options')->plugin('LockSite')->url_pic)?>" />
        <br>
           <p><?php echo Typecho_Widget::widget('Widget_Options')->plugin('LockSite')->str_word?></p>
           <p style="color:red"><?php echo $Str_Msg_PSWERR;?></p>

           <form class="passwd_form" action="<?php echo $_SERVER["REQUEST_URI"];?>" method="post" >
           <input class="pass_input" type="password"   name="index_passwd" placeholder="<?php echo htmlspecialchars(Typecho_Widget::widget('Widget_Options')->plugin('LockSite')->placeholder)?>" /> 
           
           <input class="pass_button" type="submit" value="<?php echo htmlspecialchars(Typecho_Widget::widget('Widget_Options')->plugin('LockSite')->Submit)?>">
           </form>
        </div>
        </body></html>
	 <?php
	 //停止输出其他内容
	 exit();
	 
	 }else{
		//密码存在 什么都不做
	}	
	 
	 
	}
	
	
	
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}
    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        /** 分类名称 */
        $str_word = new Typecho_Widget_Helper_Form_Element_Text('str_word', NULL, '网站已启用全站加密，请输入密码访问', _t('提示文字'));
        $form->addInput($str_word);
		
		
		$placeholder = new Typecho_Widget_Helper_Form_Element_Text('placeholder', NULL, '请输入访问权限密码', _t('输入框提示'));
        $form->addInput($placeholder);
		
		
		$Submit = new Typecho_Widget_Helper_Form_Element_Text('Submit', NULL, '提交', _t('Submit按钮提示'));
        $form->addInput($Submit);
		
		$url_pic = new Typecho_Widget_Helper_Form_Element_Text('url_pic', NULL, 'http://link.zizdog.com/icon.svg', _t('提示图片'));
        $form->addInput($url_pic);
		
		$str_Pword = new Typecho_Widget_Helper_Form_Element_Text('str_Pword', NULL, '123456',_t('设置全站访问密码'));
        $form->addInput($str_Pword);
			
		//$enable_in_html = new Typecho_Widget_Helper_Form_Element_Radio('enable_in_html', array ('0' => '加密后内容依旧可以在html和搜索引擎中可见', '1' => '彻底隐藏数据'), '0',        '是否完全隐藏内容：', '');
   // $form->addInput($enable_in_html);
    }
    
    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
    
    /**
     * 插件实现方法
     * 
     * @access public
     * @return void
     */
    public static function render()
    {
        echo '<a href="options-plugin.php?config=LockSite">全站密码</a>';
    }
	
	
	
}
