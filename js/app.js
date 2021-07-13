// recherche autocompletion
$(document).ready(function () {


    $("#rechercher").keyup(function () {

        let currentValue = $(this).val();

        if(currentValue.length == 0) {
            $("#datalistOptions").html("");
            return false;
        }

        $.ajax({
            url: "recherche.php",
            type: "GET",
            dataType: "json",
            data: { myInputValue: currentValue }
        }).done(function (data) {

            let listOptions = "";
            $.each(data, function (index, value) {
                listOptions += "<option> " + value.description_longue + " </option>";
            });

            $("#datalistOptions").html(listOptions);
        });

    });

});