<div id="elayer" class="elayer">
	
	<div class="userinfo">
		<cite id="userinfoit">
			<li><span class="itt">姓名:</span><input name="userinfo[]" maxlength="50" readonly="readonly" value=""></li>			
		</cite>
		<li id="showUserInfoli"><a href="javascript:;" class="xi2" onclick="showUserInfo()">考生信息</a></li>
	</div>
	
	<div id="emixed" class="emixed1"></div>
	<div class="p_result" id="p_result" style="display:block"></div>
	<div class="p_time" id="p_time">&nbsp;</div>	
	<div class="summary">总分:<?php echo $this->tpl_var['paper']['examsetting']['score']; ?>分 及格:<?php echo $this->tpl_var['paper']['examsetting']['passscore']; ?>分 时间:<?php echo $this->tpl_var['paper']['examsetting']['examtime']; ?>分钟</div>
	<div id="plinkbox">
		<table width="96%" id="link_box" class="p_list" cellpadding="0" cellspacing="0">
		<?php $this->tpl_var['i']=0;; ?>
		 <?php $_gid = 0;
 foreach($this->tpl_var['groups'] as $key => $_group){ 
 $_gid++; ?>
			<?php $id = 0;
 foreach($_group['list'] as $key => $val){ 
 $id++; ?>
				<?php if($this->tpl_var['i']++ % 7==0){ ?><tr align="middle" bgColor="#ffffff"><?php } ?>
				<td id="a_<?php echo $val['eid']; ?>" class="e"><?php echo $this->tpl_var['i']; ?></td>
			<?php } ?>
		<?php } ?>
		</table>
	</div>
	<table class="p_counter" width="96%" id="counter" cellpadding="0" cellspacing="0">
	   <tr>
		<td width="33.3%">未答题:<span id="count_undo"></span></td>
		<td width="33.3%">已答题:<span id="count_do">0</span></td>
		<td width="33.3%">答错题:<span id="count_wrong"></span></td>
		</tr>
	</table>
	<table class="submit" width="96%"><tr>
		<td width="50%" style="text-align:left"><button class="btn1" onclick="exam_submit();return false;">提交试卷</button></td>
		<td width="50%" style="text-align:right"><button class="btn2" id="timer_power" onclick="timer_on_off();return false;">暂停计时</button></td>
		</tr>
	</table>
</div>
<script type="text/javascript">
	function timer_on_off(){alert();
		if(TIME_LEFT>0){
			if(!TIME_TIMER){
				TIME_TIMER = setInterval('time_counter()',1000);
				$('timer_power').innerHTML = '暂停考试';
			}else{
				clearInterval(TIME_TIMER); 
				TIME_TIMER = null;
				$('timer_power').innerHTML = '继续考试';
				var myDate = new Date();
				var strTime = ('0'+myDate.getHours()).slice(-2) + ':' +  ('0'+ myDate.getMinutes()).slice(-2) + ':' + (':0'+ myDate.getSeconds()).slice(-2);
				showDialog("<em style='font-size:16px;line-height:40px'>考试于 "+ strTime +" 暂停 ......</em>", "notice",'', function(){timer_on_off();}, 1, function(){alert(1)}, 0,"继续考试");
				$('fwin_dialog_close').style.display="none"
			}
		}
	}


    $('#emixed').click(function(){
        var box = $('#plinkbox');
		if(box.css('display') == 'none'){
			box.show();
			$(this).attr('class','emixed1');
		}
		else{
			box.hide();
			$(this).attr('class','emixed2');
		}
    });

   
	
	
	function showUserInfo(){
				var ifield = "$config['userinfo']";
				var ifieldarr = ifield.split(',');
				var str = '';
				var uinfo0 = document.getElementsByName("userinfo[]");
				for(var i in ifieldarr){
					str += '<li><span class="itt">'+ifieldarr[i]+':</span><input class="iw" name="uinfo" maxlength="50" value='+ (uinfo0[i] ? uinfo0[i].value : '') +'></li>';
				}
				showDialog(str, '', '考试信息', function(){
					var istr = '';
					var uinfo = document.getElementsByName("uinfo");
					//if(uinfo[0].value!==''){
						for(var i=0;i<uinfo.length;i++){
							if(uinfo[i].value==''){
								setTimeout("showUserInfo()", 100);
								return;
							}
							istr += '<li><span class="itt">'+ifieldarr[i]+':</span><input name="userinfo[]" maxlength="50" readonly="readonly" value="'+uinfo[i].value+'"></li>';
						}
						$('userinfoit').innerHTML = istr;
					//}
				});
				var uinfo = document.getElementsByName("uinfo");
				if(uinfo.length){
					var o = uinfo[0].parentNode.parentNode;
					o.style.backgroundImage = 'none';
					o.style.paddingLeft = '35px';
				}
			}
			
			//showUserInfo();
			
     $(window).load(function() {
		
		var links = $('#link_box td');

		for(var j=0;j<links.length;j++){
			links[j].onclick = function(e){
				var eid = $(this).attr('id').substr(2);
				
					currentElement.style.display = 'none';
					currentElement = $('examid' + eid);
					currentElement.style.display = 'block';
				
				window.location.hash = eid;
			}
		}
		$('#count_undo').html($('#link_box td').length);

	
	});
	

</script>



