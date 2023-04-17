
					<form id="testform" method="post" autocomplete="off" action="index.php?exam-master-simulation-insubmit&questiontype={x2;$questiontype}&examid={x2;$examid}" onsubmit="return false">
		                  <input type="hidden" name="score" value="{x2;$score}">   
		                  <table cellpadding="0" cellspacing="0" border=0 class="table-add">
			                    <tr><th colspan=6>{x2;$questiontype}试题列表: (<a href="index.php?exam-master-simulation-help&tab=31" target="_blank" class="xi1">如何导入?</a>)</th></tr>
			                    <tr><td colspan=6>
			                    <textarea class="px in_data" id="data" name="data" style="word-wrap:normal;" oninput="adjustObjHeight(this, 330);"></textarea></td></tr>
		                  </table>
		                            
		                 <input type="submit" id="testsubmit" class="pn pnc pp" style="margin-top:15px" value="确定">
		            </form>
				
				
				<script type="text/javascript">
			       function adjustObjHeight(obj, defaultHeight) { 
				       obj.style.height = obj.scrollHeight > defaultHeight ? obj.scrollHeight - 10 + 'px' : defaultHeight + 'px'; 
			        }
		        </script> 
 