<!--
function TwoSelectInit(so,dv1,dv2){
	var o1=so.o1;var o2=so.o2;var allstr=so.str;var dt1=so.dt1;var dt2=so.dt2;var selectonce=so.selectonce;
	var _s = "*|^@";
	var s1=new Array(),v1=new Array(),s2=new Array(),v2=new Array();
	var s1i = 0,s2i = 0;
	if(dt1!=""){
		if(!selectonce){allstr=dt1+_s.charAt(1)+_s.charAt(0)+allstr;}
		else{allstr=dt1+_s.charAt(1)+dt2+_s.charAt(0)+allstr;}
	}
	aa=allstr.split(_s.charAt(0));
	for(aai=0;aai<aa.length;aai++){
		aaa=aa[aai].split(_s.charAt(1));
		tmps1 = aaa[0].split(_s.charAt(3));
		s1[aai] = tmps1[0];v1[aai] = (tmps1.length==2)?tmps1[1]:tmps1[0];
		s2[aai] = new Array();v2[aai] = new Array();
		if(v1[aai]==dv1){s1i = aai;}
		bbbb=aaa[1];
		if(dt2!=""&&!selectonce){if(bbbb==""){bbbb=dt2;}else{bbbb=dt2+_s.charAt(2)+bbbb;}}
		bb=bbbb.split(_s.charAt(2));
		for(bbi=0;bbi< bb.length;bbi++){
			tmps2 = bb[bbi].split(_s.charAt(3));
			s2[aai][bbi] = tmps2[0];v2[aai][bbi] = (tmps2.length==2)?tmps2[1]:tmps2[0];
			if(v2[aai][bbi]==dv2){s2i = bbi;}
		}
	}
	for(var i=0;i<o1.options.length;i++){o1.remove(i);i--;}
	for(k=0;k<s1.length;k++){o1.options.add(new Option(s1[k],v1[k]));}
	o1.selectedIndex=s1i;
	for(var i=0;i<o2.options.length;i++){o2.remove(i);i--;}
	for(k=0;k<s2[s1i].length;k++){o2.options.add(new Option(s2[s1i][k],v2[s1i][k]));}
	o2.selectedIndex=s2i;
}
function HwTwoSelect(o1,o2,liststr,dt1,dt2,t){this.o1=o1;this.o2=o2;this.str=liststr;this.dt1=dt1;this.dt2=dt2;this.selectonce=t}
//-->
