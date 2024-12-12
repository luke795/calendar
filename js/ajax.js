        //資料由前端透過XMLHttpRequest送到後端，由後端的php程式處理。 新增js/ajax.js，這支程式是前端與後端的資料橋樑：
        function ajax(object){      //JSON 物件
            var request = new XMLHttpRequest();    
            request.open("POST", "index.php");
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(objectToString(object));
        }

        function objectToString(object){
            let str = "";
            Object.keys(object).forEach(function(key){
                str += key;
                str += `=${object[key]}&`; //``模板字符串 
            });
            //console.log(str);
            return str;
        }
        //  註：模版字符串(template literals，backtick 反引號，跟單引號不同)
        //可以讓我們將變成或敘述插入在字串中。上面式子的等效傳統寫法 str += '=' + object[key] + '&'; 
        //我們在前端JavaScript程式中呼叫ajax(object)這個方法，
        //我們將更新資料需求包裝在object物件裏，
        //objectToString這個方法會將object這個物件的key與value值一個一個抓出來，
        //組成key1=value1&key2&vaule2&...字串，再把這個字串送到後端處理。