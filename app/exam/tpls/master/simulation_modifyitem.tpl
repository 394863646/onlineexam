{x2;if:!$userhash}
{x2;include:header}
<body>
{x2;include:nav}
<div class="container-fluid">
	<div class="row-fluid">
		<div class="main">
			<div class="col-xs-2" style="padding-top:10px;margin-bottom:0px;">
				{x2;include:menu}
			</div>
			<div class="col-xs-10" id="datacontent">
{x2;endif}
				<div class="box itembox" style="margin-bottom:0px;border-bottom:1px solid #CCCCCC;">
					<div class="col-xs-12">
						<ol class="breadcrumb">
							<li><a href="index.php?{x2;$_app}-master">{x2;$apps[$_app]['appname']}</a></li>
							<li><a href="index.php?exam-master-simulation">试卷管理</a></li>
							<li><a href="index.php?exam-master-simulation-modify&page={x2;$page}{x2;$u}&examid={x2;$exam['cid']}">组卷修改</a></li>
							<li class="active">编辑{x2;$gname} </li>
						</ol>
					</div>
				</div>
				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">
					<h4 class="title" style="padding:10px;">
						编辑{x2;$gname}
						
						<a class="btn btn-primary pull-right" href="index.php?{x2;$_app}-master-simulation-questiongroup&page={x2;$page}{x2;$u}&questiontype={x2;$gname}&examid={x2;$exam['cid']}"> 返回</a>
					</h4>
				    <form action="index.php?exam-master-exams-modify" method="post" class="form-horizontal">
						
					</form>
					<style>
					.table-add{width:100%;}
.table-add  th{font-weight:bold;padding:5px 2px;}
.table-add  td{padding:5px 2px;}
.table-add .cb{vertical-align:-2px}
.line th,.line td{border-bottom:1px dashed #CCC;padding:10px 2px;}
.table-add li{line-height:23px;list-style:none;list-style-position:outside;padding:3px 0}
.table-add b{padding-left:5px}
.imgw{width:98%;}
.titles{width:100%;}
.option{width:91%;}
.abtn{height:21px;width:21px;padding:0;background-color:#FFF;border:1px solid #CCC;outline:none;margin-right:5px;margin-left:2px;cursor:pointer;font-family:SimSun}
.abtn:hover{background-color:#EDEDED;}
.blank_data{width:98.5%;height:120px;outline:none;}
.ask_data{width:99%;height:120px;outline:none;}
.in_data{width:99%;height:300px;outline:none;overflow-y:hidden;word-wrap:normal;margin:0;padding:5px}
.px {
    height: 30px;
}
.px, .pt {
    padding: 2px 4px;
    line-height: 30px;
}
.px, .pt, .ps, select {
    border: 1px solid;
    /*border-color: #848484 #E0E0E0 #E0E0E0 #848484;*/
    background: #FFF;
}

.y {
    float: right;
}

					</style>
				    <form class="form-horizontal" method="post" autocomplete="off" action="index.php?exam-master-simulation-modifyitem">
		               <input name="gid" type="hidden" value="{x2;$exam['gid']}">
		               <table cellpadding="0" cellspacing="0" border=0 class="table-add">
			             <tr>
			               <th>题干：</th>
			               <td colspan=2 style="padding-top:1px">
			                  <input name="subject" class="titles" value="{x2;$exam['subject']}">
			               </td>
			             </tr>
			             <tr >
			               <th>参考答案:</th>
			               <td>
			               {x2;if:$exam['type']=='1'}
						     <label style="padding-right:20px"><input type="radio" class="cb" name="result" value=1 {x2;if:$exam['result']==1}checked{x2;endif}>正确</label>
				             <label><input class="cb" type="radio" name="result" value=2 {x2;if:$exam['result']==2}checked{x2;endif}>错误</label>
						   {x2;elseif:$exam['type']=='2'}
						     <ul id="ul_option">
				               {x2;tree:$data,val,k}
						        {x2;eval: $c =  chr(64 + v:k);}
						         <li>
						          <input type="text" class="px option y" name="option[]" value="{x2;v:val}">
						          <label><input type="radio" class="cb" value="{x2;$c}" name="result" {x2;if:$exam['result']==$c}checked{x2;endif}>
						          <b>{x2;$c}</b>.</label></li>
					            {x2;endtree}
				              </ul>
				              <li><button class="abtn" onclick="exam_add_li(0);return false;">+</button><button class="abtn" onclick="exam_add_li(-1);return false;">-</button></li>
						   {x2;elseif:$exam['type']=='3'}
						       
						      <ul id="ul_option">
					             {x2;tree:$data,val,k}
						             {x2;eval: $c =  chr(64 + v:k);}
						              <li>
						               <input type="text" class="px option y" name="option[]" value="{x2;v:val}">
						               <label><input type="checkbox" value="{x2;$c}" class="cb" name="result[]" {x2;if:strpos($exam['result'],$c)!==false}checked{x2;endif}>
						               <b>{x2;$c}</b>.</label></li>
					              {x2;endtree}
				               </ul>
				               <li><button class="abtn" onclick="exam_add_li(0);return false;">+</button><button class="abtn" onclick="exam_add_li(-1);return false;">-</button></li>
						   
						   {x2;elseif:$exam['type']=='4'}
						      <textarea class="pxs blank_data" name="data">{x2;$exam['data']}</textarea>
						   {x2;elseif:$exam['type']=='5'}
						      <textarea class="pxs ask_data" name="data">{x2;$exam['data']}</textarea>
						   {x2;endif}
						
			               </td>
			             </tr>
			             <tr class="line"><th width=80>图片/音视频:</th><td><input class="px imgw y" name="image" id="image" value="{x2;$exam['image']}"></td></tr>
			             <tr>
			              <th colspan=2><a href="javascript:;" onclick="$('#note').show()">
			                {x2;if: !trim($exam['note'])}添加试题解释{x2;else}试题解释{x2;endif}</a>
			              </th>
			             </tr>
			             <tr>
			               <td colspan=2>
			                <DIV {x2;if:!trim($exam['note'])}style="display:none"{x2;endif} id="note">
			                  <textarea class="ckeditor" name="note" id="questiondescribe">{x2;$exam['note']}</textarea>
			                </DIV>
			               </td>
			             </tr>
		                </table>
		                <input type="submit" id="testsubmit" class="pn pnc pp" style="margin-top:15px" value="确定" onclick="validate_editor_simple(this);return form_check();">
		                <input type="hidden" name="submitsetting" value="1"/>
						<input name="eid" type="hidden" value="{x2;$exam['eid']}">
		                <input name="cid" type="hidden" value="{x2;$exam['cid']}">
		             </form>
		
				</div>
			</div>
{x2;if:!$userhash}
		</div>
	</div>
</div>
<script type="text/javascript">

			function form_check(){	
				$('e_textarea').value = wysiwyg ? html2bbcode(getEditorContents()) : $('e_textarea').value;

				//标题
				if($('e_textarea').value.trim()==''){
					showError('标题不能为空');
					return false;
				}
				
				//参考答案
				var result = document.getElementsByName('result');
				var result_have = false;
				for(var i=0;i<result.length;i++){
					if(result[i].checked){
						result_have = true;
						result[i].value = String.fromCharCode(65+i);
						break;
					}
				}
				if( !result_have){
					showError('参考答案未选定')
					return false;
				}
 	
				//选项
				var option = document.getElementsByName('option[]');
				var options_value_have = false;
				for(var i=0;i<option.length;i++){
					if(option[i].value.replace(/(^\s*)|(\s*$)/g,'')!=''){
						options_value_have = true;
						break;
					}
				}
				if(!options_value_have){
					//showError('选项不能为空')
					//return false;
				}

			}			

			function exam_add_li(mode){
				var ul = $('#ul_option li');
				if(mode==-1){
					if(ul.length>1){
						var last = ul[ul.length-1]
						last.parentNode.removeChild(last); 
					}
				}
				else{
					if(ul.length<=26){
						var new_row_char = String.fromCharCode(65+ul.length);
						
						var newli = '<li><input class="px option y" name="option[]"><label><input type="radio" class="cb" name="result" value="'+new_row_char+'"><b>'+new_row_char+'</b>.</label></li>';
						$('#ul_option').append(newli);
					}
				}
			}
	
</script>
{x2;include:footer}
</body>
</html>
{x2;endif}