YUI().use
(
    "node-base", function(Y)
    {

        var performSubmitActions = function(e){
            e.preventDefault();

            buildGroupEmailAddress();

            var form = Y.one("#mform1");

            var valid = isGroupNameValid() && isFormValid();

            if (!valid) {
                //We know the form won't pass validation, so submit it to display errors to the user.
                form.submit();
            }
            else {
                //Form should pass validation, so now check if the API returns successfully before submitting.
                form.submit();
            }
        };

        var buildGroupEmailAddress = function() {
            var name = document.getElementById("id_name").value;
            var userEmail = document.getElementsByName("user_email")[0].value;

            var groupEmailAddressPrefix = name.toLowerCase().split(/\s/).join('_');
//            var groupEmailAddressSuffix = userEmail.split('@')[1];
         //   var groupEmailAddressSuffix = "miplacek12.org";
            var groupEmailAddressSuffix = "osisd.net";

            var groupEmailField = document.getElementsByName("group_email")[0];
            groupEmailField.value = groupEmailAddressPrefix + '@' + groupEmailAddressSuffix;
        };

        var isGroupNameValid = function() {
            var id = document.getElementsByName("id")[0].value;
            var name = document.getElementById("id_name").value;
            var isValid = 0;
            var url = document.getElementsByName("edit_validation_url")[0].value + "?groupName=" + name;

            if (id == 0 && name) {
                $.ajax(url, {
                    method: 'GET',
                    async: false,
                    success: function (data, status, xhr) {
                        isValid = data;
                    }
                });
            }

            if (isValid == 1) {
                return true;
            }
            else {
                return false;
            }
        };

        var isFormValid = function() {
            var description = document.getElementById("id_description").value;
            var purpose = document.getElementById("id_purpose").value;
            var groupEmail = document.getElementsByName("group_email")[0].value;

            return !(description.length > 200) && !(purpose.length > 200) && groupEmail;
        };

        Y.after("submit", performSubmitActions, this);
    }
);
