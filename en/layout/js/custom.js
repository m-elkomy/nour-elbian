
    //hide placehoder on focus on form and return it on blur
    $('[placeholder]').focus(function(){
        $(this).attr('data-text',$(this).attr('placeholder'));
        $(this).attr('placeholder','');
    }).blur(function(){
        $(this).attr('placeholder',$(this).attr('data-text'));
    });


    // add asterisk for required inputs field
    $('input').each(function(){
        if($(this).attr('required') === 'required'){
            $(this).after('<span class="asterisk">*</span>')
        }
    });

    //show password on hover on show pass icon
    var pass = $('.password');
    $('.show-pass').hover(function(){
        pass.attr('type','text');
    },function () {
        pass.attr('type','password');
    });

    //confirm message before delete members
    $('.confirm').click(function(){
        return confirm('Are You Sure ?');
    });

    //calling jquery select box it plugin
    $("select").selectBoxIt({
        autoWidth: false
    });

    //toggle show and hide panel body on dashboard
    $('.toggle-info').click(function () {
       $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(100);
       if($(this).hasClass('selected')){
           $(this).html('<span class="glyphicon glyphicon-minus"></span>');
       }else {
           $(this).html('<span class="glyphicon glyphicon-plus"></span>');
       }
    });
    function showCustomer(str) {
        var xhttp;
        if (str == "") {
            document.getElementById("classname").innerHTML = "";
            return;
        }
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("classname").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "getclass.php?q="+str, true);
        xhttp.send();
    }
    function showbranch(str) {
        var xhttp;
        if (str == "") {
            document.getElementById("branchname").innerHTML = "";
            return;
        }
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("branchname").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "getbranch.php?q="+str, true);
        xhttp.send();
    }

    function showclass(str) {
        var xhttp;
        if (str == "") {
            document.getElementById("cname").innerHTML = "";
            return;
        }
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("cname").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "showclasses.php?q="+str, true);
        xhttp.send();
    }

    function showteachers(str) {
        var xhttp;
        if (str == "") {
            document.getElementById("tname").innerHTML = "";
            return;
        }
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("tname").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "showteachers.php?q="+str, true);
        xhttp.send();
    }
    function showstudents(str) {
        var xhttp;
        if (str == "") {
            document.getElementById("sname").innerHTML = "";
            return;
        }
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("sname").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "showstudents.php?q="+str, true);
        xhttp.send();
    }

