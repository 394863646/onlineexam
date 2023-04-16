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
							<li class="active">组卷修改</li>
						</ol>
					</div>
				</div>
				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">
					<h4 class="title" style="padding:10px;">
						组卷修改
						<a class="btn btn-primary pull-right" href="index.php?{x2;$_app}-master-simulation&page={x2;$page}{x2;$u}">试卷管理</a>
					</h4>
			        <form action="index.php?exam-master-simulation-modify" method="post" class="form-horizontal">
						<div class="form-group">
							<label class="control-label col-sm-2">试卷名称：</label>
							<div class="col-sm-4">
					  			<input class="form-control" type="text" name="args[exam]" value="{x2;$exam['exam']}" needle="needle" msg="您必须为该试卷输入一个名称"/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">评卷方式</label>
							<div class="col-sm-9">
								<label class="radio-inline">
									<input name="args[examdecide]" type="radio" value="1"{x2;if:$exam['examdecide']} checked{x2;endif}/>教师评卷
								</label>
								<label class="radio-inline">
									<input name="args[examdecide]" type="radio" value="0"{x2;if:!$exam['examdecide']} checked{x2;endif}/>学生自评
								</label>
								<span class="help-block">教师评卷时有主观题则需要教师在后台评分后才能显示分数，无主观题自动显示分数。</span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">考试科目：</label>
						  	<div class="col-sm-9">
							  	<label class="radio-inline">
								  	<input type="hidden" name="args[examsubject]" value="{x2;$exam['examsubject']}" />
								  	{x2;$subjects[$exam['examsubject']]['subject']}
							  	</label>
					  		</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">考试时间：</label>
					  		<div class="col-sm-9 form-inline">
					  			<input class="form-control" type="text" name="args[examsetting][examtime]" size="4" needle="needle" value="{x2;$exam['examsetting']['examtime']}"/> 分钟
					  		</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">来源：</label>
					  		<div class="col-sm-4">
					  			<input class="form-control" type="text" name="args[examsetting][comfrom]" value="{x2;$exam['examsetting']['comfrom']}" size="4"/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">试卷总分：</label>
					  		<div class="col-sm-4">
					  			<input class="form-control" type="text" name="args[examsetting][score]" value="{x2;$exam['examsetting']['score']}" size="4" needle="needle" msg="你要为本考卷设置总分" datatype="number"/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">及格线：</label>
					  		<div class="col-sm-4">
					  			<input class="form-control" type="text" name="args[examsetting][passscore]" value="{x2;$exam['examsetting']['passscore']}" size="4" needle="needle" msg="你要为本考卷设置一个及格分数线" datatype="number"/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">题型排序</label>
							<div class="col-sm-9">
								<div class="sortable btn-group">
									{x2;if:$exam['examsetting']['questypelite']}
									{x2;tree:$exam['examsetting']['questypelite'],qlid,eqid}
									<a class="btn btn-primary questpanel panel_{x2;v:key}">{x2;$questypes[v:key]['questype']}<input type="hidden" name="args[examsetting][questypelite][{x2;v:key}]" value="1" class="questypepanelinput" id="panel_{x2;v:key}"/></a>
									{x2;endtree}
									{x2;else}
									{x2;tree:$questypes,questype,qid}
									<a class="btn btn-primary questpanel panel_{x2;v:questype['questid']}">{x2;v:questype['questype']}<input type="hidden" name="args[examsetting][questypelite][{x2;v:questype['questid']}]" value="1" class="questypepanelinput" id="panel_{x2;v:questype['questid']}"/></a>
									{x2;endtree}
									{x2;endif}
								</div>
								<span class="help-block">拖拽进行题型排序</span>
							</div>
						</div>
						{x2;tree:$questypes,questype,qid}
						<div class="form-group questpanel panel_{x2;v:questype['questid']}">
							<label class="control-label col-sm-2">{x2;v:questype['questype']}：</label>
							<div class="col-sm-6 form-inline">
								<span class="info">共&nbsp;</span>
								<input id="iselectallnumber_{x2;v:questype['questid']}" type="text" class="form-control" needle="needle" name="args[examsetting][questype][{x2;v:questype['questid']}][number]" value="{x2;eval: echo intval($exam['examsetting']['questype'][v:questype['questid']]['number'])}" size="2" msg="您必须填写总题数"/>
								<span class="info">&nbsp;题，每题&nbsp;</span><input class="form-control" needle="needle" type="text" name="args[examsetting][questype][{x2;v:questype['questid']}][score]" value="{x2;eval: echo floatval($exam['examsetting']['questype'][v:questype['questid']]['score'])}" size="2" msg="您必须填写每题的分值"/>
								<span class="info">&nbsp;分，描述&nbsp;</span><input class="form-control" type="text" name="args[examsetting][questype][{x2;v:questype['questid']}][describe]" value="{x2;$exam['examsetting']['questype'][v:questype['questid']]['describe']}" size="12"/>
							</div>
							<div class="col-sm-4 form-inline">
							 <label ><a title="管理题组" href="index.php?exam-master-simulation-guanli&questiontype={x2;v:questype['questype']}" >管理题组</a></label>
							 <label ><a title="导入题组" class="selfmodal" href="javascript:;" url="index.php?exam-master-simulation-detail&questiontype={x2;v:questype['questype']}&examid={x2;$exam['examid']}&score={x2;$exam['examsetting']['questype'][v:questype['questid']]['score']}" data-target="#modal">导入题组</a></label>
							 <label><a title="删除题组" href="index.php?exam-master-simulation-delg&questiontype={x2;v:questype['questype']}" >删除题组</a></label>
						    </div>
						</div>
						{x2;endtree}
						
					
						<div class="form-group">
							<label class="control-label col-sm-2"></label>
							<div class="col-sm-9">
							  	<button class="btn btn-primary" type="submit">提交</button>
								<input type="hidden" name="submitsetting" value="1"/>
								<input name="examid" type="hidden" value="{x2;$exam['examid']}">
							</div>
						</div>
					</form>
				</div>
			</div>
{x2;if:!$userhash}
		</div>
	</div>
</div>
<div id="modal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" class="close" type="button" data-dismiss="modal">×</button>
				<h4 id="myModalLabel">
					导入题组
				</h4>
			</div>
			<div class="modal-body">
			
			</div>
			<div class="modal-footer">
				 <button aria-hidden="true" class="btn btn-primary" data-dismiss="modal">关闭</button>
			</div>
		</div>
	</div>
</div>
<script>
$.getJSON('index.php?exam-master-basic-getsubjectquestype&subjectid={x2;$exam['examsubject']}&'+Math.random(),function(data){$('.questpanel').hide();$('.questypepanelinput').val('0');for(x in data){$('.panel_'+data[x]).show();$('#panel_'+data[x]).val('1');}});
</script>
{x2;include:footer}
</body>
</html>
{x2;endif}