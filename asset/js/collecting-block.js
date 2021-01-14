$(document).ready(function() {

$('.collecting-form').hide();

// Handle form selection when multiple forms are within one block.
$('#content').on('change', '.collecting-form-select', function(e) {
    var thisSelect = $(this);
    thisSelect.siblings('.collecting-form').hide();
    thisSelect.siblings('.collecting-form-' + thisSelect.val()).show();
});

// Add the CKEditor HTML text editor to any element with class="collecting-html"
$('.collecting-html').ckeditor();

});
//Edit SF, making Checkboxes work...
let selects = [];
const sleep = (milliseconds) => {
    return new Promise(resolve => setTimeout(resolve, milliseconds))
}

$(document).ready(function() {
    //Hide the input elements that are going to be filled by checkboxes
    $('.field .field-meta label').each(function(i) {
            if ($( this ).text() === "CheckboxSelect") {
                const elementToPush = $( this ).parent().parent().children(".inputs").children().first();
                selects.push(elementToPush);
                $( this ).parent().parent().hide();
            } else if ($( this ).text().includes("language:")) {  
                $( this ).parent().parent().hide();//hide the language field
                $( this ).parent().parent().find( "select" ).val($( this ).text().split(":")[1]).change(); //select value after colon in name
            } else if ($( this ).text() === "Anderes" || $( this ).text() === "Autre" || $( this ).text() === "Altro") { //hide the text input "Anderes"
                $( this ).parent().parent().hide();
            }
        }
    );
    //set standard value for url fields, which makes it easier to put in a correct url.
    let urls = document.querySelectorAll('input[type=url]');
    urls.forEach(element => {
        element.value = "http://";
    })

    //set the value of any year-field to 2020
    $(document).find('.numeric-datetime-year').each(function() {
        $(this).val('2020');
    });
});

function SetCheckboxVal(e) {
    const checkboxes = document.getElementsByName("catCheckbox");
    const warning = document.getElementById("warning");
    const fieldset = document.getElementById("fieldset");

    let numberOfCheckedItems = 0;
    for(let i = 0; i < checkboxes.length; i++)
    {
        if(checkboxes[i].checked)
            numberOfCheckedItems++;
    }
    if(numberOfCheckedItems > selects.length)
    {
        warning.innerText = warning.innerText.replace("SelectsNum", selects.length);
        warning.style.display = "block";
        fieldset.style.boxShadow = "box-shadow: 0 0 6px rgba(236, 1, 1, 0.58)";
        fieldset.style.borderColor = "red";
        sleep(3500).then(() => {
            warning.style.display = "none";
            fieldset.style.boxShadow = "box-shadow: none";
            fieldset.style.borderColor = "initial";
        })

        return false;
    }

    for(let i = 0; i < selects.length; i++){
        let currentVal = selects[i];
        if(e.checked) {
            if (!currentVal.val()) {
                currentVal.val(e.value);

                break;

            }
        } else {

            if (currentVal.val() === e.value) {
                currentVal.val(undefined);
            }
        }
    }
}

function activateOther(e) {

    $('.field .field-meta label').each(function (i) {
            if ($( this ).text() === "Anderes" || $( this ).text() === "Autre" || $( this ).text() === "Altro") {
                if (e.checked) {
                    console.log("showing");
                    console.log(e);
                    $(this).parent().parent().show();
                    $(this).parent().parent().children(".inputs").children().first().focus();
                } else {
                    console.log("hiding");
                    console.log(e);
                    $(this).parent().parent().hide();
                }
            }
        }
    );
}

