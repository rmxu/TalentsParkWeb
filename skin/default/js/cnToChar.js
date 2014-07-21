function getFirstPyLetter(s){
    execScript("tmp=asc(\""+s+"\")", "vbscript"); // 不支持 ff
    tmp = 65536 + tmp;
    
    var py = "";
    if(tmp>=45217 && tmp<=45252) {   
        py = "A"
    } else if(tmp>=45253 && tmp<=45760) {
        py = "B"
    } else if(tmp>=45761 && tmp<=46317) {
        py = "C"
    } else if(tmp>=46318 && tmp<=46825) {
        py = "D"
    } else if(tmp>=46826 && tmp<=47009) {
        py = "E"
    } else if(tmp>=47010 && tmp<=47296) {
        py = "F"
    } else if((tmp>=47297 && tmp<=47613) || (tmp == 63193)) {
        // 鲑 = 63193
        py = "G"
    } else if(tmp>=47614 && tmp<=48118) {
        py = "H"
    } else if(tmp>=48119 && tmp<=49061) {
        py = "J"
    } else if(tmp>=49062 && tmp<=49323) {
        py = "K"
    } else if(tmp>=49324 && tmp<=49895) {
        py = "L"
    } else if(tmp>=49896 && tmp<=50370) {
        py = "M"
    } else if(tmp>=50371 && tmp<=50613) {
        py = "N"
    } else if(tmp>=50614 && tmp<=50621) {
        py = "O"
    } else if(tmp>=50622 && tmp<=50905) {
        py = "P"
    } else if(tmp>=50906 && tmp<=51386) {
        py = "Q"
    } else if(tmp>=51387 && tmp<=51445) {
        py = "R"
    } else if(tmp>=51446 && tmp<=52217) {
        py = "S"
    } else if(tmp>=52218 && tmp<=52697) {
        py = "T"
    } else if(tmp>=52698 && tmp<=52979) {
        py = "W"
    } else if(tmp>=52980 && tmp<=53688) {
        py = "X"
    } else if(tmp>=53689 && tmp<=54480) {
        py = "Y"
    } else if(tmp>=54481 && tmp<=62289) {
       //修正 驿 和 怡
        if(tmp>=58105 && tmp<=59108){
           py = "Y"
        } else {
           py = "Z"
        }
    } else if(tmp==63182){
        py = "X"
    } else {
        py =s.charAt(0).toUpperCase();
    }
    return py;
}

    
function getAllPyLetters(zhstr){
    var alitter="";

    for(var i=0;i<zhstr.length;i++)
    {
       alitter+=getFirstPyLetter(zhstr.substring(i,i+1));        
    }
    return alitter;
}
