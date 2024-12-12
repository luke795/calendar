<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">                       <!--把想要的字體引入 -->
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Yuji+Mai&display=swap" rel="stylesheet">       <!--把想要的字體引入 -->
    <link rel="stylesheet" type="text/css" href="css/text.css"/>                        <!--把css  屬性引入 -->
    <link rel="stylesheet" type="text/css" href="css/current-day.css"/>
    <link rel="stylesheet" type="text/css" href="css/calender.css"/>
    <link rel="stylesheet" type="text/css" href="css/dialog.css"/>
    <link rel="icon" href="images/icon1.png" type="image/png" sizes="72x72"/>       <!--把icon 圖片引入 會顯示在頁面投標 -->
    
    <script src="https://kit.fontawesome.com/24dbe3d55d.js" crossorigin="anonymous"></script>    <!--這一段是把月份兩旁的icon 抓進來 https://fontawesome.com/icons/caret-right?f=classic&s=solid -->
    <script src="js/updateDate.js"></script> <!--這是把js 檔案嵌入-->
    <script src="js/changeTheme.js"></script> <!--這是把js 檔案嵌入-->
    <script src="js/postIts.js"></script> <!--這是把js 檔案嵌入-->
    <script src="js/ajax.js"></script> <!--這是把js 檔案嵌入  type="text/javascript" -->

    <title>Document</title>
</head>

<body>
    <?php
        function getNoteData(){       //Read
            global $connection;
            $query = "SELECT * FROM notes";
            $result = mysqli_query($connection, $query);
            if(!$result){
                die("Something went wrong");
            }
            $id = 0;
            $color = 1;
            $text = "";
            while($row = mysqli_fetch_assoc($result)){
            $id = $row['note_id'];
            $color = $row['note_color'];
            $text = $row['note_text'];
            ?>

            <script type="text/javascript">
            postIt = {
                id: <?php echo json_encode($id); ?>,
                note_num: <?php echo json_encode($color); ?>,
                note: <?php echo json_encode($text); ?>
            }
            postIts.push(postIt);
            </script>

        <?php //再接著php碼，這種寫法在混合式的php、html、JavaScript很常見的寫法，要習慣。
            }
        }
        ?>


    <?php
        $connection = mysqli_connect("localhost", "root", "root", "calendar"); //連線資料庫
        if(!$connection){ //如果連線失敗
            die("There was an error connecting to the database."); //網頁宣告到此die，並在網頁輸出…
        }
        function db_updateTheme($newTheme){
            global $connection;
            $query = "UPDATE theme SET cur_theme = '$newTheme' WHERE id = 1"; //更新theme資料表格中，id欄位值為1的資料列中的cur_theme欄位值為$newTheme
            $result = mysqli_query($connection, $query); //送出SQL查詢
            if(!$result){ //查詢失敗的話…
                die("Query failed: " . mysqli_error($connection));
            }
        }
        
        function setTheme(){
            global $connection;
            $query = "SELECT * FROM theme";
            $result = mysqli_query($connection, $query);
            if(!$result){
                die("Something went wrong...`");
            }
            while($row = mysqli_fetch_assoc($result)){
                return $row['cur_theme'];
            }
        }

        function db_insertNote($uid, $color, $text){ //新增記事資料函式  create
            global $connection;
            $text = mysqli_real_escape_string($connection, $text);
            $query = "INSERT INTO notes(note_id, note_color, note_text) VALUES('$uid', '$color', '$text')";
            $result = mysqli_query($connection, $query);
            if(!$result){
                die("Something went wrong...");
            }
          }  
        //這段程式先判斷是否從前端有傳來new_note_uid記事資料，
        //若有的話，就將傳來的記事資料3個欄位值(note_uid, note_color, note_text)
        //作為參數呼叫db_insertNote函式，此函式執行SQL的Insert into新增查詢，將資料寫入到資料庫

        function db_updateNote($uid, $text){//更新記事資料函式  update
            global $connection;
            $text = mysqli_real_escape_string($connection, $text);
            $query = "UPDATE notes SET note_text = '$text' WHERE note_id = '$uid' LIMIT 1";
            $result = mysqli_query($connection, $query);
            if(!$result){
                die("Something went wrong....");
            }
        }

        function db_deleteNote($uid){ //刪除記事資料函式 delete
            global $connection;
            $query = "DELETE FROM notes WHERE note_id = '$uid'";
            $result = mysqli_query($connection, $query);
            if(!$result){
                die("Something went wrong…");
            }
        }

        /*-------------------------------------------------------------------------- */
        if(isset($_POST['color'])){ //透過關聯陣列$_POST['color']取得傳送過來的color資料
            db_updateTheme($_POST['color']); //呼叫db_updateTheme方法
        }

        if(isset($_POST['new_note_uid'])){ //前端傳來新增記事資料   create
            db_insertNote($_POST['new_note_uid'], $_POST['new_note_color'], $_POST['new_note_text']);
          }

        if(isset($_POST['update_note_uid'])){ //若前端有傳來更新記事資料 update
            db_updateNote($_POST['update_note_uid'], $_POST['update_note_text']);
         }

        if(isset($_POST['delete_note_uid'])){ //刪除記事資料 delete
            db_deleteNote($_POST['delete_note_uid']);
        }
    ?>

    <h3 id="back-year" class="background-text off-color">2024</h3>
    <h4 class="background-text off-color">Calendar</h4>


    <div id="current-day-info" class="color">
        <h1 id="app-name-landscape" class="off-color default-cursor center">My calendar</h1>

            <div>
                <h2 id="cur-year" class="default-cursor center">2024</h2>
            </div>

            <div class="">
                <h1 id="cur-day" class="default-cursor center">Wednesday</h1>
                <h1 id="cur-month" class="current-day-heading default-cursor center">May</h1>
                <h1 id="cur-date" class="current-day-heading default-cursor center">14<sup>th</sup></h1>   <!--th 代表上標字體-->
            </div>
        
                <button id="theme-landscape" class="font button" onclick="openFavColor();">Change theme</button>
    </div>


    <div id="calendar" class="">                                       <!--開始製作右邊的行事曆-->
        <h1 id="app-name-portrait" class="center off-color">My Calendar</h1>
            <table>

                <thead class="color">                                  <!--行事曆開始設定-->

                    <tr>
                        <th colspan="7" class="border-color">     <!--每週有７天,colspan 讓7格表格合併-->
                            <h4 id="cal-year">2024</h4>
                            <div >
                                <i class="fa-solid fa-caret-left icon" onclick="fillInPreviousMonth();"></i> 
                                <h3 id="cal-month">December</h3>
                                <i class="fa-solid fa-caret-right icon" onclick="fillInNextMonth();"></i> 
                            </div>
                        </th>
                    </tr>

                    <tr>                                          <!--設定第二行tr , 有7天-->
                        <th class="weekday border-color">Sun</th>
                        <th class="weekday border-color">Mon</th>
                        <th class="weekday border-color">Tue</th>
                        <th class="weekday border-color">Wed</th>
                        <th class="weekday border-color">Thu</th>
                        <th class="weekday border-color">Fri</th>
                        <th class="weekday border-color">Sat</th>
                    </tr>
                                                                
                </thead>                                

                <tbody id="table-body" class="border-color">           <!--設定tbody , 整個表格-->

                    <tr>
                        <td class="color" onclick="dayClicked(this);">1</td>                      <!--color 這個目前是先預設-->
                        <td class="color" onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                        <td id="current-day" onclick="dayClicked(this);">1</td>                   <!--current 這個目前是先預設-->
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                    </tr>
                    <tr>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1 <img src="images/note1.png"></td>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                    </tr>
                    <tr>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                    </tr>
                    <tr>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                    </tr>
                    <tr>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                    </tr>
                    <tr>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                        <td onclick="dayClicked(this);">1</td>
                    </tr>

                </tbody>

            </table>

            <button id="theme-portrait" class="font button color" onclick="openFavColor();">Change theme</button>
    </div>

    <!--這邊開始設計對話匡 , 視窗出現時背景會變暗-->
    <dialog id="modal">                    <!-- open 改成 hidden 可以關掉-->
        
        <div id="fav-color" hidden>             <!-- 選擇theme 跳出的選擇器-->

            <div class="popup">                 <!-- 選擇theme 跳出的選擇器-->
                <h4 class="center">What's your favorite color?</h4>
                <div id="color-options">

                    <div class="color-option">
                        <div class="color-preview" id="blue" style="background-color: #1B19CD;" onclick="addCheckMark('blue');"></div>
                        <h5>Blue</h5>
                    </div>

                    <div class="color-option">
                        <div class="color-preview" id="red" style="background-color: #D01212;" onclick="addCheckMark('red');"></div>
                        <h5>Red</h5>
                    </div>

                    <div class="color-option">
                        <div class="color-preview" id="purple" style="background-color: #721D89;" onclick="addCheckMark('purple');"></div>
                        <h5>Purple</h5>
                    </div>

                    <div class="color-option">
                        <div class="color-preview" id="green" style="background-color: #158348;" onclick="addCheckMark('green');"></div>
                        <h5>Green</h5>
                    </div>

                    <div class="color-option">
                        <div class="color-preview" id="orange" style="background-color: #EE742D;" onclick="addCheckMark('orange');"></div>
                        <h5>Orange</h5>
                    </div>

                    <div class="color-option">
                        <div class="color-preview" id="deep-orange" style="background-color: #F13C26;" onclick="addCheckMark('deep-orange');"></div>
                        <h5>Deep Orange</h5>
                    </div>

                    <div class="color-option">
                        <div class="color-preview" id="baby-blue" style="background-color: #31B2FC;" onclick="addCheckMark('baby-blue');"></div>
                        <h5>Baby Blue</h5>
                    </div>

                    <div class="color-option">
                        <div class="color-preview" id="cerise" style="background-color: #EA3D69;" onclick="addCheckMark('cerise');"></div>
                        <h5>Cerise</h5>
                    </div>

                    <div class="color-option">
                        <div class="color-preview" id="lime" style="background-color: #36C945;" onclick="addCheckMark('lime');"></div>
                        <h5>Lime</h5>
                    </div>

                    <div class="color-option">
                        <div class="color-preview" id="teal" style="background-color: #2FCCB9;" onclick="addCheckMark('teal');"></div>
                        <h5>Teal</h5>
                    </div>

                    <div class="color-option">
                        <div class="color-preview" id="pink" style="background-color: #F50D7A;" onclick="addCheckMark('pink');"></div>
                        <h5>Pink</h5>
                    </div>

                    <div class="color-option">
                        <div class="color-preview" id="black" style="background-color: #212524;" onclick="addCheckMark('black');"></div>
                        <h5>Black</h5>
                    </div>

                    <button id="update-theme-button" class="button font" onclick="(changeColor());">Update</button>

                </div>
            </div>

        </div>

        <div id="make-note" hidden>                    <!-- 跳出字匡可以記錄文字-->
            <div class="popup">
              <h4>Add a note to the calendar</h4>
              <textarea id="edit-post-it" class="font" name="post-it" autofocus></textarea>
              <div>
                  <button class="button font post-it-button" id="add-post-it" onclick="submitPostIt();">Post It</button>
                  <button class="button font post-it-button" id="delete-button" onclick="deleteNote();">Delete It</button>
              </div>
            </div>
        </div>


    </dialog>

   <script>
        var postIts = []; //記事陣列，用來放置月曆中的記事物件資料
        //current 目前點擊的日期
        var currentPostItID = 0; //目前的記事ID
        var newCurrentPostIt = false; //目前的記事是否為新？也就是：目前點選的日期尚未有任何的記事資料
        var currentPostItIndex = 0; //目前的記事在postIts陣列中的位置索引
   </script> 

    <?php getNoteData(); ?> //<!--Read 把原本在行事曆的記事本讀取出來-->

    <script language="JavaScript">

        currentColor.name = <?php echo( json_encode( setTheme() )); ?> ; //json_encode將回傳的資料包裝成JSON編碼字串，然後指定給currentColor.name

        updateDates();  //這個是'動態'印出行事曆右邊and上面的日期號碼and框框內的月份動態日期
        fillInMonth(year,month);
        
    </script>



</body>
</html>