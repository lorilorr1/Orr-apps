/*
*  Suchart Bunhachirat 15/02/2011
* สุชาติ บุญหชัยรัตน์ 15 กุมภาพันธ์ 2554
* ไฟล์ java script ทั่วไปที่ใช้ร่วมกันใน orr-projects
* ข้อมูลจาก
* http://sixhead.com/2008/01/26/javascript-popup-and-window-opener/
*/
var my_title = document.title; //เก็บชื่อไตเติลบาร์เริ่มแรก เพื่อใช้เรียกคืน
var scrl = my_title;
var timer;
// function นี้มีไว้เพื่อสร้าง popup ให้อยู่กลางจอเสมอ
function popUpWindow(URL, N, W, H, S) { // name, width, height, scrollbars
    //var winleft    =    (screen.width - W) / 2;
    //var winup    =    (screen.height - H) / 2;
    var winleft    =    20;
    var winup    =    20;
    winProp        =    'width='+W+',height='+H+',left='+winleft+',top=' +winup+',scrollbars='+S+',resizable' + ',status=yes'
    Win            =    window.open(URL, N, winProp)
}

/*ฟังก์ชั่นสำหรับ เปิดหน้าต่าง popup
* 1. เก็บชื่อของ control ที่ต้องการรับค่ากลับมา
*/
function win_popup(theURL, searchValue, ControlName,width,height,scollbar) {
    var setfocus;
    searchValue = encodeURIComponent(searchValue);
    theURL = theURL + '?val_filter[filter_by]=' + searchValue + '&val_msg[btn_filter]=Filter&val_msg[control_id]=' + ControlName;
    setfocus = window.open(theURL,ControlName,'resizable=no,scrollbars='+ scollbar +',width='+ width +',height='+ height +',top=0,left=0');
    setfocus.focus();
}

//ฟังก์ชั่นสำหรับ ปิดหน้าต่าง popup
function win_close() {
    window.close();
}

//ฟังก์ชั่นเพื่อคืนค่ากลับหน้าต่างที่เปิด POPUP
function return_to_opener(strValue,strFormName,strControlName){
    //var strContent = document.frmSelect.tbTextArea.value;
    //var strContent = '123456';
    //window.opener.document.write(strContent);
    window.opener.document.my_form.txt_search.value=strValue;
    window.close();
}

function change_subpage_src(strSrc){
    document.getElementById("frm_sub").src=strSrc;
}

function go_url(strUrl){
    window.location.href = strUrl;
}


function send_form(urlPage) {
    // Get the result node
    var resultNode = dojo.byId("my_result_info");
    // Using dojo.xhrGet, as very little information is being sent
    dojo.xhrPost({
        // The URL of the request
        url: urlPage,
        // Form to send
        form: dojo.byId("my_ajax_form"),
        // The success callback with result from server
        load: function(newContent) {
            //dojo.style(resultNode,"display","block");
            resultNode.innerHTML = newContent;
            dojo.byId("my_note_id").value = 0;
        },
        // The error handler
        error: function() {
            resultNode.innerHTML = "Your form could not be sent.";
        }
    });
}


function content_refresh(urlPage, idValue, idContent){
    if(dojo.byId(idValue).value != ''){

        dojo.xhrGet({
            url: urlPage + '?val_msg[content_key_value]=' + encodeURIComponent(dojo.byId(idValue).value),
            timeout: 10000,
            load: function(result) {
                if(result == "not found!"){
                    alert('ข้อความ "' + dojo.byId(idValue).value + '" ในช่องข้อมูลไม่ถูกต้อง กรุณาใส่ข้อความใหม่!!');
                    dojo.byId(idValue).value='';
                    dojo.byId(idValue).focus();
                }else{
                    dojo.byId(idContent).innerHTML = result;
                }

            },
            error: function() {
                //alert('Error when retrieving data from the ' + urlPage + '!!!');
            }
        }
        );
    }else{
        dojo.byId(idContent).innerHTML = 'ไม่ได้ใส่ข้อมูลที่ค้นหา';
    }
}

function scrlsts() {
    scrl = scrl.substring(1, scrl.length) + scrl.substring(0, 1);
    document.title = scrl;
    timer = setTimeout("scrlsts()", 600);
}

function stoper()	{
    clearTimeout(timer);
}