var currentColor = { name : "blue", color : "#1B19CD", off_color : "#7c7EFB" }; //用來紀錄目前主題的色彩名稱、色彩編碼

var color_data = [
    {
        name: 'blue',
        color_code: '#1B19CD',
        off_color_code: '#7C7EFB'
    }, {
        name: 'red',
        color_code: '#D01212',
        off_color_code: '#EEA19B'
    }, {
        name: 'purple',
        color_code: '#721D89',
        off_color_code: '#EBADFB'
    }, {
        name: 'green',
        color_code: '#158348',
        off_color_code: '#57C664'
    }, {
        name: 'orange',
        color_code: '#EE742D',
        off_color_code: '#F7A77A'
    }, {
        name: 'deep-orange',
        color_code: '#F13C26',
        off_color_code: '#F77D59'
    }, {
        name: 'baby-blue',
        color_code: '#31B2FC',
        off_color_code: '#3D8DD9'
    }, {
        name: 'cerise',
        color_code: '#EA3D69',
        off_color_code: '#FCBECC'
    }, {
        name: 'lime',
        color_code: '#2ACC32',
        off_color_code: '#4FFA4F'
    }, {
        name: 'teal',
        color_code: '#2FCCB9',
        off_color_code: '#7FE7E3'
    }, {
        name: 'pink',
        color_code: '#F50D7A',
        off_color_code: '#FFB9EA'
    }, {
        name: 'black',
        color_code: '#212524',
        off_color_code: '#687E7B'
    }
  ];





  function addCheckMark(color_name){  //這個是點進去時，勾選change theme
    currentColor.name = color_name; //將勾選的色彩名稱color_name指定給全域變數currentColorName，以便在changeColor方法裏使用，來設定整個主題的色彩。

    let color_blocks = document.getElementsByClassName("color-preview");
    for (i = 0; i < color_blocks.length ; i++ ) color_blocks[i].innerHTML = "";
            
    let color_block = document.getElementById(color_name);
    if (color_block) color_block.innerHTML = '<i class="fas fa-check checkmark"></i>';
}

function openFavColor(){
    var modal = document.getElementById("modal");
    modal.open = true;
    var template = document.getElementById("fav-color");
    template.removeAttribute("hidden");
}

function changeColor(){   
    
    ajax({ color: currentColor.name});


    //第1步：先找出勾選色彩所設定的色碼
    //console.log(currentColor.name)
    color_data.forEach(function(arr_data){ //陣列的走訪，每走訪一個陣列元素，帶出的元素以arr_data變數呈現(arr_data我們自取的名稱)
        if(currentColor.name == arr_data.name){ //找到color_data陣列中符合的色彩，
            currentColor.color = arr_data.color_code;
            currentColor.off_color = arr_data.off_color_code;
        }
    });
    var elements;
    //先清除掉所有的style設置(td)
    elements = document.getElementsByTagName("td");
    for(let i=0; i < elements.length; i++) {
        elements[i].style = null;
    }
    //改變目前的色彩設置
    elements = document.getElementsByClassName("color");
    for(let i=0; i < elements.length; i++) {
    elements[i].style.backgroundColor = currentColor.color;
    }
    elements = document.getElementsByClassName("border-color");
        for(let i=0; i < elements.length; i++) {
        elements[i].style.borderColor = currentColor.color;
    }
    elements = document.getElementsByClassName("off-color");
        for(let i=0; i < elements.length; i++) {
        elements[i].style.color = currentColor.off_color;
    }


    var modal = document.getElementById("modal");
    modal.open = false;
    var template = document.getElementById("fav-color");
    template.setAttribute("hidden", "hidden");
}