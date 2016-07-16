<?php
/* Smarty version 3.1.29, created on 2016-05-29 19:22:39
  from "/home/hebar/notephp/Webapp/Home/View/Index/net.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_574ad0ffb51403_76639356',
  'file_dependency' => 
  array (
    'e54fd854fcb1e251103c8c78e6aa75e27f97d514' => 
    array (
      0 => '/home/hebar/notephp/Webapp/Home/View/Index/net.tpl',
      1 => 1464520952,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_574ad0ffb51403_76639356 ($_smarty_tpl) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<base href="http://localhost"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="https://portal.wifi.189.cn/v50/css/style.css" type="text/css" rel="stylesheet" />
<?php echo '<script'; ?>
 type=text/javascript src="/Public/javascript/common.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type=text/javascript src="/Public/javascript/onNet.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/JavaScript" src="/Public/javascript/default.js"><?php echo '</script'; ?>
>
<title>无标题文档</title>
<style type="">
</style>
</head>
<body>
	<input type="hidden" id="isreflash" value="1" />
	<input type="hidden" value="" />
            <div class="up"> 
                <div class="tab_js">
                    <div class="tab_sm">
                        <div class="tab_sm_2" id="tab">
                            <div class="tab_sm_1_1" onclick="queren(2);document.getElementById('tab').className='tab_sm_1'"></div>
                            <div class="tab_sm_1_2" onclick="queren(1);document.getElementById('tab').className='tab_sm_2'"></div>
                            <div class="tab_sm_1_3" onclick="queren(3);document.getElementById('tab').className='tab_sm_3'"></div>
                        </div>
                    </div>
          
                    <div class="tab1" id="queren_1">
                    	<form  action="loginv40.do" method="post" onsubmit="return false;">
							<input type="hidden" name="pro" id="pro3" value="gd" />
							<input name="fromPage" type="hidden" value="/v50/index_1.jsp"/>
							<input type="hidden" name="provinceCode" id="provinceCode" value="wlan.sck.chntel.com" />
							<input type="hidden" name="accountType" value="chcard" />
							<input type="hidden" name="wlanuserip" id="wlanuserip2" value="" />
							<input type="hidden" name="clientType" id="clientType" value="1" />
							<input type="hidden" name="serial" id="serial" value="45122631554394" />
                        <div class="input">
                        	<div class="phone"><img src="/v50/images/card.png" /></div>
                            <table class="tb_input">
                            	<tr>
                            		<td style="padding-top:36px;">
                            			<div class="focus tonjay" >
                            				<input name="userName" id="userName3" type="text"  class="input_txt" value="" />
                            				<label for="userName3" id="userNameLabel3" class="input_lab">时长卡帐号</label>
                            			</div>
                            		</td>
                            	</tr>
                                <tr>
	                                <td style="padding-top:13px;">
		                                <div class="focus tonjay">
			                                <input name="password"  id="password3" type="password"  autocomplete="off" class="input_txt" value=""  />
			                                <label for="password3" id="passwordLabel3" class="input_lab">密码</label>
		                                </div>
	                                </td>
                                </tr>
                                <tr>
	                                <td id="chRand1" style="padding-top:11px;" >
		                                <div class="focus tonjay">
			                                <input name="randCode" id="randCode3" type="text" style="width:285px;" class="input_txt"  maxlength="4"  />
			                                <label for="randCode3" class="input_lab">验证码</label>
			                                <img class="form_3_1" id="chRand" src="https://portal.wifi.189.cn/common/img.jsp" onclick="reNewRandCode(this);"/>
		                                </div>
	                                </td>
                                </tr>
                            </table>
                        	
                            
                            
                        </div>
                        <div class="dlu">
                        	<div class="form_dlu">
                        		<input type="image" src="/v50/images/dlu.png" onclick="if(!this.disabled) return checkLogin3();"/>
                        		<div style="display:none">
	                        		<span>10秒后可再次点击</span>
	                        	</div>
                        	</div>
                        </div>
                        </form>	
                    </div>
        
                    <div class="tab2" id="queren_2">
                    	<form  action="https://portal.wifi.189.cn/loginv40.do" method="post" >
                    		<input name="fromPage" type="hidden" value="/v50/index_1.jsp"/>
                            <input type="hidden" id="accountType" name="accountType" value="mobile" />
                            
                            <input type="hidden" name="pro" id="pro1" value="gd" />
                            <input type="hidden" name="wlanuserip" id="wlanuserip" value="" />
                            <input type="hidden" name="clientType" id="clientType" value="1" />
                            <input type="hidden" name="serial" id="serial" value="45122631554394" />
                    	<div class="input">
                        	<div class="phone"><img src="/v50/images/phone.png" /></div>
                            <table class="tb_input">
                            	<tr>
	                            	<td style="padding-top:36px;">
		                            	<div class="focus tonjay">
			                            	<input name="userName" id="userName1" class="input_txt"   maxlength="12" value=""/>
			                            	<label for="userName1" id="userNameLabel1" class="input_lab">手机号</label>
		                            	</div>
	                            	</td>
                            	</tr>
                                <tr>
	                                <td style="padding-top:13px;">
		                                <div class="focus tonjay">
		                                	<input name="password"  id="password1" type="password" autocomplete="off" style="width:285px;" class="input_txt" value="" />
		                                	<label for="password1"  id="passwordLabel1" class="input_lab">密码</label>
		                                	<div id="getPwdDiv">
	                                				<input class="form_2_1" type="image" src="/v50/images/hqmm_bg.png" id="cwGetPass" onclick="if(!this.disabled) return getCwPasswd(this);" />
	                                				<div style="display:none" class="form_2_2">
						                        		<span>10秒后可再次点击</span>
						                        	</div>
	                                			</div>
		                                </div>
	                                </td>
                                </tr>
                                <tr>
	                                <td id="pCode1" style="padding-top:11px;">
		                                <div class="focus tonjay">
			                                <input type="text" style="width:285px;" class="input_txt"  id="randCode1" name="randCode" maxlength="4" />
			                                <label for="randCode1" class="input_lab">验证码</label>
			                                <img id="pRand" class="form_3_1" src="https://portal.wifi.189.cn/common/img.jsp" onclick="reNewRandCode(this);"/>
		                                </div>
	                                </td>
                                </tr>
                                <tr>
                               		<td id="pSmsCode" style="padding-top:9px;display:none;">
                               			<div  class="focus tonjay">
                               				<input type="text" style="width: 345px;" class="input_txt"  id="smsCode" name="smsCode"  maxlength="4" />
                               				<label for="smsCode" class="input_lab">随机码</label>
                               			</div>
                               		</td>
                               	</tr>
                            </table>
                        	
                        </div>
                        <div class="dlu">
                        	<div class="form_dlu">
	                        		<input type="image" src="/v50/images/dlu.png" id="userImg1" onclick="if(!this.disabled) return checkLogin();" />
	                        		<div style="display:none">
		                        		<span>10秒后可再次点击</span>
		                        	</div>
	                        	</div>
                        </div>
                        </form>
                    </div>
                    
        
                    <div class="tab3" id="queren_3">
                    	<form  action="loginv40.do" method="post" onsubmit="return false;">
							<input type="hidden" name="pro" id="pro2" value="gd" />
							<input name="fromPage" type="hidden" value="/v50/index_1.jsp"/>
							<input type="hidden" name="accountType" value="common" />
							<input type="hidden" name="wlanuserip" id="wlanuserip2" value="" />
							<input type="hidden" name="clientType" id="clientType" value="1" />
							<input type="hidden" name="serial" id="serial" value="45122631554394" />
                    	<div class="input">
                        	<div id="kdImg" class="phone"><img src="/v50/images/kd.png" /></div>
                        	<table class="tb_input">
                            	<tr>
	                            	<td style="padding-top:12px;">
		                            	<div class="focus tonjay">
		                            		<input name="userName" id="userName2" type="text"  class="input_txt" onpropertychange="checkDomain();" oninput="checkDomain();" value=""/>
		                            		<label for="userName2" id="userNameLabel2" class="input_lab">宽带帐号</label>
		                            	</div>
	                            	</td>
                            	</tr>
                                <tr>
	                                <td style="padding-top:13px;">
		                                <div class="focus tonjay">
		                                	<input name="password" id="password2" type="password" autocomplete="off" class="input_txt" value="" />
		                                	<label for="password2" id="passwordLabel3" class="input_lab">密码</label>
		                                </div>
	                                </td>
                                </tr>
                                <tr><td style="padding-top:12px;"><div>
                                <div class="sel_box" style="position: relative;border:0px;height: 26px;">
                                	<select name="provinceCode" id="provinceCode2"  style="height:22px; line-height:18px; padding:2px 0;font-size: 12px;width: 341px;border:1px solid #ccc;display: block;left: 2px;top: 2px;position: absolute;color: #6B6B6B;background: #ECECEC;">
										<option value="全国">--请选择账号归属地--</option>
										<option value="wlan.sh.chntel.com"  >上海</option>
										<option value="wlan.bj.chntel.com"  >北京</option>
										<option value="wlan.tj.chntel.com"  >天津</option>
										<option value="wlan.hi.chntel.com"  >海南</option>
										<option value="wlan.he.chntel.com"  >河北</option>
										<option value="wlan.gx.chntel.com"  >广西</option>
										<option value="wlan.sx.chntel.com"  >山西</option>
										<option value="wlan.sc.chntel.com"  >四川</option>
										<option value="wlan.nm.chntel.com"  >内蒙古</option>
										<option value="wlan.cq.chntel.com"  >重庆</option>
										<option value="wlan.ln.chntel.com"  >辽宁</option>
										<option value="wlan.gz.chntel.com"  >贵州</option>
										<option value="wlan.jl.chntel.com"  >吉林</option>
										<option value="wlan.yn.chntel.com"  >云南</option>
										<option value="wlan.hl.chntel.com"  >黑龙江</option>
										<option value="wlan.xz.chntel.com"  >西藏</option>
										<option value="wlan.hb.chntel.com"  >湖北</option>
										<option value="wlan.sn.chntel.com"  >陕西</option>
										<option value="wlan.hn.chntel.com"  >湖南</option>
										<option value="wlan.gs.chntel.com"  >甘肃</option>
										<option value="wlan.ha.chntel.com"  >河南</option>
										<option value="wlan.qh.chntel.com"  >青海</option>
										<option value="wlan.js.chntel.com"  >江苏</option>
										<option value="wlan.nx.chntel.com"  >宁夏</option>
										<option value="wlan.sd.chntel.com"  >山东</option>
										<option value="wlan.xj.chntel.com"  >新疆</option>
										<option value="wlan.gd.chntel.com"  >广东</option>
										<option value="wlan.ah.chntel.com"  >安徽</option>
										<option value="wlan.jx.chntel.com"  >江西</option>
										<option value="wlan.fj.chntel.com"  >福建</option>
										<option value="wlan.zj.chntel.com"  >浙江</option>
										<option value="wlan.zx.chntel.com"  >全国中心</option>
										<option value="wlan.am.chntel.com"  >澳门</option>
										<option value="wlan.sck.chntel.com" >时长卡</option>
								    </select>
                                </div>
                                <br clear="all" />
                        		<input type="hidden" id="sel_value" />
                   			</div></td></tr>
                   				<tr>
	                                <td id="kdRand1" style="padding-top:11px;">
		                                <div class="focus tonjay">
			                                <input type="text" style="width:285px;" class="input_txt"  id="randCode2" name="randCode" maxlength="4" />
			                                <label for="randCode2" class="input_lab">验证码</label>
			                                <img id="kdRand" class="form_3_1" src="portal.wifi.189.cn/common/img.jsp" onclick="reNewRandCode(this);"/>
		                                </div>
	                                </td>
                                </tr>
                            </table>
                        	
                            
                            
                        </div>
                        <div class="dlu">
                        	<div class="form_dlu">
                        		<input type="image" src="/v50/images/dlu.png" onclick="if(!this.disabled) return checkLogin2();"/>
                        		<div style="display:none">
	                        		<span>10秒后可再次点击</span>
	                        	</div>
                        	</div>
                        </div>
                         </form>
                    </div>
       
                </div>
            </div> 
            <div class="mid">
            	<div class="mid_l">
                	<img class="mid_rvl" src="/v50/images/rvl.png" />
                    <div class="mid_sel">
                        <div class="select_box" style="position: relative;border:0px;height: 26px;">
							    <select id="romingList" style="height:22px; line-height:18px; padding:2px 0;font-size: 12px;width: 245px;border:1px solid #ccc;display: block;top: 2px;position: absolute;color: #6B6B6B;background: #ECECEC;">
							        <option value="">-Select Service Provider-</option>
									<option value="/roaming/att/">at&t</option>
									<option value="/roaming/NTT/">NTT</option>
									<option value="/roaming/CHTlanding/">中華電信</option>
									<option value="/roaming/PCCW/01.htm">PCCW-HKT mobile service</option>
									<option value="/roaming/CTM/">CTM</option>
									<option value="/roaming/StarHub/">starHub</option>
									<option value="/roaming/freedom4/">FREEDOM4</option>
									<option value="/roaming/True/">True</option>
									<option value="/roaming/FET/">Qware</option>
									<option value="/roaming/Tomizone/">tomizone</option>
									<option value="/roaming/Globe/">GLOBE</option>
									<option value="/roaming/Trustive/">TRUSTIVE</option>
									<option value="/roaming/IndosatM2/">INDOSATM2</option>
									<option value="/roaming/Wi2/">wi2</option>
									<option value="/roaming/ipass/">iPass</option>
									<option value="/roaming/Tripletgate/">WIRELESSGATE</option>
									<option value="/roaming/kt/">kt</option>
									<option value="/roaming/Y5Zone/">Y5ZONE</option>
									<option value="/roaming/BOINGO/">Boingo</option>
									<option value="/roaming/AlwaysOn/">AlwaysOn</option>
									<option value="/roaming/Comfone/">WeRoam</option>
									<option value="/roaming/GlobalMobile/01.html">Global Mobile</option>
									<option value="/roaming/orange/">Orange</option>
									<option value="/roaming/FarEasTone/">FarEasTone</option>
							    </select>
            			</div>
            			<br clear="all" />
   						<input type="hidden" id="select_value" />
                    </div>
                </div>
                <div class="mid_r">
                	<img class="mid_go" src="/v50/images/go.png" onclick="gotoRomingPage()" style="cursor:pointer;"/>
                </div>
            </div> 
			<?php echo '<script'; ?>
>
			window.onload = function(){
				//初始化输入框提示文字信息
				 initInput();
				 initOnload();
			}
			<?php echo '</script'; ?>
>
			
	
	
			<input type="hidden" name="errortimes" id="errortimes" value=""/>
    <input type="hidden" id="cwPasswdInfo" name="cwPasswdInfo" value='???cwpass.result..description???'/>
    
    <input type="hidden" name="errorCode" id="errorCode" value=""/>
    <input type="hidden" name="isWeakPwd" id="isWeakPwd" maxlength="2" value="" />
	<input type="hidden" id="titleContent" name="titleContent" value="错误提示："/>
	<input type="hidden" id="cwPasswdInput" name="cwPasswdInput" value="请在“手机号”栏输入您的天翼 133、153、180、181或 189 手机号码，点击“获取密码”按钮，系统会自动发送 WLAN 密码至您的手机上(WLAN 帐号即为您的手机号)。"/>
	<input type="hidden" id="pCodeNullInfo" name="pCodeNullInfo" value="开户地没找到，请确认你输入的是正确的天翼手机号。详情请拨打10000。"/>
	<input type="hidden" id="sameInfo" name="sameInfo" value="请你不要在短时间内重复认证！请稍候再试。"/>
	<input type="hidden" id="45" name="timeout" value="认证请求超时！"/>
	<input type="hidden" id="10005013" name="smsfail" value="您的密码过于简单，请拨打10000或到开户地网上营业厅修改密码，本次发送随机码失败！" />
	<input type="hidden" id="10005555" name="iTransferError" value="即日起，天翼WiFi免费体验活动统一通过天翼WiFi客户端开展，客户端下载地址：http://wifi.189.cn" />
	<input type="hidden" id="0001" name="0001" value="即日起，天翼WiFi免费体验活动统一通过天翼WiFi客户端开展，客户端下载地址：http://wifi.189.cn" />
	<input type="hidden" id="10005003" name="iCodeError" value="验证码错误" />
	<input type="hidden" id="0" name="0" value="success"/>
	<input type="hidden" id="1" name="1" value="password error（ErrorCode20001）"/>
	<input type="hidden" id="2" name="2" value="password sign error（ErrorCode20002）"/>
	<input type="hidden" id="3" name="3" value="account not found or password error（ErrorCode20003）"/>
	<input type="hidden" id="4" name="4" value="account not found or password error（ErrorCode20004）"/>
	<input type="hidden" id="5" name="5" value="connect timeout（ErrorCode20005）"/>
	<input type="hidden" id="6" name="6" value="else（ErrorCode20006）"/>
	<input type="hidden" id="7" name="7" value="sdx not found（ErrorCode20007）"/>
	<input type="hidden" id="8" name="8" value="It's not a valid  phone number（ErrorCode20008）"/>
	<input type="hidden" id="9" name="9" value="Account opening location is wrong（ErrorCode20009）"/>
	<input type="hidden" id="100001" name="100001" value="account not found or password error"/>
	<input type="hidden" id="1000101" name="1000101" value="connect timeout（ErrorCode20111）"/>
	<input type="hidden" id="1000102" name="1000102" value="system error（ErrorCode20112）"/>
	<input type="hidden" id="11000000" name="11000000" value="Application Exception（ErrorCode20110）"/>
	<input type="hidden" id="11001000" name="11001000" value="Sdx Not Found（ErrorCode20111）"/>
	<input type="hidden" id="11002000" name="11002000" value="WifiAP Not Found（ErrorCode20112）"/>
	<input type="hidden" id="11003000" name="11003000" value="Domain Error（ErrorCode20113）"/>
	<input type="hidden" id="12000000" name="12000000" value="Juniper Error Exception（ErrorCode20120）"/>
	<input type="hidden" id="12001000" name="12001000" value="Not Locate ServiceActivationEngine（ErrorCode20121）"/>
	<input type="hidden" id="12002000" name="12002000" value="Failed To login User（ErrorCode20122）"/>
	<input type="hidden" id="error1" name="error1" value="Account is wrong!"/>
	<input type="hidden" id="error2" name="error2" value="Password is wrong!"/>
	<input type="hidden" id="error3" name="error3" value="Incorrect verification code,please input again!Press the picture to refreshing!"/>
	<input type="hidden" id="cwGetPasswdInfo" name="cwGetPasswdInfo" value="Incorrect verification code,please input again!Press the picture to refreshing!"/>
	<input type="hidden" id="lastIP" name="lastIP" value=""/>
	<input type="hidden" id="lastAccount" name="lastAccount" value=""/>
	<input type="hidden" id="currentIP" name="currentIP" value="14.147.221.88"/>
	<input type="hidden" id="submitable" name="submitable" value="no"/>
	<input type="hidden" id="44" name="44" value="account not found or password error（ErrorCode20004）"/>

<!--0001 异网 非携号转网 -->

<?php echo '<script'; ?>
 language="JavaScript">
	var nav2 = getParentByClass("nav2");
	nav2[1].style.display="none";
	nav2[2].style.display="none";
<?php echo '</script'; ?>
>
</body>
</html>
<?php }
}
