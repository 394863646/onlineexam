<?php
/*
 * Created on 2016-5-19
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class action extends app
{
	public function display()
	{
		$action = $this->ev->url(3);
		if (!method_exists($this, $action))
			$action = "index";
		$this->$action();
		exit;
	}

	private function selectexams()
	{
		$search = $this->ev->get('search');
		$useframe = $this->ev->get('useframe');
		$target = $this->ev->get('target');
		$page = $this->ev->get('page');
		$page = $page > 0 ? $page : 1;
		$this->pg->setUrlTarget('modal-body" class="ajax');
		$args = array();
		if ($search) {
			if ($search['subjectid']) $args[] = array("AND", "examsubject = :examsubject", 'examsubject', $search['subjectid']);
		}
		if (!count($args)) $args = 1;
		$exams = $this->exam->getExamSettingList($page, 10, $args);
		$subjects = $this->basic->getSubjectList();
		$this->tpl->assign('subjects', $subjects);
		$this->tpl->assign('target', $target);
		$this->tpl->assign('exams', $exams);
		$this->tpl->display('exams_ajax');
	}

	private function delexam()
	{
		$examid = $this->ev->get('examid');
		$page = $this->ev->get('page');
		$this->exam->delExamSetting($examid);
		$message = array(
			'statusCode' => 200,
			"message" => "操作成功",
			"callbackType" => "forward",
			"forwardUrl" => "index.php?exam-master-exams&page={$page}{$u}"
		);
		$this->G->R($message);
	}

	private function preview()
	{
		$examid = $this->ev->get('examid');
		$r = $this->exam->getExamSettingById($examid);
		$this->tpl->assign("setting", $r);
		if ($r['examtype'] == 2) {
			$questions = array();
			$questionrows = array();
			foreach ($r['examquestions'] as $key => $p) {
				$qids = '';
				$qrids = '';
				if ($p['questions']) $qids = trim($p['questions'], " ,");
				if ($qids)
					$questions[$key] = $this->exam->getQuestionListByIds($qids);
				if ($p['rowsquestions']) $qrids = trim($p['rowsquestions'], " ,");
				if ($qrids) {
					$qrids = explode(",", $qrids);
					foreach ($qrids as $t) {
						$qr = $this->exam->getQuestionRowsById($t);
						if ($qr)
							$questionrows[$key][$t] = $qr;
					}
				}
			}
			$args['examsessionquestion'] = array('questions' => $questions, 'questionrows' => $questionrows);
			$args['examsessionsetting'] = $r;
			$args['examsessionstarttime'] = TIME;
			$args['examsession'] = $r['exam'];
			$args['examsessionscore'] = 0;
			$args['examsessiontime'] = $r['examsetting']['examtime'];
			$args['examsessiontype'] = 2;
			$args['examsessionkey'] = $r['examid'];
			$args['examsessionissave'] = 0;
		} else {
			$args['examsessionquestion'] = array('questions' => $r['examquestions']['questions'], 'questionrows' => $r['examquestions']['questionrows']);
			$args['examsessionsetting'] = $r;
			$args['examsessionstarttime'] = TIME;
			$args['examsession'] = $r['exam'];
			$args['examsessiontime'] = $r['examsetting']['examtime'];
			$args['examsessiontype'] = 2;
			$args['examsessionkey'] = $r['examid'];
		}
		$questype = $this->basic->getQuestypeList();
		$this->tpl->assign('questype', $questype);
		$this->tpl->assign("sessionvars", $args);
		$this->tpl->display('exams_paper');
	}

	private function modifypaper()
	{
		$examid = $this->ev->get('examid');
		
		$r = $this->exam->getExamSettingById($examid);
		
		if ($this->ev->get('modifypaper')) {
			$args = $this->ev->get('args');
			$targs = $this->ev->get('targs');
			
			$this->exam->modifyExamSetting($examid, array('examquestions' => $r['examquestions']));
			$message = array(
				'statusCode' => 200,
				"message" => "操作成功",
				"callbackType" => "forward",
				"forwardUrl" => "index.php?exam-master-exams-preview&examid=" . $examid
			);
			$this->G->R($message);
		} else {
			
			$groupso = $r['examsetting']['questype'];
			
			$score = 0;
			$groups = [];
			foreach ($groupso AS $gid=>$g){
				if(intval($g['number'])>0){
					$groups[$gid]['content'] = str_replace("\n","<br>",$g['describe']);
					
					if($exams = $this->exam->fetch_exam_by_gid($gid,$examid)){
						foreach($exams AS $ek=>$e){
						   if($e['score']==0) $exams[$ek]['score'] = $g['score'];
								
							$exams[$ek]['content'] = preg_replace("/(<span[^>]*>)|(<\/span>)/", '', $e['content']);
							$exams[$ek]['note']    = preg_replace("/(<span[^>]*>)|(<\/span>)/", '', $e['note']);
							$exams[$ek]['data']    = unserialize($e['data']);
							if($e['type']=='4'){
								$replace_str = '&nbsp;<label class="norcur"><input type="text" name="e_'. $e['eid'].'[]" value=""></label>&nbsp;';
								$exams[$ek]['subject'] = preg_replace("/(\{[^|\}]+\|[^\}]+\})|(\_{2,})|(\((\s|&nbsp;)+\))/", $replace_str, $e['subject']);
							}
							$r['total_score'] += $exams[$ek]['score'];
						}
						$groups[$gid]['list'] = $exams;
					}else{
						$groups[$gid]['list'] = array();
						//unset($groups[$gid]);
					}
				}
			}
			
			//var_dump($r);
			$this->tpl->assign("examid", $examid);
			$this->tpl->assign("paper", $r);
			$this->tpl->assign("groups", $groups);
			$this->tpl->display('simulation_modifypaper');
		}
	}


	private function  hook_match_replace($matches){
			
			global $hv,$v;
			if($matches[1]){
				$options = explode('|', $matches[1]);
				$string  = '&nbsp;<label><input type="hidden" name="e_'. $v['eid'].'[]" value="'.$hv.'">';
				$string .= '<select class="norcur" onchange="this.parentNode.getElementsByTagName(\'input\')[0].value=this.value" value="'.$hv.'"><option value="">&#x9009;&#x62E9;:</option>';
				foreach($options AS $ov){
					$selected = $ov==$hv ? 'selected' : '';
					$string .= "<option value=\"$ov\" $selected>$ov</option>";
				}
				$string .= "</select></label>&nbsp;";
			}else if($matches[2] || $matches[3]){
				$string = '&nbsp;<label class="norcur"><input type="text" name="e_'. $v['eid'].'[]" value="'.$hv.'"></label>&nbsp;';
			}
			return $string;
		}
		
	private function questiongroup()
	{
		$examid = $this->ev->get('examid');
		$questiontype = $this->ev->get('questiontype');

		$r = $this->exam->getExamSettingById($examid);
		$questype = $this->basic->getQuestypeByName($questiontype);
		$gid = $questype['questid'];
		$this->tpl->assign("questiontype", $questiontype);
		$this->tpl->assign("gid", $gid);

		if ($this->ev->get('modifypaper')) {
			$sort = $this->ev->get('sort');
			$score = $this->ev->get('score');
			
			
			foreach ($sort as $k=>$v){
				$id = $k;
				$this->exam->updateExamQuestions($id, array('sort'  => $v));
				$this->exam->updateExamQuestions($id, array('score'  => $score[$id]));
			}
			
			$message = array(
				'statusCode' => 200,
				"message" => "操作成功",
				"callbackType" => "forward",
				"forwardUrl" => "reload",//index.php?exam-master-simulation-questiongroup&page=&questiontype=" . $questiontype . "&examid=" . $examid
			);
			$this->G->R($message);
		} else {
			$questions = $this->exam->getExamQuestions($gid, $examid, 0);

			foreach ($questions as $ek => $e) {
				$questions[$ek]['subject'] = str_replace("\n", "<br>", $e['subject']);
				$questions[$ek]['note']    = str_replace("\n", "<br>", $e['note']);
				if($e['type']=='4' || $e['type']=='5'){
					$questions[$ek]['data']    = $e['data'];
				}else{
					$questions[$ek]['data']    = unserialize($e['data']);
				}
				
				if(!empty($e['image'])){
					$questions[$ek]['image']    = $this->media_html($e['image']);
				}else{
					$questions[$ek]['image']    = "";
				}
				
			}

			


			$this->tpl->assign("examid", $examid);
			$this->tpl->assign("exam", $r['exam']);
			$this->tpl->assign("examsetting", $r['examsetting']['questype'][$gid]);
			$this->tpl->assign("questions", $questions);
			$this->tpl->display('simulation_questiongroup');
		}
	}

	private function outcsv()
	{
		$this->files = $this->G->make('files');
		$examid = $this->ev->get('examid');
		$exam = $this->exam->getExamSettingById($examid);
		$questypes = $this->basic->getQuestypeList();
		$data = array();
		foreach ($exam['examquestions'] as $tp) {
			foreach ($tp as $p)
				$data[] = array(iconv("UTF-8", "GBK//IGNORE", $questypes[$p['questiontype']]['questchar']), iconv("UTF-8", "GBK//IGNORE", html_entity_decode($p['question'])), iconv("UTF-8", "GBK//IGNORE", html_entity_decode($p['questionselect'])), iconv("UTF-8", "GBK//IGNORE", $p['questionselectnumber']), iconv("UTF-8", "GBK//IGNORE", html_entity_decode($p['questionanswer'])), iconv("UTF-8", "GBK//IGNORE", html_entity_decode($p['questiondescribe'])));
		}
		$fname = 'data/exams/' . TIME . '-' . $examid . '-score.csv';
		if ($this->files->outCsv($fname, $data))
			$message = array(
				'statusCode' => 200,
				"message" => "成绩导出成功，转入下载页面，如果浏览器没有相应，请<a href=\"{$fname}\">点此下载</a>",
				"callbackType" => 'forward',
				"forwardUrl" => "{$fname}"
			);
		else
			$message = array(
				'statusCode' => 300,
				"message" => "成绩导出失败"
			);
		$this->G->R($message);
	}

	private function ajax()
	{
		switch ($this->ev->url(4)) {
			default:
				$subjectid = $this->ev->get('subjectid');
				$type = $this->ev->get('type');
				if ($subjectid) {
					$basic = $this->basic->getBasicBySubjectId($subjectid);
					$questypes = $this->basic->getQuestypeList();
					$this->tpl->assign('questypes', $questypes);
					$this->tpl->assign("type", $type);
					$this->tpl->assign("subjectid", $subjectid);
					$this->tpl->assign("basic", $basic);
					$this->tpl->display('exams_ajaxsetting');
				}
		}
	}

	private function user_exam_set_display()
	{
		$eid = $this->ev->get('eid');
		$r = $this->exam->getExamQuestionsById($eid);
		
		$display = $r['display'];
		$display = $display ? '0' : '1';
		
		$this->exam->updateExamQuestions($eid, array('display'  => $display));
		
		exit(json_encode($display));
	}
	
	private function user_exam_delete()
	{
		$eid = $this->ev->get('eid');
		$r = $this->exam->delExamQuestions($eid);
		$status=$r?1:0;
		exit(json_encode($status));
	}

	private function del()
	{
		$page = $this->ev->get('page');
		$examid = $this->ev->get('examid');
		$this->exam->delExamSetting($examid);
		$message = array(
			'statusCode' => 200,
			"message" => "操作成功",
			"callbackType" => "forward",
			"forwardUrl" => "index.php?exam-master-exams&page={$page}{$u}"
		);
		$this->G->R($message);
	}

	private function autopage()
	{
		if ($this->ev->get('submitsetting')) {
			$args = $this->ev->get('args');
			$args['examsetting'] = $args['examsetting'];
			$args['examauthorid'] = $this->_user['sessionuserid'];
			$args['examauthor'] = $this->_user['sessionusername'];
			$args['examtype'] = 8;

			foreach ($args['examsetting']['questype'] as $key => $p) {
				if (!$args['examsetting']['questypelite'][$key]) {
					unset($args['examsetting']['questype'][$key], $args['examquestions'][$key]);
				}
			}

			$this->exam->addExamSetting($args);
			$message = array(
				'statusCode' => 200,
				"message" => "操作成功",
				"forwardUrl" => "index.php?exam-master-exams&page={$page}{$u}"
			);
			$this->G->R($message);
		} else {
			$subjects = $this->basic->getSubjectList();
			$questypes = $this->basic->getQuestypeList();
			$this->tpl->assign('questypes', $questypes);
			$this->tpl->assign('subjects', $subjects);
			$this->tpl->display('simulation_auto');
		}
	}

	private function selfpage()
	{
		if ($this->ev->get('submitsetting')) {
			$args = $this->ev->get('args');
			$args['examsetting'] = $args['examsetting'];
			$args['examauthorid'] = $this->_user['sessionuserid'];
			$args['examauthor'] = $this->_user['sessionusername'];
			$args['examtype'] = 2;
			$args['examquestions'] = $args['examquestions'];

			foreach ($args['examsetting']['questype'] as $key => $p) {
				if (!$args['examsetting']['questypelite'][$key]) {
					unset($args['examsetting']['questype'][$key], $args['examquestions'][$key]);
				}
			}


			$id = $this->exam->addExamSetting($args);
			$message = array(
				'statusCode' => 200,
				"message" => "操作成功",
				"callbackType" => "forward",
				"forwardUrl" => "index.php?exam-master-exams-examself&examid={$id}&page={$page}{$u}"
			);
			$this->G->R($message);
		} else {
			$subjects = $this->basic->getSubjectList();
			$questypes = $this->basic->getQuestypeList();
			$this->tpl->assign('questypes', $questypes);
			$this->tpl->assign('subjects', $subjects);
			$this->tpl->display('exams_self');
		}
	}

	private function temppage()
	{
		if ($this->ev->get('submitsetting')) {
			$args = $this->ev->get('args');
			$uploadfile = $this->ev->get('uploadfile');
			if (!$uploadfile) {
				$message = array(
					'statusCode' => 300,
					"message" => "请上传即时试卷试题"
				);
				$this->G->R($message);
			}
			$args['examsetting'] = $args['examsetting'];
			$args['examauthorid'] = $this->_user['sessionuserid'];
			$args['examauthor'] = $this->_user['sessionusername'];
			$args['examtype'] = 3;
			setlocale(LC_ALL, 'zh_CN');
			$handle = fopen($uploadfile, "r");
			$questions = array();
			$rindex = 0;
			$index = 0;
			while ($data = fgetcsv($handle)) {
				$targs = array();
				$question = $data;
				if (count($question) >= 5) {
					$isqr = intval(trim($question[6], " \n\t"));
					if ($isqr) {
						$istitle = intval(trim($question[7], " \n\t"));;
						if ($istitle) {
							$rindex++;
							$targs['qrid'] = 'qr_' . $rindex;
							$targs['qrtype'] = $question[0];
							$targs['qrquestion'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK", "UTF-8//IGNORE", trim(nl2br($question[1]), " \n\t"))));
							$targs['qrcreatetime'] = TIME;
							$questionrows[$targs['qrtype']][intval($rindex - 1)] = $targs;
						} else {
							$index++;
							$targs['questionid'] = 'q_' . $index;
							$targs['questiontype'] = $question[0];
							$targs['question'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK", "UTF-8//IGNORE", trim(nl2br($question[1]), " \n\t"))));
							$targs['questionselect'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK", "UTF-8//IGNORE", trim(nl2br($question[2]), " \n\t"))));
							if (!$targs['questionselect'] && $targs['questiontype'] == 3)
								$targs['questionselect'] = '<p>A、对<p><p>B、错<p>';
							$targs['questionselectnumber'] = $question[3];
							$targs['questionanswer'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK", "UTF-8//IGNORE", trim($question[4], " \n\t"))));
							$targs['questiondescribe'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK", "UTF-8//IGNORE", trim($question[5], " \n\t"))));
							$targs['questioncreatetime'] = TIME;
							$questionrows[$targs['questiontype']][intval($rindex - 1)]['data'][] = $targs;
							//$qustionnumber++;
						}
					} else {
						$index++;
						$targs['questionid'] = 'q_' . $index;
						$targs['questiontype'] = $question[0];
						$targs['question'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK", "UTF-8//IGNORE", trim(nl2br($question[1]), " \n\t"))));
						$targs['questionselect'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK", "UTF-8//IGNORE", trim(nl2br($question[2]), " \n\t"))));
						if (!$targs['questionselect'] && $targs['questiontype'] == 3)
							$targs['questionselect'] = '<p>A、对<p><p>B、错<p>';
						$targs['questionselectnumber'] = intval($question[3]);
						$targs['questionanswer'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK", "UTF-8//IGNORE", trim($question[4], " \n\t"))));
						$targs['questiondescribe'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK", "UTF-8//IGNORE", trim($question[5], " \n\t"))));
						$targs['questioncreatetime'] = TIME;
						$questions[$targs['questiontype']][] = $targs;
						//$qustionnumber++;
					}
				}
			}
			$args['examquestions'] = array('questions' => $questions, 'questionrows' => $questionrows);
			//$args['examsetting']['questype'][1]['number'] = $qustionnumber;
			//$args['examsetting']['questype'][1]['score'] = intval(100/$qustionnumber);
			$id = $this->exam->addExamSetting($args);
			$message = array(
				'statusCode' => 200,
				"message" => "操作成功",
				"callbackType" => "forward",
				"forwardUrl" => "index.php?exam-master-exams-examself&examid={$id}&page={$page}{$u}"
			);
			$this->G->R($message);
		} else {
			$subjects = $this->basic->getSubjectList();
			$questypes = $this->basic->getQuestypeList();
			$this->tpl->assign('questypes', $questypes);
			$this->tpl->assign('subjects', $subjects);
			$this->tpl->display('exams_temp');
		}
	}

	private function selected()
	{
		$show = $this->ev->get('show');
		$questionids = trim($this->ev->get('questionids'), " ,");
		$rowsquestionids = trim($this->ev->get('rowsquestionids'), " ,");
		if (!$questionids) $questionids = '0';
		if (!$rowsquestionids) $rowsquestionids = '0';
		$questions = $this->exam->getQuestionListByArgs(array(array("AND", "questionstatus = 1"), array("AND", "find_in_set(questionid,:questionid)", 'questionid', $questionids)));
		$rowsquestions = array();
		$rowsquestionids = explode(',', $rowsquestionids);
		foreach ($rowsquestionids as $p) {
			if ($p)
				$rowsquestions[$p] = $this->exam->getQuestionRowsByArgs(array(array("AND", "qrstatus = 1"), array("AND", "qrid = :qrid", 'qrid', $p)));
		}
		$this->tpl->assign('rowsquestions', $rowsquestions);
		$this->tpl->assign('questions', $questions);
		$this->tpl->assign('show', $show);
		$this->tpl->display('exams_selected');
	}

	private function selectquestions()
	{
		$search = $this->ev->get('search');
		$useframe = $this->ev->get('useframe');
		$page = $this->ev->get('page');
		$page = $page > 0 ? $page : 1;
		$this->pg->setUrlTarget('modal-body" class="ajax');
		if (!$search['questionisrows']) {
			$args = array(array("AND", "quest2knows.qkquestionid = questions.questionid"), array("AND", "questions.questionstatus = '1'"), array("AND", "questions.questionparent = 0"), array("AND", "quest2knows.qktype = 0"));
			if ($search['keyword']) {
				$args[] = array("AND", "questions.question LIKE :question", 'question', '%' . $search['keyword'] . '%');
			}
			if ($search['knowsids']) {
				$args[] = array("AND", "find_in_set(questions.questionknowsid,:questionknowsid)", 'questionknowsid', $search['knowsids']);
			}
			if ($search['stime']) {
				$args[] = array("AND", "questions.questioncreatetime >= :squestioncreatetime", 'squestioncreatetime', strtotime($search['stime']));
			}
			if ($search['etime']) {
				$args[] = array("AND", "questions.questioncreatetime <= :equestioncreatetime", 'equestioncreatetime', strtotime($search['etime']));
			}
			if ($search['questiontype']) {
				$args[] = array("AND", "questions.questiontype = :questiontype", 'questiontype', $search['questiontype']);
			}
			if ($search['questionlevel']) {
				$args[] = array("AND", "questions.questionlevel = :questionlevel", 'questionlevel', $search['questionlevel']);
			}
			if ($search['questionknowsid']) {
				$args[] = array("AND", "quest2knows.qkknowsid = :qkknowsid", 'qkknowsid', $search['questionknowsid']);
			} else {
				$tmpknows = '0';
				if ($search['questionsectionid']) {
					$knows = $this->section->getKnowsListByArgs(array(array("AND", "knowsstatus = 1"), array("AND", "knowssectionid = :knowssectionid", 'knowssectionid', $search['questionsectionid'])));
					foreach ($knows as $p) {
						if ($p['knowsid']) $tmpknows .= ',' . $p['knowsid'];
					}
					$args[] = array("AND", "find_in_set(quest2knows.qkknowsid,:qkknowsid)", 'qkknowsid', $tmpknows);
				} elseif ($search['questionsubjectid']) {
					$knows = $this->section->getAllKnowsBySubject($search['questionsubjectid']);
					foreach ($knows as $p) {
						if ($p['knowsid']) $tmpknows .= ',' . $p['knowsid'];
					}
					$args[] = array("AND", "find_in_set(quest2knows.qkknowsid,:qkknowsid)", 'qkknowsid', $tmpknows);
				} else {
					$knows = $this->section->getAllKnowsBySubjects($this->teachsubjects);
					foreach ($knows as $p) {
						if ($p['knowsid']) $tmpknows .= ',' . $p['knowsid'];
					}
					$args[] = array("AND", "find_in_set(quest2knows.qkknowsid,:qkknowsid)", 'qkknowsid', $tmpknows);
				}
			}

			$questions = $this->exam->getQuestionsList($page, 10, $args);
		} else {
			$args = array(array("AND", "quest2knows.qkquestionid = questionrows.qrid"), array("AND", "questionrows.qrstatus = '1'"));
			if ($search['keyword']) {
				$args[] = array("AND", "questionrows.qrquestion LIKE :qrquestion", 'qrquestion', '%' . $search['keyword'] . '%');
			}
			if ($search['questiontype']) {
				$args[] = array("AND", "questionrows.qrtype = :qrtype", 'qrtype', $search['questiontype']);
			}
			if ($search['stime']) {
				$args[] = array("AND", "questionrows.qrtime >= :sqrtime", 'sqrtime', strtotime($search['stime']));
			}
			if ($search['etime']) {
				$args[] = array("AND", "questionrows.qrtime <= :eqrtime", 'eqrtime', strtotime($search['etime']));
			}
			if ($search['qrlevel']) {
				$args[] = array("AND", "questionrows.qrlevel = :qrlevel", 'qrlevel', $search['qrlevel']);
			}
			if ($search['questionknowsid']) {
				$args[] = array("AND", "quest2knows.qkknowsid = :qkknowsid", 'qkknowsid', $search['questionknowsid']);
			} else {
				$tmpknows = '0';
				if ($search['questionsectionid']) {
					$knows = $this->section->getKnowsListByArgs(array(array("AND", "knowsstatus = 1"), array("AND", "knowssectionid = :knowssectionid", 'knowssectionid', $search['questionsectionid'])));
					foreach ($knows as $p) {
						if ($p['knowsid']) $tmpknows .= ',' . $p['knowsid'];
					}
					$args[] = array("AND", "find_in_set(quest2knows.qkknowsid,:qkknowsid)", 'qkknowsid', $tmpknows);
				} elseif ($search['questionsubjectid']) {
					$knows = $this->section->getAllKnowsBySubject($search['questionsubjectid']);
					foreach ($knows as $p) {
						if ($p['knowsid']) $tmpknows .= ',' . $p['knowsid'];
					}
					$args[] = array("AND", "find_in_set(quest2knows.qkknowsid,:qkknowsid)", 'qkknowsid', $tmpknows);
				} else {
					$knows = $this->section->getAllKnowsBySubjects($this->teachsubjects);
					foreach ($knows as $p) {
						if ($p['knowsid']) $tmpknows .= ',' . $p['knowsid'];
					}
					$args[] = array("AND", "find_in_set(quest2knows.qkknowsid,:qkknowsid)", 'qkknowsid', $tmpknows);
				}
			}
			$questions = $this->exam->getQuestionrowsList($page, 10, $args);
		}
		if ($useframe) $questions['pages'] = str_replace('&useframe=1', '', $questions['pages']);
		$questypes = $this->basic->getQuestypeList();
		$sections = $this->section->getSectionListByArgs(array(array("AND", "sectionsubjectid = :sectionsubjectid", 'sectionsubjectid', $search['questionsubjectid'])));
		$knows = $this->section->getKnowsListByArgs(array(array("AND", "knowsstatus = 1"), array("AND", "knowssectionid = :knowssectionid", 'knowssectionid', $search['questionsectionid'])));
		//$this->tpl->assign('subjects',$subjects);
		$this->tpl->assign('search', $search);
		$this->tpl->assign('sections', $sections);
		$this->tpl->assign('knows', $knows);
		$this->tpl->assign('questypes', $questypes);
		$this->tpl->assign('questiontype', $search['questiontype']);
		$this->tpl->assign('questions', $questions);
		$this->tpl->assign('useframe', $useframe);
		$this->tpl->display('selectquestions');
	}

	private function detail()
	{
		$questiontype = $this->ev->get('questiontype');
		$examid = $this->ev->get('examid');
		$score = $this->ev->get('score');
		// if($questionparent)
		// {
		// 	$questions = $this->exam->getQuestionByArgs(array(array("AND","questionparent = :questionparent",'questionparent',$questionparent)));
		// }
		// else
		// {
		// 	$question = $this->exam->getQuestionByArgs(array(array("AND","questionid = :questionid",'questionid',$questionid)));
		// 	$sections = array();
		// 	foreach($question['questionknowsid'] as $key => $p)
		// 	{
		// 		$knows = $this->section->getKnowsByArgs(array(array("AND","knowsid = :knowsid",'knowsid',$p['knowsid'])));
		// 		$question['questionknowsid'][$key]['knows'] = $knows['knows'];
		// 		$sections[] = $this->section->getSectionByArgs(array(array("AND","sectionid = :sectionid",'sectionid',$knows['knowssectionid'])));
		// 	}
		// 	$subject = $this->basic->getSubjectById($sections[0]['sectionsubjectid']);
		// }
		//$this->tpl->assign("subject",$subject);
		//$this->tpl->assign("sections",$sections);
		$this->tpl->assign("questiontype", $questiontype);
		$this->tpl->assign("examid", $examid);
		$this->tpl->assign("score", sprintf("%.1f", $score));
		$this->tpl->display('simulation_detail');
	}

	private function help()
	{
		$questiontype = $this->ev->get('questiontype');
		// $questionparent = $this->ev->get('questionparent');
		// if($questionparent)
		// {
		// 	$questions = $this->exam->getQuestionByArgs(array(array("AND","questionparent = :questionparent",'questionparent',$questionparent)));
		// }
		// else
		// {
		// 	$question = $this->exam->getQuestionByArgs(array(array("AND","questionid = :questionid",'questionid',$questionid)));
		// 	$sections = array();
		// 	foreach($question['questionknowsid'] as $key => $p)
		// 	{
		// 		$knows = $this->section->getKnowsByArgs(array(array("AND","knowsid = :knowsid",'knowsid',$p['knowsid'])));
		// 		$question['questionknowsid'][$key]['knows'] = $knows['knows'];
		// 		$sections[] = $this->section->getSectionByArgs(array(array("AND","sectionid = :sectionid",'sectionid',$knows['knowssectionid'])));
		// 	}
		// 	$subject = $this->basic->getSubjectById($sections[0]['sectionsubjectid']);
		// }
		//$this->tpl->assign("subject",$subject);
		//$this->tpl->assign("sections",$sections);
		$this->tpl->assign("questiontype", $questiontype);
		//$this->tpl->assign("questions",$questions);
		$this->tpl->display('simulation_help');
	}


	private function modify()
	{
		$search = $this->ev->get('search');
		$examid = $this->ev->get('examid');
		$exam = $this->exam->getExamSettingById($examid);
		if ($this->ev->get('submitsetting')) {
			$args = $this->ev->get('args');
			$args['examsetting'] = $args['examsetting'];
			if ($exam['examtype'] == 3) {
				$uploadfile = $this->ev->get('uploadfile');
				if ($uploadfile) {
					setlocale(LC_ALL, 'zh_CN');
					$handle = fopen($uploadfile, "r");
					$questions = array();
					$index = 0;
					$rindex = 0;
					while ($data = fgetcsv($handle)) {
						$targs = array();
						$question = $data;
						if (count($question) >= 5) {
							$isqr = intval(trim($question[6], " \n\t"));
							if ($isqr) {
								$istitle = intval(trim($question[7], " \n\t"));;
								if ($istitle) {
									$rindex++;
									$targs['qrid'] = 'qr_' . $rindex;
									$targs['qrtype'] = $question[0];
									$targs['qrquestion'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK", "UTF-8//IGNORE", trim(nl2br($question[1]), " \n\t"))));
									$targs['qrcreatetime'] = TIME;
									$questionrows[$targs['qrtype']][intval($rindex - 1)] = $targs;
								} else {
									$index++;
									$targs['questionid'] = 'q_' . $index;
									$targs['questiontype'] = $question[0];
									$targs['question'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK", "UTF-8//IGNORE", trim(nl2br($question[1]), " \n\t"))));
									$targs['questionselect'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK", "UTF-8//IGNORE", trim(nl2br($question[2]), " \n\t"))));
									if (!$targs['questionselect'] && $targs['questiontype'] == 3)
										$targs['questionselect'] = '<p>A、对<p><p>B、错<p>';
									$targs['questionselectnumber'] = intval($question[3]);
									$targs['questionanswer'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK", "UTF-8//IGNORE", trim($question[4], " \n\t"))));
									$targs['questiondescribe'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK", "UTF-8//IGNORE", trim($question[5], " \n\t"))));
									$targs['questioncreatetime'] = TIME;
									$questionrows[$targs['questiontype']][intval($rindex - 1)]['data'][] = $targs;
									//$qustionnumber++;
								}
							} else {
								$index++;
								$targs['questionid'] = 'q_' . $index;
								$targs['questiontype'] = $question[0];
								$targs['question'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK", "UTF-8//IGNORE", trim(nl2br($question[1]), " \n\t"))));
								/**
								$ei = md5($targs['question']);
								if($isexit[$ei])
								{
									$message = array(
										'statusCode' => 300,
										"message" => "试题重复，该试题是:".$targs['question']
									);
									$this->G->R($message);
								}
								else
								$isexit[$ei] = 1;
								 **/
								$targs['questionselect'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK", "UTF-8//IGNORE", trim(nl2br($question[2]), " \n\t"))));
								if (!$targs['questionselect'] && $targs['questiontype'] == 3)
									$targs['questionselect'] = '<p>A、对<p><p>B、错<p>';
								$targs['questionselectnumber'] = $question[3];
								$targs['questionanswer'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK", "UTF-8//IGNORE", trim($question[4], " \n\t"))));
								$targs['questiondescribe'] = $this->ev->addSlashes(htmlspecialchars(iconv("GBK", "UTF-8//IGNORE", trim($question[5], " \n\t"))));
								$targs['questioncreatetime'] = TIME;
								$questions[$targs['questiontype']][] = $targs;
								//$qustionnumber++;
							}
						}
					}
					$args['examquestions'] = array('questions' => $questions, 'questionrows' => $questionrows);
				}
			} else
				$args['examquestions'] = $args['examquestions'];
			foreach ($args['examsetting']['questype'] as $key => $p) {
				if (!$args['examsetting']['questypelite'][$key]) {
					unset($args['examsetting']['questype'][$key], $args['examquestions'][$key]);
				}
			}

			$this->exam->modifyExamSetting($examid, $args);
			$message = array(
				'statusCode' => 200,
				"message" => "操作成功",
				"callbackType" => "forward",
				"forwardUrl" => "index.php?exam-master-simulation&page={$page}{$u}"
			);
			$this->G->R($message);
		} else {
			$subjects = $this->basic->getSubjectList();
			$questypes = $this->basic->getQuestypeList();
			foreach ($exam['examquestions'] as $key => $p) {
				$exam['examnumber'][$key] = $this->exam->getExamQuestionNumber($p);
			}
			foreach ($exam['examsetting']['questypelite'] as $key => $p) {
				if (!$subjects[$exam['examsubject']]['subjectsetting']['questypes'][$key]) {
					$exam['examsetting']['questypelite'][$key] = 0;
				}
			}
			foreach ($subjects[$exam['examsubject']]['subjectsetting']['questypes'] as $key => $p) {
				if (!$exam['examsetting']['questypelite'][$key]) {
					$exam['examsetting']['questypelite'][$key] = 1;
				}
			}
			$this->tpl->assign('search', $search);
			$this->tpl->assign('subjects', $subjects);
			$this->tpl->assign('exam', $exam);
			$this->tpl->assign('questypes', $questypes);
			if ($exam['examtype'] == 1)
				$this->tpl->display('exams_modifyauto');
			elseif ($exam['examtype'] == 2)
				$this->tpl->display('exams_modifyself');
			elseif ($exam['examtype'] == 3)
				$this->tpl->display('exams_modifytemp');
			else
				$this->tpl->display('simulation_modifytemp');
		}
	}
	
	private function modifyitem()
	{
		$eid = $this->ev->get('eid');
		$exam = $this->exam->getExamQuestionsById($eid);
		if ($this->ev->get('submitsetting')) {
			$gid = $this->ev->get('gid');
			$eid = $this->ev->get('eid');
			$cid = $this->ev->get('cid');//试卷号
	
// 			'tab' => array(1 => '判断题',2 => '单选题',3 => '多选题',4 => '填空题',5 => '问答题'),
// 			1	单选题	
// 			2	多选题	
// 			3	判断题	
// 			4	定值填空	
// 			5	填空题	
// 			6	简答题	
			if($gid>=1 && $gid<=6){//
				$post = array(
					'subject'	=> trim($this->ev->get('subject')),
					'result'	=> $this->ev->get('result'),
					'image'		=> $this->ev->get('image'),
					'note'		=> $this->ev->get('note'),
				);
			
				if($gid==3){//判断题
					//$post['result']	= $post['result'];
					$post['data'] = '';
				}elseif($gid==1){//单选题
					$option = $this->ev->get('option');
					for($i=count($option)-1;$i>=0;$i--){
						$o = trim($option[$i]);
						if(empty($o)){
							unset($option[$i]);
						}else{
							break;
						}
					}
					//$post['result'] = $post['result'];
					$post['data'] = serialize($this->ev->get('option'));
				}elseif($gid==2){//多选题
					$option = $this->ev->get('option');
					for($i=count($option)-1;$i>=0;$i--){
						$o = trim($option[$i]);
						if(empty($o)){
							unset($option[$i]);
						}else{
							break;
						}
					}
					$post['result']= implode($post['result']);
					$post['data']  = implode("\n",$this->ev->get('option'));
				}elseif($gid==5){//填空题
					$post['data'] = trim($this->ev->get('data'));
					$post['data'] = str_replace("\r","",$post['data']);
				}elseif($gid==6){//问答题
					$post['data']  = trim($this->ev->get('data'));
				}
			
				if(isset($post['subject'])){
				    $this->exam->updateExamQuestions($eid, $post);
			 
					//if(!empty($this->ev->get('image')))
					   //DB::query("update ".DB::table('tiny_exam3_upload')." SET `eid`='$eid' where `src`='".addslashes(substr ($post['image'], -25))."' $wu");
				}
			
				
			}
			
			$questype = $this->basic->getQuestypeById($gid);
		    $gname = $questype['questype'];
			
			$message = array(
					'statusCode' => 200,
					"message" => "操作成功",
					"callbackType" => "forward",
					"forwardUrl" => "index.php?exam-master-simulation-questiongroup&questiontype=".$gname."&examid=".$cid
			);
			$this->G->R($message);
		} else {
			//模板
			$gid = $exam['gid'];
			$questype = $this->basic->getQuestypeById($gid);
		    $gname = $questype['questype'];
			$data = unserialize($exam['data']);
			
			
			$this->tpl->assign('data', $data);
			$this->tpl->assign('gname', $gname);
			$this->tpl->assign('exam', $exam);
			$this->tpl->assign('questypes', $questypes);
			
			$this->tpl->display('simulation_modifyitem');
		}
	}
	

	private function insubmit()
	{

		$examid = $this->ev->get('examid');
		$questiontype = $this->ev->get('questiontype');
		$questype = $this->basic->getQuestypeByName($questiontype);
		$gid = $questype['questid'];

		$data = $this->ev->post('data');
		$score = $this->ev->post('score');

		$lang = array(
			//'TYPE' => array(1 => '判断题',2 => '单选题',3 => '多选题',4 => '填空题',5 => '问答题'),

			'right'  => '正确',
			'wrong'  => '错误',

			'push_to_form_subject' => "$_G[username] 于 " . date('m-d H:i', $_SERVER['REQUEST_TIME']) . " 参加了 $paper[title] 考试, 取得了 $paper[score] 分的成绩",
			'push_to_form_message' => "[url=home.php?mod=space&uid=$_G[uid] ][b]$_G[username] [/b][/url] 于 [b] " . date('m-d H:i', $_SERVER['REQUEST_TIME']) . " [/b] 参加了 [url=plugin.php?id=exam&paper=$paper[pid] ][b]$paper[title] [/b][/url] 考试, 取得了 [b]$paper[score] [/b] 分的成绩\n试卷: $paper[title] 分\n总分: $paper[total] 分\n及格: $paper[pass]  分\n考试时间: $paper[minute] 分钟",


			'in_zu'		=> '题组|题干',
			'in_da'		=> '正确答案|参考答案|答案',
			'in_dui'	=> '正确|对|是',
			'in_cuo'	=> '错误|错|否|×',
			'in_img'	=> '图片',
			'in_note'	=> '解析',
			'in_mao'	=> '：',
			'in_dun'	=> '、',
			'in_dian'	=> '．',
		);
		$in_zu	= $lang['in_zu']; //	  	 
		$in_da	= $lang['in_da']; //		
		$in_dui	= $lang['in_dui']; //	 
		$in_cuo	= $lang['in_cuo']; //	  
		$in_img	= $lang['in_img']; //	 
		$in_note = $lang['in_note']; //	
		$in_mao	= $lang['in_mao']; //	  
		$in_dun	= $lang['in_dun']; //	  
		$in_dian = $lang['in_dian']; //	  

		$_data  = str_replace('&#160;', ' ', $data);
		$txtarr = explode("\n", trim($_data));



		$nAdd      = 0;
		$ExamType  = '';
		$LineType  = '';
		$insert_id = 0;

		foreach ($txtarr as $v) {
			$line = trim($v);
			if (empty($line)) {
				$insert_id = 0;
				continue;
			}

			if (preg_match("/^(?:{$in_zu})\s*(?:\:|{$in_mao})\s*(.+)/", $line, $match)) {
				$questype = $this->basic->getQuestypeByName($match[1]);
				$gid = $questype['questid'];

				$LineType = 'group';
			} elseif ($insert_id == 0) {
				$postargs = array(
					'cid'	 => $examid,
					'gid'	 => $gid,
					'subject' => $line, //$_POST['numum'] ? preg_replace("/^[\(��\[]?\s*\d+\s*[\)��\]]?[\.����]\s*/", '' , $line) : $line,
					'addtime' => TIME,
					'score' => $score,
					'display' => 1,
				);

				$insert_id = $this->exam->addExamQuestions($postargs);


				$ExamType = preg_match("/(_{4,})|(\(\s{4,}\))/", $line, $match) ? 'BLANK' : 'ASK';
				$nAdd++;
				$LineType = 'subject';

				$_dot_n   = "";
				$simulationdata = [];
			} elseif (preg_match("/^{$in_da}\s*(?:\:|{$in_mao})\s*(.*)/", $line, $match)) {
				$LineType   = 'result';
				$line = $match[1];

				if (preg_match("/^({$in_dui})$/", $line, $match)) //�ж���
				{
					$updateargs = array('type' => 1, 'result' => 1);
					$this->exam->updateExamQuestions($insert_id, $updateargs);
				} elseif (preg_match("/^({$in_cuo})$/", $line, $match)) //�ж���
				{
					$updateargs = array('type' => 1, 'result' => 2);
					$this->exam->updateExamQuestions($insert_id, $updateargs);
				} elseif (preg_match("/^([A-Z])$/", $line, $match)) //��ѡ��
				{
					$updateargs = array('type' => 2, 'result' => $match[1]);
					$this->exam->updateExamQuestions($insert_id, $updateargs);
				} elseif (preg_match("/^([A-Z]{1,26})#?$/", $line, $match)) //��ѡ��
				{
					$updateargs = array('type' => 3, 'result' => $match[1]);
					$this->exam->updateExamQuestions($insert_id, $updateargs);
				} elseif ($ExamType == 'BLANK') {
					$updateargs = array('type' => 4, 'data' => preg_replace("/\s+/", "\n", $line));
					$this->exam->updateExamQuestions($insert_id, $updateargs);
					$LineType = 'data';
				} elseif ($ExamType == 'ASK') {
					$updateargs = array('type' => 5, 'data' => $line);
					$this->exam->updateExamQuestions($insert_id, $updateargs);
					$LineType = 'data';
				}
			} elseif (preg_match("/^{$in_img}\s*(?:\:|{$in_mao})\s*(.+)/", $line, $match)) {
				$this->exam->updateExamQuestions($insert_id, array('image' => $match[1]));
				$LineType = 'image';
			} elseif (preg_match("/^{$in_note}\s*(?:\:|{$in_mao})\s*(.*)/", $line, $match)) {
				$this->exam->updateExamQuestions($insert_id, array('note'  => $match[1]));
				$LineType = 'note';
			} elseif ($LineType == 'subject' && preg_match("/^[A-Z]\s*(?:\.|\:|$in_dun|$in_dian|$in_mao)\s*(.*)$/", $line, $match)) //
			{
				$dan = explode(":", $match[0]);
				//DB::query("update %t SET `data`=concat(`data`, %s) where eid='$insert_id' AND uid='$uid'", array('tiny_exam3_exam', $_dot_n . $match[1]));
				$simulationdata[$dan[0]] = $_dot_n . $match[1];
				$this->exam->updateExamQuestions($insert_id, array('data'  => $simulationdata));
				$_dot_n = "\n";
			} elseif ($LineType == 'data' || $LineType == 'note' || $LineType == 'subject') {
				//DB::query("update %t SET `$LineType`=concat(`$LineType`, %s) where eid='$insert_id' AND uid='$uid' AND `$LineType`<>''", array('tiny_exam3_exam', "\n".$line)) ||	
				//DB::query("update %t SET `$LineType`=%s where eid='$insert_id' AND uid='$uid'", array('tiny_exam3_exam', $line));
				$s = '\n' . $line;
				$this->exam->updateExamQuestions2($insert_id, $LineType, $s) || $this->exam->updateExamQuestions($insert_id, array($LineType  => $line));
			}
			// elseif($LineType == 'group')
			// {
			// 	//DB::query("update %t SET `content`=concat(`content`, %s) where gid='$insert_id' AND uid='$uid' AND `content`<>''", array('tiny_exam3_group', "\n".$line)) ||
			// 	//DB::query("update %t SET `content`=%s where gid='$insert_id' AND uid='$uid'", array('tiny_exam3_group', $line));

			// 	//$sql = "update examsquestions SET `content`=concat(`content`, '\n'.$line) where eid='$insert_id' AND `content`<>''";
			// 	$s = '\n'.$line;
			//     $this->exam->updateExamQuestions2($insert_id, 'content', $s) || $this->exam->updateExamQuestions($insert_id, array('content' => $line));
			// }

		}

		$message = array(
			'statusCode' => 200,
			"message" => "操作成功",
			"callbackType" => "forward",
			"forwardUrl" => "index.php?exam-master-simulation-modify&examid={$examid}&page={$page}{$u}"
		);
		$this->G->R($message);
	}

	private function media_html($src)
	{
		$ext = strtolower(substr($src, strrpos($src, '.') + 1));

		if (in_array($ext, array('jpg', 'png', 'gif', 'webp'))) {
			$html = '<img src="' . $src . '">';
		} elseif (in_array($ext, array('mp3', 'wav'))) {
			$html = '<audio id="videos" src="' . $src . '" controls="controls" controlslist="nodownload" webkit-playsinline="true" playsinline="true" x5-playsinline="true" x-webkit-airplay="allow">音乐</audio>';
		} else {
			$html = '<video id="videos" src="' . $src . '" controls="controls" controlslist="nodownload" webkit-playsinline="true" playsinline="true" x5-playsinline="true" x-webkit-airplay="allow">影片</video>';
		}
		return $html;
	}

	private function index()
	{
		$search = $this->ev->get('search');
		$page = $this->ev->get('page');
		$page = $page > 0 ? $page : 1;
		$args = array();
		$args[] = array("AND", "examtype = :examtype", 'examtype', '8');
		if ($search) {
			if ($search['examsubject']) $args[] = array("AND", "examsubject = :examsubject", 'examsubject', $search['examsubject']);
			//if($search['examtype'])$args[] = array("AND","examtype = :examtype",'examtype',$search['examtype']);
		}
		//if(!count($args))$args = 1;

		$exams = $this->exam->getExamSettingList($page, 10, $args);
		$subjects = $this->basic->getSubjectList();
		$this->tpl->assign('subjects', $subjects);
		$this->tpl->assign('exams', $exams);
		$this->tpl->display('simulation');
	}
}
