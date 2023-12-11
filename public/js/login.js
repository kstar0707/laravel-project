
function loginBtn()
{
    var loginId = $('#loginID').val();
    var password = $('#password').val();
    // if(validation(loginId, password) == false)
    // {
    //     return;
    // }
    $.ajax({
        url: "/login_action",
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
        data: { loginId : loginId, password : password},
        beforeSend: function(xhr) {
            var token = $('meta[name="csrf-token"]').attr("content");
            if (token) {
                return xhr.setRequestHeader("X-CSRF-TOKEN", token);
            }
            console.log(token);
        },
        success: function(response) {
            console.log(response);
            toastr[response['type']](response['result']);

            if(response['type'] == "success"){
                setTimeout(() => {
                    location.href = "/dashboard";
                }, 1500);
            }

        },
        error: function(jqXHR, textStatus, errorThrown) {
            toastr['error'](errorThrown);
        }
    });
}


function validation(Id, Pass)
{
    if(Id == "")
    {
        toastr['error']("Please enter your ID & Email.");
        return false;
    }
    if (!isValidEmail(Id)) {
        toastr['error']("Your email format is incorrect.");
        return false;
      }
    if(Pass == "")
    {
        toastr['error']("Please enter your Password.");
        return false;
    }
    return true;
}

function isValidEmail(email) {
    // Regular expression for email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    return emailRegex.test(email);
  }
