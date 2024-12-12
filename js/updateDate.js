function getWeekDayName(weekDay){
    var weekDayName = [ "Sunday" , "Monday" , "Tuesday" , "Wednesday" , "Thursday" , "Friday" , "Saturday"];
    return weekDayName[weekDay];
}

function getMonthName(month){
    switch (month){
        case 0: return "January";
        case 1: return "February";
        case 2: return "March";
        case 3: return "April";
        case 4: return "May";
        case 5: return "June";
        case 6: return "July";
        case 7: return "August";
        case 8: return "September";
        case 9: return "Qctober";
        case 10: return "November";
        case 11: return "December";
    }
}

function addDateOrdinal(date) { //加上日期序數
switch (date) {
case 1:
case 21:
case 31: return date + "<sup>st</sup>";
case 2:
case 22: return date + "<sup>nd</sup>";        
case 3:
case 23: return date + "<sup>rd</sup>";        
default: return date + "<sup>th</sup>";      
}
}



function updateDates(){
var today = new Date();
document.getElementById("cur-year").innerHTML = today.getFullYear();
document.getElementById("cur-month").innerHTML = getMonthName(today.getMonth());
document.getElementById("cur-day").innerHTML = getWeekDayName(today.getDay());
document.getElementById("cur-date").innerHTML = addDateOrdinal(today.getDate());

document.getElementById("cal-year").innerHTML = today.getFullYear();
document.getElementById("cal-month").innerHTML = getMonthName(today.getMonth());

document.getElementById("back-year").innerHTML = today.getFullYear();

fillInMonth(year,month);

}

var date = new Date();
            
var year = date.getFullYear();
var month = date.getMonth();  //這個是計算我要抓monthDays的月份天數(要看updateDate.js裡面內容,ex:1月 month = 0;)


function getUID(month, year, day){
    if (month == 0) { //上個月減1，進到去年份
      month = 12;
      year--;
    }
    if (month == 13) { //下個月加1，進到下年份
      month = 1;
      year++;
    }
    // console.log(month.toString() + year.toString() + day.toString())
    return month.toString() + year.toString() + day.toString();
  }

        
        //記事圖示與ToolTip處理
        function appendSpriteToCellAndTooltip(uid, elem){
            for(let i = 0; i < postIts.length; i++){
                if(uid == postIts[i].id){
                    elem.innerHTML += `<img src='images/note${postIts[i].note_num}.png'>`;
                    elem.classList.add("tooltip");
                    elem.innerHTML += `<span>${postIts[i].note}</span>`;
                }
            }
        }

function fillInMonth(year,month){
            let days = document.getElementsByTagName("td"); //將td標籤放入days物件集合中
            
            var culmonth = month + 1 ;   //這個是計算現在幾月
            
            dates = new Date(year, month, 1); 
            weekDay = dates.getDay(); //計算出今年今月1日是星期幾…

            var monthDays = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]; //每個月的天數
            if ( ((year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0) ) monthDays[1] = 29; //閏年2月是29天
            
            for (let i = 0; i < days.length; i++){                  //這邊是好把原先有顏色的部分先移除
                if (days[i].classList.contains("color")) days[i].classList.remove("color");
              }
            
            var uid;  // 先設定uid

            for (var i=weekDay, j=1; j <= monthDays[month]; i++, j++){  //當月份的計算
                days[i].innerHTML = j;
                uid = getUID(month, year, j);
                days[i].setAttribute("data-uid", uid);
                appendSpriteToCellAndTooltip(uid, days[i]);
            }
            

            for (var i = (weekDay-1), day = monthDays[culmonth-1]; i >=0; i--, day--){   //這邊是計算上個月
                days[i].innerHTML = day;
                days[i].classList.add("color");             //後面再把顏色塗上去
                uid = getUID(month-1, year, day);
                days[i].setAttribute("data-uid", uid);
                appendSpriteToCellAndTooltip(uid, days[i]);

            }

            for (var i = (weekDay+monthDays[month]), day = 1; i < days.length ;i++, day++){   //這邊是計算下個月
                days[i].innerHTML = day;
                days[i].classList.add("color");             //後面再把顏色塗上去
                uid = getUID(month+1, year, day);
                days[i].setAttribute("data-uid", uid);
                appendSpriteToCellAndTooltip(uid, days[i]);
            }

            
            //處理今日元素表格的顯著背景設定
            if (document.getElementById("current-day")) {
                document.getElementById("current-day").removeAttribute("id");  //這邊是意思是，因為我原先把底色放在隨便一個td 的欄位
            }                                                                  //所以我這邊需要找到他之後，刪除他的底色。
                                                                               //然後才能做以下動作
            thisDate = date.getDate(); //取得今天是幾號 
            if (year == date.getFullYear() && month == date.getMonth()){
                days[weekDay + thisDate - 1].setAttribute("id", "current-day");  //為什麼要 -1 呢？ 因為[]內第一個元素是從0開始
            }                 
            
            changeColor(); //--這邊放了function 主要原因是因為換主題顏色會有問題，所以後變需要再放changeColor function-->

}


  


function fillInNextMonth(){
    month++;
    if (month == 12){
        year++;
        month = 0;
    }
    changeColor();  //可能已經更改主題顏色，所以這邊也需要更新顏色，這樣下面新增的顏色才會是對的
    fillInMonth(year, month);

    document.getElementById("cal-year").innerHTML = year;
    document.getElementById("cal-month").innerHTML = getMonthName(month);
}

function fillInPreviousMonth(){
    month--;
    if (month == -1){
        year--;
        month = 11;
    }
    changeColor();  //可能已經更改主題顏色，所以這邊也需要更新顏色，這樣下面新增的顏色才會是對的
    fillInMonth(year, month);

    document.getElementById("cal-year").innerHTML = year;
    document.getElementById("cal-month").innerHTML = getMonthName(month);
}

document.onkeydown = function(e){      //這邊是把鍵盤左右鍵加入可以控制按鍵
    switch(e.keyCode){
        case 37: fillInPreviousMonth(); break;
        case 39: fillInNextMonth();break;
    }
}