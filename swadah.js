tinymce.init({
    selector: '#wadahComposer',
    menubar: false,
    branding: false,
});

tinymce.init({
    selector: '#referenceComposer',
    menubar: false,
    branding: false,
});

$(function() {
    $("#subjectheading1").autocomplete({
        source: "../sw_tools/autocomplete_sh.php"
    });
});

$(function() {
    $("#publication1_b").autocomplete({
        source: "../sw_tools/autocomplete_pub.php"
    });
});

function openPopup(url,winWidth,winHeight) {
    window.open(url, "popup_id", "scrollbars=yes,resizable=no,width="+winWidth+",height="+winHeight);
    return false;
}

function toggle_it() {
    var x = document.getElementById("adminlogin");
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}

function showList() 
{
    window.open("../sw_tools/shselector.php", "list", "scrollbars=yes,resizable=no,width=400,height=450");//seterusnya meng-get-kan windows yang terpanggil
}

function showPublisher() 
{
    window.open("../sw_tools/pubselector.php", "list", "scrollbars=yes,resizable=no,width=400,height=450");//seterusnya meng-get-kan windows yang terpanggil
}

function remLink() 
{
    if (window.sList && window.sList.open && !window.sList.closed)
    {
        window.sList.opener = null;
    }
}


