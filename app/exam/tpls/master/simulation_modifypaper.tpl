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
							<li><a href="index.php?exam-master-exams">试卷管理</a></li>
							<li class="active">修改即时试题</li>
						</ol>
					</div>
				</div>
				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">
					<h4 class="title" style="padding:10px;">
						修改即时试题
						<a class="btn btn-primary pull-right" href="index.php?{x2;$_app}-master-exams&page={x2;$page}{x2;$u}">试卷管理</a>
						<a class="btn btn-primary pull-right" href="index.php?{x2;$_app}-master-exams&page={x2;$page}{x2;$u}">考试记录</a>
					</h4>
						
					<link rel="stylesheet" type="text/css" href="app/core/styles/css/common.css" />

                    <div id="ajaxresult" style="display:none"></div>  
                    <div id="ajaxtiny"></div>
                     <div id="wp" class="wp">
	                 
                     <div id="ct" class="wp cl">
	              <div class="fl bm" style="border:none">
		             <div class="paper fl" id="examination" oncopy="return false;" oncut="return false;">
				     <div class="ptit">
					
					<center>
					<h2>{x2;$paper['exam']}</h2>
 					<p> 
 					作者: <em class="xi1">{x2;$paper['examauthor']}</em> , 
					发布日期: <em class="xi1">{x2;eval: echo date('Y-m-d H:i', $paper['examtime']);}</em>, 
					
					</p>
					</center>
					{x2;if: $paper['content']}<p class="ctt">{x2;$paper['content']} </p>{x2;endif}
				</div>
			
			    <form id="examination_form" method="post" autocomplete="off" onsubmit="" action="plugin.php?id=exam:result&replay=$_GET[replay]&formhash=$_G[formhash]">
						
                     
						{x2;eval:$no=1}
						{x2;tree:$groups,_group,_gid}
						    {x2;if:intval(v:_gid)==0}{x2;eval:continue;}{x2;endif}
							
							  <div class="group">
								{x2;if:!trim(v:_group['content'])}
								<div class="ghr"></div>
								{x2;else}
								<div class="gtit">{x2;v:_group['content']}</div>
								{x2;endif}
							
							 {x2;tree:v:_group['list'],val,id}
								
								{x2;if:$no>1}<div class="ehr"></div>{x2;endif}
								    
								<div class="exam" name="exam" id="examid{x2;v:val['eid']}">
									<a class="flag" href="javascript:;">标记</a>
									<div class="e_no">{x2;eval:echo $no++}#</div>
									<a name="{x2;v:val['eid']}" id="{x2;v:val['eid']}" class="a_tag">&nbsp;</a>
									<map id="item_{x2;v:val['eid']}" eid="{x2;v:val['eid']}" score="{x2;v:val['score']}" type="{x2;v:val['type']}" >
											
											<input type="hidden" name="e_{x2;v:val['eid']}[type]" value="{x2;v:val['type']}">
											
											<div class="subject">{x2;v:val['subject']} ({x2;v:val['score']} 分)
											
												[<a href="plugin.php?id=exam:manage&pid=$v[pid]&gid=$v[gid]&tab=$v[type]&eid=$v[eid]" target="_blank" class="xi1">编辑</a>]
											
											</div>
											{x2;if:v:val['image']}<span class="y emedia">{x2;v:val['image']}</span>{x2;endif}
											
											{x2;if:v:val['type']=='1'}
												<div class="check">
													<label><input type="radio" name="e_{x2;v:val['eid']}[]" value="1">正确</label>
													<label><input type="radio" name="e_{x2;v:val['eid']}[]" value="2">错误</label>
												</div>
											{x2;elseif:v:val['type']=='2' || v:val['type']==3}
												<div class="option">
												
												{x2;tree:v:val['data'],ovl,oid}
													{x2;eval:$c = chr(64 + v:oid);}
													<div class="br"></div>
													<label><input type="{x2;if:v:val['type']==2}radio{x2;else}checkbox{x2;endif}" name="e_{x2;v:val['eid']}[]" value="{x2;$c}" >{x2;$c}. {x2;v:ovl}</label>
												{x2;endtree}
												</div>
											{x2;elseif:v:val['type']=='4'}
												<div class="blank">
			 
												</div>
											{x2;elseif:v:val['type']=='5'}
												<div class="question">
													<textarea name="e_{x2;v:val['eid']}[]"></textarea><br><br>
													
												</div>
											{x2;endif}
											<cite class="emsg"><span class="wrong"></span><b>参考答案: </b>
												{x2;if:trim(v:val['note'])}<a class="note" href="javascript:;" onclick="exam_note_show({x2;v:val['eid']})">本题解释</a>{x2;endif}
												
												<br>
												<span class="result" style="margin-left:30px;text-indent:2em;"></span>
											</cite>
									</map>
								</div>
							  {x2;endtree}
							</div>
						{x2;endtree}
					
					{x2;include:layer}

					</form>

					{x2;if:isset($_GET['mobi'])}
						<div class="exam-next-nav">
							<button class="btn1" onclick="test_btnNav('prev')">上一题</button>
							<button class="btn1" onclick="test_btnNav('next')">下一题</button>
							{x2;if: $config[showexam]}
							   <button class="btn1" onclick="test_show_exam()">显示答案</button>
							{x2;endif}
						</div>
						<script type="text/javascript">
							var idfromurl =location.href.match(/#(\d+)/i);
							var currentElement = $('examid' + (idfromurl ? idfromurl[1] : examid_first));
							if(!currentElement)currentElement=$('examid' + examid_first);
							currentElement.style.display = 'block';
						</script>
					{x2;endif}
 				
		</div>
 	</div>
    <script type="text/javascript"  src="app/core/styles/js/commonp.js"></script>
	<script type="text/javascript"  src="app/core/styles/js/paper.js"></script>
	<script type="text/javascript">
		//常量定义
		var COLOR_FLAG		= '#F5F6CE';
		var COLOR_SELECT	= '#EFF7FC';
		var COLOR_UNSELECT	= '';
		var SCORE_TOTAL		= '{x2;$paper['total_score']}';
		var SCORE_PASS		= '{x2;$paper['examsetting']['passscore']}';
		var PAPER_PID		= '{x2;$paper['examid']}';
		var PAPER_CID		= '{x2;$paper['examsubject']}';
		var PAPER_PRICE		= '0';
		var TIME_LEFT		= '{x2;$paper['examsetting']['examtime']}' * 60;		
		var TIME_PASS		= 0;
		var TIME_TIMER		= null;
		var RESULT			= null;
		var SUBMIT_WAIT		= '{x2;eval:echo $paper['examsetting']['examtime']-30}'*60;
 
		
 
		
		
	
			
		$(window).load(function() {
			if(label_bind_click()){alert();
				TIME_TIMER = setInterval('time_counter()',1000);
			}
		});
		
		
		{x2;if: $_GET[replay]>0}
		exam_submit_get_result();
		{x2;endif}
		
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