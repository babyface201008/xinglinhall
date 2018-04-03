/*网站前台公共js*/

//===================倒计时============================
/*
 * 倒计时 
 * d 结束时间
 * endF 结束时回调
 */
$.extend($.fn,{
        fnTimeCountDown:function(d,endF){ //d为时间戳
            this.each(function(){
                var $this = $(this);
                // var thisid = $this.attr('id');
                var o = {
                    hm: $this.find(".millisecond"),
                    sec: $this.find(".second"),
                    mini: $this.find(".minute"),
                    hour: $this.find(".hour"),
                    day: $this.find(".day"),
                    month:$this.find(".month"),
                    year: $this.find(".year")
                };
                var f = {
                    haomiao: function(n){
                        if(n < 10)return "00" + n.toString();
                        if(n < 100)return "0" + n.toString();
                        return n.toString();
                    },
                    zero: function(n){
                        var _n = parseInt(n, 10);//解析字符串,返回整数
                        if(_n > 0){
                            if(_n <= 9){
                                _n = "0" + _n
                            }
                            return String(_n);
                        }else{
                            return "00";
                        }
                    },
                    dv: function(){ //计算时间
                        //d = d || Date.UTC(2050, 0, 1);//如果未定义时间，则我们设定倒计时日期是2050年1月1日
                        //var _d = $this.data("end") || parseInt(d); //当d为时间戳时使用
                        var _d = $this.data("end") || d;
                        var now = new Date(),
                        endDate = new Date(_d); 

                        var dur = (endDate - now.getTime()) / 1000 , 
                            mss = endDate - now.getTime() , //结束的时间和现在的时间差
                            pms = {
                            hm:"000",
                            sec: "00",
                            mini: "00",
                            hour: "00",
                            day: "00",
                            month: "00",
                            year: "0"
                        };

                        if(mss > 0){ //时间差大于0
                            pms.hm = f.haomiao(mss % 1000);
                            pms.sec = f.zero(dur % 60);
                            pms.mini = Math.floor((dur / 60)) > 0? f.zero(Math.floor((dur / 60)) % 60) : "00";
                            pms.hour = Math.floor((dur / 3600)) > 0? f.zero(Math.floor((dur / 3600)) % 24) : "00";
                            pms.day = Math.floor((dur / 86400)) > 0? f.zero(Math.floor((dur / 86400)) % 30) : "00";
                            //月份，以实际平均每月秒数计算
                            //pms.month = Math.floor((dur / 2629744)) > 0? f.zero(Math.floor((dur / 2629744)) % 12) : "00";
                            //年份，按按回归年365天5时48分46秒算
                            //pms.year = Math.floor((dur / 31556926)) > 0? Math.floor((dur / 31556926)) : "0";
                            // ftime = ftime + 4;
                        }else{ //时间差小于0
                            pms.year=pms.month=pms.day=pms.hour=pms.mini=pms.sec="00";
                            pms.hm = "000";
                            // enddoit(thisid);
                            // endF();
                            return 'ok';
                        }
                        return pms;
                    },
                    ui: function(){
                        if(f.dv()!='ok'){ //mss 
                            if(o.hm){
                                o.hm.html(f.dv().hm.substr(0,2));
                                
                                }
                                if(o.sec){
                                    o.sec.html(f.dv().sec);
                                }
                                if(o.mini){
                                    o.mini.html(f.dv().mini);
                                }
                                if(o.hour){
                                 o.hour.html(f.dv().hour);
                                }
                               /* if(o.day){
                                 o.day.html(f.dv().day);
                                }*/
                                // if(o.month){
                                //  o.month.html(f.dv().month);
                                // }
                                // if(o.year){
                                //  o.year.html(f.dv().year);
                                // }
                                setTimeout(f.ui, 1);
                            }else{
                                endF();
                                return 'isok';
                            }
                        }
                        
                    };
                    res = f.ui();
            });
        }
    });
//===================倒计时end=========================

//========================js验证=======================
function verifyUsername(username){
	//用户名验证 以字母开头，数字和字母组成的6~20字符串
	var preg = /^[a-zA-Z][0-9a-zA-Z]{5,19}$/;
	var res = username.match(preg);
	return Boolean(res);
}
function verifyPhone(phone){
//手机号码 正则验证
	var preg = /^(0|86|17951)?(13[0-9]|15[012356789]|16[1-9]|17[1-9]|18[0-9]|14[57])[0-9]{8}$/;
	var res = phone.match(preg);
	return Boolean(res);
}
function verifyEmail(email){
//邮箱验证
	var preg = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	var res = email.match(preg);
	return Boolean(res);
}
function verifyNickname(nickname){
//昵称验证
//正则验证 由汉字、字母、数字、"_-"组成
    var preg = /^[0-9a-zA-Z\u4e00-\u9fa5\-\_]{2,20}$/;
    var res = nickname.match(preg);
   	return Boolean(res);
}
function verifyShipcode(shipCode){
//邮政编码验证
    var preg = /^[1-9]\d{5}(?!\d)$/;
    var res = shipCode.match(preg);
    return Boolean(res);
}
function verifyTelephone(telephone){
//固定电话验证
    var preg = /^(0[0-9]{2,3}\-)?([2-9][0-9]{6,7})+(\-[0-9]{1,4})?$/;
    var res = telephone.match(preg);
    return Boolean(res);
}

//判断是否为空
function isEmpty(str){
    var preg = /\S/;
    var res = str.match(preg);
    res = res?false:true;
    return res;
}
/*
*验证密码
*/
function verifyPwd(pwd)
{
    var res = pwd.match(/^\S{6,20}$/);
    return Boolean(res);
}

/*
*手机验证码
*/
function verifyPhoneCode(pwd)
{
    var res = pwd.match(/^[\d]{6}$/);
    return Boolean(res);
}



//=================数值格式化======================

/*
 * 将千分位格式的数字字符串转换为浮点数
 */
function unformatMoney(sVal){
    var fTmp = parseFloat(sVal.replace(/,/g, ''));
    return (isNaN(fTmp) ? 0 : fTmp);
}

/*
 * 数字千分位格式化 mval 数值 iAccuracy 小数点精确位数
 */
function formatMoney(mVal, iAccuracy){
            var fTmp = 0.00;//临时变量
            var iFra = 0;//小数部分
            var iInt = 0;//整数部分
            var aBuf = []; //输出缓存
            var bPositive = true; //保存正负值标记(true:正数)
            /**
             * 输出定长字符串，不够补0
             * <li>闭包函数</li>
             * @param int iVal 值
             * @param int iLen 输出的长度
             */
            function funZero(iVal, iLen){
                var sTmp = iVal.toString();
                var sBuf = [];
                for(var i=0,iLoop=iLen-sTmp.length; i<iLoop; i++)
                    sBuf.push('0');
                sBuf.push(sTmp);
                return sBuf.join('');
            }
    if (typeof(iAccuracy) === 'undefined') iAccuracy = 2;
                
            bPositive = (mVal >= 0);//取出正负号
            fTmp = (isNaN(fTmp = parseFloat(mVal))) ? 0 : Math.abs(fTmp);//强制转换为绝对值数浮点
            //所有内容用正数规则处理
            iInt = parseInt(fTmp); //分离整数部分
            iFra = parseInt((fTmp - iInt) * Math.pow(10,iAccuracy) + 0.5); //分离小数部分(四舍五入)

            do{
                aBuf.unshift(funZero(iInt % 1000, 3));
            }while((iInt = parseInt(iInt/1000)));
            aBuf[0] = parseInt(aBuf[0]).toString();//最高段区去掉前导0
            return ((bPositive)?'':'-') + aBuf.join(',') +'.'+ ((0 === iFra)?'00':funZero(iFra, iAccuracy));
}

//=================数值格式化end===================

//=================js 操作cookie===================

//s20是代表20秒
//h是指小时，如12小时则是：h12
//d是天数，30天则：d30
// setCookie("name","hayden","s20");
function setCookie(name,value,time){
    var strsec = getsec(time);
    var exp = new Date();
    exp.setTime(exp.getTime() + strsec*1);
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}
function getsec(str){
    var str1=str.substring(1,str.length)*1;
    var str2=str.substring(0,1);
    if (str2=="s"){
        return str1*1000;
    }
    else if (str2=="h"){
        return str1*60*60*1000;
    }
    else if (str2=="d"){
        return str1*24*60*60*1000;
    }
}

//读取cookies
function getCookie(name){
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
    if(arr=document.cookie.match(reg))
    return unescape(arr[2]);
    else
    return null;
}

//删除cookies
function delCookie(name){
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval=getCookie(name);
    if(cval!=null)
    document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}

//=================js 操作cookie end===============

/*
 * 根据服务器计算出相对时间差
 * localhostTime 服务器当前时间
 * localhostEndtime 服务器完成时间
 */
 function calculateTimeC(localhostTime,localhostEndtime){
    var nowTime = new Date().getTime(); //客户端时间
    var timeC = localhostTime*1000 - nowTime; //时间差
    var timeSY = localhostEndtime*1000 - timeC;
    return timeSY;
 }


//获取对象的长度
function getLength(object){

    var objectLength = 0;

    for(var item in object){

    objectLength++;

    }

    return objectLength;

}
/**
 *
 * @param dateStart 开始时间
 * @param dateEnd 结束时间
 * @param datetype 时间类型
 */
function verifydate(dateStart,dateEnd,datetype) {
    if(datetype !=''){
        var datetypestr =datetype.textbox('getValue')
    }
    // dateStart= dateStart.textbox('getValue'),
    //     dateEnd =dateEnd.textbox('getValue'),
    var    timestamp = Date.parse(new Date()),
            timestamp2 = Date.parse(new Date(dateStart));
    if(datetypestr=='' && (dateStart || dateEnd)){
        datetype.combobox({value:'createtime'});
    }
    if(timestamp2>timestamp){
        $.messager.alert('操作警告','开始时间不能大于当前时间！','info');
        return false;
    }else if(dateStart>dateEnd && dateEnd){
        $.messager.alert('操作警告','开始时间必须大于结束时间','info');
        return false;
    }else {
        return true;
    }
}


/**
 * 省市区联动
 * @param depth 层级
 * @param aid
 */
function getArea(depth, aid) {
    var URL = CONST.BASE_URL+"Area/getSelAJAX?depth=" + depth + "&aid=" + aid;
    $.ajax({
        type: "get",
        contentType: "application/json",async:false,
        cache: false,
        url: URL,
        data: "{}",
        dataType: 'json',
        success: function (data) {
            if(depth==0){
                var str = "<option value=''>请选择城市</option>"+data;
                $('#city').html(str);
            }else if(depth==1){
                var str = "<option value=''>请选择地区</option>"+data;
                $('#area').html(str);
            }else if(depth==2){
                var str = "<option value=''>请选择镇</option>"+data;
                $('#county').html(str);
            }

        }
    });
}

function formArray(form){
    var jsonData = form.serializeArray();
    var o = {};
    $.each(jsonData,function(k,v){
        o[v.name] = v.value;
    });
    return o;
}



