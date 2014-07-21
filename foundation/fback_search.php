<?php
//语言包
function order_sc(){
	$sc_asc='';$sc_desc='';
	if(get_argg('order_sc')==''||get_argg('order_sc')=="asc"){$sc_asc="selected";}
	if(get_argg('order_sc')=="desc"){$sc_desc="selected";}
	echo '
  <SELECT name=order_sc>
		<OPTION value=asc '.$sc_asc.'>升序</OPTION>
		<OPTION value=desc '.$sc_desc.'>降序</OPTION>
  </SELECT>';
}

function perpage(){
	$p_20='';$p_50='';$p_100='';
	if(get_argg('perpage')==''||get_argg('perpage')=="20"){$p_20="selected";}
	if(get_argg('perpage')=="50"){$p_50="selected";}
	if(get_argg('perpage')=="100"){$p_100="selected";}
	echo '
  <SELECT name=perpage>
		<option value=20 '.$p_20.'>每页显示20</option>
		<option value=50 '.$p_50.'>每页显示50</option>
		<option value=100 '.$p_100.'>每页显示100</option>
  </SELECT>';
}
?>