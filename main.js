
$(function () {

let x, y, r, m;
    function isValidNum(num) {
        return !isNaN(parseFloat(num)) && isFinite(num);
    }

    function roundPlus(x, n) {
        // if(isNaN(x) || isNaN(n)) return false;
        m = Math.pow(10, n);
        return Math.round(x*m) / m;
    }

    let buttons = document.querySelectorAll("input[name=cordinateY]");
    buttons.forEach(click);
    function click(element) {
        element.onclick = function () {
            y = this.value;
            buttons.forEach(function (element) {
                element.style.boxShadow = "";
                element.style.transform = "";
            });
            this.style.boxShadow = "0 0 40px 5px #f41c52";
            this.style.transform = "scale(1.05)";
        }
    }


    function validateX() {
        x = document.querySelector("input[name=cordinateX]").value.replace(",", ".");
        if(x === "") {
            $("#text-info").after('<br class="error"><span class="error">Поле X не может быть пустым!</span>');
            return false;
        }else if(x === '-') {
            $("#text-info").after('<br class="error"><span class="error">Координата X не может состоять только из минуса</span>');
            return false;
        }else if (!isValidNum(x)) {
            $("#text-info").after('<br class="error"><span class="error">В значении X могут быть использованы только числа</span>');
            return false;
        }else if(x >= 3 || x <= (-5)) {
            $("#text-info").after('<br class="error"><span class="error">Неверный диапазон X (X = (-5...3))</span>');
            return false;
        }
        if (document.querySelector("input[name=cordinateX]").value.length > 5) {
            document.querySelector("input[name=cordinateX]").value = roundPlus(document.querySelector("input[name=cordinateX]").value.replace(",", "."), 5);
        }
        return true;

    }

    function validateY() {
        if(y === undefined) {
            $("#text-info").after('<br class="error"><span class="error">Вы не выбрали значение Y</span>');
            return false;
        }
        return true;
    }

    function validateR() {
        r = document.querySelector("input[name=radiusR]").value.replace(",", ".");
        if(r === "") {
            $("#text-info").after('<br class="error"><span class="error">Поле R не может быть пустым!</span>');
            return false;
        }else if(r === '-') {
            $("#text-info").after('<br class="error"><span class="error">Радиус R не может содержать "-"</span>');
            return false;
        }else if (!isValidNum(r)) {
            $("#text-info").after('<br class="error"><span class="error">В значении К могут быть использованы только числа</span>');
            return false;
        }else if(r < 0) {
            $("#text-info").after('<br class="error"><span class="error">Радиус R не может быть отрицательным числом</span>');
            return false;
        }else if(r <= 2 || r >= 5) {
            $("#text-info").after('<br class="error"><span class="error">Неверный диапазон R (R = (2...5))</span>');
            return false;
        }
        if (document.querySelector("input[name=radiusR]").value.length > 5) {
            document.querySelector("input[name=radiusR]").value = roundPlus(document.querySelector("input[name=radiusR]").value.replace(",", "."), 5);
        }
        return true;
    }

    function validate() {
        $(".error").remove();
        if (validateX() && validateY() && validateR()) {
            $("#text-info").after('<br class="error"><span class="error">Выполнено!</span>');
            return true;
        }
        return false;

    }




    $("#input-form").submit( function (event) {
        event.preventDefault();
        if (!validate()) return;
        $.ajax({
            url: 'myscript.php',
            method: 'post',
            dataType: 'json',
            data: $(this).serialize() + '&cordinateY=' + y + '&timeNow=' + new Date().getTimezoneOffset(),
            success: function (data) {
                $('.button').attr('disabled', false);
                newRow = '<tr>';
                newRow += '<td>' + data.cordinateX + '</td>';
                newRow += '<td>' + data.cordinateY + '</td>';
                newRow += '<td>' + data.radiusR + '</td>';
                newRow += '<td>' + data.timeLol + '</td>';
                newRow += '<td>' + data.timeLong + '</td>';
                newRow += '<td>' + data.itog + '</td>';
                $('#table_result_ok').append(newRow);
            }
        })
    });
})
