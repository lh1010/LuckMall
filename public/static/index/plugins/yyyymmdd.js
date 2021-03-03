function YYYYMMDDstart() 
{
    MonHead = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

    var y = new Date().getFullYear();

    for (var i = y; i > (y - 120); i--) {
        document.getElementById('YYYY').options.add(new Option(i, i));
    }

    for (var i = 1; i < 13; i++) {
        var value = i;
        if (i < 10) value = '0' + i;
        document.getElementById('MM').options.add(new Option(value, value)); 
    } 

    var n = MonHead[new Date().getMonth()];

    if (new Date().getMonth() == 1 && IsPinYear(YYYYvalue)) n++;

    //writeDay(n);
    //document.getElementById('YYYY').value = y;
    //document.getElementById('MM').value = new Date().getMonth() + 1;
    //document.getElementById('DD').value = new Date().getDate();
}

if (document.attachEvent) window.attachEvent("onload", YYYYMMDDstart);
else window.addEventListener('load', YYYYMMDDstart, false);

function YYYYDD(str)  
{
    var MMvalue = document.getElementById('MM').options[document.getElementById('MM').selectedIndex].value;
    if (MMvalue == "") {
        var e = document.getElementById('DD');
        optionsClear(e);
        return;
    }
    var n = MonHead[MMvalue - 1];
    if (MMvalue == 2 && IsPinYear(str)) n++;
    writeDay(n)
}

function MMDD(str)   
{
    var YYYYvalue = document.getElementById('YYYY').options[document.getElementById('YYYY').selectedIndex].value;
    if (YYYYvalue == "") {
        var e = document.getElementById('DD');
        optionsClear(e);
        return;
    }
    var n = MonHead[str - 1];
    if (str == 2 && IsPinYear(YYYYvalue)) n++;
    writeDay(n)
}

function writeDay(n)  
{
    var e = document.getElementById('DD');
    optionsClear(e);
    for (var i = 1; i < (n + 1); i++) {
        var value = i;
        if (i < 10) value = '0' + i;
        e.options.add(new Option(value, value)); 
    }
}

function IsPinYear(year)  
{
    return (0 == year % 4 && (year % 100 != 0 || year % 400 == 0));
}

function optionsClear(e)
{
    e.options.length = 1;
}