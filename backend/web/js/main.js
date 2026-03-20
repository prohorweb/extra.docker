if ($(".input-date").length) {
    $(".input-date").each(function () {
        pickmeup(this, {date: new Date(this.value).toLocaleString(), hide_on_select: true});
    });
}

$(".tabs-list dt").click(function () {
    $(this).toggleClass("active");
    $(this).next().toggleClass("active");
    return false;
});

$(".left-menu li li.active").parents("li").addClass("active");

$(".left-menu li.parent > a").click(function(){
    $(this).parent().toggleClass("active");
    //return false;
});

addFiles();

function addFiles() {
    var inputs = document.querySelectorAll('.upload-box input');
    Array.prototype.forEach.call(inputs, function (input) {
        var label = input.nextElementSibling;
        var helpblock = label.nextElementSibling;
        var labelVal = label.innerHTML;

        input.addEventListener('change', function (e) {
            var fileName = '';
            if (this.files && this.files.length > 1)
                fileName = ( this.getAttribute('data-multiple-caption') || '' ).replace('{count}', this.files.length);
            else
                fileName = e.target.value.split('\\').pop();

            if (fileName)
                helpblock/*.querySelector( 'span' )*/.innerHTML = fileName;
            else
                helpblock.innerHTML = labelVal;
        });

        // Firefox bug fix
        input.addEventListener('focus', function () {
            input.classList.add('has-focus');
        });
        input.addEventListener('blur', function () {
            input.classList.remove('has-focus');
        });
    });
}