<?php if(!$this->tpl_var['userhash']){ ?>
<?php $this->_compileInclude('header'); ?>
<body>
<?php $this->_compileInclude('nav'); ?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="main">
			<div class="col-xs-2" style="padding-top:10px;margin-bottom:0px;">
				<?php $this->_compileInclude('menu'); ?>
			</div>
			<div class="col-xs-10" id="datacontent">
<?php } ?>
				<div class="box itembox" style="margin-bottom:0px;border-bottom:1px solid #CCCCCC;">
					<div class="col-xs-12">
						<ol class="breadcrumb">
							<li><a href="index.php?<?php echo $this->tpl_var['_app']; ?>-master"><?php echo $this->tpl_var['apps'][$this->tpl_var['_app']]['appname']; ?></a></li>
							<li><a href="index.php?exam-master-simulation">试卷管理</a></li>
							<li class="active">题组管理</li>
						</ol>
					</div>
				</div>
				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">
					<h4 class="title" style="padding:10px;">
						题组管理
						<a class="btn btn-primary pull-right" href="index.php?<?php echo $this->tpl_var['_app']; ?>-master-simulation-modify&page=<?php echo $this->tpl_var['page']; ?><?php echo $this->tpl_var['u']; ?>&examid=<?php echo $this->tpl_var['examid']; ?>">试卷管理</a>
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
				<th class="bm_h"><center>题组: <?php echo $this->tpl_var['questiontype']; ?></center></th>
			<tr>

		</table>

			<div class="edit-gct"><?php echo $this->tpl_var['examsetting']['describe']; ?></div>
			<?php $qid = 0;
 foreach($this->tpl_var['questions'] as $key => $quest){ 
 $qid++; ?>
			<a name="move<?php echo $quest['eid']; ?>"></a>
			<div class="edit-item" id="id_<?php echo $quest['eid']; ?>">
				<table cellpadding="0" cellspacing="0" class="table-el">
					<tr>			
					<td class="st"><input maxLength=3 name="sort[<?php echo $quest['eid']; ?>]" value="<?php echo $quest['sort']; ?>"></td>
					<td class="st"><input maxLength=4 name="score[<?php echo $quest['eid']; ?>]" value="<?php if($quest['score']!=0){ ?><?php echo $quest['score']; ?><?php } ?>"></td>
					<td <?php if(!$quest['display']){ ?>class="hides"<?php } ?>>
						<?php if($quest['image']){ ?>
						 <div class="y emedia">
							<span class="y media"><?php echo $quest['image']; ?></span>
						 </div>
						<?php } ?>
						<div id="subject_<?php echo $quest['eid']; ?>"><?php echo $quest['subject']; ?>
							<?php if($quest['type']==2 || $quest['type']==3){ ?><br><br>
								<?php $did = 0;
 foreach($quest['data'] as $key => $list){ 
 $did++; ?>
								<?php echo chr($did+64); ?>.  <?php echo $list; ?><br>
								<?php } ?>
							<?php } ?>
						</div>
					</td>
				<tr>

				</table>
				<table cellpadding="0" cellspacing="0" class="table-el">
					<tr>
						<td width=85 valign="top" align="right">参考答案:</td>
					<td class="xi1">
						<?php if($quest['type']==1){ ?>
						  <?php if($quest['result']=='1'){ ?>正确<?php } else { ?>错误<?php } ?>
						<?php } elseif($quest['type']=='2' || $quest['type']=='3'){ ?><?php echo $quest['result']; ?>
						<?php } elseif($quest['type']=='4' || $quest['type']=='5'){ ?><?php echo $quest['data']; ?><?php } ?>
					</td>
					<td width="200">
					 <div class="ei-bar">
					     <a href="javascript:;" onclick="user_exam_set_display(<?php echo $quest['eid']; ?>,this)">开关</a>
						 <a href="index.php?exam-master-simulation-modifyitem&eid=<?php echo $quest['eid']; ?>" class="a">编辑</a>
						 <a href="javascript:;" onclick="user_exam_delete(<?php echo $quest['eid']; ?>)">删除</a>
					 </div>
				    </td>
				  </tr>
			   </table>
			</div>
			
		<?php } ?>

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
<?php if(!$this->tpl_var['userhash']){ ?>
		</div>
	</div>
</div>
<?php $this->_compileInclude('footer'); ?>
</body>
</html>
<?php } ?>