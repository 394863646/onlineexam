<style>
.panel-heading a{
color:#FFFFFF;
text-decoration:none;}
</style>
<div class="panel-group" id="panel-13465">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<a class="panel-toggle text-center" data-parent="#panel-13465" data-toggle="collapse" href="#panel-element-707348">
				<em class="glyphicon glyphglyphicon glyphicon-th-large"></em> 考试设计
			</a>
		</div>
		<div id="panel-element-707348" class="panel-collapse{x2;if:$method == 'basic'} in{x2;endif} collapse" role="tabpanel">
     		<ul class="list-group">
				<li class="list-group-item"><a href="?{x2;$_app}-master-basic">考场列表</a></li>
	    		<li class="list-group-item"><a href="?{x2;$_app}-master-basic-area">地区设置</a></li>
	    		<li class="list-group-item"><a href="?{x2;$_app}-master-basic-subject">科目管理</a></li>
	    		<li class="list-group-item"><a href="?{x2;$_app}-master-basic-questype">题型管理</a></li>
			</ul>
		</div>
	</div>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<a class="panel-toggle text-center" data-parent="#panel-13465" data-toggle="collapse" href="#panel-element-2120761">
				<em class="glyphicon glyphglyphicon glyphicon-user"></em> 课程开通
			</a>
		</div>
		<div id="panel-element-2120761" class="panel-collapse{x2;if:$method == 'users'} in{x2;endif} collapse" role="tabpanel">
     		<ul class="list-group">
				<li class="list-group-item"><a href="index.php?{x2;$_app}-master-users">开通课程</a></li>
				<li class="list-group-item"><a href="index.php?{x2;$_app}-master-users-batopen">批量开通</a></li>
			</ul>
		</div>
	</div>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<a class="panel-toggle text-center" data-parent="#panel-13465" data-toggle="collapse" href="#panel-element-212076">
				<em class="glyphicon glyphglyphicon glyphicon-text-color"></em> 试题管理
			</a>
		</div>
		<div id="panel-element-212076" class="panel-collapse{x2;if:$method == 'questions' || $method == 'simulation' || $method == 'rowsquestions'} in{x2;endif} collapse" role="tabpanel">
     		<ul class="list-group">
				<li class="list-group-item"><a href="index.php?{x2;$_app}-master-questions">普通试题管理</a></li>
				<li class="list-group-item"><a href="index.php?{x2;$_app}-master-simulation">制作试卷</a></li>
				<li class="list-group-item"><a href="index.php?{x2;$_app}-master-rowsquestions">题帽题管理</a></li>
			</ul>
		</div>
	</div>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<a class="panel-toggle text-center" data-parent="#panel-13465" data-toggle="collapse" href="#panel-element-212096">
				<em class="glyphicon glyphglyphicon glyphicon-duplicate"></em> 试卷管理
			</a>
		</div>
		<div id="panel-element-212096" class="panel-collapse{x2;if:$method == 'exams'} in{x2;endif} collapse" role="tabpanel">
     		<ul class="list-group">
				<li class="list-group-item"><a href="index.php?{x2;$_app}-master-exams">试卷列表</a></li>
				<li class="list-group-item"><a href="index.php?{x2;$_app}-master-exams-autopage">随机组卷</a></li>
				<li class="list-group-item"><a href="index.php?{x2;$_app}-master-exams-selfpage">手工组卷</a></li>
				<li class="list-group-item"><a href="index.php?{x2;$_app}-master-exams-temppage">即时组卷</a></li>
			</ul>
		</div>
	</div>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<a class="panel-toggle text-center" data-parent="#panel-13465" data-toggle="collapse" href="#panel-element-212090">
				<em class="glyphicon glyphglyphicon glyphicon-trash"></em> 回收站
			</a>
		</div>
		<div id="panel-element-212090" class="panel-collapse{x2;if:$method == 'recyle'} in{x2;endif} collapse" role="tabpanel">
     		<ul class="list-group">
				<li class="list-group-item"><a href="index.php?{x2;$_app}-master-recyle">普通试题</a></li>
				<li class="list-group-item"><a href="index.php?{x2;$_app}-master-recyle-rows">题帽题</a></li>
				<li class="list-group-item"><a href="index.php?{x2;$_app}-master-recyle-knows">知识点</a></li>
			</ul>
		</div>
	</div>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<a class="panel-toggle text-center" data-parent="#panel-13465" data-toggle="collapse" href="#panel-element-2120901">
				<em class="glyphicon glyphglyphicon glyphicon-wrench"></em> 批量工具
			</a>
		</div>
		<div id="panel-element-2120901" class="panel-collapse{x2;if:$method == 'tools'} in{x2;endif} collapse" role="tabpanel">
     		<ul class="list-group">
				<li class="list-group-item"><a href="index.php?{x2;$_app}-master-tools">删除试题</a></li>
				<li class="list-group-item"><a href="index.php?{x2;$_app}-master-tools-clearhistory">清空考试记录</a></li>
				<li class="list-group-item"><a href="index.php?{x2;$_app}-master-tools-clearsession">清理会话</a></li>
			</ul>
		</div>
	</div>
</div>