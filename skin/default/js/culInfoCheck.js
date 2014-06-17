     function infoCheck()
     {
        if(document.form1.u_name.value=="")
        {
           alert("请输入真实姓名！");
           return false;
        }
        else if(document.form1.u_call.value=="")
        {
           alert("请输入您的联系方式！");
           return false;
        }
        else if(document.form1.u_address.value=="")
        {
           alert("请输入礼品寄送地址！");
           return false;
        }
        else
        {           
           document.form1.submit();
           return true;
        }
         
     }
