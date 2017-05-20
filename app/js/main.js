$(function () { 
  $('#collapseHelp').on('show.bs.collapse', function () {
		$(".toggle-help .btn").removeClass("btn-default").addClass("btn-primary");
	});

	$('#collapseHelp').on('hide.bs.collapse', function () {
		$(".toggle-help .btn").removeClass("btn-primary").addClass("btn-default");
	});
	
	autosubmit();
	
});



/* 
Usage:
   Add a class of "autosubmit" to controls. If the control is not within a form 
   (or even if it is but you want to use a different form), add data-autosubmit-form="#selector".
   
   Then, on DOM ready, execute autosubmit() to wire up the handlers:
   $(function () { 
      autosubmit();
   });
*/
function autosubmit() {

        function submitFormFor(element) {
            // get form 
            var formSelector = $(element).data("autosubmit-form"),
                $form = formSelector ?
                    $(formSelector) :
                    $(element).closest("form");

            if ($form.length > 0) { $form.submit();}
        }

        // wire-up autosubmit
        $("input.autosubmit[type='checkbox'],input.autosubmit[type='radio']").on("click", function () {
            submitFormFor(this);
        });
        $("select.autosubmit").on("change", function () {
            if ($(this).is(":focus")) {
                submitFormFor(this);
            }
        });
        $("input.autosubmit[type='text'],textarea.autosubmit").on("blur keyup", function (e) {
            if (e.type === "blur" || e.which === 13) {
                submitFormFor(this);
            }
        });

    }