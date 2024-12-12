
function openMakeNote(){
    var modal = document.getElementById("modal");
    modal.open = true;
    var template = document.getElementById("make-note");
    template.removeAttribute("hidden");
    document.getElementById("edit-post-it").focus();

    if(!newCurrentPostIt){  //這個是處理已經打在記事本上的資料，原有資料繼續留著
        document.getElementById("edit-post-it").value = postIts[currentPostItIndex].note;
  }
}

function closeMakeNote(){
    //關閉對話方塊
var modal = document.getElementById("modal");

modal.open = false;
var template = document.getElementById("make-note");
template.setAttribute("hidden", "hidden");
}



function dayClicked(elm) {   //這是每個日期點擊會跑出視窗
    console.log(elm.dataset.uid)
    currentPostItID = elm.dataset.uid; //目前的記事ID為所點擊的日期表格上的uid
    currentDayHasNote(currentPostItID);//判斷目前點蠕擊的日期是否有記事資料
    openMakeNote();
}

function currentDayHasNote(uid){ //測試特定UID是否已經有記事
        for(var i = 0; i < postIts.length; i++){
            if(postIts[i].id == uid){
                newCurrentPostIt = false;
                currentPostItIndex = i;
                return;
            }
        }
        newCurrentPostIt = true;
}

function getRandom(min, max) { //min <= 亂數值 < max    這個是為了加入標籤紙，圖庫有5個，點擊後隨機選一個
        return Math.floor(Math.random() * (max - min) ) + min;
    }

function submitPostIt(){ //按了PostIt按鍵後，所要執行的方法
    const value = document.getElementById("edit-post-it").value;  //取出textarea 裡面的value
    document.getElementById("edit-post-it").value = "";
    let num = getRandom(1, 6); //取得1~5的亂數，用來標示便利貼顏色的檔案代號
    let postIt = {
        id: currentPostItID,
        note_num: num,
        note: value
    }
    if(newCurrentPostIt){ //如果是新記事的話
        postIts.push(postIt); //將新記事postIT物件推入postIts陣列
            ajax(
                {
                    new_note_uid: postIt.id,    ////加入新增記事資料的ajax呼叫 create
                    new_note_color: postIt.note_num, 
                    new_note_text: postIt.note
                }
            );
    } else {
        postIts[currentPostItIndex].note = postIt.note; //更新現有記事物件的記事資料
            //加入更新記事資料的ajax呼叫  update
            ajax(
                {
                    update_note_uid: postIts[currentPostItIndex].id, 
                    update_note_text: postIt.note
                }
            );
    }

    console.log(postIts)
    fillInMonth(year,month); 
    closeMakeNote();
}

function deleteNote(){              //加入deleteNote函式，利用陣列的splice方法刪去記事資料
    document.getElementById("edit-post-it").value = "";
    let indexToDel;
    if(!newCurrentPostIt){
        indexToDel =currentPostItIndex;
    }
    if(indexToDel != undefined){

        //加入刪除記事資料的ajax呼叫
        ajax(
            {
                delete_note_uid: postIts[currentPostItIndex].id
            }
        );   //delete
        postIts.splice(indexToDel, 1); //這個才執行砍掉資料
    }
    fillInMonth(year,month); 
    closeMakeNote();
}




