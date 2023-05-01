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
							<li><a href="index.php?exam-master-exams">试卷管理</a></li>
							<li class="active">修改即时试题</li>
						</ol>
					</div>
				</div>
				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">
					<h4 class="title" style="padding:10px;">
						修改即时试题
						<a class="btn btn-primary pull-right" href="index.php?<?php echo $this->tpl_var['_app']; ?>-master-exams&page=<?php echo $this->tpl_var['page']; ?><?php echo $this->tpl_var['u']; ?>">试卷管理</a>
						<a class="btn btn-primary pull-right" href="index.php?<?php echo $this->tpl_var['_app']; ?>-master-exams&page=<?php echo $this->tpl_var['page']; ?><?php echo $this->tpl_var['u']; ?>">考试记录</a>
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
					<h2><?php echo $this->tpl_var['paper']['exam']; ?></h2>
 					<p> 
 					作者: <em class="xi1"><?php echo $this->tpl_var['paper']['examauthor']; ?></em> , 
					发布日期: <em class="xi1"><?php echo date('Y-m-d H:i', $this->tpl_var['paper']['examtime']);; ?></em>, 
					
					</p>
					</center>
					<?php if( $this->tpl_var['paper']['content']){ ?><p class="ctt"><?php echo $this->tpl_var['paper']['content']; ?> </p><?php } ?>
				</div>
			
			    <form id="examination_form" method="post" autocomplete="off" onsubmit="" action="plugin.php?id=exam:result&replay=$_GET[replay]&formhash=$_G[formhash]">
						
                     
						<?php $this->tpl_var['no']=1; ?>
						<?php $_gid = 0;
 foreach($this->tpl_var['groups'] as $key => $_group){ 
 $_gid++; ?>
						    <?php if(intval($_gid)==0){ ?><?php continue;; ?><?php } ?>
							
							  <div class="group">
								<?php if(!trim($_group['content'])){ ?>
								<div class="ghr"></div>
								<?php } else { ?>
								<div class="gtit"><?php echo $_group['content']; ?></div>
								<?php } ?>
							
							 <?php $id = 0;
 foreach($_group['list'] as $key => $val){ 
 $id++; ?>
								
								<?php if($this->tpl_var['no']>1){ ?><div class="ehr"></div><?php } ?>
								    
								<div class="exam" name="exam" id="examid<?php echo $val['eid']; ?>">
									<a class="flag" href="javascript:;">标记</a>
									<div class="e_no"><?php echo $this->tpl_var['no']++; ?>#</div>
									<a name="<?php echo $val['eid']; ?>" id="<?php echo $val['eid']; ?>" class="a_tag">&nbsp;</a>
									<map id="item_<?php echo $val['eid']; ?>" eid="<?php echo $val['eid']; ?>" score="<?php echo $val['score']; ?>" type="<?php echo $val['type']; ?>" >
											
											<input type="hidden" name="e_<?php echo $val['eid']; ?>[type]" value="<?php echo $val['type']; ?>">
											
											<div class="subject"><?php echo $val['subject']; ?> (<?php echo $val['score']; ?> 分)
											
												[<a href="plugin.php?id=exam:manage&pid=$v[pid]&gid=$v[gid]&tab=$v[type]&eid=$v[eid]" target="_blank" class="xi1">编辑</a>]
											
											</div>
											<?php if($val['image']){ ?><span class="y emedia"><?php echo $val['image']; ?></span><?php } ?>
											
											<?php if($val['type']=='1'){ ?>
												<div class="check">
													<label><input type="radio" name="e_<?php echo $val['eid']; ?>[]" value="1">正确</label>
													<label><input type="radio" name="e_<?php echo $val['eid']; ?>[]" value="2">错误</label>
												</div>
											<?php } elseif($val['type']=='2' || $val['type']==3){ ?>
												<div class="option">
												
												<?php $oid = 0;
 foreach($val['data'] as $key => $ovl){ 
 $oid++; ?>
													<?php $this->tpl_var['c'] = chr(64 + $oid);; ?>
													<div class="br"></div>
													<label><input type="<?php if($val['type']==2){ ?>radio<?php } else { ?>checkbox<?php } ?>" name="e_<?php echo $val['eid']; ?>[]" value="<?php echo $this->tpl_var['c']; ?>" ><?php echo $this->tpl_var['c']; ?>. <?php echo $ovl; ?></label>
												<?php } ?>
												</div>
											<?php } elseif($val['type']=='4'){ ?>
												<div class="blank">
			 
												</div>
											<?php } elseif($val['type']=='5'){ ?>
												<div class="question">
													<textarea name="e_<?php echo $val['eid']; ?>[]"></textarea><br><br>
													
												</div>
											<?php } ?>
											<cite class="emsg"><span class="wrong"></span><b>参考答案: </b>
												<?php if(trim($val['note'])){ ?><a class="note" href="javascript:;" onclick="exam_note_show(<?php echo $val['eid']; ?>)">本题解释</a><?php } ?>
												
												<br>
												<span class="result" style="margin-left:30px;text-indent:2em;"></span>
											</cite>
									</map>
								</div>
							  <?php } ?>
							</div>
						<?php } ?>
					
					<?php $this->_compileInclude('layer'); ?>

					</form>

					<?php if(isset($this->tpl_var['_GET']['mobi'])){ ?>
						<div class="exam-next-nav">
							<button class="btn1" onclick="test_btnNav('prev')">上一题</button>
							<button class="btn1" onclick="test_btnNav('next')">下一题</button>
							<?php if( $this->tpl_var['config'][showexam]){ ?>
							   <button class="btn1" onclick="test_show_exam()">显示答案</button>
							<?php } ?>
						</div>
						<script type="text/javascript">
							var idfromurl =location.href.match(/#(\d+)/i);
							var currentElement = $('examid' + (idfromurl ? idfromurl[1] : examid_first));
							if(!currentElement)currentElement=$('examid' + examid_first);
							currentElement.style.display = 'block';
						</script>
					<?php } ?>
 				
		</div>
 	</div>
    <script type="text/javascript"  src="app/core/styles/js/commonp.js"></script>
	<script type="text/javascript"  src="app/core/styles/js/paper.js"></script>
	<script type="text/javascript">
		//常量定义
		var COLOR_FLAG		= '#F5F6CE';
		var COLOR_SELECT	= '#EFF7FC';
		var COLOR_UNSELECT	= '';
		var SCORE_TOTAL		= '<?php echo $this->tpl_var['paper']['total_score']; ?>';
		var SCORE_PASS		= '<?php echo $this->tpl_var['paper']['examsetting']['passscore']; ?>';
		var PAPER_PID		= '<?php echo $this->tpl_var['paper']['examid']; ?>';
		var PAPER_CID		= '<?php echo $this->tpl_var['paper']['examsubject']; ?>';
		var PAPER_PRICE		= '0';
		var TIME_LEFT		= '<?php echo $this->tpl_var['paper']['examsetting']['examtime']; ?>' * 60;		
		var TIME_PASS		= 0;
		var TIME_TIMER		= null;
		var RESULT			= null;
		var SUBMIT_WAIT		= '<?php echo $this->tpl_var['paper']['examsetting']['examtime']-30; ?>'*60;
 
		
 
		
		
	
			
		$(window).load(function() {
			if(label_bind_click()){alert();
				TIME_TIMER = setInterval('time_counter()',1000);
			}
		});
		
		
		<?php if( $this->tpl_var['_GET'][replay]>0){ ?>
		exam_submit_get_result();
		<?php } ?>
		
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