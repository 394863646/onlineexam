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
							<li class="active">题组管理</li>
						</ol>
					</div>
				</div>
				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">
					<h4 class="title" style="padding:10px;">
						题组管理
						<a class="btn btn-primary pull-right" href="index.php?{x2;$_app}-master-simulation-modify&page={x2;$page}{x2;$u}&examid={x2;$examid}">试卷管理</a>
					</h4>
					<!--<form action="index.php?exam-master-exams-modifypaper" method="post" class="form-horizontal">
						
						
						
					</form>-->
					<style>

					.table-el {empty-cells: show;border-collapse: collapse;width:100%;word-break: break-all; word-wrap:break-word;font-weight:400}

.table-el  tr {padding:1px 5px;}
.table-el  tr th{padding:1px 5px;}
.table-el  tr td{padding:10px 5px;position:relative }
.table-el  tr input{border:1px solid #CCC;width:50px;height:20px;padding:0 5px;outline:none}
.table-el  tr button{padding:0 10px;} 
.table-el .st{vertical-align:top;width:30px}
.table-el .shows{color:#333}
.table-el .hides{color:#CCC}

.edit-gct{background:#F2F2F2;border-top:1px solid #CCC;padding:10px;}

.edit-item {border-bottom:1px solid #DEDEDE;}
.edit-item .ei-bar{text-align:right}
.edit-item .ei-bar a{margin-left:5px;font-size:10px;color:#DDD}
.edit-item:hover a{margin-left:5px;font-size:10px;color:#333}
.xi1, .onerror {
    color: #F26C4F;
}
</style>

	<form class="form-horizontal" method="post" autocomplete="off" action="index.php?exam-master-simulation-questiongroup">	
		<fieldset>
		<table cellpadding="0" cellspacing="0" class="table-el">
			<tr>
				<th width=50 class="bm_h"><center>排序</center></th>
				<th width=65 class="bm_h"><center>分值</center></th>
				<th class="bm_h"><center>题组: {x2;$questiontype}</center></th>
			<tr>

		</table>

			<div class="edit-gct">{x2;$examsetting['describe']}</div>
			{x2;tree:$questions,quest,qid}
			<a name="move{x2;v:quest['eid']}"></a>
			<div class="edit-item" id="id_{x2;v:quest['eid']}">
				<table cellpadding="0" cellspacing="0" class="table-el">
					<tr>			
					<td class="st"><input maxLength=3 name="sort[{x2;v:quest['eid']}]" value="{x2;v:quest['sort']}"></td>
					<td class="st"><input maxLength=4 name="score[{x2;v:quest['eid']}]" value="{x2;if:v:quest['score']!=0}{x2;v:quest['score']}{x2;endif}"></td>
					<td {x2;if:!v:quest['display']}class="hides"{x2;endif}>
						{x2;if:v:quest['image']}
						 <div class="y emedia">
							<span class="y media">{x2;v:quest['image']}</span>
						 </div>
						{x2;endif}
						<div id="subject_{x2;v:quest['eid']}">{x2;v:quest['subject']}
							{x2;if:v:quest['type']==2 || v:quest['type']==3}<br><br>
								{x2;tree:v:quest['data'],list,did}
								{x2;eval: echo chr(v:did+64)}.  {x2;v:list}<br>
								{x2;endtree}
							{x2;endif}
						</div>
					</td>
				<tr>

				</table>
				<table cellpadding="0" cellspacing="0" class="table-el">
					<tr>
						<td width=85 valign="top" align="right">参考答案:</td>
					<td class="xi1">
						{x2;if:v:quest['type']==1}
						  {x2;if:v:quest['result']=='1'}正确{x2;else}错误{x2;endif}
						{x2;elseif:v:quest['type']=='2' || v:quest['type']=='3'}{x2;v:quest['result']}
						{x2;elseif:v:quest['type']=='4' || v:quest['type']=='5'}{x2;v:quest['data']}{x2;endif}
					</td>
					<td width="200">
					 <div class="ei-bar">
					     <a href="javascript:;" onclick="user_exam_set_display({x2;v:quest['eid']},this)">开关</a>
						 <a href="index.php?exam-master-simulation-modifyitem&eid={x2;v:quest['eid']}" class="a">编辑</a>
						 <a href="javascript:;" onclick="user_exam_delete({x2;v:quest['eid']})">删除</a>
					 </div>
				    </td>
				  </tr>
			   </table>
			</div>
			
		{x2;endtree}

		<input type="submit" class="pn pnc pp mtm" value="确定"> <p class="mtn xi1">小提示: 如果一个试题的分值设定为空或0,则该题目的分值继承题组中的分值, 如果设定了该分值,该题的分值则为该设定值!</p>
		<input type="hidden" name="modifypaper" value="1"/>
		</fieldset>
		</form>
        
		<script type="text/javascript">
			function saveData(){}
			function loadData(){}
			function user_exam_set_display(eid,e){
				var obj = e;
				$.getJSON('index.php?exam-master-simulation-user_exam_set_display&eid='+ eid +'&'+Math.random(),function(data){
					var color   = data=="1" ? 'shows' : 'hides';
					$('#subject_'+eid).attr('class',color);
				});
			}	
			function user_exam_delete(eid){
				$.getJSON('index.php?exam-master-simulation-user_exam_delete&eid='+eid+'&'+Math.random(),function(data){
					console.log(data);
					if(data != '1')return;
					delete_tr = $('#id_'+eid);
					tr_left_height = delete_tr.offsetHeight;
					delete_tr.append('<td colspan=3 style="padding:0;"></td>');
					delete_tr.css('height',tr_left_height);
					delete_tr.remove();
				});
			}

			
		</script>

				</div>
			</div>
{x2;if:!$userhash}
		</div>
	</div>
</div>
{x2;include:footer}
</body>
</html>
{x2;endif}